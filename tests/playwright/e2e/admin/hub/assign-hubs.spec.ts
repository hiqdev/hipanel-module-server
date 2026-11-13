import { expect, test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import Input from "@hipanel-core/input/Input";
import AssignHubsForm from "@hipanel-module-server/pages/AssignHubsForm";
import HubPage from "@hipanel-module-server/pages/HubPage";
import { AssignHubs } from "@hipanel-module-server/types";

test("the assignments are correctly works and displayed on the switch's detail page @hipanel-module-server @admin", async ({ page }) => {
  const assignHubsPage = new AssignHubsForm(page);
  const hubPage = new HubPage(page);
  const testData: AssignHubs = {
    net_id: "TEST-SW-05",
    net_port: assignHubsPage.fakePort(),
    pdu_id: "TEST-SW-06",
    pdu_port: assignHubsPage.fakePort(),
  };

  await page.goto("/server/hub/index");
  await hubPage.gotoAssignHubs("TEST-SW-05");

  // Assign hubs is reached here via the index's bulk action, so on submit the app
  // redirects back to the (previous) index page, not to the switch's detail page.
  // Verify the bindings by navigating to the detail page explicitly afterwards.
  await assignHubsPage.fill([testData]);
  await assignHubsPage.form.submit();
  await assignHubsPage.seeSuccessAlert();

  await hubPage.gotoView("TEST-SW-05");
  await assignHubsPage.seeResult(testData);

  await expect(page.getByRole("link", { name: "Assign hubs" })).toBeVisible();
  await expect(page.getByRole("heading", { name: "Hubs" })).toBeVisible();
});

// A PDU-to-PDU (daisy-chain) link in the Switches field lands on the target's mgmt NIC,
// so it must not clash with the target's power outlet of the same number.
test("a PDU mgmt port does not clash with the same-numbered power outlet @hipanel-module-server @admin", async ({ page }) => {
  const assignHubsPage = new AssignHubsForm(page);
  const hubPage = new HubPage(page);
  const suffix = faker.string.alphanumeric(6).toUpperCase();
  const [target, chained, powered] = ["A", "B", "C"].map(n => `TEST-PDU-${suffix}-${n}`);

  const powerFromTarget = async () => {
    await hubPage.gotoIndexPage();
    await hubPage.gotoAssignHubs(powered);
    await assignHubsPage.fill([{ pdu_id: target, pdu_port: "1" }]);
    await assignHubsPage.form.submit();
  };

  // The Switches combo finds a PDU by its name but labels it with its mgmt NIC (<name>nic2),
  // so Select2.setValue(), which needs the search text to equal the label, can't pick it.
  const searchTargetInSwitches = async (name: string) => {
    await hubPage.gotoIndexPage();
    await hubPage.gotoAssignHubs(name);
    await page.locator("#assignhubsform-0-net_id + .select2-container [role=combobox]").click();
    await page.locator(".select2-container--open input.select2-search__field").fill(target);
    // wait for the ajax results (an option or "No results found"), not the "Searching…" row
    await page.locator("ul.select2-results__options li:not(.loading-results)").first().waitFor();

    return page.locator("ul.select2-results__options li", { hasText: `${target}nic2` });
  };

  const chainToTarget = async (name: string) => {
    await (await searchTargetInSwitches(name)).click();
    await Input.field(page, "#assignhubsform-0-net_port").setValue("1");
    await assignHubsPage.form.submit();
  };

  await test.step("create three PDUs", async () => {
    for (const name of [target, chained, powered]) {
      await hubPage.gotoIndexPage();
      await hubPage.gotoCreate();
      // APC view shows neither INN nor note, so only fields create() can see afterwards
      await hubPage.create({ name, type: "APC", model: "TEST-MOD-00" });
    }
  });

  await test.step("power outlet 1 of the target is taken", async () => {
    await powerFromTarget();
    await assignHubsPage.seeSuccessAlert();
  });

  await test.step("mgmt port 1 of the target can still be chained", async () => {
    await chainToTarget(chained);
    await assignHubsPage.seeSuccessAlert();
  });

  await test.step("Switches combo lists the target once and never the PDU itself", async () => {
    await expect(await searchTargetInSwitches(powered)).toHaveCount(1);
    await expect(await searchTargetInSwitches(target)).toHaveCount(0);
  });

  await test.step("a second chain to the same mgmt port is rejected", async () => {
    await chainToTarget(powered);
    await assignHubsPage.alert.hasText("already assigned");
  });

  await test.step("delete the PDUs", async () => {
    for (const name of [powered, chained, target]) {
      await hubPage.gotoIndexPage();
      await hubPage.delete(name);
    }
  });
});
