"use client";

import { useState, type FormEvent } from "react";
import type { SectionCopy } from "@/lib/types";
import { API_URL } from "@/lib/api";
import { parseApiError, readApiResponse } from "@/lib/form-errors";

type Status = "idle" | "loading" | "success" | "error";

export default function FooterNewsletter({ copy }: { copy: SectionCopy }) {
  const [status, setStatus] = useState<Status>("idle");
  const [feedback, setFeedback] = useState("");
  const [emailError, setEmailError] = useState("");

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("loading");
    setFeedback("");
    setEmailError("");

    const form = e.currentTarget;
    const data = Object.fromEntries(new FormData(form).entries());

    try {
      const res = await fetch(`${API_URL}/newsletter`, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify(data),
      });
      const body = await readApiResponse(res);

      if (res.ok) {
        const json = body as { message?: string };
        setStatus("success");
        setFeedback(json.message ?? "You're subscribed!");
        form.reset();
        return;
      }

      const { message, fieldErrors } = parseApiError(res.status, body);
      setStatus("error");
      setFeedback(message);
      setEmailError(fieldErrors.email ?? "");
    } catch {
      setStatus("error");
      setFeedback("Network error. Is the API running?");
    }
  }

  return (
    <div id="newsletter" className="border-b border-line pb-8">
      <div className="mx-auto max-w-6xl px-4">
        <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div className="max-w-md">
            <p className="text-xs font-semibold uppercase tracking-widest text-accent">{copy.eyebrow}</p>
            <h2 className="mt-2 text-lg font-semibold">{copy.title}</h2>
            {copy.subtitle && (
              <p className="mt-2 text-sm leading-relaxed text-muted">{copy.subtitle}</p>
            )}
          </div>

          <form onSubmit={handleSubmit} className="w-full max-w-md lg:max-w-lg" noValidate>
            <input type="text" name="website" tabIndex={-1} autoComplete="off" className="hidden" aria-hidden="true" />
            <div className="flex flex-col gap-2 sm:flex-row">
              <div className="flex-1">
                <label className="sr-only" htmlFor="newsletter-email">
                  Email address
                </label>
                <input
                  id="newsletter-email"
                  name="email"
                  type="email"
                  required
                  placeholder="you@company.com"
                  aria-invalid={Boolean(emailError)}
                  aria-describedby={emailError ? "newsletter-email-error" : undefined}
                  className={`min-h-11 w-full rounded-xl border bg-white/[0.03] px-4 py-2.5 text-sm outline-none transition focus:border-brand ${
                    emailError ? "border-rose-400/60" : "border-line"
                  }`}
                />
                {emailError && (
                  <p id="newsletter-email-error" className="mt-1.5 text-xs text-rose-300">
                    {emailError}
                  </p>
                )}
              </div>
              <button
                type="submit"
                disabled={status === "loading"}
                className="min-h-11 shrink-0 rounded-xl bg-gradient-to-r from-brand to-brand-2 px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
              >
                {status === "loading" ? "Subscribing…" : "Subscribe"}
              </button>
            </div>

            {status === "success" && (
              <p className="mt-3 text-sm text-emerald-300" role="status">
                {feedback}
              </p>
            )}
            {status === "error" && !emailError && (
              <p className="mt-3 text-sm text-rose-300" role="alert">
                {feedback}
              </p>
            )}

            <p className="mt-3 text-xs leading-relaxed text-muted">
              By subscribing you agree to our{" "}
              <a href="/privacy" className="text-accent underline-offset-2 hover:underline">
                Privacy Policy
              </a>
              . Unsubscribe anytime by contacting us.
            </p>
          </form>
        </div>
      </div>
    </div>
  );
}
