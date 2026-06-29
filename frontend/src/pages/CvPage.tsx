import { Link } from "react-router-dom";
import PageMeta from "@/components/PageMeta";
import CvDocument from "@/components/cv/CvDocument";
import CvPageActions from "@/components/cv/CvPageActions";
import { usePortfolio } from "@/hooks/usePortfolio";
import { buildCvData } from "@/lib/cv";

export default function CvPage() {
  const { data: portfolio, loading } = usePortfolio();

  if (loading) {
    return (
      <main className="cv-page flex min-h-screen items-center justify-center px-4 text-center">
        <p className="text-slate-600">Loading résumé…</p>
      </main>
    );
  }

  if (!portfolio) {
    return (
      <main className="cv-page flex min-h-screen items-center justify-center px-4 text-center">
        <div>
          <h1 className="text-2xl font-semibold text-slate-900">Backend not reachable</h1>
          <p className="mt-3 text-slate-600">
            The résumé page needs the Laravel API. Start the backend and refresh.
          </p>
          <Link to="/" className="mt-6 inline-block text-sm text-indigo-700 hover:underline">
            ← Back to portfolio
          </Link>
        </div>
      </main>
    );
  }

  const cvData = buildCvData(portfolio);
  const name = portfolio.profile.name;

  return (
    <>
      <PageMeta
        title={`${name} — Curriculum Vitae`}
        description={`Professional résumé of ${name}, ${portfolio.profile.title}.`}
        robots="index, follow"
      />
      <div className="cv-page">
        <div className="cv-page-inner">
          <div className="no-print">
            <Link to="/" className="text-sm text-slate-600 transition hover:text-slate-900">
              ← Back to portfolio
            </Link>
          </div>

          <CvPageActions pdfUrl={cvData.resumePdfUrl} />
          <CvDocument data={cvData} />
        </div>
      </div>
    </>
  );
}
