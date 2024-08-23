import {expect, Locator, Page} from "@playwright/test";
import DetailMenu from "@hipanel-core/helper/DetailMenu";
import Server from "@hipanel-module-server/model/Server";

export default class ServerView {
    private page: Page;
    private detailMenu: DetailMenu;

    public constructor(page: Page) {
        this.page = page;
        this.detailMenu = new DetailMenu(this.page);
    }

    async gotoViewServer(id: string) {
        await this.page.goto(`/server/server/view?id=${id}`);
    }

    detailMenuItem(item: string): Locator {
        return this.detailMenu.detailMenuItem(item);
    }

    async hasServerDetailMenuButtonsOnViewPage(server: Server) {
        await this.detailMenu.hasDetailMenuItem('Update');

        if (this.isServerActive(server)) {
            await this.detailMenu.hasDetailMenuItem('Assign hubs');
        }

        await this.detailMenu.hasDetailMenuItem('Switch graphs');
        await this.detailMenu.hasDetailMenuItem('Server IPs');
        await this.detailMenu.hasDetailMenuItem('Server Accounts');

        if (this.isServerActive(server)) {
            await this.detailMenu.hasDetailMenuItem('Hardware properties');
            await this.detailMenu.hasDetailMenuItem('Software properties');
            await this.detailMenu.hasDetailMenuItem('Monitoring properties');
            await this.detailMenu.hasDetailMenuItem('Mail settings');
        }

        await this.detailMenu.hasDetailMenuItem('Resources');

        if (this.isServerActive(server)) {
            //await detailMenu.hasDetailMenuItem('Enable block');
            await this.detailMenu.hasDetailMenuItem('Delete');
        }
    }

    isServerActive(server: Server): boolean {
        return server.status !== 'Deleted';
    }

    async checkDetailViewData(server: Server) {
        const firstDetailViewTable = this.page.locator('table.detail-view').first();

        await expect(firstDetailViewTable.locator('tbody tr:nth-child(3) td')).toContainText(server.serverName);
        await expect(firstDetailViewTable.locator('tbody tr:nth-child(4) td')).toContainText(server.type);
        await expect(firstDetailViewTable.locator('tbody tr:nth-child(5) td')).toContainText(server.status);
    }

    async gotoUpdateServerPage() {
        await this.detailMenu.clickDetailMenuItem('Update');
        await expect(this.page).toHaveTitle("Update");
    }
}