import { useEffect, useRef } from "react";
import { useLocation } from "react-router-dom";
import type { SectionSettings } from "@/lib/types";
import { useAnalyticsConsent } from "@/hooks/useAnalyticsConsent";
import { trackEvent } from "@/lib/analytics";
import { isSectionEnabled, TRACKED_SECTIONS } from "@/lib/sections";

export default function HomeSectionAnalytics({ sections }: { sections: SectionSettings }) {
  const { pathname } = useLocation();
  const viewedSections = useRef(new Set<string>());
  const enabled = useAnalyticsConsent();

  useEffect(() => {
    if (!enabled || pathname !== "/") return;

    viewedSections.current.clear();
    const observers: IntersectionObserver[] = [];

    for (const section of TRACKED_SECTIONS) {
      if (!isSectionEnabled(sections, section)) continue;

      const element = document.getElementById(section);
      if (!element) continue;

      const observer = new IntersectionObserver(
        (entries) => {
          for (const entry of entries) {
            if (!entry.isIntersecting || viewedSections.current.has(section)) continue;
            viewedSections.current.add(section);
            trackEvent({
              event_type: "section_view",
              path: pathname,
              section,
            });
          }
        },
        { threshold: 0.35 },
      );

      observer.observe(element);
      observers.push(observer);
    }

    return () => observers.forEach((observer) => observer.disconnect());
  }, [pathname, enabled, sections]);

  return null;
}
