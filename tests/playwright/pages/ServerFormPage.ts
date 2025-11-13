import { expect } from "@playwright/test";
import Alert from "@hipanel-core/ui/Alert";
import Input from "@hipanel-core/input/Input";
import Dropdown from "@hipanel-core/input/Dropdown";
import { Server } from "@hipanel-module-server/types";
import Form from "@hipanel-core/page/Form";
import { faker } from "@faker-js/faker";

export default class ServerFormPage extends Form {
  public getTestData(): Server {
    const serverName = faker.helpers.fake("BTEST-DS-{{number.int({\"min\": 1000, \"max\": 9999})}}");

    return {
      serverName,
      dc: serverName,
      type: "cdn",
      status: "ok",
      hardwareSummary: "Test summary",
    };
  }

  async fill(server: Server) {
    if (server.serverName) {
      await Input.field(this.page, "#serverform-0-server").fill(server.serverName);
    }
    await Input.field(this.page, "#serverform-0-dc").fill(server.dc);
    await Dropdown.field(this.page, "#serverform-0-type").setValue(server.type);
    await Dropdown.field(this.page, "#serverform-0-state").setValue(server.status);
    await Input.field(this.page, "#serverform-0-hwsummary").fill(server.hardwareSummary);
  }

  async seeSuccessCreateAlert() {
    await Alert.on(this.page).hasText("Server has been created");
  }

  async seeSuccessUpdatedAlert() {
    await Alert.on(this.page).hasText("Server has been updated");
  }

  async seeStateField() {
    await expect(this.page.locator("#serverform-0-state")).toBeVisible();
  }

  async dontSeeStateField() {
    await expect(this.page.locator("#serverform-0-state")).not.toBeVisible();
  }
}
