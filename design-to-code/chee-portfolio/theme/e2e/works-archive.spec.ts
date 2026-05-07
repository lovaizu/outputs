import { test, expect } from "@playwright/test";

// Requires templates (Task 4-5); skipped until archive-works.html exists
test.skip("works archive shows at least 1 post", async ({ page }) => {
  await page.goto("/works/");
  const articles = page.locator("article");
  await expect(articles.first()).toBeVisible();
});
