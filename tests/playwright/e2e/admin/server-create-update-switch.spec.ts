import { test, expect } from "@hipanel-core/fixtures";
import Switch from "@hipanel-module-server/model/Switch";
import SwitchForm from "@hipanel-module-server/page/SwitchForm";
import SwitchHelper from "@hipanel-module-server/helper/SwitchHelper";

const testSwitchCreate: Switch = {
  name: 'SW-TEST-00',
  type: '1000099',
  inn: '0123456789',
  model: 'TEST-MOD-00',
  note: 'Test note',
}

const testSwitchUpdate: Switch = {
  name: 'SW-TEST-00',
  type: '1000099',
  inn: '0123456780',
  model: 'TEST-MOD-01',
  note: 'Test note update',
}

test("Test create switch @hipanel-module-server @admin", async ({ page }) => {

  const switchForm = new SwitchForm(page);
  const switchHelper = new SwitchHelper(page);

  await switchHelper.gotoIndexSwitch();
  await switchHelper.gotoCreateSwitch();
  await switchForm.fill(testSwitchCreate);
  await switchForm.saveSwitch();

  await switchForm.seeSuccessAlert();
});

test("Test update switch @hipanel-module-server @admin", async ({ page }) => {

  const switchForm = new SwitchForm(page);
  const switchHelper = new SwitchHelper(page);

  await switchHelper.gotoIndexSwitch();
  await switchHelper.gotoUpdateSwitch(testSwitchUpdate.name);
  await switchForm.updateSwitch(testSwitchUpdate);
  await switchForm.saveSwitch();

  await switchForm.seeSuccessUpdatedAlert();
});

