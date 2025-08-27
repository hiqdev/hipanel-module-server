import { Page } from "@playwright/test";
import Alert from "@hipanel-core/ui/Alert";
import Input from "@hipanel-core/input/Input";
import Select2 from "@hipanel-core/input/Select2";
import SwitchAssign from "@hipanel-module-server/model/SwitchAssign";

export default class SwitchAssignForm {
  private page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  async fill(assignSwitch: SwitchAssign) {
    await Select2.field(this.page, "#assignswitchesform-0-net_id").setValue(assignSwitch.switch);
    await Input.field(this.page, "#assignswitchesform-0-net_port").fill(assignSwitch.swPort);
    await Select2.field(this.page, "#assignswitchesform-0-kvm_id").setValue(assignSwitch.kvm);
    await Input.field(this.page, "#assignswitchesform-0-kvm_port").fill(assignSwitch.kvmPort);
    await Select2.field(this.page, "#assignswitchesform-0-pdu_id").setValue(assignSwitch.apc);
    await Input.field(this.page, "#assignswitchesform-0-pdu_port").fill(assignSwitch.apcPort);
    await Select2.field(this.page, "#assignswitchesform-0-rack_id").setValue(assignSwitch.rack);
    await Input.field(this.page, "#assignswitchesform-0-rack_port").fill(assignSwitch.rackPort);
    await Select2.field(this.page, "#assignswitchesform-0-location_id").setValue(assignSwitch.location);
  }

  async save() {
    await this.page.locator("button.btn-success:has-text(\"Save\")").click();
  }

  async seeSuccessAlert() {
    await Alert.on(this.page).hasText("Server has been created");
  }
}
