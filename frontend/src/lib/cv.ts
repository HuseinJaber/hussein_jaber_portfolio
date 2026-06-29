import { API_URL } from "./api";
import type {
  Certification,
  Education,
  Experience,
  PortfolioData,
  Profile,
  Skill,
  SocialLink,
} from "./types";
import { cvExtras } from "./cv-content";

export function apiAssetUrl(path: string | null | undefined): string | null {
  if (!path) return null;
  if (path.startsWith("http://") || path.startsWith("https://")) return path;
  const base = API_URL.replace(/\/api\/?$/, "");
  return `${base}${path.startsWith("/") ? path : `/${path}`}`;
}

/** Concise professional summary for the résumé (editable via admin → Profile → Bio). */
export function cvSummary(profile: Profile): string {
  if (profile.bio?.trim()) return profile.bio.trim();
  if (profile.headline?.trim()) return profile.headline.trim();
  return `${profile.title} based in ${profile.location ?? "Beirut, Lebanon"}.`;
}

export function formatDateRange(
  start: string | null,
  end: string | null,
  isCurrent = false,
): string {
  const startPart = start?.trim() ?? "";
  const endPart = isCurrent ? "Present" : (end?.trim() ?? "");
  if (startPart && endPart) return `${startPart} – ${endPart}`;
  return startPart || endPart || "";
}

export function cvSkillColumns(skills: Skill[]): [string[], string[]] {
  const names = skills.map((s) => s.name);
  const mid = Math.ceil(names.length / 2);
  return [names.slice(0, mid), names.slice(mid)];
}

export function socialLabel(link: SocialLink): string {
  return link.label ?? link.platform;
}

export function experienceDetail(exp: Experience): string {
  return exp.description?.trim() ?? "";
}

export function educationLine(edu: Education): string {
  const parts = [edu.degree, edu.institution].filter(Boolean);
  return parts.join(" — ");
}

export function certificationLine(cert: Certification): string {
  const date = cert.issued_at?.trim();
  const base = `${cert.title} (${cert.issuer})`;
  return date ? `${base} — ${date}` : base;
}

export type CvData = PortfolioData & {
  summary: string;
  nationality: string;
  languages: typeof cvExtras.languages;
  resumePdfUrl: string | null;
};

export function buildCvData(data: PortfolioData): CvData {
  return {
    ...data,
    summary: cvSummary(data.profile),
    nationality: cvExtras.nationality,
    languages: cvExtras.languages,
    resumePdfUrl: apiAssetUrl(data.profile.resume_url),
  };
}
