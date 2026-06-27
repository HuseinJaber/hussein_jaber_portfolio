import Link from "next/link";
import { notFound } from "next/navigation";
import { getProject } from "@/lib/api";
import Aurora from "@/components/ui/Aurora";

export default async function ProjectPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const project = await getProject(slug);

  if (!project) notFound();

  return (
    <>
      <Aurora />
      <main className="mx-auto max-w-4xl px-4 py-24">
        <Link href="/#work" className="text-sm text-muted transition hover:text-white">
          ← Back to work
        </Link>

        <p className="mt-8 text-xs uppercase tracking-widest text-accent">
          {project.category}
          {project.year ? ` · ${project.year}` : ""}
        </p>
        <h1 className="mt-3 text-4xl font-bold tracking-tight sm:text-5xl">{project.title}</h1>
        {project.short_description && (
          <p className="mt-4 text-lg text-muted">{project.short_description}</p>
        )}

        <div className="mt-6 flex flex-wrap gap-3">
          {project.live_url && (
            <a
              href={project.live_url}
              target="_blank"
              rel="noopener noreferrer"
              className="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-black transition hover:bg-accent"
            >
              Visit live site ↗
            </a>
          )}
          {project.source_url && (
            <a
              href={project.source_url}
              target="_blank"
              rel="noopener noreferrer"
              className="rounded-xl border border-line px-5 py-2.5 text-sm font-semibold transition hover:border-brand"
            >
              View source
            </a>
          )}
        </div>

        <div className="mt-10 flex h-64 items-center justify-center overflow-hidden rounded-2xl border border-line bg-gradient-to-br from-surface-2 to-surface">
          {project.cover_image ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img src={project.cover_image} alt={project.title} className="h-full w-full object-cover" />
          ) : (
            <span className="text-7xl font-bold text-white/10">{project.title.charAt(0)}</span>
          )}
        </div>

        {project.tech_stack && project.tech_stack.length > 0 && (
          <div className="mt-8 flex flex-wrap gap-2">
            {project.tech_stack.map((t) => (
              <span key={t} className="rounded-lg bg-white/5 px-3 py-1 text-sm text-muted">
                {t}
              </span>
            ))}
          </div>
        )}

        {project.description && (
          <div className="mt-8 space-y-4 text-lg leading-relaxed text-muted">
            {project.description.split("\n").filter(Boolean).map((p, i) => (
              <p key={i}>{p}</p>
            ))}
          </div>
        )}

        {project.client && (
          <p className="mt-10 text-sm text-muted">
            Client: <span className="text-white">{project.client}</span>
          </p>
        )}
      </main>
    </>
  );
}
