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

use hipanel\widgets\ModalButton;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


class SimpleOperation extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $scenario;

    /**
     * @var array. Store options for [[ModalButton]]
     */
    public $configOptions = [];

    /**
     * @var array. Store default options for [[ModalButton]]
     */
    public $defaultOptions = [
        'buttonClass' => 'btn btn-default btn-block',
        'form' => [],
    ];

    /**
     * @var array|Modal stores options for [[ModalButton]]
     * After Modal creating, stores the object.
     */
    protected $modal = [];

    /**
     * @var boolean for ignoring device states
     */
    public $skipCheckOperable = false;

    public function init()
    {
        parent::init();

        if ($this->model === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        if ($this->scenario === null) {
            throw new InvalidConfigException('Please specify the "scenario" property.');
        }

        $this->configOptions = ArrayHelper::merge($this->defaultOptions, $this->configOptions);
    }

    public function run()
    {
        $config = [
            'class' => ModalButton::class,
            'model' => $this->model,
            'scenario' => $this->scenario,
            'button' => [
                'label' => $this->configOptions['buttonLabel'],
                'class' => $this->configOptions['buttonClass'],
                'disabled' => !$this->model->isOperable() && !$this->skipCheckOperable,
            ],
            'body' => $this->configOptions['body'],
            'form' => $this->configOptions['form'],
            'modal' => [
                'header' => Html::tag('h4', $this->configOptions['modalHeaderLabel']),
                'headerOptions' =>  $this->configOptions['modalHeaderOptions'],
                'footer' => [
                    'label' => $this->configOptions['modalFooterLabel'],
                    'data-loading-text' => $this->configOptions['modalFooterLoading'],
                    'class' => $this->configOptions['modalFooterClass'],
                ],
            ],
        ];
        $this->modal = call_user_func([ArrayHelper::remove($config, 'class'), 'begin'], $config);
        $this->modal->end();
    }
}
