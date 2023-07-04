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

use DateTime;
use hipanel\modules\server\forms\HubSellForm;
use hipanel\modules\server\helpers\ServerSort;
use hipanel\modules\server\models\Server;
use yii\bootstrap\Widget;

class SellForm extends Widget
{
    /**
     * @var string
     */
    public $actionUrl;

    /**
     * @var string
     */
    public $validationUrl;

    /**
     * @var Server[]|HubSellForm[]
     */
    private $models = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $models = $this->getModels();
        return $this->render('SellForm', [
            'defaultDateTime' => $this->getDefaultDateTime(),
            'model' => \reset($models),
            'models' => $this->getModels(),
            'actionUrl' => $this->getActionUrl(),
            'validationUrl' => $this->getValidationUrl(),
        ]);
    }

    /**
     * @return array
     */
    public function getModels(): array
    {
        if ($this->isServer()) {
            return ServerSort::byServerName()->values($this->models);
        }

        return $this->models;
    }

    /**
     * @param array $models
     */
    public function setModels(array $models): void
    {
        $this->models = $models;
    }

    /**
     * @return bool
     */
    public function isServer(): bool
    {
        return \count($this->models) > 0 && \reset($this->models) instanceof Server;
    }

    /**
     * @throws \Exception
     * @return DateTime
     */
    private function getDefaultDateTime(): DateTime
    {
        return new DateTime('first day of this month 00:00');
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
