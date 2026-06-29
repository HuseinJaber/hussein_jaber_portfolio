import { useEffect, useState } from "react";
import { getPortfolio } from "@/lib/api";
import type { PortfolioData } from "@/lib/types";

export function usePortfolio() {
  const [data, setData] = useState<PortfolioData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let active = true;

    getPortfolio().then((portfolio) => {
      if (active) {
        setData(portfolio);
        setLoading(false);
      }
    });

    return () => {
      active = false;
    };
  }, []);

  return { data, loading };
}
