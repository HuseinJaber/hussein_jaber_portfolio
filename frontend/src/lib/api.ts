import type { PortfolioData, Project } from "./types";

export const API_URL =
  import.meta.env.VITE_API_URL ?? "http://localhost:8000/api";

/**
 * Fetch the full portfolio payload. `no-store` keeps the public site in sync
 * with the admin dashboard on every request.
 */
export async function getPortfolio(): Promise<PortfolioData | null> {
  try {
    const res = await fetch(`${API_URL}/portfolio`, { cache: "no-store" });
    if (!res.ok) return null;
    return (await res.json()) as PortfolioData;
  } catch {
    return null;
  }
}

export async function getProject(slug: string): Promise<Project | null> {
  try {
    const res = await fetch(`${API_URL}/projects/${slug}`, {
      cache: "no-store",
    });
    if (!res.ok) return null;
    return (await res.json()) as Project;
  } catch {
    return null;
  }
}

export async function getProjects(): Promise<Project[]> {
  try {
    const res = await fetch(`${API_URL}/projects`, { cache: "no-store" });
    if (!res.ok) return [];
    return (await res.json()) as Project[];
  } catch {
    return [];
  }
}
