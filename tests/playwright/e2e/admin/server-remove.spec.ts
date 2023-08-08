import { test, expect } from "@hipanel-core/fixtures";
import Alert from "@hipanel-core/ui/Alert";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";


const serverName = 'TEST-DS-01'

test("Test delete server @hipanel-module-server @admin", async ({ page }) => {

  const serverHelper = new ServerHelper(page)

  await serverHelper.gotoIndexServer();
  await serverHelper.deleteServer(serverName);

  await serverHelper.seeAlertMessage("Server was deleted successfully");
});


