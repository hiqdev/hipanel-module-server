import { expect, Page } from "@playwright/test";
import { DetailMenu } from "@hipanel-core/shared/ui/components";
import { Server } from "@hipanel-module-server/types";
import View from "@hipanel-core/page/View";

export default class ServerViewPage {
  private detailMenu: DetailMenu;
  view: View;

  constructor(readonly page: Page) {
    this.detailMenu = new DetailMenu(this.page);
    this.view = new View(page);
  }

  async gotoServerView(id: string) {
    await this.page.goto(`/server/server/view?id=${id}`);
  }

  async hasServerDetailMenuButtonsOnViewPage(server: Server) {
    await this.detailMenu.has("Update");

    if (this.isServerActive(server)) {
      await this.detailMenu.has("Assign hubs");
    }

    await this.detailMenu.has("Switch graphs");
    await this.detailMenu.has("Server IPs");
    await this.detailMenu.has("Server Accounts");

    if (this.isServerActive(server)) {
      await this.detailMenu.has("Hardware properties");
      await this.detailMenu.has("Software properties");
      await this.detailMenu.has("Monitoring properties");
      await this.detailMenu.has("Mail settings");
    }

    await this.detailMenu.has("Resources");

    if (this.isServerActive(server)) {
      await this.detailMenu.has("Delete");
    }
  }

  isServerActive(server: Server): boolean {
    return server.status !== "Deleted";
  }

  async checkDetailViewData(server: Server) {
    const firstDetailViewTable = this.page.locator("table.detail-view").first();

    await expect(firstDetailViewTable.locator("tbody tr:nth-child(3) td")).toContainText(server.serverName);
    await expect(firstDetailViewTable.locator("tbody tr:nth-child(4) td")).toContainText(server.type);
    await expect(firstDetailViewTable.locator("tbody tr:nth-child(5) td")).toContainText(server.status);
  }

  async gotoUpdateServerPage() {
    await this.detailMenu.click("Update");
    await expect(this.page).toHaveTitle("Update");
  }
}
