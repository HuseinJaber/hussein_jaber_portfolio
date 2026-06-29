import type { Certification, SectionCopy } from "@/lib/types";
import Reveal from "@/components/ui/Reveal";
import SectionHeading from "@/components/ui/SectionHeading";

const issuerStyles: Record<string, string> = {
  freeCodeCamp: "from-orange-500/20 to-orange-600/10 text-orange-300",
  Coursera: "from-blue-500/20 to-blue-600/10 text-blue-300",
  Esri: "from-sky-500/20 to-sky-600/10 text-sky-300",
  "Mercy Corps": "from-emerald-500/20 to-emerald-600/10 text-emerald-300",
};

function issuerClass(issuer: string) {
  return issuerStyles[issuer] ?? "from-brand/20 to-brand-2/10 text-accent";
}

export default function Certifications({
  certifications,
  copy,
}: {
  certifications: Certification[];
  copy: SectionCopy;
}) {
  if (certifications.length === 0) return null;

  return (
    <section id="certifications" className="mx-auto max-w-6xl px-4 py-16">
      <SectionHeading
        eyebrow={copy.eyebrow}
        title={copy.title}
        subtitle={copy.subtitle ?? undefined}
        align={copy.align}
      />

      <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        {certifications.map((cert, i) => {
          const inner = (
            <>
              <div
                className={`inline-flex rounded-lg bg-gradient-to-br px-2.5 py-1 text-xs font-medium ${issuerClass(cert.issuer)}`}
              >
                {cert.issuer}
              </div>
              <h3 className="mt-4 text-base font-semibold leading-snug">{cert.title}</h3>
              {cert.issued_at && (
                <p className="mt-2 text-sm text-muted">Issued {cert.issued_at}</p>
              )}
              {cert.has_credential_pdf && cert.credential_pdf_url && (
                <span className="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-accent transition group-hover:gap-2.5">
                  <svg width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" aria-hidden>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                  View certificate PDF
                </span>
              )}
            </>
          );

          return (
            <Reveal key={cert.id} delay={i % 6}>
              {cert.has_credential_pdf && cert.credential_pdf_url ? (
                <a
                  href={cert.credential_pdf_url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="group flex h-full flex-col rounded-2xl border border-line bg-white/[0.02] p-6 transition hover:-translate-y-1 hover:border-brand hover:bg-white/[0.04]"
                >
                  {inner}
                </a>
              ) : (
                <div className="group flex h-full flex-col rounded-2xl border border-line bg-white/[0.02] p-6">
                  {inner}
                </div>
              )}
            </Reveal>
          );
        })}
      </div>
    </section>
  );
}
