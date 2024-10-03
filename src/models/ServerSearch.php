<?php declare(strict_types=1);
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
        rules as defaultSearchRules;
    }

    public static function tableName()
    {
        return Server::tableName();
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
            'hide_nic',
            'hide_vds',
            'rack_inilike',
            'rack_ilike',
            'tags',
            'uses_month',
        ]);
    }

    public function rules()
    {
        $rules = $this->defaultSearchRules();
        $rules[] = [['uses_month'], 'match', 'pattern' => '/^[A-Z][a-z]{2} \d{4}$/'];

        return $rules;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'wizzarded_eq' => Yii::t('hipanel:server', 'Is wizzarded'),
            'name_dc' => Yii::t('hipanel:server', 'Name or DC'),
            'hide_nic' => Yii::t('hipanel:server', 'Hide additional network interfaces'),
            'hide_vds' => Yii::t('hipanel:server', 'Hide VDS'),
            'rack_ilike' => Yii::t('hipanel:server', 'Rack by partial like'),
            'rack_inilike' => Yii::t('hipanel:server', 'List of racks'),
            'uses_month' => Yii::t('hipanel:server', 'Consumption for {0}'),
        ]);
    }
}
