import { Link } from "react-router-dom";
import { apiAssetUrl } from "@/lib/cv";

const DEFAULT_DOWNLOAD_LABEL = "Download CV";
const DEFAULT_VIEW_LABEL = "View custom CV";

export default function CvActions({
  resumeUrl,
  downloadLabel,
  viewLabel,
}: {
  resumeUrl: string | null | undefined;
  downloadLabel?: string | null;
  viewLabel?: string | null;
}) {
  const pdfUrl = apiAssetUrl(resumeUrl);
  const downloadText = downloadLabel?.trim() || DEFAULT_DOWNLOAD_LABEL;
  const viewText = viewLabel?.trim() || DEFAULT_VIEW_LABEL;

  return (
    <>
      {pdfUrl && (
        <a
          href={pdfUrl}
          target="_blank"
          rel="noopener noreferrer"
          download
          className="hero-cta rounded-xl bg-white px-6 py-3 font-semibold text-black transition hover:bg-accent"
        >
          {downloadText}
        </a>
      )}
      <Link
        to="/cv"
        className="hero-cta rounded-xl border border-line bg-white/5 px-6 py-3 font-semibold transition hover:border-brand"
      >
        {viewText}
      </Link>
    </>
  );
}
