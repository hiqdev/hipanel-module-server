<?php
use yii\helpers\Html;

/**
 * @var \hipanel\modules\server\models\Package[] $packages
 */
$info = '<i class="fa fa-info-circle" aria-hidden="true" style="color: #3E65BF;"></i>';
$this->registerJs("
$('[data-toggle=\"popover\"]').popover();
");
?>
<div class="box">

    <div class="box-body no-padding">
        <div class="pricingbox vps-comparison">
            <div class="row spacing-25">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <?php foreach ($packages as $package) : ?>
                                    <?= Html::tag('th', $package->name) ?>
                                <?php endforeach ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php
                                echo Html::tag('td', $packages[0]->getResourceByModelType('cpu')->decorator()->displayTitle());

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByModelType('cpu')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>
                            <tr>
                                <?php
                                $title = $packages[0]->getResourceByModelType('ram')->decorator()->displayTitle();
                                $overuse = Html::tag('span', $info, ['class' => 'pull-right', 'data' => [
                                    'trigger' => 'hover',
                                    'toggle' => 'popover',
                                    'content' => Yii::t('hipanel:server:order', 'Overuse {price}/{unit}', [
                                        'price' => $packages[0]->getResourceByModelType('ram')->decorator()->displayOverusePrice(),
                                        'unit' => $packages[0]->getResourceByModelType('ram')->decorator()->displayUnit(),
                                    ]),
                                ]]);
                                echo Html::tag('td', $title . $overuse);

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByModelType('ram')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>
                            <tr>
                                <?php
                                $title = $packages[0]->getResourceByModelType('hdd')->decorator()->displayTitle();
                                $overuse = Html::tag('span', $info, ['class' => 'pull-right', 'data' => [
                                    'trigger' => 'hover',
                                    'toggle' => 'popover',
                                    'content' => Yii::t('hipanel:server:order', 'Overuse {price}/{unit}', [
                                        'price' => $packages[0]->getResourceByModelType('hdd')->decorator()->displayOverusePrice(),
                                        'unit' => $packages[0]->getResourceByModelType('hdd')->decorator()->displayUnit(),
                                    ]),
                                ]]);
                                echo Html::tag('td', $title . $overuse);

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByModelType('hdd')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>

                            <tr>
                                <?php
                                $title = $packages[0]->getResourceByType('ip_num')->decorator()->displayTitle();
                                $overuse = Html::tag('span', $info, ['class' => 'pull-right', 'data' => [
                                    'trigger' => 'hover',
                                    'toggle' => 'popover',
                                    'content' => Yii::t('hipanel:server:order', 'Overuse {price}/{unit}', [
                                        'price' => $packages[0]->getResourceByType('ip_num')->decorator()->displayOverusePrice(),
                                        'unit' => $packages[0]->getResourceByType('ip_num')->decorator()->displayUnit(),
                                    ]),
                                ]]);
                                echo Html::tag('td', $title . $overuse);

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByType('ip_num')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>
                            <tr>
                                <?php
                                echo Html::tag('td', $packages[0]->getResourceByType('support_time')->decorator()->displayTitle());

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByType('support_time')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>
                            <tr>
                                <?php
                                $title = $packages[0]->getResourceByType('server_traf_max')->decorator()->displayTitle();
                                $overuse = Html::tag('span', $info, ['class' => 'pull-right', 'data' => [
                                    'trigger' => 'hover',
                                    'toggle' => 'popover',
                                    'content' => Yii::t('hipanel:server:order', 'Overuse {price}/{unit}', [
                                        'price' => $packages[0]->getResourceByType('server_traf_max')->decorator()->displayOverusePrice(),
                                        'unit' => $packages[0]->getResourceByType('server_traf_max')->decorator()->displayUnit(),
                                    ]),
                                ]]);
                                echo Html::tag('td', $title . $overuse);

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByType('server_traf_max')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>

                            <tr>
                                <?php
                                echo Html::tag('td', $packages[0]->getResourceByType('speed')->decorator()->displayTitle());

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByType('speed')->decorator()->displayPrepaidAmount());
                                } ?>
                            </tr>

                            <tr>
                                <?php
                                echo Html::tag('td', $packages[0]->getResourceByType('panel')->decorator()->displayTitle());

                                foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getResourceByType('panel')->decorator()->displayPrepaidAmount(), ['style' => 'font-size: smaller;']);
                                } ?>
                            </tr>
                            <tr>
                                <?php
                                echo Html::tag('td', Yii::t('hipanel:server:order', 'Purpose'));

                                foreach ($packages as $package) {
                                    echo Html::tag('td', Yii::t('hipanel:server:order:purpose', $package->getTariff()->label), ['style' => 'font-size: smaller;']);
                                } ?>
                            </tr>

                            <tr class="price-comparison">
                                <td><?= Yii::t('hipanel:server:order', 'Price') ?></td>
                                <?php foreach ($packages as $package) {
                                    echo Html::tag('td', $package->getDisplayPrice());
                                } ?>
                            </tr>

                            <tr>
                                <td></td>
                                <?php foreach ($packages as $package) {
                                    $button = Html::a(Yii::t('hipanel:server:order', 'ORDER NOW'), [
                                        'order',
                                        'id' => $package->tariff->id,
                                    ], ['class' => 'btn bg-olive']);
                                    echo Html::tag('td', $button);
                                } ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
