import { test, expect } from "@hipanel-core/fixtures";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";
import ServerAssignHub from "@hipanel-module-server/model/ServerAssignHub";
import ServerAssignHubForm from "@hipanel-module-server/page/ServerAssignHubForm";


const serverName = 'TEST-DS-01';

const assignHub: ServerAssignHub = {
  switch: 'SW-TEST-00',
  swPort: '432',
  switch2: 'TEST-SW-05',
  sw2Port: '234',
  kvm: 'TEST-SW-04',
  kvmPort: '11',
  rack: 'TEST-SW-02',
  rackPort: '22',
  apc: 'TEST-SW-06',
  apcPort: '33',
  ipmi: 'TEST-SW-05',
  ipmiPort: '55',
};

test("Test assign hub for server @hipanel-module-server @admin", async ({ page }) => {

  const serverHelper = new ServerHelper(page);
  const assignHubForm = new ServerAssignHubForm(page);
  await serverHelper.gotoIndexServer();
  await serverHelper.gotoAssignHubPage(serverName);
  await assignHubForm.fill(assignHub);
  await assignHubForm.save();

  await serverHelper.seeAlertMessage("Hubs have been assigned");
});


