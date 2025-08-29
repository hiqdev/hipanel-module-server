import { test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";
import ServerForm from "@hipanel-module-server/page/ServerForm";

test("Test server pages @hipanel-module-server @admin", async ({ page }) => {
  const serverName = faker.helpers.fake("BTEST-DS-{{number.int({\"min\": 10, \"max\": 99})}}");

  const serverCreate = {
    serverName,
    dc: serverName,
    type: "cdn",
    internalNote: "Test internal note",
    status: "ok",
    hardwareSummary: "Test summary",
    hardwareComment: "Test comment",
  };

  const serverUpdate = {
    serverName,
    hardwareComment: "Test comment update",
  };

  await test.step("index page is worked", async () => {
    const serverHelper = new ServerHelper(page);
    await serverHelper.gotoIndexServer();

    await serverHelper.hasMainElementsOnIndexPage();
  });

  await test.step("it can create server", async () => {
    const serverForm = new ServerForm(page);
    const serverHelper = new ServerHelper(page);

    await serverHelper.gotoIndexServer();
    await serverHelper.gotoCreateServer();
    await serverForm.fill(serverCreate);
    await serverForm.saveServer();

    await serverForm.seeSuccessAlert();
  });

  await test.step("it can update the server", async () => {
    const serverForm = new ServerForm(page);
    const serverHelper = new ServerHelper(page);

    await serverHelper.gotoIndexServer();
    await serverHelper.gotoUpdateServer(serverUpdate.serverName);
    await serverForm.updateServer(serverUpdate);
    await serverForm.saveServer();

    await serverForm.seeSuccessUpdatedAlert();
  });
});
