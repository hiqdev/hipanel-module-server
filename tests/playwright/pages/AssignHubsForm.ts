import { expect, Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";
import Form from "@hipanel-core/page/Form";
import Input from "@hipanel-core/input/Input";
import { AssignHubs, Server } from "@hipanel-module-server/types";
import { Alert } from "@hipanel-core/shared/ui/components";
import ServerFormPage from "@hipanel-module-server/pages/ServerFormPage";
import Select2 from "@hipanel-core/input/Select2";
import View from "@hipanel-core/page/View";
import { faker } from "@faker-js/faker";

export default class AssignHubsForm extends Form {
  private alert: Alert;
  private view: View;
  private serverForm: ServerFormPage;
  readonly index: Index;

  constructor(readonly page: Page) {
    super(page);
    this.index = new Index(page);
    this.alert = Alert.on(page);
    this.view = new View(page);
    this.serverForm = new ServerFormPage(page);
  }

  fakePort() {
    return faker.string.alpha(10);
  }

  async gotoAssignHubPage(serverName: string) {
    if (this.page.url().includes("view?id=")) {
      await expect(this.page).toHaveTitle(serverName);
      await this.page.locator("text=Assign hubs").first().click();
    } else {
      await this.page.goto("index");
      await this.index.columnFilters.applyFilter("name_ilike", serverName);
      await this.index.clickPopoverMenu(1, "Assign hubs");
    }
  }

  async gotoServerViewPage(serverName: string) {
    if (!this.page.url().includes("view?id=")) {
      await this.page.goto("index");
      await this.index.columnFilters.applyFilter("name_ilike", serverName);
      await this.index.clickPopoverMenu(1, "View");
    }
  }

  async fill(assigns: AssignHubs[]) {
    for (const assign of assigns) {
      let idx = 0;
      for (const [key, value] of Object.entries(assign)) {
        const inputSelector = `#assignhubsform-${idx}-${key}`;
        const isPresent = await this.isVisible(inputSelector);
        if (!isPresent) {
          const addNetButton = ".nets + div > button.assign-hubs-reveal";
          const addPduButton = ".pdus + div > button.assign-hubs-reveal";

          const isNetButtonPresent = await this.isVisible(addNetButton);
          const isPduButtonPresent = await this.isVisible(addPduButton);

          if (inputSelector.includes("net") && isNetButtonPresent) {
            await this.page.locator(addNetButton).click();
          }
          if (inputSelector.includes("pdu") && isPduButtonPresent) {
            await this.page.locator(addPduButton).click();
          }
        }
        if (key.includes("_port")) {
          await Input.field(this.page, inputSelector).setValue(value);
        } else {
          if (value === null) {
            await Select2.field(this.page, inputSelector).clearValue();
          } else {
            await Select2.field(this.page, inputSelector).setValue(value);
          }
        }
      }
      idx++;
    }
  }

  async gotoFirstRowView() {
    await this.index.clickPopoverMenu(1, "View");
  }

  async seeSuccessAlert() {
    await this.alert.hasText("Hubs have been assigned");
  }

  async seeResult(assignHubsFormTestData: AssignHubs) {
    await this.view.see(Object.values(assignHubsFormTestData));
  }

  async createTestServer(): Promise<Server> {
    const testServerData = this.serverForm.getTestData();
    testServerData.type = "dedicated";
    await this.page.goto("/server/server/create");
    await this.serverForm.fill(testServerData);
    await this.serverForm.submit();
    await expect(this.page).toHaveURL(/.*view\?id=\d+/);
    await this.serverForm.seeSuccessCreateAlert();

    return testServerData;
  }

  async clearAssigments(testData: AssignHubs) {
    for (const fieldName of Object.keys(testData)) {
      testData[fieldName] = null;
    }
    this.fill([testData]);
  }

  async save(assignment: AssignHubs) {
    await this.fill([assignment]);
    await this.submit();
    await this.seeSuccessAlert();
    await this.seeResult(assignment);
  }
}
