import { test } from "@hipanel-core/fixtures";
import Index from "@hipanel-core/page/Index";
import { Locator } from "@playwright/test";
import Select2 from "@hipanel-core/input/Select2";
import Form from "@hipanel-core/page/Form";

test("Set Rack No. bulk command @hipanel-module-server @admin", async ({ page }) => {
  const indexPage = new Index(page);
  const formPage = new Form(page);

  await test.step("I can set Rack No.", async () => {
    await page.goto("/server/server/index");
    await indexPage.columnFilters.applyFilter("name_ilike", "TEST-DS-01");
    await indexPage.chooseNumberRowOnTable(1);
    await indexPage.bulkActions
      .dropdown("Basic actions")
      .then(
        async (dropdown: Locator) => await dropdown.getByText("Set Rack No.").click(),
      );
    await Select2.field(page, "#assignhubsform-rack_id").setValue("TEST-SW-02");
    await formPage.submit();
    await formPage.seeAlert("Rack No. has been assigned");
    await formPage.see(["Rack", "TEST-SW-02"]);
  });

  await test.step("I can unset Rack No.", async () => {
    await page.goto("/server/server/index");
    await indexPage.columnFilters.applyFilter("name_ilike", "TEST-DS-01");
    await indexPage.chooseNumberRowOnTable(1);
    await indexPage.bulkActions
      .dropdown("Basic actions")
      .then(
        async (dropdown: Locator) => await dropdown.getByText("Set Rack No.").click(),
      );
    await formPage.submit();
    await formPage.seeAlert("Rack No. has been assigned");
    await formPage.notSee(["Rack", "TEST-SW-02"]);
  });
});
