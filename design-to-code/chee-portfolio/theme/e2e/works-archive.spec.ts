import { test, expect } from "@playwright/test";

test("works archive shows at least 1 post", async ({ page }) => {
  await page.goto("/works/");
  await expect(page.locator(".work-card").first()).toBeVisible();
});

test("works archive has breadcrumb", async ({ page }) => {
  await page.goto("/works/");
  await expect(page.locator(".breadcrumb")).toBeVisible();
});

test("single works shows client field", async ({ page }) => {
  await page.goto("/works/");
  await page.locator(".work-card-title a").first().click();
  await expect(page.locator(".works-meta__client")).toBeVisible();
});
