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

use yii\bootstrap\Widget;
use Yii;

class MonitoringSettingsForm extends Widget
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $validationUrl;

    /**
     * @var string
     */
    public $breadcrumbsLabel;

    /**
     * @var Server|Hub
     */
    public $model;

    /**
     * @var array
     */
    public $nicMediaOptions;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('MonitoringSettingsForm', [
            'label' => $this->breadcrumbsLabel,
            'model' => $this->model,
            'title' => $this->title ?? Yii::t('hipanel:server', 'Monitoring properties'),
            'nicMediaOptions' => $this->nicMediaOptions,
        ]);
    }

    /**
     * @return string
     */
    private function getActionUrl(): string
    {
        return $this->actionUrl;
    }

    /**
     * @return string
     */
    private function getValidationUrl(): string
    {
        return $this->validationUrl;
    }
}
