"use client";

import { useEffect, useState } from "react";
import { CONSENT_EVENT, hasAnalyticsConsent } from "@/lib/consent";

export function useAnalyticsConsent(): boolean {
  const [enabled, setEnabled] = useState(() => hasAnalyticsConsent());

  useEffect(() => {
    const onConsent = () => setEnabled(hasAnalyticsConsent());
    window.addEventListener(CONSENT_EVENT, onConsent);
    return () => window.removeEventListener(CONSENT_EVENT, onConsent);
  }, []);

  return enabled;
}
