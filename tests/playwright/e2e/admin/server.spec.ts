import { test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";
import ServerForm from "@hipanel-module-server/page/ServerForm";
import ServerAssignHub from "@hipanel-module-server/model/ServerAssignHub";
import ServerAssignHubForm from "@hipanel-module-server/page/ServerAssignHubForm";

test("Test server pages @hipanel-module-server @admin", async ({ page }) => {
  const serverName = faker.helpers.fake("BTEST-DS-{{number.int({\"min\": 10, \"max\": 9999})}}");

  const serverCreate = {
    serverName,
    dc: serverName,
    type: "cdn",
    internalNote: "Test internal note",
    status: "ok",
    hardwareSummary: "Test summary",
    hardwareComment: "Test comment",
  };

  const assignHubFormData: ServerAssignHub = {
    net: "TEST-SW-05",
    net_port: faker.string.alpha(10),
    kvm: "TEST-SW-04",
    kvm_port: faker.string.alpha(10),
    rack: "TEST-SW-02",
    rack_port: faker.string.alpha(10),
    pdu: "TEST-SW-06",
    pdu_port: faker.string.alpha(10),
    ipmi: "TEST-SW-05",
    ipmi_port: faker.string.alpha(10),
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

  await test.step("it assign hub to server @hipanel-module-server @admin", async () => {
    const serverHelper = new ServerHelper(page);
    const assignHubForm = new ServerAssignHubForm(page);

    await serverHelper.gotoIndexServer();
    await serverHelper.gotoAssignHubPage(serverName);
    await assignHubForm.fill(assignHubFormData);
    await assignHubForm.save();

    await serverHelper.seeAlertMessage("Hubs have been assigned");
  });

  await test.step("it can delete the server", async () => {
    const serverHelper = new ServerHelper(page);

    await serverHelper.gotoIndexServer();
    await serverHelper.deleteServer(serverName);

    await serverHelper.seeAlertMessage("Server was deleted successfully");
  });
});
