"use client";

import { openCookieSettings } from "@/lib/consent";

export default function CookieSettingsLink() {
  return (
    <button
      type="button"
      onClick={openCookieSettings}
      className="text-xs text-muted underline-offset-2 transition hover:text-white hover:underline"
    >
      Cookie settings
    </button>
  );
}
