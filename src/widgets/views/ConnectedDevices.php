<?php

use hipanel\widgets\Box;
use yii\helpers\Html;

/**
 * @var string $title
 * @var array<string, array<int, array{port: string, device: string}>> $groups
 */

?>
<?php $box = Box::begin(['renderBody' => false, 'bodyOptions' => ['class' => 'no-padding']]) ?>
<?php $box->beginHeader() ?>
<?= $box->renderTitle($title) ?>
<?php Box::endHeader() ?>
<?php $box->beginBody() ?>
<table class="table table-condensed connected-devices">
    <?php foreach ($groups as $label => $rows) : ?>
        <tbody>
            <tr><th colspan="2"><?= Html::encode($label) ?></th></tr>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?= Html::encode($row['port']) ?></td>
                    <td>&larr; <?= $row['device'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    <?php endforeach ?>
</table>
<?php $box->endBody() ?>
<?php Box::end() ?>
