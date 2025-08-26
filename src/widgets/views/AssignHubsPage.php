<?php

use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\widgets\AssignHubsPage;
use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\ApplyToAllWidget;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\View;

/**
 * @var View $this
 * @var AssignHubsForm[] $models
 * @var AssignHubsForm $model
 * @var AssignHubsPage $context
 * @var ActiveForm $form
 * @var AssignHubsPage $context
 */

$renderedAttributes = [];
$context = $this->context;
$this->registerCss(
    <<<CSS
.item li > div {
    display: flex;
    gap: 15px;
    & > div {
      flex: 1 1 0;
      min-width: 0;
    }
}
.item h5 {
  padding-left: 45px;
  padding-bottom: none;
  font-weight: bold;
  margin-bottom: 0;
  margin-top: 0;
}
.item .row > div:last-child .row > div:has(input):not(:has(label)) {
  padding-top: 25px;
}
CSS
);

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 999, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => Inflector::camel2id(reset($models)->formName()) . '-form',
    'formFields' => $context->getFormFields(),
]) ?>

<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <div class="item">
            <?= Html::activeHiddenInput($model, "[$i]id") ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title" style="display: flex; justify-content: space-between; align-items: center;">
                        <span><?= Html::a($model->name, ['view', 'id' => $model->id], ['target' => '_blank']) ?></span>
                        <span class="badge bg-red"><?= mb_strtoupper($model->type) ?></span>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php [$nets, $pdus, $other] = $context->splitIntoGroups($model->getHubVariants()) ?>
                        <div class="col-md-4" style="<?= empty($nets) ? 'display: none' : '' ?>">
                            <h5><?= Yii::t('hipanel:server', 'Switches') ?></h5>
                            <ol>
                            <?php foreach ($nets as $variant) : ?>
                                <?php $renderedAttributes[] = $variant ?>
                                <?php if ($context->hasPort($variant)) : ?>
                                    <li>
                                        <div>
                                            <?= $form->field(
                                                $model,
                                                "[$i]{$variant}_id"
                                            )->widget(
                                                HubCombo::class,
                                                $context->prepareHubComboOptions($variant)
                                            )->label($context->getAttributeLabel($model, $variant)) ?>
                                            <?= $form->field($model, "[$i]{$variant}_port")
                                                     ->textInput(['placeholder' => 'Port'])
                                                     ->label($context->getAttributeLabel($model, $variant . '_port')) ?>
                                        </div>
                                    </li>
                                <?php else : ?>
                                    <li>
                                        <div>
                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                        </div>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                            </ol>
                        </div>
                        <div class="col-md-4" style="<?= empty($pdus) ? 'display: none' : '' ?>">
                            <h5><?= Yii::t('hipanel:server', 'APCs') ?></h5>
                            <ol>
                            <?php foreach ($pdus as $variant) : ?>
                                <?php $renderedAttributes[] = $variant ?>
                                <?php if ($context->hasPort($variant)) : ?>
                                    <li>
                                        <div>
                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                            <?= $form->field($model, "[$i]{$variant}_port")
                                                     ->textInput(['placeholder' => 'Port'])
                                                     ->label($context->getAttributeLabel($model, $variant . '_port')) ?>
                                        </div>
                                    </li>
                                <?php else : ?>
                                    <li>
                                        <div>
                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                        </div>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                            </ol>
                        </div>
                        <div class="col-md-4">
                            <?php foreach ($other as $variant) : ?>
                                <div class="row">
                                <?php $renderedAttributes[] = $variant ?>
                                    <?php if ($context->hasPort($variant)) : ?>
                                        <div class="col-md-6">
                                        <?= $form->field($model, "[$i]{$variant}_id")
                                                 ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                 ->label($context->getAttributeLabel($model, $variant)) ?>
                                    </div>
                                        <div class="col-md-6">
                                        <?= $form->field($model, "[$i]{$variant}_port")
                                                 ->textInput(['placeholder' => 'Port'])
                                                 ->label($context->getAttributeLabel($model, $variant . '_port')) ?>
                                    </div>
                                    <?php else : ?>
                                        <div class="col-md-12">
                                        <?= $form->field($model, "[$i]{$variant}_id")
                                                 ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                 ->label($context->getAttributeLabel($model, $variant)) ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?php DynamicFormWidget::end() ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(
            Yii::t('hipanel', 'Cancel'),
            ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']
        ) ?>
    </div>
</div>

<?= ApplyToAllWidget::widget([
    'models' => $models,
    'attributes' => array_values(array_unique($renderedAttributes)),
]) ?>

<?php $this->registerJs(
    <<<JS
    // javascript
    (function () {
        'use strict';

        // Titles of columns to process (matching h5 textContent)
        var COLUMN_TITLES = ['Switches', 'APCs'];

        // Find .col-md-4 columns that contain an h5 with a target title
        function findTargetColumns() {
            var cols = Array.prototype.slice.call(document.querySelectorAll('.col-md-4'));
            return cols.filter(function (col) {
                var h5 = col.querySelector('h5');
                return h5 && COLUMN_TITLES.indexOf(h5.textContent.trim()) !== -1;
            });
        }

        // Determine if an <li> is "empty": all inputs/selects/textareas inside are empty / unchecked
        function isListItemEmpty(li) {
            var fields = li.querySelectorAll('input, select, textarea');
            if (!fields || fields.length === 0) {
                // If there are no fields inside - treat as non-empty to avoid accidental removal
                return false;
            }
            for (var i = 0; i < fields.length; i++) {
                var f = fields[i];
                var tag = f.tagName.toLowerCase();
                var type = f.type ? f.type.toLowerCase() : null;
                if (tag === 'select') {
                    if (String(f.value).trim() !== '') return false;
                } else if (tag === 'input' || tag === 'textarea') {
                    if (type === 'checkbox' || type === 'radio') {
                        if (f.checked) return false;
                    } else {
                        if (String(f.value).trim() !== '') return false;
                    }
                } else {
                    if (String(f.value).trim() !== '') return false;
                }
            }
            return true;
        }

        // Create reveal button that will open hidden pairs one by one
        function createRevealButton(hiddenCount) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-default btn-sm assign-hubs-reveal';
            btn.style.marginRight = '8px';
            btn.textContent = hiddenCount > 1 ? 'Показать ещё (' + hiddenCount + ')' : 'Показать';
            btn.setAttribute('aria-expanded', 'false');
            return btn;
        }

        // Create remove button that will delete the last empty hidden pair
        function createRemoveButton() {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-danger btn-sm assign-hubs-remove';
            btn.style.marginRight = '8px';
            btn.textContent = 'Удалить пустую';
            return btn;
        }

        // Recompute current hidden empty <li> elements inside ol
        function getCurrentHiddenItems(ol) {
            var lis = Array.prototype.slice.call(ol.querySelectorAll('li'));
            return lis.filter(function (li) {
                // consider item hidden if computed style display is 'none' OR inline style display === 'none'
                var cs = window.getComputedStyle(li);
                var hiddenByDisplay = (li.style && li.style.display === 'none') || cs.display === 'none';
                return hiddenByDisplay && isListItemEmpty(li);
            });
        }

        // Update buttons state and text based on current hidden items (recompute from DOM)
        function updateButtonsState(col, revealBtn, removeBtn) {
            var ol = col.querySelector('ol');
            if (!ol) return;
            var curHidden = getCurrentHiddenItems(ol);
            if (revealBtn) {
                if (curHidden.length > 0) {
                    revealBtn.textContent = curHidden.length > 1 ? 'Показать ещё (' + curHidden.length + ')' : 'Показать';
                    revealBtn.disabled = false;
                } else {
                    if (revealBtn.parentNode) revealBtn.parentNode.removeChild(revealBtn);
                    revealBtn = null;
                }
            }
            if (removeBtn) {
                if (curHidden.length > 0) {
                    removeBtn.disabled = false;
                } else {
                    if (removeBtn.parentNode) removeBtn.parentNode.removeChild(removeBtn);
                    removeBtn = null;
                }
            }
        }

        // Initialize a single column: hide empty list items, add buttons and wire handlers
        function initColumn(col) {
            var h5 = col.querySelector('h5');
            if (!h5) return;

            var ol = col.querySelector('ol');
            if (!ol) return;
            var listItems = Array.prototype.slice.call(ol.querySelectorAll('li'));
            if (!listItems.length) return;

            // Initially hide empty items
            listItems.forEach(function (li) {
                if (isListItemEmpty(li)) {
                    li.style.display = 'none';
                    var inputs = li.querySelectorAll('input');
                    inputs.forEach(function(input) {
                        input.disabled = true; 
                    });
                }
            });

            var currentHidden = getCurrentHiddenItems(ol);
            if (currentHidden.length === 0) return;

            // Create button wrapper and buttons
            var btnWrap = document.createElement('div');
            btnWrap.style.marginTop = '8px';
            btnWrap.style.marginBottom = '10px';
            btnWrap.style.marginLeft = '40px';

            var revealBtn = createRevealButton(currentHidden.length);
            var removeBtn = createRemoveButton();

            btnWrap.appendChild(revealBtn);
            btnWrap.appendChild(removeBtn);

            // Insert button wrapper right after h5
            if (h5.nextSibling) {
                h5.parentNode.insertBefore(btnWrap, h5.nextSibling);
            } else {
                h5.parentNode.appendChild(btnWrap);
            }

            // Reveal handler: reveal the first hidden item (DOM order)
            revealBtn.addEventListener('click', function () {
                var curHidden = getCurrentHiddenItems(ol);
                if (curHidden.length === 0) {
                    updateButtonsState(col, revealBtn, removeBtn);
                    return;
                }
                var toShow = curHidden[0];
                // remove inline display none
                if (toShow.style) toShow.style.display = '';
                else toShow.removeAttribute('style');
                const inputs = toShow.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.disabled = false; 
                });
                updateButtonsState(col, revealBtn, removeBtn);
            });

            // Remove handler: remove the last hidden item (DOM order)
            removeBtn.addEventListener('click', function () {
                var curHidden = getCurrentHiddenItems(ol);
                if (curHidden.length === 0) {
                    updateButtonsState(col, revealBtn, removeBtn);
                    return;
                }
                var toRemove = curHidden[curHidden.length - 1];
                if (toRemove && toRemove.parentNode) {
                    toRemove.parentNode.removeChild(toRemove);
                }
                // After direct removal, update buttons
                updateButtonsState(col, revealBtn, removeBtn);
            });

            // Observe DOM mutations to keep state synced (li added/removed or style/value changes)
            try {
                var observer = new MutationObserver(function () {
                    updateButtonsState(col, revealBtn, removeBtn);
                });
                observer.observe(ol, { attributes: true, childList: true, subtree: true, attributeFilter: ['style', 'value', 'selected'] });
            } catch (e) {
                // MutationObserver not available: still works without live updates
            }
        }

        // Initialize all target columns on page
        // function init() {
        //     var cols = findTargetColumns();
        //     cols.forEach(function (col) {
        //         try {
        //             initColumn(col);
        //         } catch (e) {
        //             // swallow per-column exceptions
        //         }
        //     });
        // }
        //
        // // Run after DOM ready
        // if (document.readyState === 'loading') {
        //     document.addEventListener('DOMContentLoaded', init);
        // } else {
        //     init();
        // }
    })();
    JS
) ?>
