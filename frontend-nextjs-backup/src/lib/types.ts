export type SectionKey =
  | "about"
  | "services"
  | "skills"
  | "work"
  | "experience"
  | "certifications"
  | "testimonials"
  | "contact"
  | "newsletter";

export type SectionSettings = Record<SectionKey, boolean>;

export interface SectionCopy {
  nav_label: string;
  eyebrow: string;
  title: string;
  subtitle: string | null;
  align: "center" | "left";
}

export type SectionCopyMap = Record<SectionKey, SectionCopy>;

export interface Profile {
  id: number;
  name: string;
  title: string;
  headline: string | null;
  bio: string | null;
  about: string | null;
  email: string | null;
  phone: string | null;
  location: string | null;
  avatar: string | null;
  resume_url: string | null;
  sections: SectionSettings;
  section_order?: SectionKey[];
  section_copy?: Partial<SectionCopyMap>;
  years_experience: number;
  projects_completed: number;
  happy_clients: number;
  available_for_work: boolean;
  meta_title: string | null;
  meta_description: string | null;
}

export interface SocialLink {
  id: number;
  platform: string;
  label: string | null;
  url: string;
  icon: string | null;
}

export interface Skill {
  id: number;
  name: string;
  category: string;
  level: number;
  icon: string | null;
}

export interface Service {
  id: number;
  title: string;
  slug: string;
  description: string | null;
  icon: string | null;
}

export interface Project {
  id: number;
  title: string;
  slug: string;
  category: string;
  categories: string[];
  engagement_type: "development" | "support";
  contribution_areas: string[];
  contribution_labels: string[];
  work_context: "none" | "company" | "freelance";
  experience_id: number | null;
  experience?: { id: number; company: string; role: string } | null;
  short_description: string | null;
  description: string | null;
  cover_image: string | null;
  gallery: string[] | null;
  tech_stack: string[] | null;
  live_url: string | null;
  source_url: string | null;
  client: string | null;
  year: number | null;
  sites_count: number | null;
  is_featured: boolean;
}

export interface Experience {
  id: number;
  role: string;
  company: string;
  location: string | null;
  start_date: string | null;
  end_date: string | null;
  is_current: boolean;
  description: string | null;
}

export interface Education {
  id: number;
  degree: string;
  institution: string;
  location: string | null;
  start_date: string | null;
  end_date: string | null;
  description: string | null;
}

export interface Certification {
  id: number;
  title: string;
  issuer: string;
  issued_at: string | null;
  has_credential_pdf: boolean;
  credential_pdf_url: string | null;
}

export interface Testimonial {
  id: number;
  name: string;
  role: string | null;
  company: string | null;
  avatar: string | null;
  content: string;
  rating: number;
}

export interface PortfolioData {
  profile: Profile;
  socials: SocialLink[];
  skills: Skill[];
  services: Service[];
  experiences: Experience[];
  education: Education[];
  certifications: Certification[];
  projects: Project[];
  testimonials: Testimonial[];
}
