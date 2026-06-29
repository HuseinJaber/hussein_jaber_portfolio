"use client";

import { useEffect } from "react";
import { usePathname } from "next/navigation";
import { useAnalyticsConsent } from "@/hooks/useAnalyticsConsent";
import { trackEvent } from "@/lib/analytics";

export default function AnalyticsTracker() {
  const pathname = usePathname();
  const enabled = useAnalyticsConsent();

  useEffect(() => {
    if (!enabled) return;

    trackEvent({ event_type: "page_view", path: pathname });
  }, [pathname, enabled]);

  useEffect(() => {
    if (!enabled) return;

    const handleClick = (event: MouseEvent) => {
      const anchor = (event.target as HTMLElement).closest("a[href^='#']");
      if (!anchor) return;

      const href = anchor.getAttribute("href");
      if (!href || href === "#") return;

      const section = href.slice(1);
      if (!section) return;

      trackEvent({
        event_type: "section_click",
        path: pathname,
        section,
      });
    };

    document.addEventListener("click", handleClick);
    return () => document.removeEventListener("click", handleClick);
  }, [pathname, enabled]);

  return null;
}
