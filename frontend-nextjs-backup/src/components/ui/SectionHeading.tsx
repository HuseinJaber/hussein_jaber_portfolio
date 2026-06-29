import Reveal from "./Reveal";

interface Props {
  eyebrow: string;
  title: string;
  subtitle?: string;
  align?: "center" | "left";
}

export default function SectionHeading({ eyebrow, title, subtitle, align = "center" }: Props) {
  return (
    <div className={align === "center" ? "mx-auto max-w-2xl text-center" : "max-w-2xl"}>
      <Reveal>
        <span className="inline-flex items-center gap-2 rounded-full border border-line bg-white/5 px-3 py-1 text-xs font-medium uppercase tracking-widest text-accent">
          <span className="h-1.5 w-1.5 rounded-full bg-accent" />
          {eyebrow}
        </span>
      </Reveal>
      <Reveal delay={1}>
        <h2 className="mt-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">{title}</h2>
      </Reveal>
      {subtitle && (
        <Reveal delay={2}>
          <p className="mt-4 text-base text-muted">{subtitle}</p>
        </Reveal>
      )}
    </div>
  );
}
