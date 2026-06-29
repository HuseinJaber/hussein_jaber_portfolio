"use client";

interface ScrollProgressProps {
  progress: number;
}

export default function ScrollProgress({ progress }: ScrollProgressProps) {
  const percent = Math.round(progress * 100);
  const fillHeight = `${percent}%`;
  const show = percent > 1;

  return (
    <>
      {/* Full-height track — sits on the viewport edge, away from the nav */}
      <div
        className="pointer-events-none fixed inset-y-0 right-0 z-40 w-px bg-white/[0.07]"
        aria-hidden="true"
      />

      {/* Progress fill — grows downward as you scroll */}
      <div
        className={`pointer-events-none fixed right-0 top-0 z-40 w-[2px] bg-gradient-to-b from-brand via-brand-2 to-accent transition-[height,opacity] duration-200 ease-out ${
          show ? "opacity-100" : "opacity-0"
        }`}
        style={{ height: fillHeight }}
        aria-hidden="true"
      />

      {/* Subtle percentage — only after you start scrolling */}
      <div
        className={`pointer-events-none fixed bottom-5 right-4 z-40 transition-opacity duration-200 ${
          show ? "opacity-100" : "opacity-0"
        }`}
        aria-hidden="true"
      >
        <span className="glass rounded-full px-2 py-0.5 text-[10px] font-medium tabular-nums text-muted">
          {percent}%
        </span>
      </div>
    </>
  );
}
