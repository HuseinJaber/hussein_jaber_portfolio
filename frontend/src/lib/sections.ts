import type { SectionCopy, SectionCopyMap, SectionKey, SectionSettings } from "@/lib/types";

export const DEFAULT_SECTION_COPY: SectionCopyMap = {
  about: {
    nav_label: "About",
    eyebrow: "About",
    title: "Building Laravel Products That Perform",
    subtitle:
      "Laravel developer in Beirut with 3+ years building e-commerce platforms, corporate websites, and custom web applications for businesses across the Middle East.",
    align: "left",
  },
  services: {
    nav_label: "Services",
    eyebrow: "Services",
    title: "Laravel Development Services",
    subtitle:
      "End-to-end Laravel development — from architecture and Livewire admin panels to JavaScript frontends, integrations, and long-term support.",
    align: "center",
  },
  skills: {
    nav_label: "Skills",
    eyebrow: "Technical Expertise",
    title: "Skills & Technologies",
    subtitle:
      "A Laravel-first stack — PHP, Livewire, Filament, JavaScript, and MySQL — built for fast, secure, and maintainable web applications.",
    align: "center",
  },
  work: {
    nav_label: "Work",
    eyebrow: "Portfolio",
    title: "Featured Projects",
    subtitle:
      "Laravel e-commerce platforms, corporate websites, and ongoing support engagements for brands such as Midis Group — built to ship and scale.",
    align: "center",
  },
  experience: {
    nav_label: "Experience",
    eyebrow: "Professional Experience",
    title: "Career Highlights",
    subtitle:
      "From GIS internships to full-time Laravel development — delivering production-ready PHP applications for regional and international clients.",
    align: "center",
  },
  certifications: {
    nav_label: "Certifications",
    eyebrow: "Certifications",
    title: "Professional Credentials",
    subtitle:
      "Verified training in front-end development, JavaScript, responsive design, and GIS — with certificates available to view on this site.",
    align: "center",
  },
  testimonials: {
    nav_label: "Testimonials",
    eyebrow: "Client Feedback",
    title: "Trusted by Clients",
    subtitle: "Delivering polished, reliable work that helps teams launch faster and look their best.",
    align: "center",
  },
  contact: {
    nav_label: "Contact",
    eyebrow: "Get in Touch",
    title: "Start Your Next Project",
    subtitle:
      "Need a Laravel developer for a new build, custom API, or ongoing support? Share your goals — I typically respond within 24 hours.",
    align: "center",
  },
  newsletter: {
    nav_label: "Newsletter",
    eyebrow: "Newsletter",
    title: "Insights & Updates",
    subtitle:
      "Occasional notes from {name} — new Laravel projects, JavaScript tips, and availability updates. No spam, ever.",
    align: "center",
  },
};

export const DEFAULT_SECTION_ORDER: SectionKey[] = [
  "about",
  "services",
  "skills",
  "work",
  "experience",
  "certifications",
  "testimonials",
  "contact",
  "newsletter",
];

/** Sections rendered on the home page body (excludes footer-only newsletter). */
export const MAIN_SECTION_KEYS: SectionKey[] = DEFAULT_SECTION_ORDER.filter(
  (key) => key !== "newsletter",
);

export const TRACKED_SECTIONS: SectionKey[] = [...MAIN_SECTION_KEYS];

const NAV_EXCLUDED = new Set<SectionKey>(["newsletter"]);

export function isSectionEnabled(
  sections: SectionSettings | undefined,
  key: SectionKey,
): boolean {
  return sections?.[key] !== false;
}

export const DEFAULT_SECTIONS: SectionSettings = {
  about: true,
  services: true,
  skills: true,
  work: true,
  experience: true,
  certifications: true,
  testimonials: true,
  contact: true,
  newsletter: true,
};

export function resolveSections(sections?: Partial<SectionSettings>): SectionSettings {
  return { ...DEFAULT_SECTIONS, ...sections };
}

export function resolveSectionCopy(copy?: Partial<SectionCopyMap>): SectionCopyMap {
  const resolved = { ...DEFAULT_SECTION_COPY };

  if (!copy) return resolved;

  for (const key of DEFAULT_SECTION_ORDER) {
    const stored = copy[key];
    if (!stored) continue;

    resolved[key] = {
      nav_label: stored.nav_label?.trim() || resolved[key].nav_label,
      eyebrow: stored.eyebrow?.trim() || resolved[key].eyebrow,
      title: stored.title?.trim() || resolved[key].title,
      subtitle:
        stored.subtitle === undefined
          ? resolved[key].subtitle
          : stored.subtitle?.trim() || null,
      align: stored.align === "left" ? "left" : "center",
    };
  }

  return resolved;
}

export function sectionCopyFor(
  copy: SectionCopyMap,
  key: SectionKey,
  vars?: { name?: string },
): SectionCopy {
  const item = copy[key];

  if (!vars?.name || !item.subtitle) {
    return item;
  }

  return {
    ...item,
    subtitle: item.subtitle.replaceAll("{name}", vars.name),
  };
}

export function resolveSectionOrder(order?: SectionKey[] | null): SectionKey[] {
  if (!order?.length) return DEFAULT_SECTION_ORDER;

  const valid = new Set(DEFAULT_SECTION_ORDER);
  const seen = new Set<SectionKey>();
  const resolved: SectionKey[] = [];

  for (const key of order) {
    if (valid.has(key) && !seen.has(key)) {
      resolved.push(key);
      seen.add(key);
    }
  }

  for (const key of DEFAULT_SECTION_ORDER) {
    if (!seen.has(key)) resolved.push(key);
  }

  return resolved;
}

export function enabledNavSections(
  sections: SectionSettings | undefined,
  order?: SectionKey[] | null,
  copy?: SectionCopyMap,
) {
  const resolvedCopy = copy ?? DEFAULT_SECTION_COPY;

  return resolveSectionOrder(order)
    .filter((key) => !NAV_EXCLUDED.has(key) && isSectionEnabled(sections, key))
    .map((key) => ({
      key,
      href: `#${key}`,
      label: resolvedCopy[key].nav_label,
    }));
}

export function enabledMainSections(
  sections: SectionSettings | undefined,
  order?: SectionKey[] | null,
): SectionKey[] {
  return resolveSectionOrder(order).filter(
    (key) => key !== "newsletter" && isSectionEnabled(sections, key),
  );
}

/** Drop enabled sections that have nothing to show (e.g. testimonials with no entries). */
export function visibleMainSections(
  sections: SectionSettings | undefined,
  order: SectionKey[] | null | undefined,
  hasContent: (key: SectionKey) => boolean,
): SectionKey[] {
  return enabledMainSections(sections, order).filter(hasContent);
}

export function visibleNavSections(
  sections: SectionSettings | undefined,
  order: SectionKey[] | null | undefined,
  copy: SectionCopyMap | undefined,
  hasContent: (key: SectionKey) => boolean,
) {
  const resolvedCopy = copy ?? DEFAULT_SECTION_COPY;

  return resolveSectionOrder(order)
    .filter(
      (key) =>
        !NAV_EXCLUDED.has(key) &&
        isSectionEnabled(sections, key) &&
        hasContent(key),
    )
    .map((key) => ({
      key,
      href: `#${key}`,
      label: resolvedCopy[key].nav_label,
    }));
}

export function firstEnabledSectionHref(
  sections: SectionSettings | undefined,
  order?: SectionKey[] | null,
): string {
  const nav = enabledNavSections(sections, order);
  if (nav.length > 0) return nav[0].href;
  if (isSectionEnabled(sections, "contact")) return "#contact";
  return "#home";
}
