import { expect, Locator, Page } from "@playwright/test";
import Alert from "@hipanel-core/ui/Alert";
import Index from "@hipanel-core/page/Index";
import Input from "@hipanel-core/input/Input";
import Dropdown from "@hipanel-core/input/Dropdown";
import Switch from "@hipanel-module-server/model/Switch";
import Select2 from "@hipanel-core/input/Select2";

export default class SwitchForm {
  private page: Page;
  private index: Index

  constructor(page: Page) {
    this.page = page;
    this.index = new Index(page);
  }

  async fill(testSwitch: Switch) {
    await Input.field(this.page, '#hub-0-name').fill(testSwitch.name);
    await Dropdown.field(this.page, '#hub-0-type_id').setValue(testSwitch.type);
    await Input.field(this.page, '#hub-0-inn').fill(testSwitch.inn);
    await Input.field(this.page, '#hub-0-model').fill(testSwitch.model);
    await Input.field(this.page, '#hub-0-note').fill(testSwitch.note);
  }

  async saveSwitch() {
    await this.page.locator('button.btn-success:has-text("Save")').click();
  }

  async updateSwitch(testSwitch) {
    await Input.field(this.page, '#hub-0-inn').fill(testSwitch.inn);
    await Input.field(this.page, '#hub-0-model').fill(testSwitch.model);
    await Input.field(this.page, '#hub-0-note').fill(testSwitch.note);
  }

  async seeSuccessAlert() {
    await Alert.on(this.page).hasText("Switch was created");
  }

  async seeSuccessUpdatedAlert() {
    await Alert.on(this.page).hasText("Switch was updated");
  }
}
