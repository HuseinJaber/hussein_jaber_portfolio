"use client";

import { useState, type FormEvent } from "react";
import type { Profile, SocialLink } from "@/lib/types";
import { API_URL } from "@/lib/api";
import SectionHeading from "@/components/ui/SectionHeading";
import { SocialIcon } from "@/components/ui/icons";

type Status = "idle" | "loading" | "success" | "error";

export default function Contact({
  profile,
  socials,
}: {
  profile: Profile;
  socials: SocialLink[];
}) {
  const [status, setStatus] = useState<Status>("idle");
  const [feedback, setFeedback] = useState("");

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("loading");
    const form = e.currentTarget;
    const data = Object.fromEntries(new FormData(form).entries());

    try {
      const res = await fetch(`${API_URL}/contact`, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify(data),
      });
      const json = await res.json();
      if (res.ok) {
        setStatus("success");
        setFeedback(json.message ?? "Message sent!");
        form.reset();
      } else {
        setStatus("error");
        setFeedback(json.message ?? "Something went wrong. Please try again.");
      }
    } catch {
      setStatus("error");
      setFeedback("Network error. Is the API running?");
    }
  }

  return (
    <section id="contact" className="mx-auto max-w-6xl px-4 py-24">
      <SectionHeading
        eyebrow="Contact"
        title="Let's build something great"
        subtitle="Have a project in mind or just want to say hi? Send a message — I usually reply within a day."
      />

      <div className="mt-14 grid gap-10 lg:grid-cols-[1fr_1.2fr]">
        <div className="space-y-6">
          <div className="glass rounded-2xl p-6">
            <h3 className="font-semibold">Get in touch</h3>
            <ul className="mt-4 space-y-3 text-sm text-muted">
              {profile.email && (
                <li>
                  <a className="hover:text-white" href={`mailto:${profile.email}`}>
                    ✉ {profile.email}
                  </a>
                </li>
              )}
              {profile.phone && (
                <li>
                  <a className="hover:text-white" href={`tel:${profile.phone}`}>
                    ☎ {profile.phone}
                  </a>
                </li>
              )}
              {profile.location && <li>◉ {profile.location}</li>}
            </ul>
            <div className="mt-6 flex gap-3">
              {socials.map((s) => (
                <a
                  key={s.id}
                  href={s.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={s.label ?? s.platform}
                  className="flex h-10 w-10 items-center justify-center rounded-xl border border-line bg-white/5 text-muted transition hover:border-brand hover:text-white"
                >
                  <SocialIcon name={s.icon ?? s.platform} className="h-5 w-5" />
                </a>
              ))}
            </div>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="glass rounded-2xl p-6">
          <input type="text" name="website" tabIndex={-1} autoComplete="off" className="hidden" aria-hidden="true" />
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Name" name="name" required />
            <Field label="Email" name="email" type="email" required />
          </div>
          <div className="mt-4">
            <Field label="Subject" name="subject" />
          </div>
          <div className="mt-4">
            <label className="mb-1.5 block text-sm font-medium">Message</label>
            <textarea
              name="message"
              required
              rows={5}
              className="w-full rounded-xl border border-line bg-white/[0.03] px-4 py-3 text-sm outline-none transition focus:border-brand"
              placeholder="Tell me about your project…"
            />
          </div>

          <button
            type="submit"
            disabled={status === "loading"}
            className="mt-5 w-full rounded-xl bg-gradient-to-r from-brand to-brand-2 px-6 py-3 font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:-translate-y-0.5 disabled:opacity-60"
          >
            {status === "loading" ? "Sending…" : "Send message"}
          </button>

          {status === "success" && (
            <p className="mt-4 rounded-lg bg-emerald-400/10 px-4 py-3 text-sm text-emerald-300">{feedback}</p>
          )}
          {status === "error" && (
            <p className="mt-4 rounded-lg bg-rose-400/10 px-4 py-3 text-sm text-rose-300">{feedback}</p>
          )}
        </form>
      </div>
    </section>
  );
}

function Field({
  label,
  name,
  type = "text",
  required = false,
}: {
  label: string;
  name: string;
  type?: string;
  required?: boolean;
}) {
  return (
    <label className="block">
      <span className="mb-1.5 block text-sm font-medium">
        {label}
        {required && <span className="text-brand"> *</span>}
      </span>
      <input
        name={name}
        type={type}
        required={required}
        className="w-full rounded-xl border border-line bg-white/[0.03] px-4 py-3 text-sm outline-none transition focus:border-brand"
      />
    </label>
  );
}
