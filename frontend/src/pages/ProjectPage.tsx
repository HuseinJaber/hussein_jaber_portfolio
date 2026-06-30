import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import Aurora from "@/components/ui/Aurora";
import PageMeta from "@/components/PageMeta";
import { ProjectContributionBadges } from "@/components/ui/ProjectContributionBadges";
import { getPortfolio, getProject } from "@/lib/api";
import { isSectionEnabled, resolveSections } from "@/lib/sections";
import type { PortfolioData, Project } from "@/lib/types";

export default function ProjectPage() {
  const { slug } = useParams<{ slug: string }>();
  const [project, setProject] = useState<Project | null | undefined>(undefined);
  const [portfolio, setPortfolio] = useState<PortfolioData | null>(null);

  useEffect(() => {
    if (!slug) return;

    let active = true;

    Promise.all([getProject(slug), getPortfolio()]).then(([projectData, portfolioData]) => {
      if (active) {
        setProject(projectData);
        setPortfolio(portfolioData);
      }
    });

    return () => {
      active = false;
    };
  }, [slug]);

  if (project === undefined) {
    return (
      <main className="flex min-h-screen items-center justify-center px-4">
        <Aurora />
        <p className="text-muted">Loading project…</p>
      </main>
    );
  }

  if (!project) {
    return (
      <main className="flex min-h-screen flex-col items-center justify-center px-4 text-center">
        <Aurora />
        <h1 className="text-2xl font-bold">Project not found</h1>
        <Link to="/" className="mt-4 text-sm text-muted transition hover:text-white">
          ← Back to home
        </Link>
      </main>
    );
  }

  const sections = resolveSections(portfolio?.profile.sections);
  const workEnabled = isSectionEnabled(sections, "work");

  return (
    <>
      <PageMeta
        title={`${project.title} — ${portfolio?.profile.name ?? "Portfolio"}`}
        description={project.short_description ?? project.description ?? undefined}
      />
      <Aurora />
      <main className="mx-auto max-w-4xl px-4 py-24">
        <Link
          to={workEnabled ? "/#work" : "/"}
          className="text-sm text-muted transition hover:text-white"
        >
          ← {workEnabled ? "Back to work" : "Back to home"}
        </Link>

        <p className="mt-8 text-xs uppercase tracking-widest text-accent">
          {project.category}
          {project.year ? ` · ${project.year}` : ""}
          {project.sites_count ? ` · ${project.sites_count}+ sites` : ""}
        </p>
        <div className="mt-4 flex flex-wrap items-center gap-2">
          <span
            className={`rounded-full px-3 py-1 text-xs font-semibold ${
              project.engagement_type === "support"
                ? "bg-sky-400/20 text-sky-200"
                : "bg-violet-400/20 text-violet-200"
            }`}
          >
            {project.engagement_type === "support" ? "Support" : "Development"}
          </span>
          <ProjectContributionBadges project={project} />
        </div>
        <h1 className="mt-3 text-4xl font-bold tracking-tight sm:text-5xl">{project.title}</h1>
        {project.short_description && (
          <p className="mt-4 text-lg text-muted">{project.short_description}</p>
        )}

        <div className="mt-6 flex flex-wrap gap-3">
          {project.live_url && (
            <a
              href={project.live_url}
              target="_blank"
              rel="noopener noreferrer"
              className="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-black transition hover:bg-accent"
            >
              Visit live site ↗
            </a>
          )}
          {project.source_url && (
            <a
              href={project.source_url}
              target="_blank"
              rel="noopener noreferrer"
              className="rounded-xl border border-line px-5 py-2.5 text-sm font-semibold transition hover:border-brand"
            >
              View source
            </a>
          )}
        </div>

        <div className="relative mx-auto mt-6 aspect-video w-full max-w-xl overflow-hidden rounded-2xl border border-line bg-gradient-to-br from-surface-2 to-surface">
          {project.cover_image ? (
            <img
              src={project.cover_image}
              alt={project.title}
              className="absolute inset-0 h-full w-full object-contain p-4"
            />
          ) : (
            <span className="flex h-full items-center justify-center text-7xl font-bold text-white/10">
              {project.title.charAt(0)}
            </span>
          )}
        </div>

        {project.tech_stack && project.tech_stack.length > 0 && (
          <div className="mt-8 flex flex-wrap gap-2">
            {project.tech_stack.map((t) => (
              <span key={t} className="rounded-lg bg-white/5 px-3 py-1 text-sm text-muted">
                {t}
              </span>
            ))}
          </div>
        )}

        {project.description && (
          <div className="mt-8 space-y-4 text-lg leading-relaxed text-muted">
            {project.description.split("\n").filter(Boolean).map((p, i) => (
              <p key={i}>{p}</p>
            ))}
          </div>
        )}

        {project.client && (
          <p className="mt-10 text-sm text-muted">
            Client: <span className="text-white">{project.client}</span>
          </p>
        )}
      </main>
    </>
  );
}
