"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { AnimatePresence, motion } from "motion/react";
import type { Project, SectionCopy } from "@/lib/types";
import SectionHeading from "@/components/ui/SectionHeading";
import { ProjectContributionBadges } from "@/components/ui/ProjectContributionBadges";

const PAGE_SIZE = 6;

const engagementLabels: Record<Project["engagement_type"], string> = {
  development: "Development",
  support: "Support",
};

function ProjectCard({ project }: { project: Project }) {
  return (
    <Link
      to={`/projects/${project.slug}`}
      className="group block h-full overflow-hidden rounded-2xl border border-line bg-white/[0.02] transition hover:-translate-y-1 hover:border-brand"
    >
      <div className="relative aspect-video w-full overflow-hidden bg-gradient-to-br from-surface-2 to-surface">
        {project.cover_image ? (
          <img
            src={project.cover_image}
            alt={project.title}
            className="absolute inset-0 h-full w-full object-contain p-4"
          />
        ) : (
          <div className="flex h-full items-center justify-center text-5xl font-bold text-white/10">
            {project.title.charAt(0)}
          </div>
        )}
        <span
          className={`absolute left-3 top-3 rounded-full px-2 py-0.5 text-xs font-semibold ${
            project.engagement_type === "support"
              ? "bg-sky-400/90 text-black"
              : "bg-violet-400/90 text-black"
          }`}
        >
          {engagementLabels[project.engagement_type]}
        </span>
        {project.is_featured && (
          <span className="absolute right-3 top-3 rounded-full bg-amber-400/90 px-2 py-0.5 text-xs font-semibold text-black">
            Featured
          </span>
        )}
      </div>
      <div className="p-4 sm:p-5">
        <p className="text-xs uppercase tracking-widest text-accent">
          {(project.categories?.length ? project.categories : [project.category]).join(" · ")}
          {project.sites_count ? ` · ${project.sites_count}+ sites` : ""}
          {project.work_context === "freelance" ? " · Freelance" : ""}
          {project.work_context === "company" && project.experience?.company
            ? ` · ${project.experience.company}`
            : ""}
        </p>
        <h3 className="mt-2 text-base font-semibold sm:text-lg">{project.title}</h3>
        <div className="mt-2">
          <ProjectContributionBadges project={project} size="xs" />
        </div>
        <p className="mt-2 line-clamp-2 text-sm text-muted">{project.short_description}</p>
        <div className="mt-4 flex flex-wrap gap-1.5">
          {(project.tech_stack ?? []).slice(0, 4).map((t) => (
            <span key={t} className="rounded-md bg-white/5 px-2 py-0.5 text-xs text-muted">
              {t}
            </span>
          ))}
        </div>
      </div>
    </Link>
  );
}

export default function Work({ projects, copy }: { projects: Project[]; copy: SectionCopy }) {
  const [category, setCategory] = useState("All");
  const [visibleCount, setVisibleCount] = useState(PAGE_SIZE);
  const scrollToWorkTop = useRef(false);

  const NAV_OFFSET = 96;

  const categories = useMemo(
    () => [
      "All",
      ...Array.from(
        new Set(projects.flatMap((p) => (p.categories?.length ? p.categories : [p.category]))),
      ),
    ],
    [projects],
  );

  const filtered = useMemo(
    () =>
      projects.filter((p) => {
        const projectCategories = p.categories?.length ? p.categories : [p.category];
        return category === "All" || projectCategories.includes(category);
      }),
    [projects, category],
  );

  useEffect(() => {
    if (!scrollToWorkTop.current) return;

    scrollToWorkTop.current = false;

    const timer = window.setTimeout(() => {
      const section = document.getElementById("work");
      if (!section) return;

      const top = section.getBoundingClientRect().top + window.scrollY - NAV_OFFSET;
      window.scrollTo({ top: Math.max(0, top), behavior: "smooth" });
    }, 320);

    return () => window.clearTimeout(timer);
  }, [visibleCount]);

  if (projects.length === 0) return null;

  const initialVisible = Math.min(PAGE_SIZE, filtered.length);
  const effectiveVisible = Math.max(initialVisible, Math.min(visibleCount, filtered.length));
  const visibleProjects = filtered.slice(0, effectiveVisible);

  const canShowMore = effectiveVisible < filtered.length;
  const canShowLess = effectiveVisible > PAGE_SIZE && filtered.length > PAGE_SIZE;

  function showMore() {
    setVisibleCount((count) => Math.min(count + PAGE_SIZE, filtered.length));
  }

  function showLess() {
    scrollToWorkTop.current = true;
    setVisibleCount((count) => Math.max(PAGE_SIZE, count - PAGE_SIZE));
  }

  return (
    <section id="work" className="mx-auto max-w-6xl px-4 py-16">
      <SectionHeading
        eyebrow={copy.eyebrow}
        title={copy.title}
        subtitle={copy.subtitle ?? undefined}
        align={copy.align}
      />

      {categories.length > 2 && (
        <div className="mt-8 flex flex-wrap justify-center gap-2">
          {categories.map((cat) => (
            <button
              key={cat}
              type="button"
              onClick={() => {
                setCategory(cat);
                setVisibleCount(PAGE_SIZE);
              }}
              className={`rounded-full px-3 py-1 text-xs transition ${
                category === cat
                  ? "border border-brand text-white"
                  : "border border-line/60 text-muted hover:text-white"
              }`}
            >
              {cat}
            </button>
          ))}
        </div>
      )}

      {filtered.length === 0 ? (
        <p className="mt-12 text-center text-sm text-muted">
          No projects match this category. Try another filter.
        </p>
      ) : (
        <>
          <motion.div layout className="mt-8 grid gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
            <AnimatePresence mode="popLayout">
              {visibleProjects.map((project, index) => (
                <motion.div
                  key={project.id}
                  layout
                  initial={{ opacity: 0, y: 28, scale: 0.96 }}
                  animate={{ opacity: 1, y: 0, scale: 1 }}
                  exit={{ opacity: 0, y: -16, scale: 0.96 }}
                  transition={{
                    duration: 0.4,
                    delay: index >= PAGE_SIZE ? ((index - PAGE_SIZE) % PAGE_SIZE) * 0.05 : 0,
                    ease: [0.22, 1, 0.36, 1],
                  }}
                >
                  <ProjectCard project={project} />
                </motion.div>
              ))}
            </AnimatePresence>
          </motion.div>

          <div className="mt-8 flex flex-col items-center gap-4 border-t border-line/60 pt-6">
            <p className="text-xs font-medium uppercase tracking-widest text-muted">
              {effectiveVisible === filtered.length
                ? `Showing all ${filtered.length} project${filtered.length === 1 ? "" : "s"}`
                : `Showing ${effectiveVisible} of ${filtered.length} projects`}
            </p>

            {(canShowMore || canShowLess) && (
              <motion.div
                layout
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                className="flex w-full flex-col items-stretch justify-center gap-3 sm:w-auto sm:flex-row sm:items-center"
              >
                {canShowLess && (
                  <motion.button
                    type="button"
                    layout
                    initial={{ opacity: 0, y: 8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: 8 }}
                    onClick={showLess}
                    className="min-h-11 w-full rounded-xl border border-line px-6 py-2.5 text-sm font-semibold text-muted transition hover:border-white/30 hover:text-white sm:w-auto"
                  >
                    Show less
                  </motion.button>
                )}
                {canShowMore && (
                  <motion.button
                    type="button"
                    layout
                    initial={{ opacity: 0, y: 8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: 8 }}
                    onClick={showMore}
                    className="min-h-11 w-full rounded-xl bg-white px-6 py-2.5 text-sm font-semibold text-black transition hover:bg-accent sm:w-auto"
                  >
                    Show more
                    <span className="ml-1.5 font-normal text-black/60">
                      (+{Math.min(PAGE_SIZE, filtered.length - effectiveVisible)})
                    </span>
                  </motion.button>
                )}
              </motion.div>
            )}
          </div>
        </>
      )}
    </section>
  );
}
