import { test } from "@hipanel-core/fixtures";
import { faker } from "@faker-js/faker";
import SwitchHelper from "@hipanel-module-server/helper/SwitchHelper";
import SwitchForm from "@hipanel-module-server/page/SwitchForm";
import Switch from "@hipanel-module-server/model/Switch";
import SwitchAssign from "@hipanel-module-server/model/SwitchAssign";
import SwitchAssignForm from "@hipanel-module-server/page/SwitchAssignForm";

test("Test hub pages @hipanel-module-server @admin", async ({ page }) => {
  const hubName = faker.helpers.fake("TEST-SW-{{number.int({\"min\": 10, \"max\": 9999})}}");

  const testSwitchFormData: Switch = {
    name: hubName,
    type: "1000099",
    inn: "0123456789",
    model: "TEST-MOD-00",
    note: "Test note",
  };

  const testSwitchUpdateFormData: Switch = {
    inn: "0123456780",
    model: "TEST-MOD-01",
    note: "Test note update",
  };

  const switchAssign: SwitchAssign = {
    switch: "TEST-SW-05",
    swPort: faker.string.alpha(10),
    kvm: "TEST-SW-04",
    kvmPort: faker.string.alpha(10),
    rack: "TEST-SW-02",
    rackPort: faker.string.alpha(10),
    apc: "TEST-SW-06",
    apcPort: faker.string.alpha(10),
  };

  await test.step("index page is worked", async () => {
    const h = new SwitchHelper(page);
    await h.gotoIndexPage();
  });


  await test.step("Test create switch @hipanel-module-server @admin", async () => {
    const switchForm = new SwitchForm(page);
    const switchHelper = new SwitchHelper(page);

    await switchHelper.gotoIndexPage();
    await switchHelper.gotoCreateSwitch();
    await switchForm.fill(testSwitchFormData);
    await switchForm.saveSwitch();

    await switchForm.seeSuccessAlert();
  });

  await test.step("Test update switch @hipanel-module-server @admin", async () => {
    const switchForm = new SwitchForm(page);
    const switchHelper = new SwitchHelper(page);

    await switchHelper.gotoIndexPage();
    await switchHelper.gotoUpdateSwitch(hubName);
    await switchForm.updateSwitch(testSwitchUpdateFormData);
    await switchForm.saveSwitch();

    await switchForm.seeSuccessUpdatedAlert();
  });

  await test.step("Test assign hub for server @hipanel-module-server @admin", async () => {
    const switchHelper = new SwitchHelper(page);
    const assignHubForm = new SwitchAssignForm(page);

    await switchHelper.gotoIndexPage();
    await switchHelper.gotoAssignSwitchPage(hubName);
    await assignHubForm.fill(switchAssign);
    await assignHubForm.save();

    await switchHelper.seeAlertMessage("Hubs have been assigned");
  });

  await test.step("Test delete switch @hipanel-module-server @admin", async () => {
    const switchHelper = new SwitchHelper(page);

    await switchHelper.gotoIndexPage();
    await switchHelper.deleteSwitch(hubName);

    await switchHelper.seeAlertMessage("Switches have been deleted");
  });
});
