"use client";

import { useState, type FormEvent } from "react";
import type { Profile, SectionCopy, SocialLink } from "@/lib/types";
import { API_URL } from "@/lib/api";
import { parseApiError, readApiResponse, type FieldErrors } from "@/lib/form-errors";
import SectionHeading from "@/components/ui/SectionHeading";
import {
  ContactIconBox,
  EmailIcon,
  LocationIcon,
  PhoneIcon,
  SocialIcon,
} from "@/components/ui/icons";

type Status = "idle" | "loading" | "success" | "error";

export default function Contact({
  profile,
  socials,
  copy,
}: {
  profile: Profile;
  socials: SocialLink[];
  copy: SectionCopy;
}) {
  const [status, setStatus] = useState<Status>("idle");
  const [feedback, setFeedback] = useState("");
  const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("loading");
    setFeedback("");
    setFieldErrors({});

    const form = e.currentTarget;
    const data = Object.fromEntries(new FormData(form).entries());

    try {
      const res = await fetch(`${API_URL}/contact`, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify(data),
      });
      const body = await readApiResponse(res);

      if (res.ok) {
        const json = body as { message?: string };
        setStatus("success");
        setFeedback(json.message ?? "Message sent!");
        form.reset();
        return;
      }

      const { message, fieldErrors: errors } = parseApiError(res.status, body);
      setStatus("error");
      setFeedback(message);
      setFieldErrors(errors);
    } catch {
      setStatus("error");
      setFeedback("Network error. Is the API running?");
    }
  }

  return (
    <section id="contact" className="mx-auto max-w-6xl px-4 py-16">
      <SectionHeading
        eyebrow={copy.eyebrow}
        title={copy.title}
        subtitle={copy.subtitle ?? undefined}
        align={copy.align}
      />

      <div className="mt-10 grid gap-8 lg:grid-cols-[1fr_1.2fr]">
        <div className="space-y-6">
          <div className="glass rounded-2xl p-6">
            <h3 className="font-semibold">Direct contact</h3>
            <ul className="mt-4 space-y-2 text-sm text-muted">
              {profile.email && (
                <li>
                  <a
                    className="group flex items-center gap-3 rounded-xl px-2 py-2 transition hover:bg-white/[0.04] hover:text-white"
                    href={`mailto:${profile.email}`}
                  >
                    <ContactIconBox>
                      <EmailIcon className="h-4 w-4" />
                    </ContactIconBox>
                    <span>{profile.email}</span>
                  </a>
                </li>
              )}
              {profile.phone && (
                <li>
                  <a
                    className="group flex items-center gap-3 rounded-xl px-2 py-2 transition hover:bg-white/[0.04] hover:text-white"
                    href={`tel:${profile.phone.replace(/\s/g, "")}`}
                  >
                    <ContactIconBox>
                      <PhoneIcon className="h-4 w-4" />
                    </ContactIconBox>
                    <span>{profile.phone}</span>
                  </a>
                </li>
              )}
              {profile.location && (
                <li className="flex items-center gap-3 rounded-xl px-2 py-2">
                  <ContactIconBox>
                    <LocationIcon className="h-4 w-4" />
                  </ContactIconBox>
                  <span>{profile.location}</span>
                </li>
              )}
            </ul>
            <div className="mt-6 flex gap-3">
              {socials.map((s) => (
                <a
                  key={s.id}
                  href={s.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={s.label ?? s.platform}
                  className={`flex h-10 w-10 items-center justify-center rounded-xl border border-line bg-white/5 text-muted transition hover:-translate-y-0.5 hover:text-white ${
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
        </div>

        <form onSubmit={handleSubmit} className="glass rounded-2xl p-6" noValidate>
          <input type="text" name="website" tabIndex={-1} autoComplete="off" className="hidden" aria-hidden="true" />
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Name" name="name" required error={fieldErrors.name} />
            <Field label="Email" name="email" type="email" required error={fieldErrors.email} />
          </div>
          <div className="mt-4">
            <Field label="Subject" name="subject" error={fieldErrors.subject} />
          </div>
          <div className="mt-4">
            <label className="mb-1.5 block text-sm font-medium" htmlFor="contact-message">
              Message<span className="text-brand"> *</span>
            </label>
            <textarea
              id="contact-message"
              name="message"
              required
              rows={5}
              aria-invalid={Boolean(fieldErrors.message)}
              aria-describedby={fieldErrors.message ? "contact-message-error" : undefined}
              className={`w-full rounded-xl border bg-white/[0.03] px-4 py-3 text-sm outline-none transition focus:border-brand ${
                fieldErrors.message ? "border-rose-400/60" : "border-line"
              }`}
              placeholder="Tell me about your project, timeline, and goals…"
            />
            {fieldErrors.message && (
              <p id="contact-message-error" className="mt-1.5 text-xs text-rose-300">
                {fieldErrors.message}
              </p>
            )}
          </div>

          <button
            type="submit"
            disabled={status === "loading"}
            className="mt-5 w-full rounded-xl bg-gradient-to-r from-brand to-brand-2 px-6 py-3 font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:-translate-y-0.5 disabled:opacity-60"
          >
            {status === "loading" ? "Sending…" : "Send message"}
          </button>

          {status === "success" && (
            <p className="mt-4 rounded-lg bg-emerald-400/10 px-4 py-3 text-sm text-emerald-300" role="status">
              {feedback}
            </p>
          )}
          {status === "error" && (
            <p className="mt-4 rounded-lg bg-rose-400/10 px-4 py-3 text-sm text-rose-300" role="alert">
              {feedback}
            </p>
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
  error,
}: {
  label: string;
  name: string;
  type?: string;
  required?: boolean;
  error?: string;
}) {
  const inputId = `contact-${name}`;

  return (
    <label className="block" htmlFor={inputId}>
      <span className="mb-1.5 block text-sm font-medium">
        {label}
        {required && <span className="text-brand"> *</span>}
      </span>
      <input
        id={inputId}
        name={name}
        type={type}
        required={required}
        aria-invalid={Boolean(error)}
        aria-describedby={error ? `${inputId}-error` : undefined}
        className={`w-full rounded-xl border bg-white/[0.03] px-4 py-3 text-sm outline-none transition focus:border-brand ${
          error ? "border-rose-400/60" : "border-line"
        }`}
      />
      {error && (
        <p id={`${inputId}-error`} className="mt-1.5 text-xs text-rose-300">
          {error}
        </p>
      )}
    </label>
  );
}
