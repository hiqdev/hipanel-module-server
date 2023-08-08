import { test, expect } from "@hipanel-core/fixtures";
import Alert from "@hipanel-core/ui/Alert";
import ServerForm from "../../page/ServerForm";
import Server from "../../model/Server";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";

const serverCreate: Server = {
  serverName: 'DS-TEST-00',
  dc: 'DS-TEST-00',
  type: 'cdn',
  order: 'Test order',
  internalNote: 'Test internal note',
  status: 'ok',
  hardwareSummary: 'Test summary',
  hardwareComment: 'Test comment',
}

const serverUpdate: Server = {
  serverName: 'TEST-DS-01',
  dc: 'TEST-DS-00',
  status: 'blocked',
  hardwareComment: 'Test comment update',
}

test("Test create server @hipanel-module-server @admin", async ({ page }) => {

  const serverForm = new ServerForm(page);
  const serverHelper = new ServerHelper(page)

  await serverHelper.gotoIndexServer();
  await serverHelper.gotoCreateServer();
  await serverForm.fill(serverCreate);
  await serverForm.saveServer();

  await serverForm.seeSuccessAlert();
});

test("Test update server @hipanel-module-server @admin", async ({ page }) => {

  const serverForm = new ServerForm(page);
  const serverHelper = new ServerHelper(page)

  await serverHelper.gotoIndexServer();
  await serverHelper.gotoUpdateServer(serverUpdate.serverName);
  await serverForm.updateServer(serverUpdate);
  await serverForm.saveServer();

  await serverForm.seeSuccessUpdatedAlert();
});

