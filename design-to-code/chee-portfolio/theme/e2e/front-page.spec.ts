import { test, expect } from "@playwright/test";

test("home page h1 is visible", async ({ page }) => {
  await page.goto("/");
  await expect(page.locator("h1")).toBeVisible();
});

test("home page shows all 8 sections", async ({ page }) => {
  await page.goto("/");
  for (const id of ["fv", "works", "voice", "service", "profile", "flow", "contact"]) {
    await expect(page.locator(`#${id}`)).toBeAttached();
  }
});
