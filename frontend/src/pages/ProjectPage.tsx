import { useEffect, useState, type ReactNode } from "react";
import { Link, useParams } from "react-router-dom";
import Aurora from "@/components/ui/Aurora";
import PageMeta from "@/components/PageMeta";
import { ProjectContributionBadges } from "@/components/ui/ProjectContributionBadges";
import { getPortfolio, getProject } from "@/lib/api";
import { isSectionEnabled, resolveSections } from "@/lib/sections";
import type { PortfolioData, Project } from "@/lib/types";

function ProjectMeta({ project }: { project: Project }) {
  const parts = [
    ...(project.categories?.length ? project.categories : project.category ? [project.category] : []),
    ...(project.year ? [String(project.year)] : []),
    ...(project.sites_count ? [`${project.sites_count}+ sites`] : []),
    ...(project.work_context === "freelance" ? ["Freelance"] : []),
  ];

  if (parts.length === 0) return null;

  return (
    <p className="text-[11px] font-medium uppercase tracking-[0.18em] text-accent sm:text-xs">
      {parts.join(" · ")}
    </p>
  );
}

function ProjectCover({ project }: { project: Project }) {
  return (
    <div className="relative aspect-[16/10] w-full overflow-hidden rounded-xl border border-line/80 bg-surface-2/60 sm:rounded-2xl">
      {project.cover_image ? (
        <img
          src={project.cover_image}
          alt={project.title}
          className="absolute inset-0 h-full w-full object-contain p-4 sm:p-5"
        />
      ) : (
        <span className="flex h-full items-center justify-center text-5xl font-bold text-white/10 sm:text-6xl">
          {project.title.charAt(0)}
        </span>
      )}
    </div>
  );
}

function SectionLabel({ children }: { children: ReactNode }) {
  return (
    <p className="text-[10px] font-semibold uppercase tracking-[0.2em] text-muted/80 sm:text-[11px]">
      {children}
    </p>
  );
}

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
  const descriptionParagraphs = project.description?.split("\n").filter(Boolean) ?? [];
  const hasActions = Boolean(project.live_url || project.source_url);
  const engagementLabel = project.engagement_type === "support" ? "Support" : "Development";

  return (
    <>
      <PageMeta
        title={`${project.title} — ${portfolio?.profile.name ?? "Portfolio"}`}
        description={project.short_description ?? project.description ?? undefined}
      />
      <Aurora />
      <main className="mx-auto w-full max-w-5xl px-4 pb-12 pt-6 sm:px-6 sm:pb-16 sm:pt-8 lg:max-w-6xl">
        <Link
          to={workEnabled ? "/#work" : "/"}
          className="inline-flex items-center gap-2 rounded-lg border border-transparent px-1 py-1 text-sm text-muted transition hover:border-line hover:text-white"
        >
          <span aria-hidden className="text-base leading-none">
            ←
          </span>
          {workEnabled ? "Back to work" : "Back to home"}
        </Link>

        <article className="glass glow mt-4 overflow-hidden rounded-2xl border border-line sm:mt-5 sm:rounded-3xl">
          <div className="md:grid md:grid-cols-[minmax(0,34%)_minmax(0,1fr)] md:items-stretch xl:grid-cols-[minmax(0,360px)_minmax(0,1fr)]">
            <figure className="border-b border-line bg-gradient-to-br from-surface-2/90 to-surface/50 p-4 sm:p-5 md:flex md:items-center md:border-b-0 md:border-r md:p-6">
              <ProjectCover project={project} />
            </figure>

            <div className="flex min-w-0 flex-col">
              <header className="space-y-3 border-b border-line/60 p-5 sm:space-y-3.5 sm:p-6 md:p-7">
                <ProjectMeta project={project} />

                <div className="flex flex-wrap items-center gap-2">
                  <span
                    className={`rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide sm:text-[11px] ${
                      project.engagement_type === "support"
                        ? "bg-sky-400/20 text-sky-200"
                        : "bg-violet-400/20 text-violet-200"
                    }`}
                  >
                    {engagementLabel}
                  </span>
                  <ProjectContributionBadges project={project} size="xs" />
                </div>

                <div className="space-y-2">
                  <h1 className="text-xl font-bold leading-snug tracking-tight sm:text-2xl lg:text-[1.85rem] lg:leading-tight">
                    {project.title}
                  </h1>
                  {project.short_description && (
                    <p className="max-w-2xl text-sm leading-relaxed text-muted sm:text-[0.95rem]">
                      {project.short_description}
                    </p>
                  )}
                </div>

                {hasActions && (
                  <div className="flex flex-col gap-2 pt-1 sm:flex-row sm:flex-wrap">
                    {project.live_url && (
                      <a
                        href={project.live_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex min-h-10 items-center justify-center rounded-xl bg-white px-4 text-sm font-semibold text-black transition hover:bg-accent sm:min-h-0 sm:py-2"
                      >
                        Visit live site ↗
                      </a>
                    )}
                    {project.source_url && (
                      <a
                        href={project.source_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex min-h-10 items-center justify-center rounded-xl border border-line px-4 text-sm font-semibold transition hover:border-brand sm:min-h-0 sm:py-2"
                      >
                        View source
                      </a>
                    )}
                  </div>
                )}
              </header>

              <div className="space-y-5 p-5 sm:space-y-6 sm:p-6 md:p-7">
                {project.tech_stack && project.tech_stack.length > 0 && (
                  <section>
                    <SectionLabel>Tech stack</SectionLabel>
                    <div className="mt-2.5 flex flex-wrap gap-1.5 sm:gap-2">
                      {project.tech_stack.map((t) => (
                        <span
                          key={t}
                          className="rounded-lg bg-white/5 px-2.5 py-1 text-xs text-muted ring-1 ring-inset ring-white/5"
                        >
                          {t}
                        </span>
                      ))}
                    </div>
                  </section>
                )}

                {descriptionParagraphs.length > 0 && (
                  <section>
                    <SectionLabel>About this project</SectionLabel>
                    <div className="mt-2.5 space-y-2 text-sm leading-relaxed text-muted sm:text-[0.95rem]">
                      {descriptionParagraphs.map((p, i) => (
                        <p key={i}>{p}</p>
                      ))}
                    </div>
                  </section>
                )}

                {(project.client || project.experience?.company) && (
                  <footer className="flex flex-wrap gap-2 border-t border-line/60 pt-5">
                    {project.client && (
                      <span className="inline-flex items-center gap-2 rounded-full border border-line bg-white/[0.03] px-3 py-1.5 text-xs text-muted">
                        <span className="font-medium uppercase tracking-wider text-white/50">Client</span>
                        <span className="font-medium text-white/90">{project.client}</span>
                      </span>
                    )}
                    {project.experience?.company && project.work_context === "company" && (
                      <span className="inline-flex items-center gap-2 rounded-full border border-line bg-white/[0.03] px-3 py-1.5 text-xs text-muted">
                        <span className="font-medium uppercase tracking-wider text-white/50">Company</span>
                        <span className="font-medium text-white/90">{project.experience.company}</span>
                      </span>
                    )}
                  </footer>
                )}
              </div>
            </div>
          </div>
        </article>
      </main>
    </>
  );
}
