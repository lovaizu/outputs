import { test, expect } from "@playwright/test";
import * as fs from "fs";
import * as path from "path";

const screenshotDir = path.join(__dirname, "screenshots");

test.beforeAll(() => {
  if (!fs.existsSync(screenshotDir)) {
    fs.mkdirSync(screenshotDir, { recursive: true });
  }
});

test("screenshot: full home page", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  await page.screenshot({
    path: path.join(screenshotDir, "home-full.png"),
    fullPage: true,
  });
  await expect(page.locator("h1")).toBeVisible();
});

test("screenshot: sec01-fv", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#fv");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec01-fv.png") });
});

test("screenshot: sec02-works", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#works");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec02-works.png") });
});

test("screenshot: sec03-voice", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#voice");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec03-voice.png") });
});

test("screenshot: sec04-service", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#service");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec04-service.png") });
});

test("screenshot: sec05-cta", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#cta");
  if (await el.count() > 0) {
    await el.screenshot({ path: path.join(screenshotDir, "sec05-cta.png") });
  } else {
    await page.screenshot({ path: path.join(screenshotDir, "sec05-cta-fallback.png"), fullPage: false });
  }
});

test("screenshot: sec06-profile", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#profile");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec06-profile.png") });
});

test("screenshot: sec07-flow", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#flow");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec07-flow.png") });
});

test("screenshot: sec08-contact", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("#contact");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec08-contact.png") });
});

test("screenshot: footer", async ({ page }) => {
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  const el = page.locator("footer.site-footer");
  await expect(el).toBeAttached();
  await el.screenshot({ path: path.join(screenshotDir, "sec09-footer.png") });
});

test("screenshot: 375px mobile", async ({ page }) => {
  await page.setViewportSize({ width: 375, height: 812 });
  await page.goto("/");
  await page.waitForLoadState("networkidle");
  await page.screenshot({
    path: path.join(screenshotDir, "home-mobile-375.png"),
    fullPage: true,
  });
  await expect(page.locator("h1")).toBeVisible();
});
