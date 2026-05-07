import { test, expect } from "@playwright/test";

test("header nav is visible on home page", async ({ page }) => {
  await page.goto("/");
  await expect(page.locator("header nav")).toBeVisible();
});

test("footer is visible on home page", async ({ page }) => {
  await page.goto("/");
  await expect(page.locator("footer.site-footer")).toBeVisible();
});
