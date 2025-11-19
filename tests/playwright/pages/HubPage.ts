import { expect, Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";
import { Alert } from "@hipanel-core/shared/ui/components";
import Form from "@hipanel-core/page/Form";
import { Hub } from "@hipanel-module-server/types";
import View from "@hipanel-core/page/View";
import Input from "@hipanel-core/input/Input";
import Dropdown from "@hipanel-core/input/Dropdown";

export default class HubPage {
  readonly index: Index;
  readonly form: Form;
  readonly view: View;

  constructor(readonly page: Page) {
    this.index = new Index(page);
    this.form = new Form(page);
    this.view = new View(page);
  }

  async gotoIndexPage() {
    await this.page.goto("/server/hub/index");
    await expect(this.page).toHaveTitle("Switches");
  }

  async gotoCreate() {
    await this.page.locator("text=Create switch").click();
  }

  async gotoUpdate(name: string) {
    await this.index.columnFilters.applyFilter("name_ilike", name);
    await this.index.clickPopoverMenu(1, "Update");
  }

  async gotoAssignHubs(name: string) {
    await this.index.columnFilters.applyFilter("name_ilike", name);
    await this.index.chooseNumberRowOnTable(1);
    await this.index.clickBulkButton("Assign hubs");
  }

  async gotoView(name: string) {
    await this.index.columnFilters.applyFilter("name_ilike", name);
    await this.index.clickPopoverMenu(1, "View");
  }

  async delete(name: string) {
    await this.index.columnFilters.applyFilter("name_ilike", name);
    await this.index.clickPopoverMenu(1, "View");
    this.page.on("dialog", dialog => dialog.accept());
    await this.view.detailMenu.click("Delete");
    await this.seeAlertMessage("Switches have been deleted");
  }

  async seeAlertMessage(message: string) {
    await Alert.on(this.page).hasText(message);
  }

  async hasColumns() {
    await expect(this.page.getByRole("button", { name: "View: common" })).toBeVisible();
    for (const name of ["Name", "INN", "Model", "Type", "IP", "Server type", "State", "IP", "MAC address", "Order No."]) {
      await expect(this.page.getByRole("cell", { name, exact: true }).first()).toBeVisible();
    }
  }

  async hasFilters() {
    await expect(this.page.getByRole("link", { name: "Name" })).toBeVisible();
    await expect(this.page.getByRole("cell", { name: "INN" })).toBeVisible();
    await expect(this.page.getByRole("cell", { name: "Name" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "Switch" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "INN" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "IP" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "MAC address" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "Model" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "Order No." })).toBeVisible();
    await expect(this.page.locator("#hubsearch-type_id")).toBeVisible();
    await expect(this.page.getByRole("combobox", { name: "Buyer" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "Tariff" })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "Rack", exact: true })).toBeVisible();
    await expect(this.page.getByRole("textbox", { name: "List of racks" })).toBeVisible();
    await expect(this.page.locator("div").filter({ hasText: /^Tags$/ }).nth(1)).toBeVisible();
    await expect(this.page.getByRole("searchbox", { name: "State" })).toBeVisible();
  }

  async hasBulkButtons() {
    await expect(this.page.getByRole("button", { name: "Update" })).toBeVisible();
    await expect(this.page.getByRole("button", { name: "Assign hubs" })).toBeVisible();
    await expect(this.page.getByRole("button", { name: "Bulk actions" })).toBeVisible();

    await this.page.locator("input[name=\"selection_all\"]").check();
    await this.page.getByRole("button", { name: "Bulk actions" }).click();

    await expect(this.page.getByRole("link", { name: "Set Rack No." })).toBeVisible();
    await expect(this.page.getByRole("link", { name: "Set units" })).toBeVisible();
    await expect(this.page.locator("a").filter({ hasText: "Restore" })).toBeVisible();
  }

  async create(hub: Hub) {
    await this.fill(hub);
    await this.form.submit();
    await this.seeAlertMessage("Switch has been created");
    await this.view.see(Object.values(hub));
  }

  async update(hub: Hub) {
    await this.fill(hub);
    await this.form.submit();
    await this.seeAlertMessage("Switch has been updated");
    await this.view.see(Object.values(hub));
  }

  async cannotCreateWithEmptyForm() {
    await this.form.submit();
    await this.form.hasErrors();
  }

  private async fill(hub: Hub) {
    await Input.field(this.page, "#hub-0-name").fill(hub.name);
    await Input.field(this.page, "#hub-0-inn").fill(hub.inn);
    await Dropdown.field(this.page, "#hub-0-type_id").setLabel(hub.type);
    await Input.field(this.page, "#hub-0-model").fill(hub.model);
    await Input.field(this.page, "#hub-0-order_no").fill(hub.order_no);
    await Input.field(this.page, "#hub-0-note").fill(hub.note);
  }
}
