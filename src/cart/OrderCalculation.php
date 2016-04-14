<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use Yii;

class OrderCalculation extends \hipanel\modules\finance\models\Calculation
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        if (Yii::$app->user->getIsGuest()) {
            $this->seller = Yii::$app->params['seller'];
        } else {
            $this->seller = Yii::$app->user->identity->seller;
            $this->client = Yii::$app->user->identity->login;
        }

        $this->object = 'server';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['tariff_id'], 'integer'],
        ]);
    }
}
