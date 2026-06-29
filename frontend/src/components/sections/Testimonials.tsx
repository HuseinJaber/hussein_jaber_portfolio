import type { SectionCopy, Testimonial } from "@/lib/types";
import Reveal from "@/components/ui/Reveal";
import SectionHeading from "@/components/ui/SectionHeading";

export default function Testimonials({
  testimonials,
  copy,
}: {
  testimonials: Testimonial[];
  copy: SectionCopy;
}) {
  if (testimonials.length === 0) return null;

  return (
    <section id="testimonials" className="mx-auto max-w-6xl px-4 py-16">
      <SectionHeading
        eyebrow={copy.eyebrow}
        title={copy.title}
        subtitle={copy.subtitle ?? undefined}
        align={copy.align}
      />

      <div className="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        {testimonials.map((t, i) => (
          <Reveal key={t.id} delay={i}>
            <figure className="flex h-full flex-col rounded-2xl border border-line bg-white/[0.02] p-6">
              <div className="text-amber-400">{"★".repeat(t.rating)}<span className="text-white/15">{"★".repeat(5 - t.rating)}</span></div>
              <blockquote className="mt-4 flex-1 text-sm leading-relaxed text-muted">
                “{t.content}”
              </blockquote>
              <figcaption className="mt-6 flex items-center gap-3">
                <span className="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-brand to-brand-2 text-sm font-semibold text-white">
                  {t.name.charAt(0)}
                </span>
                <div>
                  <p className="text-sm font-medium text-white">{t.name}</p>
                  <p className="text-xs text-muted">
                    {t.role}{t.company ? `, ${t.company}` : ""}
                  </p>
                </div>
              </figcaption>
            </figure>
          </Reveal>
        ))}
      </div>
    </section>
  );
}
