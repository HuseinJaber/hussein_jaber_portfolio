import type { Metadata } from "next";
import { Plus_Jakarta_Sans } from "next/font/google";
import "./globals.css";
import { getPortfolio } from "@/lib/api";
import AnalyticsTracker from "@/components/AnalyticsTracker";
import CookieConsent from "@/components/CookieConsent";

const sans = Plus_Jakarta_Sans({
  variable: "--font-sans",
  subsets: ["latin"],
  display: "swap",
});

export async function generateMetadata(): Promise<Metadata> {
  const data = await getPortfolio();
  const profile = data?.profile;
  return {
    title: profile?.meta_title ?? `${profile?.name ?? "Portfolio"} — ${profile?.title ?? "Full Stack Developer"}`,
    description:
      profile?.meta_description ??
      profile?.bio ??
      "Full Stack Developer building fast, secure and beautiful web applications.",
    openGraph: {
      title: profile?.meta_title ?? profile?.name ?? "Portfolio",
      description: profile?.meta_description ?? profile?.bio ?? "",
      type: "website",
    },
  };
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={`${sans.variable} antialiased`}>
      <body>
        <AnalyticsTracker />
        <CookieConsent />
        {children}
      </body>
    </html>
  );
}
