<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class HubSearch extends Hub
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public static function tableName()
    {
        return 'hub';
    }

    /**
     * {@inheritdoc}
     */
    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'with_bindings',
            'with_servers',
            'name_inilike',
            'rack_ilike',
            'order_no_ilike',
            'state_in',
            'tags',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name_inilike' => Yii::t('hipanel:server', 'Switch'),
            'rack_ilike' => Yii::t('hipanel:server', 'Rack'),
            'order_no_ilike' => Yii::t('hipanel:server:hub', 'Order No.'),
            'state_in' => Yii::t('hipanel:server:hub', 'State'),
        ]);
    }

    public function getStateOptions(): array
    {
        return [
            self::STATE_OK => Yii::t('hipanel:server:hub', 'OK'),
            self::STATE_DELETED => Yii::t('hipanel:server:hub', 'Deleted'),
        ];
    }
}
