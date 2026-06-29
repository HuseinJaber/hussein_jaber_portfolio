"use client";

import { useEffect, useRef } from "react";
import { gsap } from "gsap";
import type { Profile, SocialLink } from "@/lib/types";
import { AnimatedCounter } from "@/components/ui/AnimatedCounter";
import { SocialIcon, EmailIcon, LocationIcon, ContactIconBox } from "@/components/ui/icons";
import CvActions from "@/components/cv/CvActions";

export default function Hero({
  profile,
  socials,
  projectCount,
}: {
  profile: Profile;
  socials: SocialLink[];
  projectCount: number;
}) {
  const root = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
      tl.from(".hero-badge", { y: 20, opacity: 0, duration: 0.6 })
        .from(".hero-line", { y: 40, opacity: 0, duration: 0.8, stagger: 0.12 }, "-=0.2")
        .from(".hero-sub", { y: 20, opacity: 0, duration: 0.6 }, "-=0.4")
        .from(".hero-cta", { y: 20, opacity: 0, duration: 0.6, stagger: 0.1 }, "-=0.3")
        .from(".hero-social", { scale: 0, opacity: 0, duration: 0.4, stagger: 0.06 }, "-=0.4");
    }, root);
    return () => ctx.revert();
  }, []);

  const stats = [
    { value: profile.years_experience, label: "Years of experience" },
    { value: projectCount, label: "Projects delivered" },
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
              Open to new projects
            </span>
          )}

          <h1 className="mt-6 text-4xl font-bold leading-[1.05] tracking-tight sm:text-6xl md:text-7xl">
            <span className="hero-line block">Hi, I&apos;m {profile.name.split(" ")[0]}.</span>
            <span className="hero-line block text-gradient">{profile.title}</span>
          </h1>

          <p className="hero-sub mt-6 max-w-xl text-lg text-muted">
            {profile.headline ?? profile.bio}
          </p>

          <div className="mt-6 flex flex-wrap items-center gap-4">
            <CvActions resumeUrl={profile.resume_url} />
          </div>

          <div className="mt-10 flex gap-3">
            {socials.map((s) => (
              <a
                key={s.id}
                href={s.url}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={s.label ?? s.platform}
                className={`hero-social flex h-11 w-11 items-center justify-center rounded-xl border border-line bg-white/5 text-muted transition hover:-translate-y-0.5 hover:text-white ${
                  (s.icon ?? s.platform).toLowerCase() === "whatsapp"
                    ? "hover:border-emerald-400/50 hover:bg-emerald-400/10 hover:text-emerald-300"
                    : "hover:border-brand"
                }`}
              >
                <SocialIcon name={s.icon ?? s.platform} className="h-5 w-5" />
              </a>
            ))}
          </div>
        </div>

        <div className="relative">
          <div className="glass glow rounded-3xl p-8">
            <div className="grid grid-cols-2 gap-6">
              {stats.map((s, index) => (
                <div key={s.label} className="text-center">
                  <p className="text-3xl font-bold text-gradient sm:text-4xl">
                    <AnimatedCounter value={s.value} delay={index * 0.15} />
                  </p>
                  <p className="mt-1 text-xs text-muted">{s.label}</p>
                </div>
              ))}
            </div>
            <div className="mt-8 space-y-2 text-sm">
              {profile.location && (
                <p className="flex items-center gap-3 text-muted">
                  <ContactIconBox>
                    <LocationIcon className="h-4 w-4" />
                  </ContactIconBox>
                  <span>Based in {profile.location}</span>
                </p>
              )}
              {profile.email && (
                <a
                  href={`mailto:${profile.email}`}
                  className="group flex items-center gap-3 rounded-xl text-muted transition hover:text-white"
                >
                  <ContactIconBox>
                    <EmailIcon className="h-4 w-4" />
                  </ContactIconBox>
                  <span className="truncate">{profile.email}</span>
                </a>
              )}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
