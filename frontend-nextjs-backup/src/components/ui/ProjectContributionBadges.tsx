import type { Project } from "@/lib/types";

const contributionStyles: Record<string, string> = {
  Frontend: "bg-emerald-400/15 text-emerald-200 ring-emerald-400/25",
  Backend: "bg-blue-400/15 text-blue-200 ring-blue-400/25",
  "UI / Design": "bg-pink-400/15 text-pink-200 ring-pink-400/25",
  API: "bg-cyan-400/15 text-cyan-200 ring-cyan-400/25",
  "CMS / WordPress": "bg-orange-400/15 text-orange-200 ring-orange-400/25",
  "DevOps / Deployment": "bg-amber-400/15 text-amber-200 ring-amber-400/25",
};

export function contributionBadgeClass(label: string): string {
  return contributionStyles[label] ?? "bg-white/10 text-muted ring-white/10";
}

export function ProjectContributionBadges({
  project,
  size = "sm",
}: {
  project: Pick<Project, "contribution_labels">;
  size?: "sm" | "xs";
}) {
  const labels = project.contribution_labels ?? [];

  if (labels.length === 0) {
    return null;
  }

  const sizeClass = size === "xs" ? "px-2 py-0.5 text-[10px]" : "px-2.5 py-0.5 text-xs";

  return (
    <div className="flex flex-wrap gap-1.5">
      {labels.map((label) => (
        <span
          key={label}
          className={`rounded-full font-medium ring-1 ring-inset ${sizeClass} ${contributionBadgeClass(label)}`}
        >
          {label}
        </span>
      ))}
    </div>
  );
}
