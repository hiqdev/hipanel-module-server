# hiqdev/hipanel-module-server

## [Under development]

    - [4398013] 2017-06-20 renamed `web` config <- hisite [@hiqsol]
    - [d1a8bd7] 2017-06-20 renamed `hidev.yml` [@hiqsol]
    - [dda7e09] 2017-06-09 Merge pull request #11 from bladeroot/server-move-simple-operation [@hiqsol]
    - [66ca62e] 2017-06-09 move SimpleOperation to core, fix translation [@BladeRoot]
    - [abe986e] 2017-06-02 Added getBinding() to Server [@tafid]
    - [7ffa57f] 2017-06-02 Hub minor [@tafid]
    - [cd1decb] 2017-06-02 Added new attribute labels and add Hub::getBinding() [@tafid]
    - [345b11a] 2017-06-02 Hub translations [@tafid]
    - [3568258] 2017-06-02 Added new attributes to HubGridView [@tafid]
    - [dc047de] 2017-06-01 Removed BindingQuery and Binding::find() [@tafid]
    - [fb00123] 2017-06-01 Added new search attribute `with_servers` to HubSearch [@tafid]
    - [b42ccd6] 2017-06-01 Added new attributes to Hub model `traf_server_id_label`, `vlan_server_id_label` [@tafid]
    - [05f01da] 2017-06-01 Added new items to HubGridView [@tafid]
    - [35a8ba9] 2017-06-01 Added `with_servers` to HubController view [@tafid]
    - [79eefd4] 2017-06-01 Merge pull request #10 from bladeroot/server-isolated-view-bulk-operation [@hiqsol]
    - [b093177] 2017-06-01 rename to camelCase [@BladeRoot]
    - [462711c] 2017-06-01 Changed markup in hub options view [@tafid]
    - [160a71c] 2017-06-01 remove scenario [@BladeRoot]
    - [106cb3b] 2017-06-01 move css classes to view, isolate all operations in same vies [@BladeRoot]
    - [56b0150] 2017-05-31 Added detailView for bindings to Hub view [@tafid]
    - [0420705] 2017-05-31 Changed markup template for Hub create/update form [@tafid]
    - [145b7a0] 2017-05-31 Added MAC address validator [@tafid]
    - [8734a27] 2017-05-31 Added new search attribute `with_bindings` [@tafid]
    - [0bc4ee1] 2017-05-31 Added method Hub::getBindings() [@tafid]
    - [3788066] 2017-05-31 Fixed type from `switches` to `bindings` [@tafid]
    - [8bb51e1] 2017-05-31 Added on beforePerform for Hub view and added `with_bindings` [@tafid]
    - [5071d1e] 2017-05-31 Added validation IP and MAC to Hub model [@tafid]
    - [9e9418e] 2017-05-30 Changed `type` column `findOptions` for HubGridView [@tafid]
    - [245ea19] 2017-05-30 Added ServerCombo to optison form in Hub [@tafid]
    - [e70bc4d] 2017-05-30 Added Hub attributes to HubGridView::detailView [@tafid]
    - [7e9d860] 2017-05-30 Added `id` hidden attribute to options form in Hub [@tafid]
    - [b9548a8] 2017-05-30 Added scenarios as const [@tafid]
    - [88ee0d6] 2017-05-30 Merge pull request #8 from bladeroot/bulk-refuse [@hiqsol]
    - [a22f258] 2017-05-30 approve/reject moved to bulk op [@BladeRoot]
    - [8cc28b9] 2017-05-30 remove one tab [@BladeRoot]
    - [ef753c8] 2017-05-26 Added new scenario `options` to Hub model [@tafid]
    - [4bfde8e] 2017-05-26 Added HubController::getFullFromRef() method [@tafid]
    - [668ffe7] 2017-05-26 Added new widget for details display [@tafid]
    - [2821750] 2017-05-26 Added fields for options form in Hub [@tafid]
    - [c446584] 2017-05-26 Added `view` item for Hub menus [@tafid]
    - [673d7e2] 2017-05-26 Added additional data for options view in HubController [@tafid]
    - [e270334] 2017-05-25 Added title, breadcrumbs and render sub view to Hub options view [@tafid]
    - [e81f438] 2017-05-25 Passed array of types to Hub search form [@tafid]
    - [9609dd4] 2017-05-25 Added sesarch fields to Hub search [@tafid]
    - [efd2d90] 2017-05-25 Added fields to options form for Hub [@tafid]
    - [eecd299] 2017-05-25 Fixed markup for hub `_form` subview [@tafid]
    - [073c72f] 2017-05-25 Added new attributes and labels to Hub model [@tafid]
    - [1bed480] 2017-05-25 Fixed url for options action in HubActionMenu [@tafid]
    - [43c6cb8] 2017-05-25 Added `text-align` to model attribute in HubGridView [@tafid]
    - [4bef7b9] 2017-05-25 Added `options` action to HubController, fixed messages [@tafid]
    - [029bba2] 2017-05-24 Created main views for Hub [@tafid]
    - [58328b7] 2017-05-24 Added actions and detail menu for Hub [@tafid]
    - [4ba32f1] 2017-05-24 Added create button and update bulk button to Hub index page [@tafid]
    - [ae5f42a] 2017-05-24 Added requried field for create and update scenarios in Hub model [@tafid]
    - [54278bc] 2017-05-24 Minor changes in HubGridView [@tafid]
    - [4a425e0] 2017-05-24 Added create and update action to HubController [@tafid]
    - [8b07332] 2017-05-24 Added @hub alias [@tafid]
    - [9354ae5] 2017-05-23 Added scafolding for Hub [@tafid]
    - [2f9dbe8] 2017-05-23 Added new translation file and added it to config [@tafid]
    - [6635161] 2017-05-23 Added new item `Switches` to SidebarMenu [@tafid]
    - [ff6fbff] 2017-05-22 Merge pull request #7 from bladeroot/server-boot-live [@hiqsol]
    - [5ed4ac8] 2017-05-22 fix path to boot live widget [@BladeRoot]
    - [2f0f989] 2017-05-19 Changed `with_switches` to `with_bindings` search query param in ServerController [@tafid]
    - [1f22853] 2017-05-19 Prevented useless api search query in Binding [@tafid]
    - [490a3e4] 2017-05-19 Showed switches data from Bindings relation [@tafid]
    - [8a0e16b] 2017-05-19 Added Binding relation [@tafid]
    - [8cf182b] 2017-05-19 Added rules to Binding model [@tafid]
    - [023a965] 2017-05-19 Added Binding with [@tafid]
    - [0e45a97] 2017-05-19 Merge pull request #6 from bladeroot/server-bulk-operation [@hiqsol]
    - [97b7666] 2017-05-18 Fixed merage [@tafid]
    - [15f242e] 2017-05-18 Added Binding model [@tafid]
    - [00b091a] 2017-05-18 Added block Switches to server view file [@tafid]
    - [25389aa] 2017-05-18 translations [@tafid]
    - [dc786a9] 2017-05-18 bulk operation widget moved to core [@BladeRoot]
    - [f0a9da8] 2017-05-17 Merge pull request #5 from bladeroot/server-vnc [@hiqsol]
    - [722563b] 2017-05-17 JsExpression [@BladeRoot]
    - [9fa3b6f] 2017-05-17 cache VNC data [@BladeRoot]
    - [5049323] 2017-05-11 Merge pull request #3 from bladeroot/vnc-only-for-svds [@hiqsol]
    - [9e34ec4] 2017-05-11 Merge pull request #4 from bladeroot/fix-server-column [@hiqsol]
    - [242271d] 2017-05-11 fix id attribute [@BladeRoot]
    - [da14561] 2017-05-11 boot live to widget [@BladeRoot]
    - [ea6f088] 2017-05-10 Event Log to core widget [@BladeRoot]
    - [3f56ccd] 2017-05-10 remove empty line [@BladeRoot]
    - [3d23c1f] 2017-05-10 type require in disable block [@BladeRoot]
    - [8af0acc] 2017-05-10 move bulk opertion to widget [@BladeRoot]
    - [7f1c57f] 2017-05-10 move bulk opertion to widget [@BladeRoot]
    - [85f6d62] 2017-05-10 prettify [@BladeRoot]
    - [bb48ce2] 2017-05-10 change run [@BladeRoot]
    - [148e317] 2017-05-10 prettyfy code [@BladeRoot]
    - [0913690] 2017-05-10 rename options [@BladeRoot]
    - [a9adb19] 2017-05-10 move reset password to simple operation widget [@BladeRoot]
    - [3ec78f4] 2017-05-10 move reset password to simple operation widget [@BladeRoot]
    - [8fc1e83] 2017-05-10 multiple joins in one function [@BladeRoot]
    - [cbc4553] 2017-05-10 VIRTUAL_DEVICE => VIRTUAL_DEVICES [@BladeRoot]
    - [eb8cb03] 2017-05-05 + TrafficConsumption widget [@BladeRoot]
    - [bfbac33] 2017-05-05 syntax fixes [@BladeRoot]
    - [6c44ec4] 2017-05-05 + icons to actions [@BladeRoot]
    - [cb9da57] 2017-05-05 move BLOCK to Block widget [@BladeRoot]
    - [365d36d] 2017-05-05 move delete to simple widget [@BladeRoot]
    - [cd7e8b9] 2017-05-05 Fixed link to IP address on server view page [@SilverFire]
    - [42d7b38] 2017-05-05 switches init; wizzarded [@BladeRoot]
    - [cbe5b53] 2017-05-05 move operation to SimpleOperation widget [@BladeRoot]
    - [3f4bdd5] 2017-05-05 SimpleOperation init [@BladeRoot]
    - [6c1d309] 2017-05-05 Wizzard init [@BladeRoot]
    - [e55978e] 2017-05-04 vnc support only for sVPS [@BladeRoot]
    - [f70e897] 2017-04-26 Added search by `client_like` to Servers [@tafid]
    - [4bc8e5f] 2017-04-12 Fixed save representation view [@tafid]
    - [f38efdd] 2017-04-12 Removed set-orientation action [@tafid]
    - [5fa9a07] 2017-03-25 phpcsfixed [@SilverFire]
    - [8e0636d] 2017-03-24 Added PHPUnit 6 support; [@SilverFire]
    - [9fe2bd7] 2017-03-15 Merge pull request #2 from bladeroot/server-fix-boot-live [@SilverFire]
    - [cea9ff2] 2017-03-14 fix boot live [@BladeRoot]
    - [e927e32] 2017-02-21 Added new translations [@tafid]
    - [2c90ef2] 2017-02-19 PHPdocs enhanced [@SilverFire]
    - [55c012d] 2017-02-20 Fixed SwitchGraph model to follow HiArt API changes [@SilverFire]
    - [756bc4f] 2017-02-20 removed use of `hipanel\grid\DataColumn` in favour of `hiqdev\higrid\DataColumn` [@hiqsol]
    - [79950a5] 2017-02-17 Fixed not correct layout for OSForrmatter::generateOSInfo, changed icon for OSFormatter::generateInfoCircle, change class `row` to `table-responsive` [@tafid]
    - [403b082] 2017-02-16 Updated actions in ServerController to use SmartPerformAction [@SilverFire]
    - [65ede2e] 2017-02-15 Translations updated [@SilverFire]
    - [c62900d] 2017-02-15 Fixed ServerController::actionDrawChart to follow HiArt API changes [@SilverFire]
    - [3ac2082] 2017-02-15 ServerController - updated search action to use ComboSearchAction [@SilverFire]
    - [c0bbe7c] 2017-02-14 Updated ServerHelper, ServerController to use cache->getOrSet instead of getTimeCached [@SilverFire]
    - [6a07df5] 2017-02-14 Updated order/index view to follow Package API changes [@SilverFire]
    - [93a1c5c] 2017-01-31 redone scenarioActions <- scenarioCommands [@hiqsol]
    - [baf937a] 2017-01-30 renamed hiqdev\\hiart\\ResponseErrorException <- ErrorResponseException [@hiqsol]
    - [f933991] 2017-01-27 renamed from -> `tableName` in ActiveRecord [@hiqsol]
    - [d593541] 2017-01-27 Fixed ServerGridView to display tariff details link [@SilverFire]
    - [3d28fc9] 2017-01-27 changed index/type -> `from` in ActiveRecord [@hiqsol]
    - [2308604] 2017-01-24 fixes after redoing hiart [@hiqsol]
    - [1974f5d] 2017-01-23 Added pre-order alias [@SilverFire]
    - [6ea4392] 2016-12-22 redone yii2-thememanager -> yii2-menus [@hiqsol]
    - [d902aa9] 2016-12-21 redone Menus: widget instead of create+render [@hiqsol]
    - [7c1f431] 2016-12-21 moved menus definitions to DI [@hiqsol]
    - [007929f] 2016-11-29 Added new menu [@tafid]
    - [f688347] 2016-11-29 fixed translations category for hipanel:synt and hipanel:block-reasons [@hiqsol]
    - [5559ca3] 2016-11-29 translation [@hiqsol]
    - [cb169f7] 2016-11-15 Changed translation dictionaries [@tafid]
    - [b9b622b] 2016-11-15 Fixed typo, changed translations dic [@tafid]
    - [394a8ea] 2016-11-15 Changed i18n config [@tafid]
    - [5027d51] 2016-11-15 Added translation config [@tafid]
    - [949bfeb] 2016-11-15 Enhanced vds_has_tasks error handling for ServerController [@SilverFire]
    - [9a47dd0] 2016-11-11 Fixed ServerRenewCalculation inheritance [@SilverFire]
    - [2cac3a5] 2016-11-10 Show VNC management block even when server executes some operation (e.g. reboot, reset) [@SilverFire]
    - [ba9a129] 2016-11-03 Hid management filters from clients [@SilverFire]
    - [9e4509e] 2016-11-03 Hid server block/unblock, delete buttons for users that can not manage [@SilverFire]
    - [00aa6de] 2016-11-02 added with_tariffs in server index [@hiqsol]
    - [2c7d408] 2016-10-31 fixed permissions [@hiqsol]
    - [27bf392] 2016-10-26 Fixed server order blocks height [@tafid]
    - [cb89f9e] 2016-10-26 translations [@tafid]
    - [0a187b4] 2016-10-20 Implemented caching for server tariffs [@SilverFire]
    - [66b7bc4] 2016-10-04 Changed params->seller to params->user.seller [@SilverFire]
    - [3849c56] 2016-09-30 Minor, translation updates [@SilverFire]
    - [02dd081] 2016-09-30 ServerHelper updated to use Calculator [@SilverFire]
    - [ed48ab1] 2016-09-26 Changed getLocations method call to LoactionResourceDecorator call [@tafid]
    - [2714ab0] 2016-09-26 Added translations [@tafid]
    - [6571eaf] 2016-09-22 removed unused hidev config [@hiqsol]
    - [b6f1f3f] 2016-09-22 redone menu to new style [@hiqsol]
    - [959b561] 2016-09-21 Translation updated [@SilverFire]
    - [591d052] 2016-09-21 Fixed ChartJs initialization after API BC-breaking chages [@SilverFire]
    - [24c1746] 2016-09-20 Deleted OrderCalculation class as redundant, updated orver/order view to follow API changes [@SilverFire]
    - [b7a3eab] 2016-09-13 Fixed OrderController, ServerHelper, Package to follow finance models API changes [@SilverFire]
    - [5441adf] 2016-09-13 Dropped cart/Tariff, cart/TariffPageCalculator - replaced in finance module [@SilverFire]
    - [7395420] 2016-09-08 Updated Package to use resources decorators, updated related views [@SilverFire]
    - [e2dfc5c] 2016-09-08 Updated translations [@SilverFire]
    - [95954cf] 2016-09-02 Moving from Package god-object to decorators in finance module [@SilverFire]
    - [81b2791] 2016-08-31 Package - added chassis [@SilverFire]
    - [b862dd3] 2016-08-24 redone subtitle to original Yii style [@hiqsol]
    - [0bee983] 2016-08-23 redone breadcrumbs to original Yii style [@hiqsol]
    - [e7e2814] 2016-08-22 Check if null and replace to 0 getResourceValue_cpu in Package.php [@tafid]
    - [e93ba24] 2016-07-21 Removed Client and Seller filters from the AdvancedSearch view for non-support [@SilverFire]
    - [78033eb] 2016-07-13 Added server operability state checker on server view page [@SilverFire]
    - [9114d2c] 2016-07-12 Fixed server resetup on OS without panel [@SilverFire]
    - [874b510] 2016-07-05 Added PanelServerCombo::state for convenience [@SilverFire]
    - [b90ae2b] 2016-07-04 Updated State widget to display servers in `disabled` state as regular [@SilverFire]
    - [3026ced] 2016-07-10 csfixed [@hiqsol]
    - [b7ce290] 2016-06-29 fixed minor issues [@hiqsol]
    - [182576b] 2016-06-29 + hwsummary server filter [@hiqsol]
    - [128e2e3] 2016-06-29 improved servers index [@hiqsol]
    - [2e34a1e] 2016-06-27 fixed IP block at server view page [@hiqsol]
    - [b9e2f31] 2016-06-27 simplified attribute labels [@hiqsol]
    - [f70da47] 2016-06-24 added admin representation, switches displaying and filtering [@hiqsol]
    - [142faa7] 2016-06-16 Changed Ref::getList to $this->getRefs in controllers, changed Ref::getList calling signature fo follow mmethod changes, other minors [@SilverFire]
    - [f402c75] 2016-06-16 Changed i18n server dictionary config: added forceTranslation [@SilverFire]
    - [ef7d13c] 2016-06-16 Updated translations [@SilverFire]
    - [35fad20] 2016-06-16 Replace ActionBox to IndexPage widget in switch-graph [@tafid]
    - [51ae53e] 2016-06-16 Replace ActionBox to IndexPage widget [@tafid]
    - [5ede957] 2016-06-16 allowed build failure for php 5.5 [@hiqsol]
    - [d5d1c83] 2016-06-15 removed use of 2amigos DateTimePicker [@hiqsol]
    - [8b2cd43] 2016-06-13 Updated translations [@SilverFire]
    - [fbb2e2f] 2016-06-10 representations moved to grid [@hiqsol]
    - [690abaf] 2016-06-08 merged [@hiqsol]
    - [536db32] 2016-06-08 + rack showing and filtering [@hiqsol]
    - [af4be85] 2016-06-08 Implemented server refusing, enhanced server pre-order moderation [@SilverFire]
    - [abed572] 2016-06-08 Updated SidebarMenu [@SilverFire]
    - [567629b] 2016-06-08 Updated translations [@SilverFire]
    - [8849f1a] 2016-06-07 doing server representations [@hiqsol]
    - [79ae680] 2016-06-07 Implemented servers pre-order verification [@SilverFire]
    - [58aba06] 2016-06-07 Updated SidebarMenu - added pre-order item [@SilverFire]
    - [9f6faef] 2016-06-07 Updated translations [@SilverFire]
    - [315b4bb] 2016-06-05 lang [@hiqsol]
    - [eea8580] 2016-06-04 used RefCombo, no need for getting types and states in index action [@hiqsol]
    - [57a2396] 2016-06-03 Implemented servers pre-order page with approve button. To be continued [@SilverFire]
    - [e11d3b2] 2016-06-03 Updated translations [@SilverFire]
    - [ea6653b] 2016-06-03 used RefCombo in filter [@hiqsol]
    - [390e01b] 2016-06-03 moved Change tariff button to financial block [@hiqsol]
    - [1d671f5] 2016-06-02 Added changes support to server modules [@SilverFire]
    - [72b2e16] 2016-06-02 used dosamigos date time picker [@hiqsol]
    - [928df81] 2016-06-02 adding sale (change tariff) operation [@hiqsol]
    - [45671db] 2016-05-31 Change index layout [@tafid]
    - [08dfd6f] 2016-05-31 Add Orientation layout [@tafid]
    - [5ac185b] 2016-05-27 Add ServerAsset, publish xenssd and openvz images [@tafid]
    - [0c00068] 2016-05-26 Add images to assets folder [@tafid]
    - [50ac7a6] 2016-05-26 Updated translations [@SilverFire]
    - [b138ec6] 2016-05-25 ServerOrderPurchase - fixed passing object to PendingPurchaseException [@SilverFire]
    - [0cb54d6] 2016-05-25 Updated PHPDocs [@SilverFire]
    - [3ef01ab] 2016-05-25 Updated translations [@SilverFire]
    - [d748993] 2016-05-25 Updated hisite config to handle some translations with `forceTranslation` property [@SilverFire]
    - [0aa79b3] 2016-05-25 Updated translations [@SilverFire]
    - [5819399] 2016-05-25 Add popover to table [@tafid]
    - [f2c9e6f] 2016-05-25 Add designe to server/order [@tafid]
    - [62d54f7] 2016-05-24 Updated ServerOrderProduct::getPurchaseModel() not to pass `panel` to PurchaseModel, when OsImage is wothout panel [@SilverFire]
    - [1711498] 2016-05-24 ServerOrderPurchase - added method ::execute() override to handle pending server purchases [@SilverFire]
    - [f30cf29] 2016-05-24 Transslations updated [@SilverFire]
    - [63673b1] 2016-05-24 PHPDoc updated [@SilverFire]
    - [e500a08] 2016-05-24 Fixed OsSelection.js to prevent JS exception when accessing unexisting soft pack in array [@SilverFire]
    - [d64e0b4] 2016-05-23 Implementing server ordering [@SilverFire]
    - [5724eb1] 2016-05-20 Server::getIps - Fixed join condition [@SilverFire]
    - [effd5a3] 2016-05-19 fixed broken namespace [@hiqsol]
    - [0d68293] 2016-05-18 redone to composer-config-plugin [@hiqsol]
    - [dee7fca] 2016-05-14 PHPdoc updated [@SilverFire]
    - [117ffbd] 2016-05-11 Updated composer.json - changed url to asset-packagist.org [@SilverFire]
    - [8239571] 2016-05-08 Updated PHPDoc [@SilverFire]
    - [fac6601] 2016-05-06 Updated PHPDoc [@SilverFire]
    - [9002d18] 2016-05-05 Updated PHPDoc [@SilverFire]
    - [c5a5f5b] 2016-05-02 Updated PHPDoc [@SilverFire]
- Fixed build with asset-packagist and newer hidev
    - [cab1ba2] 2016-04-28 phpcsfixed [@hiqsol]
    - [ceebbfa] 2016-04-28 rehideved [@hiqsol]
    - [e293d58] 2016-04-28 added tests [@hiqsol]
    - [4d3908b] 2016-04-06 phpcsfixed [@hiqsol]
    - [e4342b0] 2016-04-06 inited tests [@hiqsol]
    - [b82c8a7] 2016-04-06 fixed build with asset-packagist [@hiqsol]
    - [009697d] 2016-04-06 rehideved [@hiqsol]
- Added server ordering and bulk operations
    - [e20745c] 2016-04-27 translations [@hiqsol]
    - [380ab33] 2016-04-27 Added bulk server blocking, unblocking and deleting [@SilverFire]
    - [7427c2c] 2016-04-19 Hadle on click action in pop-over on oreder page [@tafid]
    - [775519a] 2016-04-18 Fixed server/view: typos in variable names [@SilverFire]
    - [ee333a7] 2016-04-15 implemented server order class [@SilverFire]
    - [441ca22] 2016-04-14 Major refactoring of server module. Added posibility to add server to cart for order operations [@SilverFire]
    - [2124278] 2016-04-14 Translations updated [@SilverFire]
    - [e090b2f] 2016-04-14 Calculation renamed to OrderCalculation [@SilverFire]
- Added RRD and network graphs
    - [af6aee9] 2016-04-28 minor improved rrd search form [@hiqsol]
    - [d96c027] 2016-04-04 Fixed view layout [@tafid]
    - [c584d4b] 2016-04-04 Fixed view layout [@tafid]
    - [88ea775] 2016-03-30 Added server oreder by `status_time` [@SilverFire]
    - [5f60ceb] 2016-03-29 Add css list-style:none for ChartJs ul.line-legend [@tafid]
    - [9b26364] 2016-03-29 Back to lagacy pow function [@tafid]
    - [c9a958f] 2016-03-25 Server RRD view page re-designed [@SilverFire]
    - [8dd0661] 2016-03-25 Updated translations [@SilverFire]
    - [079cdd2] 2016-03-25 Added Switch Graphs [@SilverFire]
    - [27f3609] 2016-03-22 Server reinstall button moved to server actions block [@SilverFire]
    - [bfb2e86] 2016-03-22 server/view - VNC wrapped in a PJAX call [@SilverFire]
    - [fb94df7] 2016-03-22 Added SearchAction [@SilverFire]
    - [ce26caf] 2016-03-22 Added PanelServerCombo [@SilverFire]
    - [350949a] 2016-03-22 Translations updated [@SilverFire]
    - [4d0b435] 2016-03-21 ServerRenewProduct::loadRelatedData() renamed to ensureRelatedData() [@SilverFire]
    - [1a17ed8] 2016-03-21 ServerController - fixed typo in variable name [@SilverFire]
    - [f74e93e] 2016-03-18 Added server renew operation [@SilverFire]
    - [0dfa016] 2016-03-18 Server expire widget - fixed colors [@SilverFire]
    - [7d81b71] 2016-03-16 Translations update [@SilverFire]
    - [58f1b6a] 2016-03-16 Added missing translation [@SilverFire]
    - [f105bc0] 2016-03-14 RRD view - autosubmit on radio filters change [@SilverFire]
    - [7f11806] 2016-03-14 RRD view - changed ListView to GridView [@SilverFire]
    - [efc2e49] 2016-03-14 Fixed hipanel/server/rrd dictionary filename [@SilverFire]
    - [ab98858] 2016-03-12 RRD page implemented [@SilverFire]
    - [a2881e6] 2016-03-12 Server - added link to RRD [@SilverFire]
    - [c649235] 2016-03-12 Added rrd translation dict, translations updated [@SilverFire]
    - [9a70df9] 2016-03-12 Server rrd inited [@SilverFire]
    - [2b01f0b] 2016-03-11 Updated translations [@SilverFire]
    - [77ed40d] 2016-03-09 Implemented PTR records management for the server IP addresses [@SilverFire]
    - [eb8296c] 2016-03-07 Server view - added IP addresses table [@SilverFire]
    - [12eb52e] 2016-03-01 view page: added parts block [@SilverFire]
- Added traffic charts
    - [6ad898c] 2016-02-29 Server view - charts are hidden, when no data is available [@SilverFire]
    - [1e1d52e] 2016-02-26 Minor refactors [@SilverFire]
    - [6b271a9] 2016-02-25 Server charts config redone with CartOptions widget [@SilverFire]
    - [b61bdb0] 2016-02-25 Server traffic chart dynamic update implemented [@SilverFire]
    - [1e1b697] 2016-02-24 Server view - chart resizing implemted [@SilverFire]
    - [e110ff1] 2016-02-24 composer.json updated [@SilverFire]
    - [44ec30c] 2016-02-24 removed omnilight/yii2-bootstrap-daterangepicker dependency [@SilverFire]
    - [818d9b7] 2016-02-24 Added omnilight/yii2-bootstrap-daterangepicker dependency [@SilverFire]
    - [461a691] 2016-02-24 Added datepicker dependency [@SilverFire]
    - [0ba13c9] 2016-02-23 Added Server::groupUsesForCharts() method [@SilverFire]
    - [9b6e3fd] 2016-02-23 Server view action redone to use relations [@SilverFire]
    - [95663f5] 2016-02-23 spelling updates [@SilverFire]
    - [38ce0f5] 2016-02-23 Added server bandwidth and traffice consumption views [@SilverFire]
    - [96bfe22] 2016-02-23 Added ServerUse model [@SilverFire]
    - [d088c8e] 2016-02-22 added traffic chart [@SilverFire]
    - [12b0733] 2016-02-22 ServerController - cached osimages [@SilverFire]
    - [ab34d4a] 2016-02-18 Changed XEditableColumn import namespace [@SilverFire]
    - [889efc1] 2016-02-10 Fixed error when no tariff is available [@SilverFire]
    - [4081d58] 2016-02-10 spelling [@SilverFire]
    - [ecb31ca] 2016-02-10 Fixed i18n config [@SilverFire]
    - [dfd828f] 2016-02-10 Translations improved [@SilverFire]
    - [be0994a] 2016-02-10 added messages files; [@SilverFire]
    - [baa7e3b] 2016-02-08 Translations moved to hipanel/server category, other minor [@SilverFire]
    - [5db921b] 2016-02-02 Fixed standalone action to use updated api [@tafid]
    - [033ba81] 2016-01-25 server/view - fixed loading of huge data [@SilverFire]
    - [4167fd2] 2016-01-22 ServerController::indexAction - added filterStorageMap [@SilverFire]
    - [12a6672] 2015-12-09 Removed PHP short-tags [@SilverFire]
    - [3b7f781] 2015-12-04 Classes notation changed from pathtoClassName to PHP 5.6 ClassName::class [@SilverFire]
    - [03fa3a6] 2015-11-26 Fix markup on index page when Advanced Search show up [@tafid]
    - [b0540fc] 2015-11-11 Changed ServerColumn default attribute name device -> server [@SilverFire]
    - [1a85ec9] 2015-11-11 Expires widget updated due to parent class API change [@SilverFire]
    - [cf1014e] 2015-10-15 ServerGridView changed call of ArraySpoiler due to class API changes [@SilverFire]
    - [4daa0ee] 2015-10-12 Added server event list [@SilverFire]
    - [b15ff81] 2015-10-09 Server reinstall - hides IPS manager for customers without ISP in tariff [@SilverFire]
    - [e7a34ff] 2015-10-09 Server Reinstall OS fixed button [@SilverFire]
    - [2b195f9] 2015-09-28 + value to button [@BladeRoot]
    - [1ff4e38] 2015-09-28 ServerSearch  - added search attributes [@SilverFire]
    - [83d1448] 2015-09-23 fixed Label zclass -> color [@hiqsol]
    - [d1e80bf] 2015-09-21 fixed translation, redone Re::l to Yii::t [@hiqsol]
    - [18a2f18] 2015-09-17 * improve language pack; - remove unnesessary lines [@BladeRoot]
    - [cadd45b] 2015-09-16 fixed problem with ViewAction data closure [@hiqsol]
    - [9750999] 2015-09-16 used ClientSellerLink::widget [@hiqsol]
    - [32df923] 2015-09-16 * change language [@BladeRoot]
    - [780f3b8] 2015-09-14 ServerController::actionList - removed [@SilverFire]
    - [3d585e1] 2015-09-15 localized menu [@hiqsol]
    - [21fc871] 2015-09-01 ServerCombo - changed search url [@SilverFire]
    - [efdd860] 2015-08-28 Fixed osimages list for different virtualisations, index.php - removed column `os_and_panel` [@SilverFire]
    - [b49f726] 2015-08-28 composer.json - added dependencies on client, tariff modules [@SilverFire]
    - [280bb07] 2015-08-27 Note column separated to note/label [@SilverFire]
    - [e9f8728] 2015-08-27 Fixed breadcrumbs subtitle [@SilverFire]
    - [4ce7c81] 2015-08-25 minor] [@SilverFire]
    - [12ad378] 2015-08-25 Fix warnings [@tafid]
- Added server refuse, renew and blocking
    - [17ee153] 2015-09-28 Incresased Server reinstall modal size [@SilverFire]
    - [7b71771] 2015-09-22 Implemented server blocking [@SilverFire]
    - [68c4d70] 2015-08-27 Server refuse/renew implemented [@SilverFire]
    - [77b9b25] 2015-08-26 ServerGridView - added note, `tariff_and_discount` columns. Other columns fixed/improved. Other minor [@SilverFire]
    - [7855150] 2015-08-26 fixed access control [@hiqsol]
- Fixed reset-password and reinstal
    - [e767c21] 2015-08-25 ServerController - fixed reset-password, reinstal, other minor [@SilverFire]
    - [96d66fd] 2015-08-19 Index page redone with actual standarts [@SilverFire]
    - [09d4ca0] 2015-08-19 ServerColumn - fixed Combo call [@SilverFire]
    - [2516278] 2015-08-19 hideved [@hiqsol]
- Added server/buy redirect
    - [d43dba5] 2015-08-19 + server/buy redirect [@hiqsol]
    - [7201011] 2015-08-19 fixed @server alias [@hiqsol]
    - [3de1bcc] 2015-08-17 Changes [@tafid]
    - [7e04acf] 2015-08-12 Add per page and rewrite sorter [@tafid]
    - [befcb6c] 2015-08-07 View/Actions redone with ModalButton widget [@SilverFire]
    - [e6ca06d] 2015-08-06 Deep coding. Server view page, redone controller with standalone actions, etc. [@SilverFire]
    - [3299314] 2015-08-05 Fix\ [@tafid]
    - [9f427ff] 2015-08-04 Fix BulkButton -> css [@tafid]
    - [7c7ed71] 2015-08-04 Refactor to new ActionBox [@tafid]
    - [024ee18] 2015-08-04 Add pull-right class to ButtonDropsdown [@tafid]
    - [ba38409] 2015-08-02 Code updated to actual agreed style Minor changes [@SilverFire]
    - [725eb99] 2015-08-02 * Plugin: + aliases [@hiqsol]
    - [65edb18] 2015-07-31 removed unused uses [@SilverFire]
- Added basics
    - [0d4e9de] 2015-07-31 Sources moved to src directory [@SilverFire]
    - [bc3b774] 2015-07-31 Server grid view implementation [@SilverFire]
    - [07a0697] 2015-07-30 Fixed phpdoc [@SilverFire]
    - [e03fc6e] 2015-07-29 Old unccommited changes [@SilverFire]
    - [9917a58] 2015-05-28 Server model filled in, ServerColumn fixed [@SilverFire]
    - [ad249f0] 2015-05-25 renamed hiart [@hiqsol]
    - [6c67b76] 2015-05-22 combo - updated parent namespace [@SilverFire]
    - [37b3e0d] 2015-05-15 + Plugin, * Menu [@hiqsol]
    - [852b297] 2015-05-15 Merge commit '9e44f2c' [@hiqsol]
    - [9e44f2c] 2015-05-14 + Menu.php [@hiqsol]
    - [1a9a010] 2015-05-14 {Combo2} -> {Combo} [@SilverFire]
    - [f88db36] 2015-05-14 Combo2 fixed clearWhen condition [@SilverFire]
    - [c6b2046] 2015-05-02 Changes in Combo2 configs to suite new scheme [@SilverFire]
    - [d727f1b] 2015-04-29 Filled the module [@SilverFire]
    - [cd13473] 2015-04-22 doc [@hiqsol]
    - [dcaccea] 2015-04-22 inited [@hiqsol]

## [Development started] - 2015-04-22

[@hiqsol]: https://github.com/hiqsol
[sol@hiqdev.com]: https://github.com/hiqsol
[@SilverFire]: https://github.com/SilverFire
[d.naumenko.a@gmail.com]: https://github.com/SilverFire
[@tafid]: https://github.com/tafid
[andreyklochok@gmail.com]: https://github.com/tafid
[@BladeRoot]: https://github.com/BladeRoot
[bladeroot@gmail.com]: https://github.com/BladeRoot
[cab1ba2]: https://github.com/hiqdev/hipanel-module-server/commit/cab1ba2
[ceebbfa]: https://github.com/hiqdev/hipanel-module-server/commit/ceebbfa
[e293d58]: https://github.com/hiqdev/hipanel-module-server/commit/e293d58
[4d3908b]: https://github.com/hiqdev/hipanel-module-server/commit/4d3908b
[e4342b0]: https://github.com/hiqdev/hipanel-module-server/commit/e4342b0
[b82c8a7]: https://github.com/hiqdev/hipanel-module-server/commit/b82c8a7
[009697d]: https://github.com/hiqdev/hipanel-module-server/commit/009697d
[e20745c]: https://github.com/hiqdev/hipanel-module-server/commit/e20745c
[380ab33]: https://github.com/hiqdev/hipanel-module-server/commit/380ab33
[7427c2c]: https://github.com/hiqdev/hipanel-module-server/commit/7427c2c
[775519a]: https://github.com/hiqdev/hipanel-module-server/commit/775519a
[ee333a7]: https://github.com/hiqdev/hipanel-module-server/commit/ee333a7
[441ca22]: https://github.com/hiqdev/hipanel-module-server/commit/441ca22
[2124278]: https://github.com/hiqdev/hipanel-module-server/commit/2124278
[e090b2f]: https://github.com/hiqdev/hipanel-module-server/commit/e090b2f
[af6aee9]: https://github.com/hiqdev/hipanel-module-server/commit/af6aee9
[d96c027]: https://github.com/hiqdev/hipanel-module-server/commit/d96c027
[c584d4b]: https://github.com/hiqdev/hipanel-module-server/commit/c584d4b
[88ea775]: https://github.com/hiqdev/hipanel-module-server/commit/88ea775
[5f60ceb]: https://github.com/hiqdev/hipanel-module-server/commit/5f60ceb
[9b26364]: https://github.com/hiqdev/hipanel-module-server/commit/9b26364
[c9a958f]: https://github.com/hiqdev/hipanel-module-server/commit/c9a958f
[8dd0661]: https://github.com/hiqdev/hipanel-module-server/commit/8dd0661
[079cdd2]: https://github.com/hiqdev/hipanel-module-server/commit/079cdd2
[27f3609]: https://github.com/hiqdev/hipanel-module-server/commit/27f3609
[bfb2e86]: https://github.com/hiqdev/hipanel-module-server/commit/bfb2e86
[fb94df7]: https://github.com/hiqdev/hipanel-module-server/commit/fb94df7
[ce26caf]: https://github.com/hiqdev/hipanel-module-server/commit/ce26caf
[350949a]: https://github.com/hiqdev/hipanel-module-server/commit/350949a
[4d0b435]: https://github.com/hiqdev/hipanel-module-server/commit/4d0b435
[1a17ed8]: https://github.com/hiqdev/hipanel-module-server/commit/1a17ed8
[f74e93e]: https://github.com/hiqdev/hipanel-module-server/commit/f74e93e
[0dfa016]: https://github.com/hiqdev/hipanel-module-server/commit/0dfa016
[7d81b71]: https://github.com/hiqdev/hipanel-module-server/commit/7d81b71
[58f1b6a]: https://github.com/hiqdev/hipanel-module-server/commit/58f1b6a
[f105bc0]: https://github.com/hiqdev/hipanel-module-server/commit/f105bc0
[7f11806]: https://github.com/hiqdev/hipanel-module-server/commit/7f11806
[efc2e49]: https://github.com/hiqdev/hipanel-module-server/commit/efc2e49
[ab98858]: https://github.com/hiqdev/hipanel-module-server/commit/ab98858
[a2881e6]: https://github.com/hiqdev/hipanel-module-server/commit/a2881e6
[c649235]: https://github.com/hiqdev/hipanel-module-server/commit/c649235
[9a70df9]: https://github.com/hiqdev/hipanel-module-server/commit/9a70df9
[2b01f0b]: https://github.com/hiqdev/hipanel-module-server/commit/2b01f0b
[77ed40d]: https://github.com/hiqdev/hipanel-module-server/commit/77ed40d
[eb8296c]: https://github.com/hiqdev/hipanel-module-server/commit/eb8296c
[12eb52e]: https://github.com/hiqdev/hipanel-module-server/commit/12eb52e
[6ad898c]: https://github.com/hiqdev/hipanel-module-server/commit/6ad898c
[1e1d52e]: https://github.com/hiqdev/hipanel-module-server/commit/1e1d52e
[6b271a9]: https://github.com/hiqdev/hipanel-module-server/commit/6b271a9
[b61bdb0]: https://github.com/hiqdev/hipanel-module-server/commit/b61bdb0
[1e1b697]: https://github.com/hiqdev/hipanel-module-server/commit/1e1b697
[e110ff1]: https://github.com/hiqdev/hipanel-module-server/commit/e110ff1
[44ec30c]: https://github.com/hiqdev/hipanel-module-server/commit/44ec30c
[818d9b7]: https://github.com/hiqdev/hipanel-module-server/commit/818d9b7
[461a691]: https://github.com/hiqdev/hipanel-module-server/commit/461a691
[0ba13c9]: https://github.com/hiqdev/hipanel-module-server/commit/0ba13c9
[9b6e3fd]: https://github.com/hiqdev/hipanel-module-server/commit/9b6e3fd
[95663f5]: https://github.com/hiqdev/hipanel-module-server/commit/95663f5
[38ce0f5]: https://github.com/hiqdev/hipanel-module-server/commit/38ce0f5
[96bfe22]: https://github.com/hiqdev/hipanel-module-server/commit/96bfe22
[d088c8e]: https://github.com/hiqdev/hipanel-module-server/commit/d088c8e
[12b0733]: https://github.com/hiqdev/hipanel-module-server/commit/12b0733
[ab34d4a]: https://github.com/hiqdev/hipanel-module-server/commit/ab34d4a
[889efc1]: https://github.com/hiqdev/hipanel-module-server/commit/889efc1
[4081d58]: https://github.com/hiqdev/hipanel-module-server/commit/4081d58
[ecb31ca]: https://github.com/hiqdev/hipanel-module-server/commit/ecb31ca
[dfd828f]: https://github.com/hiqdev/hipanel-module-server/commit/dfd828f
[be0994a]: https://github.com/hiqdev/hipanel-module-server/commit/be0994a
[baa7e3b]: https://github.com/hiqdev/hipanel-module-server/commit/baa7e3b
[5db921b]: https://github.com/hiqdev/hipanel-module-server/commit/5db921b
[033ba81]: https://github.com/hiqdev/hipanel-module-server/commit/033ba81
[4167fd2]: https://github.com/hiqdev/hipanel-module-server/commit/4167fd2
[12a6672]: https://github.com/hiqdev/hipanel-module-server/commit/12a6672
[3b7f781]: https://github.com/hiqdev/hipanel-module-server/commit/3b7f781
[03fa3a6]: https://github.com/hiqdev/hipanel-module-server/commit/03fa3a6
[b0540fc]: https://github.com/hiqdev/hipanel-module-server/commit/b0540fc
[1a85ec9]: https://github.com/hiqdev/hipanel-module-server/commit/1a85ec9
[cf1014e]: https://github.com/hiqdev/hipanel-module-server/commit/cf1014e
[4daa0ee]: https://github.com/hiqdev/hipanel-module-server/commit/4daa0ee
[b15ff81]: https://github.com/hiqdev/hipanel-module-server/commit/b15ff81
[e7a34ff]: https://github.com/hiqdev/hipanel-module-server/commit/e7a34ff
[2b195f9]: https://github.com/hiqdev/hipanel-module-server/commit/2b195f9
[1ff4e38]: https://github.com/hiqdev/hipanel-module-server/commit/1ff4e38
[83d1448]: https://github.com/hiqdev/hipanel-module-server/commit/83d1448
[d1e80bf]: https://github.com/hiqdev/hipanel-module-server/commit/d1e80bf
[18a2f18]: https://github.com/hiqdev/hipanel-module-server/commit/18a2f18
[cadd45b]: https://github.com/hiqdev/hipanel-module-server/commit/cadd45b
[9750999]: https://github.com/hiqdev/hipanel-module-server/commit/9750999
[32df923]: https://github.com/hiqdev/hipanel-module-server/commit/32df923
[780f3b8]: https://github.com/hiqdev/hipanel-module-server/commit/780f3b8
[3d585e1]: https://github.com/hiqdev/hipanel-module-server/commit/3d585e1
[21fc871]: https://github.com/hiqdev/hipanel-module-server/commit/21fc871
[efdd860]: https://github.com/hiqdev/hipanel-module-server/commit/efdd860
[b49f726]: https://github.com/hiqdev/hipanel-module-server/commit/b49f726
[280bb07]: https://github.com/hiqdev/hipanel-module-server/commit/280bb07
[e9f8728]: https://github.com/hiqdev/hipanel-module-server/commit/e9f8728
[4ce7c81]: https://github.com/hiqdev/hipanel-module-server/commit/4ce7c81
[12ad378]: https://github.com/hiqdev/hipanel-module-server/commit/12ad378
[17ee153]: https://github.com/hiqdev/hipanel-module-server/commit/17ee153
[7b71771]: https://github.com/hiqdev/hipanel-module-server/commit/7b71771
[68c4d70]: https://github.com/hiqdev/hipanel-module-server/commit/68c4d70
[77b9b25]: https://github.com/hiqdev/hipanel-module-server/commit/77b9b25
[7855150]: https://github.com/hiqdev/hipanel-module-server/commit/7855150
[e767c21]: https://github.com/hiqdev/hipanel-module-server/commit/e767c21
[96d66fd]: https://github.com/hiqdev/hipanel-module-server/commit/96d66fd
[09d4ca0]: https://github.com/hiqdev/hipanel-module-server/commit/09d4ca0
[2516278]: https://github.com/hiqdev/hipanel-module-server/commit/2516278
[d43dba5]: https://github.com/hiqdev/hipanel-module-server/commit/d43dba5
[7201011]: https://github.com/hiqdev/hipanel-module-server/commit/7201011
[3de1bcc]: https://github.com/hiqdev/hipanel-module-server/commit/3de1bcc
[7e04acf]: https://github.com/hiqdev/hipanel-module-server/commit/7e04acf
[befcb6c]: https://github.com/hiqdev/hipanel-module-server/commit/befcb6c
[e6ca06d]: https://github.com/hiqdev/hipanel-module-server/commit/e6ca06d
[3299314]: https://github.com/hiqdev/hipanel-module-server/commit/3299314
[9f427ff]: https://github.com/hiqdev/hipanel-module-server/commit/9f427ff
[7c7ed71]: https://github.com/hiqdev/hipanel-module-server/commit/7c7ed71
[024ee18]: https://github.com/hiqdev/hipanel-module-server/commit/024ee18
[ba38409]: https://github.com/hiqdev/hipanel-module-server/commit/ba38409
[725eb99]: https://github.com/hiqdev/hipanel-module-server/commit/725eb99
[65edb18]: https://github.com/hiqdev/hipanel-module-server/commit/65edb18
[0d4e9de]: https://github.com/hiqdev/hipanel-module-server/commit/0d4e9de
[bc3b774]: https://github.com/hiqdev/hipanel-module-server/commit/bc3b774
[07a0697]: https://github.com/hiqdev/hipanel-module-server/commit/07a0697
[e03fc6e]: https://github.com/hiqdev/hipanel-module-server/commit/e03fc6e
[9917a58]: https://github.com/hiqdev/hipanel-module-server/commit/9917a58
[ad249f0]: https://github.com/hiqdev/hipanel-module-server/commit/ad249f0
[6c67b76]: https://github.com/hiqdev/hipanel-module-server/commit/6c67b76
[37b3e0d]: https://github.com/hiqdev/hipanel-module-server/commit/37b3e0d
[852b297]: https://github.com/hiqdev/hipanel-module-server/commit/852b297
[9e44f2c]: https://github.com/hiqdev/hipanel-module-server/commit/9e44f2c
[1a9a010]: https://github.com/hiqdev/hipanel-module-server/commit/1a9a010
[f88db36]: https://github.com/hiqdev/hipanel-module-server/commit/f88db36
[c6b2046]: https://github.com/hiqdev/hipanel-module-server/commit/c6b2046
[d727f1b]: https://github.com/hiqdev/hipanel-module-server/commit/d727f1b
[cd13473]: https://github.com/hiqdev/hipanel-module-server/commit/cd13473
[dcaccea]: https://github.com/hiqdev/hipanel-module-server/commit/dcaccea
[4398013]: https://github.com/hiqdev/hipanel-module-server/commit/4398013
[d1a8bd7]: https://github.com/hiqdev/hipanel-module-server/commit/d1a8bd7
[dda7e09]: https://github.com/hiqdev/hipanel-module-server/commit/dda7e09
[66ca62e]: https://github.com/hiqdev/hipanel-module-server/commit/66ca62e
[abe986e]: https://github.com/hiqdev/hipanel-module-server/commit/abe986e
[7ffa57f]: https://github.com/hiqdev/hipanel-module-server/commit/7ffa57f
[cd1decb]: https://github.com/hiqdev/hipanel-module-server/commit/cd1decb
[345b11a]: https://github.com/hiqdev/hipanel-module-server/commit/345b11a
[3568258]: https://github.com/hiqdev/hipanel-module-server/commit/3568258
[dc047de]: https://github.com/hiqdev/hipanel-module-server/commit/dc047de
[fb00123]: https://github.com/hiqdev/hipanel-module-server/commit/fb00123
[b42ccd6]: https://github.com/hiqdev/hipanel-module-server/commit/b42ccd6
[05f01da]: https://github.com/hiqdev/hipanel-module-server/commit/05f01da
[35a8ba9]: https://github.com/hiqdev/hipanel-module-server/commit/35a8ba9
[79eefd4]: https://github.com/hiqdev/hipanel-module-server/commit/79eefd4
[b093177]: https://github.com/hiqdev/hipanel-module-server/commit/b093177
[462711c]: https://github.com/hiqdev/hipanel-module-server/commit/462711c
[160a71c]: https://github.com/hiqdev/hipanel-module-server/commit/160a71c
[106cb3b]: https://github.com/hiqdev/hipanel-module-server/commit/106cb3b
[56b0150]: https://github.com/hiqdev/hipanel-module-server/commit/56b0150
[0420705]: https://github.com/hiqdev/hipanel-module-server/commit/0420705
[145b7a0]: https://github.com/hiqdev/hipanel-module-server/commit/145b7a0
[8734a27]: https://github.com/hiqdev/hipanel-module-server/commit/8734a27
[0bc4ee1]: https://github.com/hiqdev/hipanel-module-server/commit/0bc4ee1
[3788066]: https://github.com/hiqdev/hipanel-module-server/commit/3788066
[8bb51e1]: https://github.com/hiqdev/hipanel-module-server/commit/8bb51e1
[5071d1e]: https://github.com/hiqdev/hipanel-module-server/commit/5071d1e
[9e9418e]: https://github.com/hiqdev/hipanel-module-server/commit/9e9418e
[245ea19]: https://github.com/hiqdev/hipanel-module-server/commit/245ea19
[e70bc4d]: https://github.com/hiqdev/hipanel-module-server/commit/e70bc4d
[7e9d860]: https://github.com/hiqdev/hipanel-module-server/commit/7e9d860
[b9548a8]: https://github.com/hiqdev/hipanel-module-server/commit/b9548a8
[88ee0d6]: https://github.com/hiqdev/hipanel-module-server/commit/88ee0d6
[a22f258]: https://github.com/hiqdev/hipanel-module-server/commit/a22f258
[8cc28b9]: https://github.com/hiqdev/hipanel-module-server/commit/8cc28b9
[ef753c8]: https://github.com/hiqdev/hipanel-module-server/commit/ef753c8
[4bfde8e]: https://github.com/hiqdev/hipanel-module-server/commit/4bfde8e
[668ffe7]: https://github.com/hiqdev/hipanel-module-server/commit/668ffe7
[2821750]: https://github.com/hiqdev/hipanel-module-server/commit/2821750
[c446584]: https://github.com/hiqdev/hipanel-module-server/commit/c446584
[673d7e2]: https://github.com/hiqdev/hipanel-module-server/commit/673d7e2
[e270334]: https://github.com/hiqdev/hipanel-module-server/commit/e270334
[e81f438]: https://github.com/hiqdev/hipanel-module-server/commit/e81f438
[9609dd4]: https://github.com/hiqdev/hipanel-module-server/commit/9609dd4
[efd2d90]: https://github.com/hiqdev/hipanel-module-server/commit/efd2d90
[eecd299]: https://github.com/hiqdev/hipanel-module-server/commit/eecd299
[073c72f]: https://github.com/hiqdev/hipanel-module-server/commit/073c72f
[1bed480]: https://github.com/hiqdev/hipanel-module-server/commit/1bed480
[43c6cb8]: https://github.com/hiqdev/hipanel-module-server/commit/43c6cb8
[4bef7b9]: https://github.com/hiqdev/hipanel-module-server/commit/4bef7b9
[029bba2]: https://github.com/hiqdev/hipanel-module-server/commit/029bba2
[58328b7]: https://github.com/hiqdev/hipanel-module-server/commit/58328b7
[4ba32f1]: https://github.com/hiqdev/hipanel-module-server/commit/4ba32f1
[ae5f42a]: https://github.com/hiqdev/hipanel-module-server/commit/ae5f42a
[54278bc]: https://github.com/hiqdev/hipanel-module-server/commit/54278bc
[4a425e0]: https://github.com/hiqdev/hipanel-module-server/commit/4a425e0
[8b07332]: https://github.com/hiqdev/hipanel-module-server/commit/8b07332
[9354ae5]: https://github.com/hiqdev/hipanel-module-server/commit/9354ae5
[2f9dbe8]: https://github.com/hiqdev/hipanel-module-server/commit/2f9dbe8
[6635161]: https://github.com/hiqdev/hipanel-module-server/commit/6635161
[ff6fbff]: https://github.com/hiqdev/hipanel-module-server/commit/ff6fbff
[5ed4ac8]: https://github.com/hiqdev/hipanel-module-server/commit/5ed4ac8
[2f0f989]: https://github.com/hiqdev/hipanel-module-server/commit/2f0f989
[1f22853]: https://github.com/hiqdev/hipanel-module-server/commit/1f22853
[490a3e4]: https://github.com/hiqdev/hipanel-module-server/commit/490a3e4
[8a0e16b]: https://github.com/hiqdev/hipanel-module-server/commit/8a0e16b
[8cf182b]: https://github.com/hiqdev/hipanel-module-server/commit/8cf182b
[023a965]: https://github.com/hiqdev/hipanel-module-server/commit/023a965
[0e45a97]: https://github.com/hiqdev/hipanel-module-server/commit/0e45a97
[97b7666]: https://github.com/hiqdev/hipanel-module-server/commit/97b7666
[15f242e]: https://github.com/hiqdev/hipanel-module-server/commit/15f242e
[00b091a]: https://github.com/hiqdev/hipanel-module-server/commit/00b091a
[25389aa]: https://github.com/hiqdev/hipanel-module-server/commit/25389aa
[dc786a9]: https://github.com/hiqdev/hipanel-module-server/commit/dc786a9
[f0a9da8]: https://github.com/hiqdev/hipanel-module-server/commit/f0a9da8
[722563b]: https://github.com/hiqdev/hipanel-module-server/commit/722563b
[9fa3b6f]: https://github.com/hiqdev/hipanel-module-server/commit/9fa3b6f
[5049323]: https://github.com/hiqdev/hipanel-module-server/commit/5049323
[9e34ec4]: https://github.com/hiqdev/hipanel-module-server/commit/9e34ec4
[242271d]: https://github.com/hiqdev/hipanel-module-server/commit/242271d
[da14561]: https://github.com/hiqdev/hipanel-module-server/commit/da14561
[ea6f088]: https://github.com/hiqdev/hipanel-module-server/commit/ea6f088
[3f56ccd]: https://github.com/hiqdev/hipanel-module-server/commit/3f56ccd
[3d23c1f]: https://github.com/hiqdev/hipanel-module-server/commit/3d23c1f
[8af0acc]: https://github.com/hiqdev/hipanel-module-server/commit/8af0acc
[7f1c57f]: https://github.com/hiqdev/hipanel-module-server/commit/7f1c57f
[85f6d62]: https://github.com/hiqdev/hipanel-module-server/commit/85f6d62
[bb48ce2]: https://github.com/hiqdev/hipanel-module-server/commit/bb48ce2
[148e317]: https://github.com/hiqdev/hipanel-module-server/commit/148e317
[0913690]: https://github.com/hiqdev/hipanel-module-server/commit/0913690
[a9adb19]: https://github.com/hiqdev/hipanel-module-server/commit/a9adb19
[3ec78f4]: https://github.com/hiqdev/hipanel-module-server/commit/3ec78f4
[8fc1e83]: https://github.com/hiqdev/hipanel-module-server/commit/8fc1e83
[cbc4553]: https://github.com/hiqdev/hipanel-module-server/commit/cbc4553
[eb8cb03]: https://github.com/hiqdev/hipanel-module-server/commit/eb8cb03
[bfbac33]: https://github.com/hiqdev/hipanel-module-server/commit/bfbac33
[6c44ec4]: https://github.com/hiqdev/hipanel-module-server/commit/6c44ec4
[cb9da57]: https://github.com/hiqdev/hipanel-module-server/commit/cb9da57
[365d36d]: https://github.com/hiqdev/hipanel-module-server/commit/365d36d
[cd7e8b9]: https://github.com/hiqdev/hipanel-module-server/commit/cd7e8b9
[42d7b38]: https://github.com/hiqdev/hipanel-module-server/commit/42d7b38
[cbe5b53]: https://github.com/hiqdev/hipanel-module-server/commit/cbe5b53
[3f4bdd5]: https://github.com/hiqdev/hipanel-module-server/commit/3f4bdd5
[6c1d309]: https://github.com/hiqdev/hipanel-module-server/commit/6c1d309
[e55978e]: https://github.com/hiqdev/hipanel-module-server/commit/e55978e
[f70e897]: https://github.com/hiqdev/hipanel-module-server/commit/f70e897
[4bc8e5f]: https://github.com/hiqdev/hipanel-module-server/commit/4bc8e5f
[f38efdd]: https://github.com/hiqdev/hipanel-module-server/commit/f38efdd
[5fa9a07]: https://github.com/hiqdev/hipanel-module-server/commit/5fa9a07
[8e0636d]: https://github.com/hiqdev/hipanel-module-server/commit/8e0636d
[9fe2bd7]: https://github.com/hiqdev/hipanel-module-server/commit/9fe2bd7
[cea9ff2]: https://github.com/hiqdev/hipanel-module-server/commit/cea9ff2
[e927e32]: https://github.com/hiqdev/hipanel-module-server/commit/e927e32
[2c90ef2]: https://github.com/hiqdev/hipanel-module-server/commit/2c90ef2
[55c012d]: https://github.com/hiqdev/hipanel-module-server/commit/55c012d
[756bc4f]: https://github.com/hiqdev/hipanel-module-server/commit/756bc4f
[79950a5]: https://github.com/hiqdev/hipanel-module-server/commit/79950a5
[403b082]: https://github.com/hiqdev/hipanel-module-server/commit/403b082
[65ede2e]: https://github.com/hiqdev/hipanel-module-server/commit/65ede2e
[c62900d]: https://github.com/hiqdev/hipanel-module-server/commit/c62900d
[3ac2082]: https://github.com/hiqdev/hipanel-module-server/commit/3ac2082
[c0bbe7c]: https://github.com/hiqdev/hipanel-module-server/commit/c0bbe7c
[6a07df5]: https://github.com/hiqdev/hipanel-module-server/commit/6a07df5
[93a1c5c]: https://github.com/hiqdev/hipanel-module-server/commit/93a1c5c
[baf937a]: https://github.com/hiqdev/hipanel-module-server/commit/baf937a
[f933991]: https://github.com/hiqdev/hipanel-module-server/commit/f933991
[d593541]: https://github.com/hiqdev/hipanel-module-server/commit/d593541
[3d28fc9]: https://github.com/hiqdev/hipanel-module-server/commit/3d28fc9
[2308604]: https://github.com/hiqdev/hipanel-module-server/commit/2308604
[1974f5d]: https://github.com/hiqdev/hipanel-module-server/commit/1974f5d
[6ea4392]: https://github.com/hiqdev/hipanel-module-server/commit/6ea4392
[d902aa9]: https://github.com/hiqdev/hipanel-module-server/commit/d902aa9
[7c1f431]: https://github.com/hiqdev/hipanel-module-server/commit/7c1f431
[007929f]: https://github.com/hiqdev/hipanel-module-server/commit/007929f
[f688347]: https://github.com/hiqdev/hipanel-module-server/commit/f688347
[5559ca3]: https://github.com/hiqdev/hipanel-module-server/commit/5559ca3
[cb169f7]: https://github.com/hiqdev/hipanel-module-server/commit/cb169f7
[b9b622b]: https://github.com/hiqdev/hipanel-module-server/commit/b9b622b
[394a8ea]: https://github.com/hiqdev/hipanel-module-server/commit/394a8ea
[5027d51]: https://github.com/hiqdev/hipanel-module-server/commit/5027d51
[949bfeb]: https://github.com/hiqdev/hipanel-module-server/commit/949bfeb
[9a47dd0]: https://github.com/hiqdev/hipanel-module-server/commit/9a47dd0
[2cac3a5]: https://github.com/hiqdev/hipanel-module-server/commit/2cac3a5
[ba9a129]: https://github.com/hiqdev/hipanel-module-server/commit/ba9a129
[9e4509e]: https://github.com/hiqdev/hipanel-module-server/commit/9e4509e
[00aa6de]: https://github.com/hiqdev/hipanel-module-server/commit/00aa6de
[2c7d408]: https://github.com/hiqdev/hipanel-module-server/commit/2c7d408
[27bf392]: https://github.com/hiqdev/hipanel-module-server/commit/27bf392
[cb89f9e]: https://github.com/hiqdev/hipanel-module-server/commit/cb89f9e
[0a187b4]: https://github.com/hiqdev/hipanel-module-server/commit/0a187b4
[66b7bc4]: https://github.com/hiqdev/hipanel-module-server/commit/66b7bc4
[3849c56]: https://github.com/hiqdev/hipanel-module-server/commit/3849c56
[02dd081]: https://github.com/hiqdev/hipanel-module-server/commit/02dd081
[ed48ab1]: https://github.com/hiqdev/hipanel-module-server/commit/ed48ab1
[2714ab0]: https://github.com/hiqdev/hipanel-module-server/commit/2714ab0
[6571eaf]: https://github.com/hiqdev/hipanel-module-server/commit/6571eaf
[b6f1f3f]: https://github.com/hiqdev/hipanel-module-server/commit/b6f1f3f
[959b561]: https://github.com/hiqdev/hipanel-module-server/commit/959b561
[591d052]: https://github.com/hiqdev/hipanel-module-server/commit/591d052
[24c1746]: https://github.com/hiqdev/hipanel-module-server/commit/24c1746
[b7a3eab]: https://github.com/hiqdev/hipanel-module-server/commit/b7a3eab
[5441adf]: https://github.com/hiqdev/hipanel-module-server/commit/5441adf
[7395420]: https://github.com/hiqdev/hipanel-module-server/commit/7395420
[e2dfc5c]: https://github.com/hiqdev/hipanel-module-server/commit/e2dfc5c
[95954cf]: https://github.com/hiqdev/hipanel-module-server/commit/95954cf
[81b2791]: https://github.com/hiqdev/hipanel-module-server/commit/81b2791
[b862dd3]: https://github.com/hiqdev/hipanel-module-server/commit/b862dd3
[0bee983]: https://github.com/hiqdev/hipanel-module-server/commit/0bee983
[e7e2814]: https://github.com/hiqdev/hipanel-module-server/commit/e7e2814
[e93ba24]: https://github.com/hiqdev/hipanel-module-server/commit/e93ba24
[78033eb]: https://github.com/hiqdev/hipanel-module-server/commit/78033eb
[9114d2c]: https://github.com/hiqdev/hipanel-module-server/commit/9114d2c
[874b510]: https://github.com/hiqdev/hipanel-module-server/commit/874b510
[b90ae2b]: https://github.com/hiqdev/hipanel-module-server/commit/b90ae2b
[3026ced]: https://github.com/hiqdev/hipanel-module-server/commit/3026ced
[b7ce290]: https://github.com/hiqdev/hipanel-module-server/commit/b7ce290
[182576b]: https://github.com/hiqdev/hipanel-module-server/commit/182576b
[128e2e3]: https://github.com/hiqdev/hipanel-module-server/commit/128e2e3
[2e34a1e]: https://github.com/hiqdev/hipanel-module-server/commit/2e34a1e
[b9e2f31]: https://github.com/hiqdev/hipanel-module-server/commit/b9e2f31
[f70da47]: https://github.com/hiqdev/hipanel-module-server/commit/f70da47
[142faa7]: https://github.com/hiqdev/hipanel-module-server/commit/142faa7
[f402c75]: https://github.com/hiqdev/hipanel-module-server/commit/f402c75
[ef7d13c]: https://github.com/hiqdev/hipanel-module-server/commit/ef7d13c
[35fad20]: https://github.com/hiqdev/hipanel-module-server/commit/35fad20
[51ae53e]: https://github.com/hiqdev/hipanel-module-server/commit/51ae53e
[5ede957]: https://github.com/hiqdev/hipanel-module-server/commit/5ede957
[d5d1c83]: https://github.com/hiqdev/hipanel-module-server/commit/d5d1c83
[8b2cd43]: https://github.com/hiqdev/hipanel-module-server/commit/8b2cd43
[fbb2e2f]: https://github.com/hiqdev/hipanel-module-server/commit/fbb2e2f
[690abaf]: https://github.com/hiqdev/hipanel-module-server/commit/690abaf
[536db32]: https://github.com/hiqdev/hipanel-module-server/commit/536db32
[af4be85]: https://github.com/hiqdev/hipanel-module-server/commit/af4be85
[abed572]: https://github.com/hiqdev/hipanel-module-server/commit/abed572
[567629b]: https://github.com/hiqdev/hipanel-module-server/commit/567629b
[8849f1a]: https://github.com/hiqdev/hipanel-module-server/commit/8849f1a
[79ae680]: https://github.com/hiqdev/hipanel-module-server/commit/79ae680
[58aba06]: https://github.com/hiqdev/hipanel-module-server/commit/58aba06
[9f6faef]: https://github.com/hiqdev/hipanel-module-server/commit/9f6faef
[315b4bb]: https://github.com/hiqdev/hipanel-module-server/commit/315b4bb
[eea8580]: https://github.com/hiqdev/hipanel-module-server/commit/eea8580
[57a2396]: https://github.com/hiqdev/hipanel-module-server/commit/57a2396
[e11d3b2]: https://github.com/hiqdev/hipanel-module-server/commit/e11d3b2
[ea6653b]: https://github.com/hiqdev/hipanel-module-server/commit/ea6653b
[390e01b]: https://github.com/hiqdev/hipanel-module-server/commit/390e01b
[1d671f5]: https://github.com/hiqdev/hipanel-module-server/commit/1d671f5
[72b2e16]: https://github.com/hiqdev/hipanel-module-server/commit/72b2e16
[928df81]: https://github.com/hiqdev/hipanel-module-server/commit/928df81
[45671db]: https://github.com/hiqdev/hipanel-module-server/commit/45671db
[08dfd6f]: https://github.com/hiqdev/hipanel-module-server/commit/08dfd6f
[5ac185b]: https://github.com/hiqdev/hipanel-module-server/commit/5ac185b
[0c00068]: https://github.com/hiqdev/hipanel-module-server/commit/0c00068
[50ac7a6]: https://github.com/hiqdev/hipanel-module-server/commit/50ac7a6
[b138ec6]: https://github.com/hiqdev/hipanel-module-server/commit/b138ec6
[0cb54d6]: https://github.com/hiqdev/hipanel-module-server/commit/0cb54d6
[3ef01ab]: https://github.com/hiqdev/hipanel-module-server/commit/3ef01ab
[d748993]: https://github.com/hiqdev/hipanel-module-server/commit/d748993
[0aa79b3]: https://github.com/hiqdev/hipanel-module-server/commit/0aa79b3
[5819399]: https://github.com/hiqdev/hipanel-module-server/commit/5819399
[f2c9e6f]: https://github.com/hiqdev/hipanel-module-server/commit/f2c9e6f
[62d54f7]: https://github.com/hiqdev/hipanel-module-server/commit/62d54f7
[1711498]: https://github.com/hiqdev/hipanel-module-server/commit/1711498
[f30cf29]: https://github.com/hiqdev/hipanel-module-server/commit/f30cf29
[63673b1]: https://github.com/hiqdev/hipanel-module-server/commit/63673b1
[e500a08]: https://github.com/hiqdev/hipanel-module-server/commit/e500a08
[d64e0b4]: https://github.com/hiqdev/hipanel-module-server/commit/d64e0b4
[5724eb1]: https://github.com/hiqdev/hipanel-module-server/commit/5724eb1
[effd5a3]: https://github.com/hiqdev/hipanel-module-server/commit/effd5a3
[0d68293]: https://github.com/hiqdev/hipanel-module-server/commit/0d68293
[dee7fca]: https://github.com/hiqdev/hipanel-module-server/commit/dee7fca
[117ffbd]: https://github.com/hiqdev/hipanel-module-server/commit/117ffbd
[8239571]: https://github.com/hiqdev/hipanel-module-server/commit/8239571
[fac6601]: https://github.com/hiqdev/hipanel-module-server/commit/fac6601
[9002d18]: https://github.com/hiqdev/hipanel-module-server/commit/9002d18
[c5a5f5b]: https://github.com/hiqdev/hipanel-module-server/commit/c5a5f5b
[Under development]: https://github.com/hiqdev/hipanel-module-server/releases
[Under]: https://github.com/hiqdev/hipanel-module-server/releases/tag/Under
