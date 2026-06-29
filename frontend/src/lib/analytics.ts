import { API_URL } from "./api";
import { hasAnalyticsConsent } from "./consent";

export type AnalyticsEventType = "page_view" | "section_view" | "section_click";

export type AnalyticsPayload = {
  event_type: AnalyticsEventType;
  path: string;
  section?: string | null;
};

function getSessionId(): string {
  const key = "portfolio_session";
  let id = sessionStorage.getItem(key);
  if (!id) {
    id = crypto.randomUUID();
    sessionStorage.setItem(key, id);
  }
  return id;
}

export function trackEvent(payload: AnalyticsPayload): void {
  if (typeof window === "undefined" || !hasAnalyticsConsent()) return;

  const body = JSON.stringify({
    ...payload,
    session_id: getSessionId(),
    referrer: document.referrer || null,
    user_agent: navigator.userAgent,
    website: "",
  });

  try {
    if (navigator.sendBeacon) {
      const blob = new Blob([body], { type: "application/json" });
      navigator.sendBeacon(`${API_URL}/analytics`, blob);
      return;
    }
  } catch {
    // fall through to fetch
  }

  fetch(`${API_URL}/analytics`, {
    method: "POST",
    headers: { "Content-Type": "application/json", Accept: "application/json" },
    body,
    keepalive: true,
  }).catch(() => {
    // Analytics should never interrupt the visitor experience.
  });
}
