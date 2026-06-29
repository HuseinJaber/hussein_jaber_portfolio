import type { SectionCopy, Service } from "@/lib/types";
import Reveal from "@/components/ui/Reveal";
import SectionHeading from "@/components/ui/SectionHeading";
import { ServiceIcon } from "@/components/ui/icons";

export default function Services({ services, copy }: { services: Service[]; copy: SectionCopy }) {
  if (services.length === 0) return null;

  return (
    <section id="services" className="mx-auto max-w-6xl px-4 py-16">
      <SectionHeading
        eyebrow={copy.eyebrow}
        title={copy.title}
        subtitle={copy.subtitle ?? undefined}
        align={copy.align}
      />

      <div className="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        {services.map((service, i) => (
          <Reveal key={service.id} delay={i}>
            <div className="group h-full rounded-2xl border border-line bg-white/[0.02] p-6 transition hover:-translate-y-1 hover:border-brand hover:bg-white/[0.04]">
              <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-brand/20 to-brand-2/20 text-accent transition group-hover:scale-110">
                <ServiceIcon name={service.icon} className="h-6 w-6" />
              </div>
              <h3 className="mt-5 text-lg font-semibold">{service.title}</h3>
              <p className="mt-2 text-sm leading-relaxed text-muted">{service.description}</p>
            </div>
          </Reveal>
        ))}
      </div>
    </section>
  );
}
