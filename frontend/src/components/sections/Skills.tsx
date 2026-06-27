"use client";

import { motion } from "motion/react";
import type { Skill } from "@/lib/types";
import SectionHeading from "@/components/ui/SectionHeading";

export default function Skills({ skills }: { skills: Skill[] }) {
  if (skills.length === 0) return null;

  const categories = Array.from(new Set(skills.map((s) => s.category)));

  return (
    <section id="skills" className="mx-auto max-w-6xl px-4 py-24">
      <SectionHeading
        eyebrow="Skills"
        title="My technical toolbox"
        subtitle="The technologies I use to build reliable, modern products."
      />

      <div className="mt-14 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        {categories.map((cat) => (
          <div key={cat} className="glass rounded-2xl p-6">
            <h3 className="mb-5 text-sm font-semibold uppercase tracking-widest text-accent">
              {cat}
            </h3>
            <div className="space-y-4">
              {skills
                .filter((s) => s.category === cat)
                .map((skill) => (
                  <div key={skill.id}>
                    <div className="mb-1.5 flex items-center justify-between text-sm">
                      <span>{skill.name}</span>
                      <span className="text-muted">{skill.level}%</span>
                    </div>
                    <div className="h-2 overflow-hidden rounded-full bg-white/5">
                      <motion.div
                        className="h-full rounded-full bg-gradient-to-r from-brand to-accent"
                        initial={{ width: 0 }}
                        whileInView={{ width: `${skill.level}%` }}
                        viewport={{ once: true }}
                        transition={{ duration: 1, ease: "easeOut" }}
                      />
                    </div>
                  </div>
                ))}
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}
