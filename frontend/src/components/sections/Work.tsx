"use client";

import { useState } from "react";
import Link from "next/link";
import { AnimatePresence, motion } from "motion/react";
import type { Project } from "@/lib/types";
import SectionHeading from "@/components/ui/SectionHeading";

export default function Work({ projects }: { projects: Project[] }) {
  const categories = ["All", ...Array.from(new Set(projects.map((p) => p.category)))];
  const [active, setActive] = useState("All");

  if (projects.length === 0) return null;

  const filtered = active === "All" ? projects : projects.filter((p) => p.category === active);

  return (
    <section id="work" className="mx-auto max-w-6xl px-4 py-24">
      <SectionHeading
        eyebrow="Portfolio"
        title="Selected work"
        subtitle="A few projects I'm proud of. Each one solved a real problem for a real client."
      />

      <div className="mt-10 flex flex-wrap justify-center gap-2">
        {categories.map((cat) => (
          <button
            key={cat}
            onClick={() => setActive(cat)}
            className={`rounded-full px-4 py-1.5 text-sm transition ${
              active === cat
                ? "bg-white text-black"
                : "border border-line text-muted hover:text-white"
            }`}
          >
            {cat}
          </button>
        ))}
      </div>

      <motion.div layout className="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <AnimatePresence mode="popLayout">
          {filtered.map((project) => (
            <motion.div
              key={project.id}
              layout
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.95 }}
              transition={{ duration: 0.35 }}
            >
              <Link
                href={`/projects/${project.slug}`}
                className="group block h-full overflow-hidden rounded-2xl border border-line bg-white/[0.02] transition hover:-translate-y-1 hover:border-brand"
              >
                <div className="relative h-44 overflow-hidden bg-gradient-to-br from-surface-2 to-surface">
                  {project.cover_image ? (
                    // eslint-disable-next-line @next/next/no-img-element
                    <img
                      src={project.cover_image}
                      alt={project.title}
                      className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />
                  ) : (
                    <div className="flex h-full items-center justify-center text-5xl font-bold text-white/10">
                      {project.title.charAt(0)}
                    </div>
                  )}
                  {project.is_featured && (
                    <span className="absolute left-3 top-3 rounded-full bg-amber-400/90 px-2 py-0.5 text-xs font-semibold text-black">
                      Featured
                    </span>
                  )}
                </div>
                <div className="p-5">
                  <p className="text-xs uppercase tracking-widest text-accent">{project.category}</p>
                  <h3 className="mt-2 text-lg font-semibold">{project.title}</h3>
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
            </motion.div>
          ))}
        </AnimatePresence>
      </motion.div>
    </section>
  );
}
