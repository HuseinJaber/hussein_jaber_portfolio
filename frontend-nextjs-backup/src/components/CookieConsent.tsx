"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { AnimatePresence, motion } from "motion/react";
import {
  CONSENT_EVENT,
  OPEN_SETTINGS_EVENT,
  type ConsentChoice,
  getConsent,
  setConsent,
} from "@/lib/consent";

export default function CookieConsent() {
  const [choice, setChoice] = useState<ConsentChoice | null>(() => getConsent());
  const [bannerOpen, setBannerOpen] = useState(() => !getConsent());
  const [settingsOpen, setSettingsOpen] = useState(false);
  const [draft, setDraft] = useState<ConsentChoice>(() => getConsent() ?? "essential");

  useEffect(() => {
    const onConsent = (event: Event) => {
      const next = (event as CustomEvent<ConsentChoice>).detail;
      setChoice(next);
      setDraft(next);
      setBannerOpen(false);
      setSettingsOpen(false);
    };

    const onOpenSettings = () => {
      const current = getConsent() ?? "essential";
      setDraft(current);
      setSettingsOpen(true);
      setBannerOpen(true);
    };

    window.addEventListener(CONSENT_EVENT, onConsent);
    window.addEventListener(OPEN_SETTINGS_EVENT, onOpenSettings);

    return () => {
      window.removeEventListener(CONSENT_EVENT, onConsent);
      window.removeEventListener(OPEN_SETTINGS_EVENT, onOpenSettings);
    };
  }, []);

  function save( next: ConsentChoice) {
    setConsent(next);
    setChoice(next);
    setDraft(next);
    setBannerOpen(false);
    setSettingsOpen(false);
  }

  const showBanner = bannerOpen && (!choice || settingsOpen);

  return (
    <AnimatePresence>
      {showBanner && (
        <motion.div
          role="dialog"
          aria-labelledby="cookie-consent-title"
          aria-describedby="cookie-consent-desc"
          initial={{ y: 80, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          exit={{ y: 80, opacity: 0 }}
          transition={{ duration: 0.35, ease: "easeOut" }}
          className="fixed inset-x-0 bottom-0 z-[60] p-4 sm:p-6"
        >
          <div className="glass glow mx-auto max-w-4xl rounded-2xl p-5 sm:p-6">
            <div className="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
              <div className="min-w-0">
                <div className="flex items-center gap-3">
                  <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand/30 to-brand-2/20 text-lg">
                    🍪
                  </span>
                  <h2 id="cookie-consent-title" className="text-lg font-semibold">
                    {settingsOpen ? "Cookie preferences" : "We value your privacy"}
                  </h2>
                </div>

                {!settingsOpen ? (
                  <p id="cookie-consent-desc" className="mt-3 text-sm leading-relaxed text-muted">
                    This site uses essential cookies to remember your choices and optional analytics to
                    understand how visitors use the portfolio (page views and section engagement). No
                    advertising or third-party tracking cookies are used.{" "}
                    <Link href="/privacy" className="text-accent underline-offset-2 hover:underline">
                      Privacy policy
                    </Link>
                    {" · "}
                    <Link href="/cookies" className="text-accent underline-offset-2 hover:underline">
                      Cookie policy
                    </Link>
                    .
                  </p>
                ) : (
                  <div id="cookie-consent-desc" className="mt-4 space-y-3">
                    <label className="flex cursor-pointer items-start gap-3 rounded-xl border border-line bg-white/[0.02] p-4 transition hover:border-brand/40">
                      <input
                        type="radio"
                        name="cookie-choice"
                        checked={draft === "essential"}
                        onChange={() => setDraft("essential")}
                        className="mt-1 accent-brand"
                      />
                      <span>
                        <span className="block font-medium">Essential only</span>
                        <span className="mt-1 block text-sm text-muted">
                          Store your consent choice. No visit analytics are collected.
                        </span>
                      </span>
                    </label>
                    <label className="flex cursor-pointer items-start gap-3 rounded-xl border border-line bg-white/[0.02] p-4 transition hover:border-brand/40">
                      <input
                        type="radio"
                        name="cookie-choice"
                        checked={draft === "accepted"}
                        onChange={() => setDraft("accepted")}
                        className="mt-1 accent-brand"
                      />
                      <span>
                        <span className="block font-medium">Essential + analytics</span>
                        <span className="mt-1 block text-sm text-muted">
                          Anonymous page views, section clicks and scroll engagement to improve the site.
                        </span>
                      </span>
                    </label>
                  </div>
                )}
              </div>

              <div className="flex shrink-0 flex-col gap-2 sm:flex-row lg:flex-col xl:flex-row">
                {!settingsOpen ? (
                  <>
                    <button
                      type="button"
                      onClick={() => save("accepted")}
                      className="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-black transition hover:bg-accent"
                    >
                      Accept all
                    </button>
                    <button
                      type="button"
                      onClick={() => save("essential")}
                      className="rounded-xl border border-line px-5 py-2.5 text-sm font-semibold transition hover:border-brand hover:bg-white/5"
                    >
                      Essential only
                    </button>
                    <button
                      type="button"
                      onClick={() => setSettingsOpen(true)}
                      className="rounded-xl px-5 py-2.5 text-sm font-medium text-muted transition hover:text-white"
                    >
                      Manage
                    </button>
                  </>
                ) : (
                  <>
                    <button
                      type="button"
                      onClick={() => save(draft)}
                      className="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-black transition hover:bg-accent"
                    >
                      Save preferences
                    </button>
                    <button
                      type="button"
                      onClick={() => {
                        if (choice) {
                          setSettingsOpen(false);
                          setBannerOpen(false);
                        } else {
                          setSettingsOpen(false);
                        }
                      }}
                      className="rounded-xl border border-line px-5 py-2.5 text-sm font-semibold transition hover:border-brand hover:bg-white/5"
                    >
                      {choice ? "Cancel" : "Back"}
                    </button>
                  </>
                )}
              </div>
            </div>
          </div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
