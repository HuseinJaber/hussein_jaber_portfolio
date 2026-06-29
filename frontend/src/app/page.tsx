import type { ReactNode } from "react";
import { getPortfolio } from "@/lib/api";
import Aurora from "@/components/ui/Aurora";
import Navbar from "@/components/layout/Navbar";
import Footer from "@/components/layout/Footer";
import HomeSectionAnalytics from "@/components/HomeSectionAnalytics";
import Hero from "@/components/sections/Hero";
import About from "@/components/sections/About";
import Services from "@/components/sections/Services";
import Skills from "@/components/sections/Skills";
import Work from "@/components/sections/Work";
import Experience from "@/components/sections/Experience";
import Certifications from "@/components/sections/Certifications";
import Testimonials from "@/components/sections/Testimonials";
import Contact from "@/components/sections/Contact";
import {
  enabledMainSections,
  resolveSectionCopy,
  resolveSectionOrder,
  resolveSections,
  sectionCopyFor,
} from "@/lib/sections";
import type { SectionKey } from "@/lib/types";

export default async function Home() {
  const data = await getPortfolio();

  if (!data) {
    return (
      <main className="flex min-h-screen flex-col items-center justify-center px-4 text-center">
        <Aurora />
        <h1 className="text-3xl font-bold">Backend not reachable</h1>
        <p className="mt-3 max-w-md text-muted">
          The Next.js site could not load data from the Laravel API at{" "}
          <code className="rounded bg-white/10 px-1.5 py-0.5">
            {process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api"}
          </code>
          . Start the backend via Herd (<code className="rounded bg-white/10 px-1.5 py-0.5">herd link</code>) or <code className="rounded bg-white/10 px-1.5 py-0.5">php artisan serve</code> and refresh.
        </p>
      </main>
    );
  }

  const { profile, socials, skills, services, projects, experiences, education, certifications, testimonials } = data;
  const sections = resolveSections(profile.sections);
  const sectionOrder = resolveSectionOrder(profile.section_order);
  const sectionCopy = resolveSectionCopy(profile.section_copy);
  const mainSectionKeys = enabledMainSections(sections, sectionOrder);
  const newsletterCopy = sectionCopyFor(sectionCopy, "newsletter", { name: profile.name });

  const sectionBlocks: Record<SectionKey, ReactNode> = {
    about: <About profile={profile} education={education} copy={sectionCopy.about} />,
    services: <Services services={services} copy={sectionCopy.services} />,
    skills: <Skills skills={skills} copy={sectionCopy.skills} />,
    work: <Work projects={projects} copy={sectionCopy.work} />,
    experience: <Experience experiences={experiences} copy={sectionCopy.experience} />,
    certifications: <Certifications certifications={certifications} copy={sectionCopy.certifications} />,
    testimonials: <Testimonials testimonials={testimonials} copy={sectionCopy.testimonials} />,
    contact: <Contact profile={profile} socials={socials} copy={sectionCopy.contact} />,
    newsletter: null,
  };

  return (
    <>
      <Aurora />
      <Navbar
        name={profile.name}
        sections={sections}
        sectionOrder={sectionOrder}
        scrollSectionIds={mainSectionKeys}
        sectionCopy={sectionCopy}
        email={profile.email}
      />
      <HomeSectionAnalytics sections={sections} />
      <main>
        <Hero profile={profile} socials={socials} />
        {mainSectionKeys.map((key) => (
          <div key={key}>{sectionBlocks[key]}</div>
        ))}
      </main>
      <Footer profile={profile} socials={socials} newsletterCopy={newsletterCopy} />
    </>
  );
}
