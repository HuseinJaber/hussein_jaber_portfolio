import { Link } from "react-router-dom";
import LegalPageShell from "@/components/layout/LegalPageShell";
import CookieSettingsLink from "@/components/CookieSettingsLink";
import PageMeta from "@/components/PageMeta";
import { usePortfolio } from "@/hooks/usePortfolio";

export default function PrivacyPage() {
  const { data } = usePortfolio();
  const profile = data?.profile;
  const siteName = profile?.name ?? "Portfolio owner";
  const contactEmail = profile?.email;

  return (
    <>
      <PageMeta
        title="Privacy Policy"
        description="How this portfolio collects, uses and protects your personal data."
      />
      <LegalPageShell title="Privacy Policy">
        <section>
          <h2 className="text-xl font-semibold text-white">Who we are</h2>
          <p className="mt-3">
            This website is the personal portfolio of {siteName}. For privacy-related requests,
            contact{" "}
            {contactEmail ? (
              <a href={`mailto:${contactEmail}`} className="text-accent hover:underline">
                {contactEmail}
              </a>
            ) : (
              "us through the contact form on this site"
            )}
            .
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Information we collect</h2>
          <ul className="mt-3 list-inside list-disc space-y-2">
            <li>
              <strong className="text-white">Contact form:</strong> name, email, optional subject and
              message, plus your IP address for abuse prevention.
            </li>
            <li>
              <strong className="text-white">Newsletter:</strong> email address and IP address when you
              subscribe.
            </li>
            <li>
              <strong className="text-white">Analytics (with consent):</strong> anonymous session ID,
              pages viewed, section interactions, referrer and browser user agent. See our{" "}
              <Link to="/cookies" className="text-accent hover:underline">
                Cookie Policy
              </Link>
              .
            </li>
            <li>
              <strong className="text-white">Technical data:</strong> standard server logs (e.g. request
              time, browser type) needed to operate and secure the site.
            </li>
          </ul>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">How we use your information</h2>
          <ul className="mt-3 list-inside list-disc space-y-2">
            <li>Respond to contact messages and project inquiries.</li>
            <li>Send newsletter updates you signed up for (and related service emails).</li>
            <li>Understand how visitors use the portfolio when analytics consent is given.</li>
            <li>Protect the site from spam, abuse and security incidents.</li>
          </ul>
          <p className="mt-3">We do not sell your personal data or use it for third-party advertising.</p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Legal basis (GDPR)</h2>
          <p className="mt-3">Where applicable, we rely on:</p>
          <ul className="mt-3 list-inside list-disc space-y-2">
            <li>
              <strong className="text-white">Consent</strong> — newsletter subscription and optional
              analytics.
            </li>
            <li>
              <strong className="text-white">Legitimate interests</strong> — replying to contact
              messages, securing the website and maintaining the portfolio.
            </li>
          </ul>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">How long we keep data</h2>
          <ul className="mt-3 list-inside list-disc space-y-2">
            <li>Contact messages: until deleted from the admin dashboard or no longer needed.</li>
            <li>Newsletter subscribers: until you unsubscribe or we remove your record.</li>
            <li>Analytics events: up to 90 days, then automatically deleted.</li>
            <li>Cookie consent preference: stored in your browser until you clear it.</li>
          </ul>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Sharing and processors</h2>
          <p className="mt-3">
            Data is stored on servers we control. Email notifications may be sent through our
            configured mail provider (e.g. hosting or SMTP) solely to deliver messages related to
            your inquiry or subscription. We do not share data with advertisers or data brokers.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Your rights</h2>
          <p className="mt-3">
            Depending on your location, you may have the right to access, correct, delete or restrict
            use of your personal data, withdraw consent (including via <CookieSettingsLink />
            ), or object to certain processing. To exercise these rights, email us at the address above.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Security</h2>
          <p className="mt-3">
            We use industry-standard measures including HTTPS, input validation, rate limiting, admin
            access controls and secure file handling. No method of transmission over the internet is
            100% secure, but we work to protect your information responsibly.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Changes</h2>
          <p className="mt-3">
            We may update this policy occasionally. The &quot;Last updated&quot; date at the top will
            reflect the latest version.
          </p>
        </section>
      </LegalPageShell>
    </>
  );
}
