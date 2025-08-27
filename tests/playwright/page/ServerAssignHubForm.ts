import { Page } from "@playwright/test";
import Alert from "@hipanel-core/ui/Alert";
import Input from "@hipanel-core/input/Input";
import Select2 from "@hipanel-core/input/Select2";
import ServerAssignHub from "@hipanel-module-server/model/ServerAssignHub";

export default class ServerAssignHubForm {
  private page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  async fill(assignHub: ServerAssignHub) {
    await Select2.field(this.page, "#assignhubsform-0-net_id").setValue(assignHub.switch);
    await Input.field(this.page, "#assignhubsform-0-net_port").fill(assignHub.swPort);
    await Select2.field(this.page, "#assignhubsform-0-net2_id").setValue(assignHub.switch2);
    await Input.field(this.page, "#assignhubsform-0-net2_port").fill(assignHub.sw2Port);
    await Select2.field(this.page, "#assignhubsform-0-kvm_id").setValue(assignHub.kvm);
    await Input.field(this.page, "#assignhubsform-0-kvm_port").fill(assignHub.kvmPort);
    await Select2.field(this.page, "#assignhubsform-0-rack_id").setValue(assignHub.rack);
    await Input.field(this.page, "#assignhubsform-0-rack_port").fill(assignHub.rackPort);
    await Select2.field(this.page, "#assignhubsform-0-pdu_id").setValue(assignHub.apc);
    await Input.field(this.page, "#assignhubsform-0-pdu_port").fill(assignHub.apcPort);
    await Select2.field(this.page, "#assignhubsform-0-ipmi_id").setValue(assignHub.ipmi);
    await Input.field(this.page, "#assignhubsform-0-ipmi_port").fill(assignHub.ipmiPort);
  }

  async save() {
    await this.page.locator("button.btn-success:has-text(\"Save\")").click();
  }

  async seeSuccessAlert() {
    await Alert.on(this.page).hasText("Server has been created");
  }
}
