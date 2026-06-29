import { Link } from "react-router-dom";
import type { Profile, SectionCopy, SocialLink } from "@/lib/types";
import { SocialIcon } from "@/components/ui/icons";
import CookieSettingsLink from "@/components/CookieSettingsLink";
import FooterNewsletter from "@/components/layout/FooterNewsletter";
import { isSectionEnabled } from "@/lib/sections";
import { apiAssetUrl } from "@/lib/cv";

export default function Footer({
  profile,
  socials,
  newsletterCopy,
}: {
  profile: Profile;
  socials: SocialLink[];
  newsletterCopy: SectionCopy;
}) {
  const pdfUrl = apiAssetUrl(profile.resume_url);

  return (
    <footer className="border-t border-line pt-8">
      {isSectionEnabled(profile.sections, "newsletter") && (
        <FooterNewsletter copy={newsletterCopy} />
      )}

      <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-4 py-8 sm:flex-row">
        <div className="text-center sm:text-left">
          <p className="font-semibold">{profile.name}</p>
          <p className="text-sm text-muted">{profile.title}</p>
        </div>

        <div className="flex items-center gap-3">
          {socials.map((s) => (
            <a
              key={s.id}
              href={s.url}
              target="_blank"
              rel="noopener noreferrer"
              aria-label={s.label ?? s.platform}
              className={`flex h-10 w-10 items-center justify-center rounded-xl border border-line bg-white/5 text-muted transition hover:-translate-y-0.5 hover:text-white ${
                (s.icon ?? s.platform).toLowerCase() === "whatsapp"
                  ? "hover:border-emerald-400/50 hover:bg-emerald-400/10 hover:text-emerald-300"
                  : "hover:border-brand"
              }`}
            >
              <SocialIcon name={s.icon ?? s.platform} className="h-5 w-5" />
            </a>
          ))}
        </div>

        <div className="flex flex-col items-center gap-2 text-center sm:items-end sm:text-right">
          <p className="text-xs text-muted">
            © {new Date().getFullYear()} {profile.name}. All rights reserved.
          </p>
          <div className="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 sm:justify-end">
            <Link to="/cv" className="text-xs text-muted underline-offset-2 transition hover:text-white hover:underline">
              View résumé
            </Link>
            {pdfUrl && (
              <>
                <span className="text-xs text-line">·</span>
                <a
                  href={pdfUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  download
                  className="text-xs text-muted underline-offset-2 transition hover:text-white hover:underline"
                >
                  Download CV
                </a>
              </>
            )}
            <span className="text-xs text-line">·</span>
            <Link to="/privacy" className="text-xs text-muted underline-offset-2 transition hover:text-white hover:underline">
              Privacy
            </Link>
            <span className="text-xs text-line">·</span>
            <Link to="/cookies" className="text-xs text-muted underline-offset-2 transition hover:text-white hover:underline">
              Cookies
            </Link>
            <span className="text-xs text-line">·</span>
            <Link to="/terms" className="text-xs text-muted underline-offset-2 transition hover:text-white hover:underline">
              Terms
            </Link>
            <span className="text-xs text-line">·</span>
            <CookieSettingsLink />
          </div>
        </div>
      </div>
    </footer>
  );
}
