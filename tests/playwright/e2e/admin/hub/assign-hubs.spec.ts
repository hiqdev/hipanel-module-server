import { expect, test } from "@hipanel-core/fixtures";
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

  await assignHubsPage.save(testData);

  await expect(page.getByRole("link", { name: "Assign hubs" })).toBeVisible();
  await expect(page.getByRole("heading", { name: "Hubs" })).toBeVisible();
});
