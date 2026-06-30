"use client";

import { useEffect, useState, type MouseEvent } from "react";
import { AnimatePresence, motion } from "motion/react";
import type { SectionCopyMap, SectionKey, SectionSettings } from "@/lib/types";
import { isSectionEnabled, visibleNavSections } from "@/lib/sections";
import { scrollToSection } from "@/lib/scroll";
import { useHomeScroll } from "@/hooks/useHomeScroll";
import ScrollProgress from "@/components/layout/ScrollProgress";

export default function Navbar({
  name,
  sections,
  sectionOrder,
  scrollSectionIds,
  sectionCopy,
  email,
  sectionHasContent,
}: {
  name: string;
  sections: SectionSettings;
  sectionOrder: SectionKey[];
  scrollSectionIds: SectionKey[];
  sectionCopy: SectionCopyMap;
  email?: string | null;
  sectionHasContent: (key: SectionKey) => boolean;
}) {
  const { progress, activeSection } = useHomeScroll(scrollSectionIds);
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);
  const links = visibleNavSections(sections, sectionOrder, sectionCopy, sectionHasContent);
  const showContactCta = isSectionEnabled(sections, "contact");
  const hireHref = showContactCta ? "#contact" : email ? `mailto:${email}` : undefined;

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 20);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [open]);

  const handleSectionClick = (event: MouseEvent<HTMLAnchorElement>, sectionId: string) => {
    event.preventDefault();
    setOpen(false);
    document.body.style.overflow = "";
    scrollToSection(sectionId);
  };

  const initials = name
    .split(" ")
    .map((w) => w[0])
    .slice(0, 2)
    .join("");

  const linkClass = (key: SectionKey) => {
    const isActive = activeSection === key;
    return [
      "relative rounded-lg px-3 py-2 text-sm transition",
      isActive
        ? "font-medium text-white"
        : "text-muted hover:bg-white/5 hover:text-white",
    ].join(" ");
  };

  return (
    <>
      <ScrollProgress progress={progress} />

      <motion.header
        initial={{ y: -80, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.6, ease: "easeOut" }}
        className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
          scrolled ? "py-2" : "py-4"
        }`}
      >
        <div className="mx-auto max-w-6xl px-4">
          <nav
            className={`relative z-50 flex items-center justify-between rounded-2xl px-4 py-3 transition-all ${
              scrolled ? "glass glow" : ""
            }`}
          >
            <a
              href="#home"
              onClick={(event) => handleSectionClick(event, "home")}
              className={`flex items-center gap-2 font-bold transition ${
                activeSection === "home" ? "text-white" : ""
              }`}
            >
              <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand to-brand-2 text-white">
                {initials}
              </span>
              <span className="hidden sm:block">{name}</span>
            </a>

            <ul className="hidden items-center gap-1 md:flex">
              {links.map((l) => (
                <li key={l.href}>
                  <a
                    href={l.href}
                    onClick={(event) => handleSectionClick(event, l.key)}
                    className={linkClass(l.key)}
                  >
                    {l.label}
                    {activeSection === l.key && (
                      <span className="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-gradient-to-r from-brand to-brand-2" />
                    )}
                  </a>
                </li>
              ))}
            </ul>

            <div className="flex items-center gap-2">
              {hireHref && (
                <a
                  href={hireHref}
                  onClick={
                    showContactCta
                      ? (event) => handleSectionClick(event, "contact")
                      : undefined
                  }
                  className="hidden rounded-xl bg-white px-4 py-2 text-sm font-semibold text-black transition hover:bg-accent sm:inline-block"
                >
                  Hire me
                </a>
              )}
              <button
                onClick={() => setOpen((v) => !v)}
                className="rounded-lg p-2 text-white md:hidden"
                aria-label="Toggle menu"
              >
                <svg width="24" height="24" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" d={open ? "M6 6l12 12M6 18L18 6" : "M4 7h16M4 12h16M4 17h16"} />
                </svg>
              </button>
            </div>
          </nav>

          <AnimatePresence>
            {open && (
              <>
                <motion.button
                  type="button"
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  exit={{ opacity: 0 }}
                  aria-label="Close menu"
                  className="fixed inset-0 z-40 bg-bg/70 backdrop-blur-sm md:hidden"
                  onClick={() => setOpen(false)}
                />
                <motion.ul
                  initial={{ opacity: 0, y: -8 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, y: -8 }}
                  className="glass relative z-50 mt-2 rounded-2xl p-2 md:hidden"
                >
                  {links.map((l) => (
                    <li key={l.href}>
                      <a
                        href={l.href}
                        onClick={(event) => handleSectionClick(event, l.key)}
                        className={`block rounded-lg px-4 py-3 text-sm transition ${
                          activeSection === l.key
                            ? "bg-white/10 font-medium text-white"
                            : "text-muted hover:bg-white/5 hover:text-white"
                        }`}
                      >
                        {l.label}
                      </a>
                    </li>
                  ))}
                </motion.ul>
              </>
            )}
          </AnimatePresence>
        </div>
      </motion.header>
    </>
  );
}
