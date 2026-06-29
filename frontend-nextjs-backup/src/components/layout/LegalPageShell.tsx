import type { ReactNode } from "react";
import Link from "next/link";
import Aurora from "@/components/ui/Aurora";

export default function LegalPageShell({
  title,
  children,
}: {
  title: string;
  children: ReactNode;
}) {
  return (
    <>
      <Aurora />
      <main className="mx-auto max-w-3xl px-4 py-16">
        <Link href="/" className="text-sm text-muted transition hover:text-white">
          ← Back to home
        </Link>

        <p className="mt-8 text-xs uppercase tracking-widest text-accent">Legal</p>
        <h1 className="mt-3 text-4xl font-bold tracking-tight">{title}</h1>
        <p className="mt-3 text-sm text-muted">Last updated: June 28, 2026</p>

        <div className="mt-10 space-y-8 text-base leading-relaxed text-muted">{children}</div>

        <nav className="mt-12 flex flex-wrap gap-x-4 gap-y-2 border-t border-line pt-8 text-sm">
          <Link href="/privacy" className="text-accent hover:underline">
            Privacy Policy
          </Link>
          <Link href="/cookies" className="text-accent hover:underline">
            Cookie Policy
          </Link>
          <Link href="/terms" className="text-accent hover:underline">
            Terms of Use
          </Link>
        </nav>
      </main>
    </>
  );
}
