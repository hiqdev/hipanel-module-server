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

class ServerSearch extends Server
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'with_requests',
            'show_deleted',
            'with_discounts',
            'wizzarded_eq',
            'name_inilike',
            'name_dc',
            'primary_only',
            'type_of_sale',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'wizzarded_eq' => Yii::t('hipanel:server', 'Is wizzarded'),
            'name_dc' => Yii::t('hipanel:server', 'Name or DC'),
        ]);
    }
}
