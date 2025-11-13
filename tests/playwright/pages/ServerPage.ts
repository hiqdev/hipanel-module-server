import { expect, Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";
import Alert from "@hipanel-core/ui/Alert";

export default class ServerPage {
  private page: Page;
  public index: Index;

  public constructor(page: Page) {
    this.page = page;
    this.index = new Index(page);
  }

  public async gotoIndex() {
    await this.page.goto("/server/server/index");
    await expect(this.page).toHaveTitle("Servers");
  }

  public async gotoIndexShowDeleted() {
    await this.page.goto(`/server/server/index?ServerSearch[show_deleted]=1`);
  }

  public async gotoServerCreate() {
    await this.page.goto("/server/server/create");
    await expect(this.page).toHaveTitle("Create");
  }

  public async gotoServerUpdate(serverName: string) {
    await this.findByName(serverName);
    await this.index.clickPopoverMenu(1, "Update");
  }

  public async deleteServer(serverName: string) {
    await this.findByName(serverName);
    await this.index.chooseNumberRowOnTable(1);
    await this.index.clickDropdownBulkButton("Basic actions", "Delete");
    await this.index.clickButton("Delete");
  }

  public async seeAlertMessage(message: string) {
    await Alert.on(this.page).hasText(message);
  }

  public async gotoServerPage(rowNumber: number) {
    await this.index.clickColumnOnTable("Name", rowNumber);
  }

  public async hasMainElementsOnIndexPage() {
    await this.index.hasAdvancedSearchInputs([
      "ServerSearch[name_dc]",
      "ServerSearch[name_ilike]",
      "ServerSearch[note_like]",
      "ServerSearch[label_like]",
      "ServerSearch[dc_like]",
      "ServerSearch[ip_like]",
      "ServerSearch[client_id]",
      "ServerSearch[seller_id]",
      "ServerSearch[hwsummary_like]",
      "ServerSearch[type][]",
      "ServerSearch[state][]",
      "ServerSearch[net_ilike]",
      "ServerSearch[kvm_ilike]",
      "ServerSearch[pdu_ilike]",
      "ServerSearch[rack_ilike]",
      "ServerSearch[rack_inilike]",
      "ServerSearch[mac_ilike]",
    ]);

    // Switch to some spevific (common/admin) view
    //await indexPage.hasColumns(["Name", "Client", "Reseller", "IPs", "Tariff", "Hardware Summary"]);
  }

  public async getNumberOfRows() {
    return await this.page.locator("input[name=\"selection[]\"]").count();
  }

  private async getServerNameFromIndexPage(numberRow: number) {
    const column = await this.index.getColumnNumberByName("Name");
    const value = await this.page.locator(`//section[@class='content container-fluid']//tbody//tr[${numberRow}]//td[${column}]//a`).first().innerText();

    return value.trim();
  }

  private async findByName(serverName: string) {
    await this.index.columnFilters.applyFilter("name_ilike", serverName);
  }
}
