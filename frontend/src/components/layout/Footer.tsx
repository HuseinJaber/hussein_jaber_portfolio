import type { Profile, SocialLink } from "@/lib/types";
import { SocialIcon } from "@/components/ui/icons";

export default function Footer({
  profile,
  socials,
}: {
  profile: Profile;
  socials: SocialLink[];
}) {
  return (
    <footer className="border-t border-line py-10">
      <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-4 sm:flex-row">
        <div className="text-center sm:text-left">
          <p className="font-semibold">{profile.name}</p>
          <p className="text-sm text-muted">{profile.title}</p>
        </div>

        <div className="flex items-center gap-3">
          {socials.map((s) => (
            <a
              key={s.id}
              href={s.url}
              target="_blank"
              rel="noopener noreferrer"
              aria-label={s.label ?? s.platform}
              className="flex h-10 w-10 items-center justify-center rounded-xl border border-line bg-white/5 text-muted transition hover:-translate-y-0.5 hover:border-brand hover:text-white"
            >
              <SocialIcon name={s.icon ?? s.platform} className="h-5 w-5" />
            </a>
          ))}
        </div>

        <p className="text-xs text-muted">
          © {new Date().getFullYear()} {profile.name}. All rights reserved.
        </p>
      </div>
    </footer>
  );
}
