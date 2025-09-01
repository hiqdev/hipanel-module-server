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
    await Select2.field(this.page, "#assignhubsform-0-net_id").setValue(assignHub.net);
    await Input.field(this.page, "#assignhubsform-0-net_port").fill(assignHub.net_port);
    await Select2.field(this.page, "#assignhubsform-0-kvm_id").setValue(assignHub.kvm);
    await Input.field(this.page, "#assignhubsform-0-kvm_port").fill(assignHub.kvm_port);
    await Select2.field(this.page, "#assignhubsform-0-rack_id").setValue(assignHub.rack);
    await Input.field(this.page, "#assignhubsform-0-rack_port").fill(assignHub.rack_port);
    await Select2.field(this.page, "#assignhubsform-0-pdu_id").setValue(assignHub.pdu);
    await Input.field(this.page, "#assignhubsform-0-pdu_port").fill(assignHub.pdu_port);
    await Select2.field(this.page, "#assignhubsform-0-ipmi_id").setValue(assignHub.ipmi);
    await Input.field(this.page, "#assignhubsform-0-ipmi_port").fill(assignHub.ipmi_port);
  }

  async save() {
    await this.page.locator("button.btn-success:has-text(\"Save\")").click();
  }

  async seeSuccessAlert() {
    await Alert.on(this.page).hasText("Server has been created");
  }
}
