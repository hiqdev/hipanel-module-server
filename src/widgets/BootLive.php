<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\widgets\ModalButton;
use hipanel\widgets\SimpleOperation;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class BootLive extends SimpleOperation
{
    /**
     * @var string
     */
    public $scenario = 'boot-live';

    public $osimageslivecd;

    protected $osItems;

    public function init()
    {
        $this->scenario = 'boot-live';

        parent::init();

        if ($this->model === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        $this->buttonLabel = Yii::t('hipanel:server', 'Boot LiveCD');
        $this->buttonPosition = ModalButton::BUTTON_IN_MODAL;
        $this->buttonVisible = $this->model->isLiveCDSupported();
        $this->modalHeaderLabel = Yii::t('hipanel:server', 'Confirm booting from Live CD');
        $this->modalHeaderOptions = ['class' => 'label-info'];
        $this->modalFooter = \yii\bootstrap\ButtonDropdown::widget([
            'label' => Yii::t('hipanel:server', 'Boot LiveCD'),
            'dropdown' => [
                'items' => $this->generatOSItemList(),
            ],
            'options' => [
                'class' => 'btn btn-info',
                'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin"></i> ' . Yii::t('hipanel','loading'),
            ],
        ]);
        $this->body = Html::hiddenInput('osimage', null, ['class' => 'livecd-osimage']) .
            Yii::t('hipanel:server', 'This action will shutdown the server and boot live cd image');
    }

    protected function generatOSItemList()
    {
        foreach ($this->osimageslivecd as $item) {
            $js = "$(this).closest('form').find('.livecd-osimage').val('{$item['osimage']}').end().submit(); $(this).closest('button').button('loading');";
            $osItems[] = [
                'label' => $item['os'] . ' ' . $item['bitwise'],
                'url' => '#',
                'options' => [
                    'onclick' => new \yii\web\JsExpression($js),
                ],
            ];
        }

        return $osItems;
    }
}
