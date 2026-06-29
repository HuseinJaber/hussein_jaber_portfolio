import Link from "next/link";
import { getPortfolio } from "@/lib/api";
import LegalPageShell from "@/components/layout/LegalPageShell";
import CookieSettingsLink from "@/components/CookieSettingsLink";

export const metadata = {
  title: "Cookie Policy",
  description: "How this portfolio uses cookies and similar technologies.",
};

export default async function CookiesPage() {
  const data = await getPortfolio();
  const profile = data?.profile;
  const siteName = profile?.name ?? "this portfolio";
  const contactEmail = profile?.email;

  return (
    <LegalPageShell title="Cookie Policy">
      <section>
        <h2 className="text-xl font-semibold text-white">Overview</h2>
        <p className="mt-3">
          {siteName}&apos;s portfolio uses cookies and similar browser storage to remember your
          preferences and, with your consent, to collect anonymous usage statistics. We do not use
          advertising cookies or sell visitor data. For how we handle personal data more broadly,
          see our <Link href="/privacy" className="text-accent hover:underline">Privacy Policy</Link>.
        </p>
      </section>

      <section>
        <h2 className="text-xl font-semibold text-white">What are cookies?</h2>
        <p className="mt-3">
          Cookies are small text files stored in your browser. This site also uses local storage and
          session storage for consent preferences and anonymous analytics session identifiers.
        </p>
      </section>

      <section>
        <h2 className="text-xl font-semibold text-white">Technologies we use</h2>
        <div className="mt-4 overflow-hidden rounded-2xl border border-line">
          <table className="min-w-full text-sm">
            <thead className="bg-white/[0.03] text-left text-xs uppercase tracking-wide text-muted">
              <tr>
                <th className="px-4 py-3 font-medium">Category</th>
                <th className="px-4 py-3 font-medium">Purpose</th>
                <th className="px-4 py-3 font-medium">Storage</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-line">
              <tr>
                <td className="px-4 py-4 align-top font-medium text-white">Essential</td>
                <td className="px-4 py-4 align-top">
                  Remembers whether you accepted or declined optional analytics.
                </td>
                <td className="px-4 py-4 align-top">Browser local storage</td>
              </tr>
              <tr>
                <td className="px-4 py-4 align-top font-medium text-white">Analytics (optional)</td>
                <td className="px-4 py-4 align-top">
                  Anonymous page views, section clicks and scroll engagement. Includes a random
                  session ID, page path, referrer and browser user agent. Stored on our server only
                  — not shared with third-party ad networks.
                </td>
                <td className="px-4 py-4 align-top">Session storage + server (up to 90 days)</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p className="mt-4 text-sm">
          Newsletter sign-ups and contact messages are handled under our{" "}
          <Link href="/privacy" className="text-accent hover:underline">Privacy Policy</Link> — they
          are not stored in marketing cookies.
        </p>
      </section>

      <section>
        <h2 className="text-xl font-semibold text-white">Your choices</h2>
        <p className="mt-3">
          On your first visit you can accept all, choose essential only, or open{" "}
          <CookieSettingsLink /> to change your preference anytime. If you choose essential only,
          analytics tracking is disabled.
        </p>
      </section>

      <section>
        <h2 className="text-xl font-semibold text-white">Data retention</h2>
        <p className="mt-3">
          Analytics events are deleted after 90 days. Your consent choice stays in your browser until
          you clear site data or update it via cookie settings.
        </p>
      </section>

      <section>
        <h2 className="text-xl font-semibold text-white">Contact</h2>
        <p className="mt-3">
          Questions about cookies? Email{" "}
          {contactEmail ? (
            <a href={`mailto:${contactEmail}`} className="text-accent hover:underline">
              {contactEmail}
            </a>
          ) : (
            "the contact address on this site"
          )}
          .
        </p>
      </section>
    </LegalPageShell>
  );
}
