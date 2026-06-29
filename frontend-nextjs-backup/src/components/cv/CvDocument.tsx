import type { CvData } from "@/lib/cv";
import {
  certificationLine,
  cvSkillColumns,
  educationLine,
  experienceDetail,
  formatDateRange,
  socialLabel,
} from "@/lib/cv";

function CvSection({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <section className="cv-section">
      <h2 className="cv-section-title">{title}</h2>
      {children}
    </section>
  );
}

function CvBulletList({ items }: { items: string[] }) {
  return (
    <ul className="cv-list">
      {items.map((item) => (
        <li key={item}>{item}</li>
      ))}
    </ul>
  );
}

export default function CvDocument({ data }: { data: CvData }) {
  const { profile, socials, experiences, education, skills, certifications } = data;
  const [skillsLeft, skillsRight] = cvSkillColumns(skills);

  const contactRows: { label: string; value: React.ReactNode }[] = [
    { label: "Name", value: profile.name },
    { label: "Nationality", value: data.nationality },
    { label: "Address", value: profile.location },
    { label: "Phone", value: profile.phone },
    { label: "Email", value: profile.email },
  ].filter((row) => row.value);

  return (
    <article className="cv-document">
      <header className="cv-header">
        <div className="cv-contact-grid">
          {contactRows.map((row) => (
            <p key={row.label} className="cv-contact-row">
              <span className="cv-label">{row.label}:</span>{" "}
              {row.label === "Email" && typeof row.value === "string" ? (
                <a href={`mailto:${row.value}`} className="cv-link">
                  {row.value}
                </a>
              ) : (
                row.value
              )}
            </p>
          ))}
          {socials.length > 0 && (
            <p className="cv-contact-row cv-contact-links">
              <span className="cv-label">Links:</span>{" "}
              {socials.map((link, index) => (
                <span key={link.id}>
                  {index > 0 && " · "}
                  <a href={link.url} className="cv-link" target="_blank" rel="noopener noreferrer">
                    {socialLabel(link)}
                  </a>
                </span>
              ))}
            </p>
          )}
        </div>
      </header>

      <CvSection title="Summary">
        <p className="cv-summary">{data.summary}</p>
      </CvSection>

      {experiences.length > 0 && (
        <CvSection title="Experience">
          <ul className="cv-entry-list">
            {experiences.map((exp) => {
              const detail = experienceDetail(exp);
              return (
                <li key={exp.id} className="cv-entry">
                  <div className="cv-entry-head">
                    <p className="cv-entry-title">
                      <strong>{exp.company}</strong>
                      {exp.location ? ` · ${exp.location}` : ""}
                    </p>
                    <p className="cv-entry-dates">
                      {formatDateRange(exp.start_date, exp.end_date, exp.is_current)}
                    </p>
                  </div>
                  <p className="cv-entry-role">{exp.role}</p>
                  {detail && <p className="cv-entry-body">{detail}</p>}
                </li>
              );
            })}
          </ul>
        </CvSection>
      )}

      {education.length > 0 && (
        <CvSection title="Education">
          <ul className="cv-entry-list">
            {education.map((edu) => (
              <li key={edu.id} className="cv-entry">
                <div className="cv-entry-head">
                  <p className="cv-entry-title">{educationLine(edu)}</p>
                  <p className="cv-entry-dates">
                    {formatDateRange(edu.start_date, edu.end_date)}
                  </p>
                </div>
                {edu.description && <p className="cv-entry-body">{edu.description}</p>}
              </li>
            ))}
          </ul>
        </CvSection>
      )}

      {(skills.length > 0 || data.languages.length > 0) && (
        <CvSection title="Skills">
          {skills.length > 0 && (
            <div className="cv-skills-grid">
              <CvBulletList items={skillsLeft} />
              <CvBulletList items={skillsRight} />
            </div>
          )}
          {data.languages.length > 0 && (
            <p className="cv-languages">
              <span className="cv-label">Languages:</span>{" "}
              {data.languages.map((lang) => `${lang.name} (${lang.level})`).join(" · ")}
            </p>
          )}
        </CvSection>
      )}

      {certifications.length > 0 && (
        <CvSection title="Certifications">
          <CvBulletList items={certifications.map(certificationLine)} />
        </CvSection>
      )}
    </article>
  );
}
