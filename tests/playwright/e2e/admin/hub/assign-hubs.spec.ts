import { expect, test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import Input from "@hipanel-core/input/Input";
import AssignHubsForm from "@hipanel-module-server/pages/AssignHubsForm";
import HubPage from "@hipanel-module-server/pages/HubPage";
import { AssignHubs } from "@hipanel-module-server/types";
import { Page } from "@playwright/test";

const searchInCombo = async (page: Page, query: string, field = "net_id") => {
  await page.locator(`#assignhubsform-0-${field} + .select2-container [role=combobox]`).click();
  await page.locator(".select2-container--open input.select2-search__field").fill(query);
  // wait for the ajax results (an option or "No results found"), not the "Searching…" row
  await page.locator("ul.select2-results__options li:not(.loading-results)").first().waitFor();

  return page.locator("ul.select2-results__options li");
};

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

test("Switches never offer a PDU and a taken port names its holder @hipanel-module-server @admin", async ({ page }) => {
  const assignHubsPage = new AssignHubsForm(page);
  const hubPage = new HubPage(page);
  const suffix = faker.string.alphanumeric(6).toUpperCase();
  const [a, b, c] = ["A", "B", "C"].map(n => `TEST-PDU-${suffix}-${n}`);
  const switchName = "TEST-SW-05";
  const switchPort = assignHubsPage.fakePort();
  const portError = (field: string) => page.locator(`.field-assignhubsform-0-${field}_port .help-block`);

  const openForm = async (name: string) => {
    await hubPage.gotoIndexPage();
    await hubPage.gotoAssignHubs(name);
  };
  const save = async (name: string, assignment: AssignHubs) => {
    await openForm(name);
    await assignHubsPage.fill([assignment]);
    await assignHubsPage.form.submit();
    await assignHubsPage.seeSuccessAlert();
  };
  const submitRejected = async (name: string, assignment: AssignHubs) => {
    await openForm(name);
    await assignHubsPage.fill([assignment]);
    await assignHubsPage.form.submit();
  };

  await test.step("create PDUs A, B, C", async () => {
    for (const name of [a, b, c]) {
      await hubPage.gotoIndexPage();
      await hubPage.gotoCreate();
      await hubPage.create({ name, type: "APC", model: "TEST-MOD-00" });
    }
  });

  await test.step("Switches on a PDU form offers no PDU base device", async () => {
    await openForm(a);
    await expect((await searchInCombo(page, b)).filter({ hasText: new RegExp(`^${b}$`) })).toHaveCount(0);
  });

  await test.step("APCs on a PDU form still offers the other PDU", async () => {
    await openForm(a);
    await expect((await searchInCombo(page, b, "pdu_id")).filter({ hasText: new RegExp(`^${b}$`) })).toHaveCount(1);
  });

  await test.step("a taken power outlet is named as such", async () => {
    await save(c, { pdu_id: b, pdu_port: "1" });
    await submitRejected(a, { pdu_id: b, pdu_port: "1" });
    await expect(portError("pdu")).toHaveText(`Power outlet ${b}:1 is already taken by ${c}`);
  });

  await test.step("a taken network port is named as such", async () => {
    await save(c, { net_id: switchName, net_port: switchPort });
    await submitRejected(a, { net_id: switchName, net_port: switchPort });
    await expect(portError("net")).toHaveText(`Network port ${switchName}:${switchPort} is already taken by ${c}`);
    await expect(portError("net")).not.toContainText("::");
  });

  await test.step("free the switch port", async () => {
    await openForm(c);
    await assignHubsPage.clearAssigments({ net_id: null, net_port: null });
    await assignHubsPage.form.submit();
    await assignHubsPage.seeSuccessAlert();
  });

  await test.step("delete the PDUs", async () => {
    for (const name of [c, b, a]) {
      await hubPage.gotoIndexPage();
      await hubPage.delete(name);
    }
  });
});
