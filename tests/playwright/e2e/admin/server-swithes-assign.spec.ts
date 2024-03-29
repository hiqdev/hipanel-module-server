import { test, expect } from "@hipanel-core/fixtures";
import Alert from "@hipanel-core/ui/Alert";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";
import ServerAssignHub from "@hipanel-module-server/model/ServerAssignHub";
import ServerAssignHubForm from "@hipanel-module-server/page/ServerAssignHubForm";
import SwitchAssign from "@hipanel-module-server/model/SwitchAssign";
import SwitchHelper from "@hipanel-module-server/helper/SwitchHelper";
import SwitchAssignForm from "@hipanel-module-server/page/SwitchAssignForm";


const switchName = 'SW-TEST-00';

const switchAssign: SwitchAssign = {
  switch: 'TEST-SW-05',
  swPort: '11',
  kvm: 'TEST-SW-04',
  kvmPort: '22',
  rack: 'TEST-SW-02',
  rackPort: '44',
  apc: 'TEST-SW-06',
  apcPort: '333',
  location: 'TEST-SW-03',
};

test("Test assign hub for server @hipanel-module-server @admin", async ({ page }) => {

  const switchHelper = new SwitchHelper(page);
  const assignHubForm = new SwitchAssignForm(page);

  await switchHelper.gotoIndexSwitch();
  await switchHelper.gotoAssignSwitchPage(switchName);
  await assignHubForm.fill(switchAssign);
  await assignHubForm.save();

  await switchHelper.seeAlertMessage("Switches have been edited");
});


