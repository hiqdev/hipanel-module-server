import { test, expect } from "@hipanel-core/fixtures";
import Alert from "@hipanel-core/ui/Alert";
import SwitchHelper from "@hipanel-module-server/helper/SwitchHelper";


const switchName = 'SW-TEST-00'

test("Test delete switch @hipanel-module-server @admin", async ({ page }) => {

  const switchHelper = new SwitchHelper(page)

  await switchHelper.gotoIndexSwitch();
  await switchHelper.deleteSwitch(switchName);

  await switchHelper.seeAlertMessage('Switches have been deleted');
});


