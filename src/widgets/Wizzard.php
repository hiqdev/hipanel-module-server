<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use hipanel\widgets\ModalButton;
use hipanel\helpers\ArrayHelper;
use yii\bootstrap\Modal;

class Wizzard extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /** @var bool */
    public $wizzarded = null;

    /**
     * @var array|Modal stores options for [[ModalButton]]
     * After Modal creating, stores the object.
     */
    protected $modal;

    public function init()
    {
        parent::init();
        if ($this->wizzarded === null) {
            $this->wizzarded = $this->model->wizzarded;
        }
    }

    public function run()
    {
        $this->modalBegin();
        echo $this->wizzarded ? $this->renderUnWizzardForm() : $this->renderWizzardForm();
        $this->modalEnd();
    }

    protected function modalBegin()
    {
        $this->modal = Yii::createObject([
            'class' => ModalButton::class,
            'model' => $this->model,
            'scenario' => $this->wizzarded ? 'disable-wizzard' : 'enable-wizzard',
            'button' => [
                'label' => $this->wizzarded ? Yii::t('hipanel:server', 'UnWizzard') : Yii::t('hipanel:server', 'Wizzard'),
                'class' => 'btn btn-default btn-block',
                'disabled' => !$this->model->isOperable(),
            ],
            'form' => [
                'enableAjaxValidation' => false,
            ],
            'modal' => [
                'size' => Modal::SIZE_LARGE,
                'header' => Html::tag('h4', $this->wizzarded ? Yii::t('hipanel:server', 'Confirm server unwizzard') : Yii::t('hipanel:server', 'Confirm server wizzard')),
                'headerOptions' =>  ['class' => 'label-warning'],
                'footer' => [
                    'label' => $this->wizzarded ? Yii::t('hipanel:server', 'UnWizzard') : Yii::t('hipanel:server', 'Wizzard'),
                    'data-loading-text' => $this->wizzarded ? Yii::t('hipanel:server', 'UnWizzarding...') : Yii::t('hipanel:server', 'Wizzarding...'),
                    'class' => $this->wizzarded ? 'btn btn-warning' : 'btn btn-success',
                ],
            ],
        ]);
    }

    protected function renderUnWizzardForm()
    {
        return Html::tag('span', Yii::t('hipanel', 'Are you sure?'), ['class' => 'text-danger']);
    }

    protected function renderWizzardForm()
    {
        return $this->render('enable-wizzard', [
            'model' => $this->model,
            'form' => $this->modal->form,
        ]);
    }

    protected function modalEnd()
    {
        $this->modal->run();
    }


}
