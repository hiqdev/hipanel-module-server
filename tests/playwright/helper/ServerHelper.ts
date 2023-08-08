import { expect, Locator, Page } from "@playwright/test";
import Select2 from "@hipanel-core/input/Select2";
import Index from "@hipanel-core/page/Index";
import Input from "@hipanel-core/input/Input";
import Server from "@hipanel-module-server/model/Server";
import Alert from "@hipanel-core/ui/Alert";

export default class ServerHelper {
    private page: Page;
    private index: Index;

    public constructor(page: Page) {
        this.page = page;
        this.index = new Index(page);
    }

    async gotoIndexServer() {
        await this.page.goto('/server/server/index');
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
}
