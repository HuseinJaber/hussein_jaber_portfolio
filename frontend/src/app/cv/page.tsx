import type { Metadata } from "next";
import Link from "next/link";
import { Libre_Baskerville } from "next/font/google";
import { getPortfolio } from "@/lib/api";
import { buildCvData } from "@/lib/cv";
import CvDocument from "@/components/cv/CvDocument";
import CvPageActions from "@/components/cv/CvPageActions";

const serif = Libre_Baskerville({
  subsets: ["latin"],
  weight: ["400", "700"],
  variable: "--font-cv",
  display: "swap",
});

export async function generateMetadata(): Promise<Metadata> {
  const data = await getPortfolio();
  const name = data?.profile.name ?? "Hussein Jaber";
  return {
    title: `${name} — Curriculum Vitae`,
    description: `Professional résumé of ${name}, ${data?.profile.title ?? "Full Stack Developer"}.`,
    robots: { index: true, follow: true },
  };
}

export default async function CvPage() {
  const portfolio = await getPortfolio();

  if (!portfolio) {
    return (
      <main className="cv-page flex min-h-screen items-center justify-center px-4 text-center">
        <div>
          <h1 className="text-2xl font-semibold text-slate-900">Backend not reachable</h1>
          <p className="mt-3 text-slate-600">
            The résumé page needs the Laravel API. Start the backend and refresh.
          </p>
          <Link href="/" className="mt-6 inline-block text-sm text-indigo-700 hover:underline">
            ← Back to portfolio
          </Link>
        </div>
      </main>
    );
  }

  const cvData = buildCvData(portfolio);

  return (
    <div className={`cv-page ${serif.variable}`}>
      <div className="cv-page-inner">
        <div className="no-print">
          <Link href="/" className="text-sm text-slate-600 transition hover:text-slate-900">
            ← Back to portfolio
          </Link>
        </div>

        <CvPageActions pdfUrl={cvData.resumePdfUrl} />
        <CvDocument data={cvData} />
      </div>
    </div>
  );
}
