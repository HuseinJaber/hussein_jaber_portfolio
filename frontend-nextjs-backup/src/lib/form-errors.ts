export type FieldErrors = Record<string, string>;

export type ApiErrorResult = {
  message: string;
  fieldErrors: FieldErrors;
};

export function parseApiError(
  status: number,
  body: unknown,
  fallback = "Something went wrong. Please try again.",
): ApiErrorResult {
  const payload = body && typeof body === "object" ? (body as Record<string, unknown>) : {};
  const fieldErrors: FieldErrors = {};

  if (payload.errors && typeof payload.errors === "object") {
    for (const [field, messages] of Object.entries(payload.errors as Record<string, unknown>)) {
      if (Array.isArray(messages) && typeof messages[0] === "string") {
        fieldErrors[field] = messages[0];
      }
    }
  }

  if (typeof payload.message === "string" && payload.message.trim() !== "") {
    const hasFieldErrors = Object.keys(fieldErrors).length > 0;
    const isGenericValidation =
      payload.message === "The given data was invalid." ||
      payload.message.endsWith("more errors)");

    return {
      message: hasFieldErrors && isGenericValidation
        ? "Please fix the highlighted fields and try again."
        : payload.message,
      fieldErrors,
    };
  }

  if (status === 429) {
    return {
      message: "Too many attempts. Please wait a minute and try again.",
      fieldErrors,
    };
  }

  if (status === 403) {
    return {
      message: "This request could not be completed. Please refresh and try again.",
      fieldErrors,
    };
  }

  return { message: fallback, fieldErrors };
}

export async function readApiResponse(res: Response): Promise<unknown> {
  const text = await res.text();
  if (!text) return null;

  try {
    return JSON.parse(text);
  } catch {
    return { message: text.slice(0, 200) };
  }
}
