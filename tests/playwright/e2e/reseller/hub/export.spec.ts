import { test } from "@hipanel-core/fixtures";
import Index from "@hipanel-core/page/Index";

test("hub export works correctly @hipanel-module-server @seller", async ({ page }) => {
  const indexPage = new Index(page);

  await page.goto("/server/hub/index");
  await indexPage.testExport();
});
