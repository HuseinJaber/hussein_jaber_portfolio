export type ConsentChoice = "accepted" | "essential";

const STORAGE_KEY = "portfolio_cookie_consent";

export const CONSENT_EVENT = "portfolio:consent";

export const OPEN_SETTINGS_EVENT = "portfolio:open-cookie-settings";

export function getConsent(): ConsentChoice | null {
  if (typeof window === "undefined") return null;

  const value = localStorage.getItem(STORAGE_KEY);
  if (value === "accepted" || value === "essential") return value;

  return null;
}

export function setConsent(choice: ConsentChoice): void {
  localStorage.setItem(STORAGE_KEY, choice);
  window.dispatchEvent(new CustomEvent(CONSENT_EVENT, { detail: choice }));
}

export function hasAnalyticsConsent(): boolean {
  return getConsent() === "accepted";
}

export function openCookieSettings(): void {
  window.dispatchEvent(new CustomEvent(OPEN_SETTINGS_EVENT));
}
