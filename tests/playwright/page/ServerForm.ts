import { expect, Locator, Page } from "@playwright/test";
import Alert from "@hipanel-core/ui/Alert";
import Index from "@hipanel-core/page/Index";
import Input from "@hipanel-core/input/Input";
import Dropdown from "@hipanel-core/input/Dropdown";
import Server from "@hipanel-module-server/model/Server";

export default class ServerForm {
  private page: Page;
  private index: Index

  constructor(page: Page) {
    this.page = page;
    this.index = new Index(page);
  }

  async fill(server: Server) {
    await Input.field(this.page, '#serverform-0-server').fill(server.serverName);
    await Input.field(this.page, '#serverform-0-dc').fill(server.dc);
    await Dropdown.field(this.page, '#serverform-0-type').setValue(server.type);
    await Input.field(this.page, '#serverform-0-order_no').fill(server.order);
    await Input.field(this.page, '#serverform-0-label').fill(server.internalNote);
    await Dropdown.field(this.page, '#serverform-0-state').setValue(server.status);
    await Input.field(this.page, '#serverform-0-hwsummary').fill(server.hardwareSummary);
    await Input.field(this.page, '#serverform-0-hwcomment').fill(server.hardwareComment);
  }

  async updateServer(server) {
    await Dropdown.field(this.page, '#serverform-0-state').setValue(server.status);
    await Input.field(this.page, '#serverform-0-hwcomment').fill(server.hardwareComment);
  }

  async saveServer() {
    await this.page.locator('button.btn-success:has-text("Save")').click();
  }

  async seeSuccessAlert() {
    await Alert.on(this.page).hasText("Server has been created");
  }

  async seeSuccessUpdatedAlert() {
    await Alert.on(this.page).hasText("Server has been updated");
  }

  async seeStateField() {
    await expect(this.page.locator('#serverform-0-state')).toBeVisible();
  }

  async dontSeeStateField() {
    await expect(this.page.locator('#serverform-0-state')).not.toBeVisible();
  }
}
