import { useEffect } from "react";

function upsertMeta(
  selector: string,
  create: () => HTMLMetaElement,
  content: string,
) {
  let element = document.querySelector<HTMLMetaElement>(selector);
  if (!element) {
    element = create();
    document.head.appendChild(element);
  }
  element.setAttribute("content", content);
}

export default function PageMeta({
  title,
  description,
  robots,
}: {
  title: string;
  description?: string;
  robots?: string;
}) {
  useEffect(() => {
    document.title = title;

    if (description) {
      upsertMeta(
        'meta[name="description"]',
        () => {
          const meta = document.createElement("meta");
          meta.setAttribute("name", "description");
          return meta;
        },
        description,
      );
      upsertMeta(
        'meta[property="og:title"]',
        () => {
          const meta = document.createElement("meta");
          meta.setAttribute("property", "og:title");
          return meta;
        },
        title,
      );
      upsertMeta(
        'meta[property="og:description"]',
        () => {
          const meta = document.createElement("meta");
          meta.setAttribute("property", "og:description");
          return meta;
        },
        description,
      );
      upsertMeta(
        'meta[property="og:type"]',
        () => {
          const meta = document.createElement("meta");
          meta.setAttribute("property", "og:type");
          return meta;
        },
        "website",
      );
    }

    if (robots) {
      upsertMeta(
        'meta[name="robots"]',
        () => {
          const meta = document.createElement("meta");
          meta.setAttribute("name", "robots");
          return meta;
        },
        robots,
      );
    }
  }, [title, description, robots]);

  return null;
}
