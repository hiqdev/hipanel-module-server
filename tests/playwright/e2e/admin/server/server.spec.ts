import { test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import { Server } from "@hipanel-module-server/types";
import ServerPage from "@hipanel-module-server/pages/ServerPage";
import ServerFormPage from "@hipanel-module-server/pages/ServerFormPage";
import ServerViewPage from "@hipanel-module-server/pages/ServerViewPage";

test("Test server pages @hipanel-module-server @admin", async ({ page }) => {
  const serverName = faker.helpers.fake("BTEST-DS-{{number.int({\"min\": 10, \"max\": 9999})}}");
  const serverPage = new ServerPage(page);
  const serverViewPage = new ServerViewPage(page);
  const serverForm = new ServerFormPage(page);

  const serverCreateTestData: Server = {
    serverName,
    dc: serverName,
    type: "cdn",
    status: "ok",
    hardwareSummary: "Test summary",
  };

  const serverUpdateTestData: Server = {
    ...serverCreateTestData,
    serverName: null,
    hardwareSummary: "Test summary comment update",
  };

  await test.step("index page is worked", async () => {
    await serverPage.gotoIndex();
    await serverPage.hasMainElementsOnIndexPage();
  });

  await test.step("it can not submit empty form", async () => {
    await serverPage.gotoServerCreate();
    await serverForm.submit();
    await serverForm.hasErrors();
  });

  await test.step("it can create server", async () => {
    await serverPage.gotoServerCreate();
    await serverForm.fill(serverCreateTestData);
    await serverForm.submit();
    await serverForm.seeSuccessCreateAlert();
    await serverViewPage.view.see(Object.values(serverCreateTestData));
  });

  await test.step("it can update the server", async () => {
    await serverPage.gotoIndex();
    await serverPage.gotoServerUpdate(serverCreateTestData.serverName);
    await serverForm.fill(serverUpdateTestData);
    await serverForm.submit();
    await serverForm.seeSuccessUpdatedAlert();
  });

  await test.step("it can delete the server", async () => {
    await serverPage.gotoIndex();
    await serverPage.deleteServer(serverName);
    await serverPage.seeAlertMessage("Server was deleted successfully");
  });
});
