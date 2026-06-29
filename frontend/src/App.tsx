import { Route, Routes } from "react-router-dom";
import AnalyticsTracker from "@/components/AnalyticsTracker";
import CookieConsent from "@/components/CookieConsent";
import CookiesPage from "@/pages/CookiesPage";
import CvPage from "@/pages/CvPage";
import HomePage from "@/pages/HomePage";
import PrivacyPage from "@/pages/PrivacyPage";
import ProjectPage from "@/pages/ProjectPage";
import TermsPage from "@/pages/TermsPage";

export default function App() {
  return (
    <>
      <AnalyticsTracker />
      <CookieConsent />
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/projects/:slug" element={<ProjectPage />} />
        <Route path="/cv" element={<CvPage />} />
        <Route path="/privacy" element={<PrivacyPage />} />
        <Route path="/cookies" element={<CookiesPage />} />
        <Route path="/terms" element={<TermsPage />} />
      </Routes>
    </>
  );
}
