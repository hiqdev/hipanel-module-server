import { expect, Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";
import Input from "@hipanel-core/input/Input";
import Alert from "@hipanel-core/ui/Alert";
import Server from "@hipanel-module-server/model/Server";

export default class ServerHelper {
    private page: Page;
    private index: Index;

    public constructor(page: Page) {
        this.page = page;
        this.index = new Index(page);
    }

    async gotoIndexServer(status?: string) {
        if (status) {
            await this.page.goto(`/server/server/index?ServerSearch[state]=${status}`);
        } else {
            await this.page.goto('/server/server/index');
        }
        await expect(this.page).toHaveTitle("Servers");
    }

    async gotoCreateServer() {
        await this.page.locator('text=Create server').click();
    }

    async gotoUpdateServer(serverName: string) {
        await Input.filterBy(this.page, 'Name').setValue(serverName);
        await this.index.clickPopoverMenu(1, 'Update');
    }

    async deleteServer(serverName: string) {
        await Input.filterBy(this.page, 'Name').setValue(serverName);
        await this.index.chooseNumberRowOnTable(1);
        await this.index.clickDropdownBulkButton('Basic actions', 'Delete');
        await this.index.clickButton('Delete');
    }

    async gotoAssignHubPage(serverName: string) {
        await Input.filterBy(this.page, 'Name').setValue(serverName);
        await this.index.clickPopoverMenu(1, 'Assign hubs');
    }

    async seeAlertMessage(message: string) {
        await Alert.on(this.page).hasText(message);
    }

    async gotoServerPage(rowNumber: number) {
        await this.index.clickColumnOnTable('Name', rowNumber);
    }

    async hasMainElementsOnIndexPage() {
        const indexPage = new Index(this.page);
        await indexPage.hasAdvancedSearchInputs([
            `ServerSearch[name_dc]`,
            `ServerSearch[name_ilike]`,
            `ServerSearch[note_like]`,
            `ServerSearch[label_like]`,
            `ServerSearch[dc_like]`,
            `ServerSearch[ip_like]`,
            `ServerSearch[client_id]`,
            `ServerSearch[seller_id]`,
            `ServerSearch[hwsummary_like]`,
            `ServerSearch[type][]`,
            `ServerSearch[state][]`,
            `ServerSearch[net_ilike]`,
            `ServerSearch[kvm_ilike]`,
            `ServerSearch[pdu_ilike]`,
            `ServerSearch[rack_ilike]`,
            `ServerSearch[rack_inilike]`,
            `ServerSearch[mac_ilike]`,
        ]);

        // Switch to some spevific (common/admin) view
        //await indexPage.hasColumns(["Name", "Client", "Reseller", "IPs", "Tariff", "Hardware Summary"]);
    }

    async getNumberOfRows() {
        return this.page.locator("input[name=\"selection[]\"]").count();
    }

    async fillServerFromIndexPage(numberRow: number, status: string) {
        const index = new Index(this.page);
        const server = new Server();

        server.serverName = await this.getServerNameFromIndexPage(numberRow);
        server.hardwareSummary = await index.getValueInColumnByNumberRow('Hardware Summary', numberRow);
        server.type = await index.getValueInColumnByNumberRow('Type', numberRow);
        server.status = status;

        return server;
    }

    async getServerNameFromIndexPage(numberRow: number) {
        const index = new Index(this.page);
        const column = await index.getColumnNumberByName('Name');
        let value = await this.page.locator(`//section[@class='content container-fluid']//tbody//tr[${numberRow}]//td[${column}]//a`).first().innerText();

        return value.trim();
    }
}
