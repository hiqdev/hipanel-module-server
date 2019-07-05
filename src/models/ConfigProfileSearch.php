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

class ConfigProfileSearch extends ConfigProfile
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    /** {@inheritdoc} */
    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'name_like',
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name_like' => Yii::t('hipanel:server', 'Name'),
        ]);
    }
}
