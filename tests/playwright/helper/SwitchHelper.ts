import { expect, Locator, Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";
import Input from "@hipanel-core/input/Input";
import Alert from "@hipanel-core/ui/Alert";

export default class SwitchHelper {
    private page: Page;
    private index: Index;

    public constructor(page: Page) {
        this.page = page;
        this.index = new Index(page);
    }

    async gotoIndexPage() {
        await this.page.goto('/server/hub/index');
        await expect(this.page).toHaveTitle("Switches");
    }

    async gotoCreateSwitch() {
        await this.page.locator('text=Create switch').click();
    }

    async gotoUpdateSwitch(switchName: string) {
        await Input.filterBy(this.page, 'Name').setValue(switchName);
        await this.index.clickPopoverMenu(1, 'Update');
    }

    async gotoAssignSwitchPage(switchName: string) {
        await Input.filterBy(this.page, 'Name').setValue(switchName);
        await this.index.chooseNumberRowOnTable(1);
        await this.index.clickBulkButton('Assign hubs');
    }

    async deleteSwitch(serverName: string) {
        await Input.filterBy(this.page, 'Name').setValue(serverName);
        await this.index.clickPopoverMenu(1, 'View');
        this.page.on('dialog', dialog => dialog.accept());
        await this.index.clickProfileMenuOnViewPage('Delete');
    }

    async seeAlertMessage(message: string) {
        await Alert.on(this.page).hasText(message);
    }
}
