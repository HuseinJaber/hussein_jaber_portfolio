import type { Experience as Exp } from "@/lib/types";
import Reveal from "@/components/ui/Reveal";
import SectionHeading from "@/components/ui/SectionHeading";

export default function Experience({ experiences }: { experiences: Exp[] }) {
  if (experiences.length === 0) return null;

  return (
    <section id="experience" className="mx-auto max-w-4xl px-4 py-24">
      <SectionHeading eyebrow="Career" title="Where I've worked" />

      <div className="relative mt-14 border-l border-line pl-8">
        {experiences.map((exp, i) => (
          <Reveal key={exp.id} delay={i} className="relative pb-10 last:pb-0">
            <span className="absolute -left-[41px] top-1 flex h-4 w-4 items-center justify-center rounded-full border-2 border-brand bg-bg">
              <span className="h-1.5 w-1.5 rounded-full bg-brand" />
            </span>
            <div className="glass rounded-2xl p-6">
              <div className="flex flex-wrap items-center justify-between gap-2">
                <h3 className="text-lg font-semibold">{exp.role}</h3>
                <span className="rounded-full bg-white/5 px-3 py-1 text-xs text-muted">
                  {exp.start_date} – {exp.is_current ? "Present" : exp.end_date}
                </span>
              </div>
              <p className="mt-1 text-sm text-accent">
                {exp.company}
                {exp.location ? ` · ${exp.location}` : ""}
              </p>
              <p className="mt-3 text-sm leading-relaxed text-muted">{exp.description}</p>
            </div>
          </Reveal>
        ))}
      </div>
    </section>
  );
}
