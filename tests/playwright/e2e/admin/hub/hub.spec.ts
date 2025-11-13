import { test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import HubPage from "@hipanel-module-server/pages/HubPage";
import { Hub } from "@hipanel-module-server/types";

test("Test hub pages @hipanel-module-server @admin", async ({ page }) => {
  const hubName = faker.helpers.fake("TEST-SW-{{number.int({\"min\": 10, \"max\": 9999})}}");
  const hubPage = new HubPage(page);

  const hubTestData: Hub = {
    name: hubName,
    type: "Switch",
    inn: "0123456789",
    model: "TEST-MOD-00",
    note: "Test note",
  };

  const updateHubTestData: Hub = {
    ...hubTestData,
    inn: "0123456780",
    model: "TEST-MOD-01",
    note: "Test note update",
  };

  await test.step("index page is worked", async () => {
    await hubPage.gotoIndexPage();
    await hubPage.hasColumns();
    await hubPage.hasFilters();
    await hubPage.hasBulkButtons();
  });

  await test.step("create hub @hipanel-module-server @admin", async () => {
    await hubPage.gotoIndexPage();
    await hubPage.gotoCreate();

    await hubPage.cannotCreateWtihEmptyForm();

    await hubPage.create(hubTestData);
  });

  await test.step("update hub @hipanel-module-server @admin", async () => {
    await hubPage.gotoIndexPage();
    await hubPage.gotoUpdate(hubName);
    await hubPage.update(updateHubTestData);
  });

  await test.step("delete hub @hipanel-module-server @admin", async () => {
    await hubPage.gotoIndexPage();
    await hubPage.delete(hubName);
  });
});
