import type { Education, Profile } from "@/lib/types";
import Reveal from "@/components/ui/Reveal";
import SectionHeading from "@/components/ui/SectionHeading";

export default function About({
  profile,
  education,
}: {
  profile: Profile;
  education: Education[];
}) {
  return (
    <section id="about" className="mx-auto max-w-6xl px-4 py-24">
      <SectionHeading eyebrow="About" title="A bit about me" align="left" />
      <div className="mt-10 grid gap-10 lg:grid-cols-[1.4fr_1fr]">
        <Reveal>
          <div className="space-y-5 text-lg leading-relaxed text-muted">
            {(profile.about ?? profile.bio ?? "").split("\n").filter(Boolean).map((p, i) => (
              <p key={i}>{p}</p>
            ))}
          </div>
        </Reveal>

        <Reveal delay={1}>
          <div className="glass rounded-2xl p-6">
            <h3 className="font-semibold">Education</h3>
            <ul className="mt-4 space-y-4">
              {education.map((e) => (
                <li key={e.id} className="border-l-2 border-brand pl-4">
                  <p className="font-medium">{e.degree}</p>
                  <p className="text-sm text-muted">
                    {e.institution} · {e.start_date}–{e.end_date}
                  </p>
                </li>
              ))}
              {education.length === 0 && (
                <li className="text-sm text-muted">Add your education from the admin dashboard.</li>
              )}
            </ul>
          </div>
        </Reveal>
      </div>
    </section>
  );
}
