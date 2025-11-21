import { expect, test } from "@hipanel-core/fixtures";
import AssignHubsForm from "@hipanel-module-server/pages/AssignHubsForm";
import { AssignHubs, Server } from "@hipanel-module-server/types";

test("assign hubs @hipanel-module-server @admin", async ({ page }) => {
  await page.goto("/server/server/index");
  const assignForm = new AssignHubsForm(page);
  const defaultTestData: AssignHubs = {
    net_id: "TEST-SW-05",
    net_port: assignForm.fakePort(),
    pdu_id: "TEST-SW-06",
    pdu_port: assignForm.fakePort(),
    ipmi_id: "TEST-SW-05",
    ipmi_port: assignForm.fakePort(),
    kvm_id: "TEST-SW-04",
    kvm_port: assignForm.fakePort(),
    rack_id: "TEST-SW-02",
    rack_port: assignForm.fakePort(),
  };

  const filterPortsOnly = (data: AssignHubs): Partial<AssignHubs> => Object.fromEntries(
    Object.entries(data).filter(([key]) => key.endsWith("_port")),
  ) as Partial<AssignHubs>;

  const testServer: Server = await test.step(
    "create test server @admin",
    async (): Promise<Server> => await assignForm.createTestServer(),
  );

  await test.step("assign hubs", async () => {
    await assignForm.gotoAssignHubPage(testServer.serverName);
    await assignForm.save(defaultTestData);
  });

  await test.step("assign second interface", async () => {
    await assignForm.gotoAssignHubPage(testServer.serverName);
    const testData = {
      net2_id: "TEST-SW-05",
      net2_port: assignForm.fakePort(),
      pdu2_id: "TEST-SW-06",
      pdu2_port: assignForm.fakePort(),
    };
    await assignForm.save(testData);
  });

  await test.step("update assigned hubs", async () => {
    await assignForm.gotoAssignHubPage(testServer.serverName);
    defaultTestData.net_port = assignForm.fakePort();
    defaultTestData.pdu2_port = assignForm.fakePort();
    defaultTestData.ipmi_port = assignForm.fakePort();
    defaultTestData.kvm_port = assignForm.fakePort();
    defaultTestData.rack_port = assignForm.fakePort();
    await assignForm.save(filterPortsOnly(defaultTestData));
  });

  await test.step("cancel action after making changes with assigned hubs", async () => {
    await assignForm.gotoServerViewPage(testServer.serverName);
    const ipmi = `${defaultTestData.ipmi_id}:${defaultTestData.ipmi_port}`;
    await expect(page.getByRole("link", { name: ipmi })).toBeVisible();
    await assignForm.gotoAssignHubPage(testServer.serverName);
    await assignForm.fill([{ ipmi_port: assignForm.fakePort() }]);
    await assignForm.form.cancel();
    await expect(page.getByRole("link", { name: ipmi })).toBeVisible();
  });

  await test.step("unlink IPMI", async () => {
    const ipmiCell = `${defaultTestData.ipmi_id}:${defaultTestData.ipmi_port}`;
    await assignForm.gotoAssignHubPage(testServer.serverName);
    await assignForm.clearAssigments({
      ipmi_id: null,
      ipmi_port: null,
    });
    await assignForm.form.submit();
    await assignForm.seeSuccessAlert();
    await expect(page.getByText(ipmiCell)).not.toBeVisible();
  });

  await test.step("the requirement that a new nic row is saved if either a Switch or an APC is provided", async () => {
    const server: Server = await test.step(
      "create test server @admin",
      async (): Promise<Server> => await assignForm.createTestServer(),
    );
    await assignForm.gotoAssignHubPage(server.serverName);
    const testAssignment: AssignHubs = {
      net2_id: "TEST-SW-05",
      net2_port: assignForm.fakePort(),
      pdu2_id: "TEST-SW-06",
      pdu2_port: assignForm.fakePort(),
    };
    await assignForm.save(testAssignment);
    await expect(page.getByRole("cell", { name: `Switch:2 ${server.serverName}nic2` })).toBeVisible();
    await expect(page.getByRole("cell", { name: `APC:2 ${server.serverName}nic2` })).toBeVisible();
  });

  await test.step("the UI and backend handle multiple nic additions in a single operation", async () => {
    const server: Server = await test.step(
      "create test server @admin",
      async (): Promise<Server> => await assignForm.createTestServer(),
    );
    await assignForm.gotoAssignHubPage(server.serverName);
    const testData: AssignHubs = {
      pdu_id: "TEST-SW-06",
      pdu_port: assignForm.fakePort(),
      pdu2_id: "TEST-SW-06",
      pdu2_port: assignForm.fakePort(),
      pdu3_id: "TEST-SW-06",
      pdu3_port: assignForm.fakePort(),
      pdu4_id: "TEST-SW-06",
      pdu4_port: assignForm.fakePort(),
    };
    await assignForm.save(testData);
  });
});
