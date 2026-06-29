"use client";

import { useEffect, useState } from "react";
import type { SectionKey } from "@/lib/types";

const SCROLL_OFFSET = 120;

export function useHomeScroll(sectionIds: SectionKey[]) {
  const [progress, setProgress] = useState(0);
  const [activeSection, setActiveSection] = useState<SectionKey | "home">("home");

  useEffect(() => {
    const ids = ["home", ...sectionIds] as const;

    const update = () => {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      setProgress(docHeight > 0 ? Math.min(1, scrollTop / docHeight) : 0);

      let current: SectionKey | "home" = "home";
      for (const id of ids) {
        const el = document.getElementById(id);
        if (!el) continue;
        if (el.getBoundingClientRect().top <= SCROLL_OFFSET) {
          current = id as SectionKey | "home";
        }
      }
      setActiveSection(current);
    };

    update();
    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);

    return () => {
      window.removeEventListener("scroll", update);
      window.removeEventListener("resize", update);
    };
  }, [sectionIds]);

  return { progress, activeSection };
}
