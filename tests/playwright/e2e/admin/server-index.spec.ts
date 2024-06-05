import { test } from "@hipanel-core/fixtures";
import ServerHelper from "@hipanel-module-server/helper/ServerHelper";

test("Correct view Service @hipanel-module-server @admin", async ({ adminPage }) => {
    const serverHelper = new ServerHelper(adminPage);
    await serverHelper.gotoIndexServer();

    await serverHelper.hasMainElementsOnIndexPage();
});
