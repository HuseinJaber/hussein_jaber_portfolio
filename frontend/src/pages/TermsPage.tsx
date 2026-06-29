import { Link } from "react-router-dom";
import LegalPageShell from "@/components/layout/LegalPageShell";
import PageMeta from "@/components/PageMeta";
import { usePortfolio } from "@/hooks/usePortfolio";

export default function TermsPage() {
  const { data } = usePortfolio();
  const profile = data?.profile;
  const siteName = profile?.name ?? "Portfolio owner";
  const contactEmail = profile?.email;

  return (
    <>
      <PageMeta
        title="Terms of Use"
        description="Terms for using this portfolio website."
      />
      <LegalPageShell title="Terms of Use">
        <section>
          <h2 className="text-xl font-semibold text-white">Agreement</h2>
          <p className="mt-3">
            By accessing this website, you agree to these Terms of Use. If you do not agree, please do
            not use the site.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Purpose of the site</h2>
          <p className="mt-3">
            This website showcases the professional work of {siteName}, provides information about
            services and experience, and offers ways to get in touch or subscribe to updates. It is
            not an e-commerce platform unless explicitly stated on a specific page.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Acceptable use</h2>
          <p className="mt-3">You agree not to:</p>
          <ul className="mt-3 list-inside list-disc space-y-2">
            <li>Submit false, misleading or unlawful content through the contact or newsletter forms.</li>
            <li>Attempt to disrupt, scrape or attack the website or its infrastructure.</li>
            <li>Use automated tools to abuse forms, analytics or other endpoints.</li>
            <li>Copy or republish site content for commercial use without permission.</li>
          </ul>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Intellectual property</h2>
          <p className="mt-3">
            Unless otherwise noted, text, design, branding, project descriptions and media on this site
            belong to {siteName} or respective clients and may not be reproduced without permission.
            Third-party trademarks and logos remain the property of their owners.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">External links</h2>
          <p className="mt-3">
            This site may link to external websites (e.g. live projects, social profiles). We are not
            responsible for the content or privacy practices of those third-party sites.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Disclaimer</h2>
          <p className="mt-3">
            The site is provided &quot;as is&quot; for informational purposes. We strive for accuracy
            but do not guarantee that all content is complete, current or error-free. Nothing on this
            site constitutes a binding contract until agreed separately in writing.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Limitation of liability</h2>
          <p className="mt-3">
            To the fullest extent permitted by law, {siteName} shall not be liable for any indirect,
            incidental or consequential damages arising from your use of this website.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Privacy</h2>
          <p className="mt-3">
            Your use of the site is also governed by our{" "}
            <Link to="/privacy" className="text-accent hover:underline">
              Privacy Policy
            </Link>{" "}
            and{" "}
            <Link to="/cookies" className="text-accent hover:underline">
              Cookie Policy
            </Link>
            .
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Changes</h2>
          <p className="mt-3">
            We may revise these terms from time to time. Continued use of the site after changes are
            posted constitutes acceptance of the updated terms.
          </p>
        </section>

        <section>
          <h2 className="text-xl font-semibold text-white">Contact</h2>
          <p className="mt-3">
            Questions about these terms? Email{" "}
            {contactEmail ? (
              <a href={`mailto:${contactEmail}`} className="text-accent hover:underline">
                {contactEmail}
              </a>
            ) : (
              "us through the contact form"
            )}
            .
          </p>
        </section>
      </LegalPageShell>
    </>
  );
}
