export const SCROLL_OFFSET = 120;

export function scrollToSection(id: string, updateHash = true): boolean {
  const el = document.getElementById(id);
  if (!el) return false;

  const top = el.getBoundingClientRect().top + window.scrollY - SCROLL_OFFSET;
  window.scrollTo({ top: Math.max(0, top), behavior: "smooth" });

  if (updateHash) {
    window.history.pushState(null, "", `#${id}`);
  }

  return true;
}
