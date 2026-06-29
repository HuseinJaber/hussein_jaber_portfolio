import { Link } from "react-router-dom";
import { apiAssetUrl } from "@/lib/cv";

export default function CvActions({
  resumeUrl,
}: {
  resumeUrl: string | null | undefined;
}) {
  const pdfUrl = apiAssetUrl(resumeUrl);

  return (
    <>
      <Link
        to="/cv"
        className="hero-cta rounded-xl border border-line bg-white/5 px-6 py-3 font-semibold transition hover:border-brand"
      >
        View CV
      </Link>
      {pdfUrl && (
        <a
          href={pdfUrl}
          target="_blank"
          rel="noopener noreferrer"
          download
          className="hero-cta rounded-xl border border-line bg-white/5 px-6 py-3 font-semibold transition hover:border-brand"
        >
          Download CV
        </a>
      )}
    </>
  );
}
