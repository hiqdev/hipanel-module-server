<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\JsExpression;

class VNCOperation extends Widget
{
    protected $vncEnd;
    protected $vncEnabled;
    public $model;

    public function init()
    {
        parent::init();
        if ($this->model === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        $this->vncEnabled = $this->model->vnc['enabled'];

        $this->vncEnd = 0;
        if (is_array($this->model->statuses) && isset($this->model->statuses['serverEnableVNC'])) {
            $this->vncEnd = strtotime('+8 hours', strtotime($this->model->statuses['serverEnableVNC']));
        }
        if (isset($this->model->vnc['endTime'])) {
            $this->vncEnd = $this->vncEnd < time() ? ($this->model->vnc['endTime'] > time() ? $this->model->vnc['endTime'] : $this->vncEnd) : $this->vncEnd;
        }
    }

    public function run()
    {
        if ($this->model->isVNCSupported()) {
            if ($this->vncEnabled === false) {
                echo $this->renderEnableButton();
            } else {
                echo $this->renderToggleButton();
            }
        }
    }

    protected function renderEnableButton()
    {
        $form = Html::beginForm(['enable-vnc', 'id' => $this->model->id], 'POST', ['data' => ['pjax' => 1], 'class' => 'inline']);
        $form .= Html::submitButton(
            Yii::t('hipanel:server', 'Enable'),
            [
                'class' => 'btn btn-success btn-block',
                'data-loading-text' => Yii::t('hipanel:server', 'Enabling...'),
                'onClick' => new JsExpression("$(this).closest('form').submit(); $(this).button('loading')"),
                'disabled' => !$this->model->canEnableVnc(),
            ]
        );

        return $form . Html::endForm();
    }

    protected function renderToggleButton()
    {
        $data = Html::button(
            Yii::t('hipanel', 'Show'),
            [
                'class' => 'btn btn-success btn-block',
                'onClick' => new JsExpression("
                    if ($('#vnc-access-data').hasClass('hidden')) {
                        $(this).text('" . Yii::t('hipanel', 'Hide') . "');
                        $('#vnc-access-data').removeClass('hidden');
                    } else {
                        $(this).text('" . Yii::t('hipanel', 'Show') . "');
                        $('#vnc-access-data').addClass('hidden');
                    }
                "),
            ]
        );
        $fields = [
            Yii::t('hipanel:server', 'IP') => $this->model->vnc['vnc_ip'],
            Yii::t('hipanel:server', 'Port') => $this->model->vnc['vnc_port'],
            Yii::t('hipanel:server', 'Password') => $this->model->vnc['vnc_password'],
        ];
        $data .= Html::beginTag('dl', ['class' => 'dl-horizontal hidden', 'id' => 'vnc-access-data']);
        foreach ($fields as $name => $value) {
            $data .= Html::tag('dt', $name);
            $data .= Html::tag('dd', $value);
        }
        $data .= Html::endTag('dl');
        $data .= Yii::t('hipanel:server', 'VNC will be disabled {time}',
                ['time' => Yii::$app->formatter->asRelativeTime($this->model->vnc['endTime'])]);

        return $data;
    }
}
