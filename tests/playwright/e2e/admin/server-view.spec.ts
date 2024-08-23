import { test } from "@hipanel-core/fixtures";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";
import ServerView from "@hipanel-module-server/page/ServerView";
import ServerForm from "@hipanel-module-server/page/ServerForm";

test("Correct view Server @hipanel-module-server @admin", async ({ adminPage }) => {
    const serverHelper = new ServerHelper(adminPage);

    await serverHelper.gotoIndexServer();
    await serverHelper.hasMainElementsOnIndexPage();
});

test("Correct view Deleted Server @hipanel-module-server @admin", async ({ adminPage }) => {
    const serverHelper = new ServerHelper(adminPage);
    const serverView = new ServerView(adminPage);
    const serverForm = new ServerForm(adminPage);

    await serverHelper.gotoIndexServer('deleted');

    if (await serverHelper.getNumberOfRows() > 0) {
        const server = await serverHelper.fillServerFromIndexPage(1, 'Deleted');

        await serverHelper.gotoServerPage(1);
        await serverView.checkDetailViewData(server);
        await serverView.hasServerDetailMenuButtonsOnViewPage(server);
        await serverView.gotoUpdateServerPage();
        await serverForm.dontSeeStateField();
    }
});

test("Correct view NonDeleted Server @hipanel-module-server @admin", async ({ adminPage }) => {
    const serverHelper = new ServerHelper(adminPage);
    const serverView = new ServerView(adminPage);
    const serverForm = new ServerForm(adminPage);

    await serverHelper.gotoIndexServer();

    await serverHelper.gotoIndexServer('ok');
    const server = await serverHelper.fillServerFromIndexPage(1, 'Ok');

    await serverHelper.gotoServerPage(1);
    await serverView.checkDetailViewData(server);
    await serverView.hasServerDetailMenuButtonsOnViewPage(server);
    await serverView.gotoUpdateServerPage();
    await serverForm.seeStateField();
});