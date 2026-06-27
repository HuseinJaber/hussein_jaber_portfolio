import { getPortfolio } from "@/lib/api";
import Aurora from "@/components/ui/Aurora";
import Navbar from "@/components/layout/Navbar";
import Footer from "@/components/layout/Footer";
import Hero from "@/components/sections/Hero";
import About from "@/components/sections/About";
import Services from "@/components/sections/Services";
import Skills from "@/components/sections/Skills";
import Work from "@/components/sections/Work";
import Experience from "@/components/sections/Experience";
import Testimonials from "@/components/sections/Testimonials";
import Contact from "@/components/sections/Contact";

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
          . Start the backend with <code className="rounded bg-white/10 px-1.5 py-0.5">php artisan serve</code> and refresh.
        </p>
      </main>
    );
  }

  const { profile, socials, skills, services, projects, experiences, education, testimonials } = data;

  return (
    <>
      <Aurora />
      <Navbar name={profile.name} />
      <main>
        <Hero profile={profile} socials={socials} />
        <About profile={profile} education={education} />
        <Services services={services} />
        <Skills skills={skills} />
        <Work projects={projects} />
        <Experience experiences={experiences} />
        <Testimonials testimonials={testimonials} />
        <Contact profile={profile} socials={socials} />
      </main>
      <Footer profile={profile} socials={socials} />
    </>
  );
}
