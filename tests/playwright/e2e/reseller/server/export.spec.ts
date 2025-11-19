import { test } from "@hipanel-core/fixtures";
import Index from "@hipanel-core/page/Index";

test("server export works correctly @hipanel-module-server @seller", async ({ page }) => {
  const indexPage = new Index(page);

  await page.goto("/server/server/index");
  await indexPage.testExport();
});
