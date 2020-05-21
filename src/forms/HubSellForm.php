<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\forms;

use hipanel\modules\server\models\Hub;

class HubSellForm extends Hub
{
    use \hipanel\base\ModelTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hub';
    }

    /**
     * Create HubSellForm model from Hub model.
     *
     * @param Hub $hub
     * @return HubSellForm
     */
    public static function fromHub(Hub $hub): HubSellForm
    {
        return new self(array_merge($hub->getAttributes(), ['scenario' => 'sell']));
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['tariff_id', 'sale_time'], 'required'],
            [['id', 'client_id', 'tariff_id'], 'integer', 'on' => 'sell'],
            [['sale_time'], 'date', 'format'  => 'php:Y-m-d H:i:s', 'on' => 'sell'],
            [['move_accounts'], 'boolean', 'on' => ['sell']],
        ]);
    }
}
