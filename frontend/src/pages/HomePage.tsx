import type { ReactNode } from "react";
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
import PageMeta from "@/components/PageMeta";
import { usePortfolio } from "@/hooks/usePortfolio";
import { API_URL } from "@/lib/api";
import {
  resolveSectionCopy,
  resolveSectionOrder,
  resolveSections,
  sectionCopyFor,
  visibleMainSections,
} from "@/lib/sections";
import type { PortfolioData, SectionKey } from "@/lib/types";

function sectionHasContent(data: PortfolioData, key: SectionKey): boolean {
  switch (key) {
    case "testimonials":
      return data.testimonials.length > 0;
    case "work":
      return data.projects.length > 0;
    case "skills":
      return data.skills.length > 0;
    case "services":
      return data.services.length > 0;
    case "experience":
      return data.experiences.length > 0;
    case "certifications":
      return data.certifications.length > 0;
    default:
      return true;
  }
}

export default function HomePage() {
  const { data, loading } = usePortfolio();

  if (loading) {
    return (
      <main className="flex min-h-screen flex-col items-center justify-center px-4 text-center">
        <Aurora />
        <p className="text-muted">Loading portfolio…</p>
      </main>
    );
  }

  if (!data) {
    return (
      <main className="flex min-h-screen flex-col items-center justify-center px-4 text-center">
        <Aurora />
        <h1 className="text-3xl font-bold">Backend not reachable</h1>
        <p className="mt-3 max-w-md text-muted">
          The site could not load data from the Laravel API at{" "}
          <code className="rounded bg-white/10 px-1.5 py-0.5">{API_URL}</code>. Start the
          backend and refresh.
        </p>
      </main>
    );
  }

  const {
    profile,
    socials,
    skills,
    services,
    projects,
    experiences,
    education,
    certifications,
    testimonials,
  } = data;
  const sections = resolveSections(profile.sections);
  const sectionOrder = resolveSectionOrder(profile.section_order);
  const sectionCopy = resolveSectionCopy(profile.section_copy);
  const hasContent = (key: SectionKey) => sectionHasContent(data, key);
  const mainSectionKeys = visibleMainSections(sections, sectionOrder, hasContent);
  const newsletterCopy = sectionCopyFor(sectionCopy, "newsletter", { name: profile.name });

  const sectionBlocks: Record<SectionKey, ReactNode> = {
    about: <About profile={profile} education={education} copy={sectionCopy.about} />,
    services: <Services services={services} copy={sectionCopy.services} />,
    skills: <Skills skills={skills} copy={sectionCopy.skills} />,
    work: <Work projects={projects} copy={sectionCopy.work} />,
    experience: <Experience experiences={experiences} copy={sectionCopy.experience} />,
    certifications: (
      <Certifications certifications={certifications} copy={sectionCopy.certifications} />
    ),
    testimonials: <Testimonials testimonials={testimonials} copy={sectionCopy.testimonials} />,
    contact: <Contact profile={profile} socials={socials} copy={sectionCopy.contact} />,
    newsletter: null,
  };

  return (
    <>
      <PageMeta
        title={
          profile.meta_title ??
          `${profile.name} — ${profile.title}`
        }
        description={
          profile.meta_description ??
          profile.bio ??
          "Full Stack Developer building fast, secure and beautiful web applications."
        }
      />
      <Aurora />
      <Navbar
        name={profile.name}
        sections={sections}
        sectionOrder={sectionOrder}
        scrollSectionIds={mainSectionKeys}
        sectionCopy={sectionCopy}
        email={profile.email}
        sectionHasContent={hasContent}
      />
      <HomeSectionAnalytics sections={sections} />
      <main>
        <Hero profile={profile} projectCount={projects.length} />
        {mainSectionKeys.map((key) => (
          <div key={key}>{sectionBlocks[key]}</div>
        ))}
      </main>
      <Footer profile={profile} socials={socials} newsletterCopy={newsletterCopy} />
    </>
  );
}
