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
