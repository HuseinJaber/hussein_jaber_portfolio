"use client";

import { useEffect, useRef, useState } from "react";

type AnimatedCounterProps = {
  value: number;
  suffix?: string;
  duration?: number;
  delay?: number;
  className?: string;
};

export function AnimatedCounter({
  value,
  suffix = "+",
  duration = 1.6,
  delay = 0,
  className,
}: AnimatedCounterProps) {
  const [display, setDisplay] = useState(0);
  const frame = useRef<number | undefined>(undefined);

  useEffect(() => {
    const target = Math.max(0, Math.round(value));
    const timeout = window.setTimeout(() => {
      const start = performance.now();
      const durationMs = duration * 1000;

      const tick = (now: number) => {
        const progress = Math.min((now - start) / durationMs, 1);
        const eased = 1 - (1 - progress) ** 3;
        setDisplay(Math.round(eased * target));

        if (progress < 1) {
          frame.current = requestAnimationFrame(tick);
        }
      };

      frame.current = requestAnimationFrame(tick);
    }, delay * 1000);

    return () => {
      window.clearTimeout(timeout);
      if (frame.current !== undefined) {
        cancelAnimationFrame(frame.current);
      }
    };
  }, [value, duration, delay]);

  return (
    <span className={className}>
      {display}
      {suffix}
    </span>
  );
}
