import { test, expect } from "@playwright/test";

test("home page renders without PHP fatal", async ({ page }) => {
  await page.goto("/");
  const body = page.locator("body");
  await expect(body).not.toContainText("Fatal error");
  await expect(body).not.toContainText("Parse error");
});
