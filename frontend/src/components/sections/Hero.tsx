"use client";

import { useEffect, useRef } from "react";
import { gsap } from "gsap";
import type { Profile, SocialLink } from "@/lib/types";
import { SocialIcon } from "@/components/ui/icons";

export default function Hero({
  profile,
  socials,
}: {
  profile: Profile;
  socials: SocialLink[];
}) {
  const root = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
      tl.from(".hero-badge", { y: 20, opacity: 0, duration: 0.6 })
        .from(".hero-line", { y: 40, opacity: 0, duration: 0.8, stagger: 0.12 }, "-=0.2")
        .from(".hero-sub", { y: 20, opacity: 0, duration: 0.6 }, "-=0.4")
        .from(".hero-cta", { y: 20, opacity: 0, duration: 0.6, stagger: 0.1 }, "-=0.3")
        .from(".hero-stat", { y: 20, opacity: 0, duration: 0.5, stagger: 0.1 }, "-=0.2")
        .from(".hero-social", { scale: 0, opacity: 0, duration: 0.4, stagger: 0.06 }, "-=0.4");
    }, root);
    return () => ctx.revert();
  }, []);

  const stats = [
    { value: `${profile.years_experience}+`, label: "Years experience" },
    { value: `${profile.projects_completed}+`, label: "Projects shipped" },
    { value: `${profile.happy_clients}+`, label: "Happy clients" },
  ];

  return (
    <section id="home" ref={root} className="relative flex min-h-screen items-center pt-28">
      <div className="mx-auto grid max-w-6xl gap-12 px-4 lg:grid-cols-[1.3fr_1fr] lg:items-center">
        <div>
          {profile.available_for_work && (
            <span className="hero-badge inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-300">
              <span className="relative flex h-2 w-2">
                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                <span className="relative inline-flex h-2 w-2 rounded-full bg-emerald-400" />
              </span>
              Available for new projects
            </span>
          )}

          <h1 className="mt-6 text-4xl font-bold leading-[1.05] tracking-tight sm:text-6xl md:text-7xl">
            <span className="hero-line block">Hi, I&apos;m {profile.name.split(" ")[0]}.</span>
            <span className="hero-line block text-gradient">{profile.title}</span>
          </h1>

          <p className="hero-sub mt-6 max-w-xl text-lg text-muted">
            {profile.headline ?? profile.bio}
          </p>

          <div className="mt-8 flex flex-wrap items-center gap-4">
            <a
              href="#contact"
              className="hero-cta rounded-xl bg-gradient-to-r from-brand to-brand-2 px-6 py-3 font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:-translate-y-0.5"
            >
              Let&apos;s work together
            </a>
            <a
              href="#work"
              className="hero-cta rounded-xl border border-line bg-white/5 px-6 py-3 font-semibold transition hover:border-brand"
            >
              View my work
            </a>
          </div>

          <div className="mt-10 flex gap-3">
            {socials.map((s) => (
              <a
                key={s.id}
                href={s.url}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={s.label ?? s.platform}
                className="hero-social flex h-11 w-11 items-center justify-center rounded-xl border border-line bg-white/5 text-muted transition hover:-translate-y-0.5 hover:border-brand hover:text-white"
              >
                <SocialIcon name={s.icon ?? s.platform} className="h-5 w-5" />
              </a>
            ))}
          </div>
        </div>

        <div className="relative">
          <div className="glass glow rounded-3xl p-8">
            <div className="grid grid-cols-3 gap-6">
              {stats.map((s) => (
                <div key={s.label} className="hero-stat text-center">
                  <p className="text-3xl font-bold text-gradient sm:text-4xl">{s.value}</p>
                  <p className="mt-1 text-xs text-muted">{s.label}</p>
                </div>
              ))}
            </div>
            <div className="mt-8 space-y-3 text-sm">
              {profile.location && (
                <p className="flex items-center gap-2 text-muted">
                  <span className="text-accent">◉</span> Based in {profile.location}
                </p>
              )}
              {profile.email && (
                <p className="flex items-center gap-2 text-muted">
                  <span className="text-accent">✉</span> {profile.email}
                </p>
              )}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
