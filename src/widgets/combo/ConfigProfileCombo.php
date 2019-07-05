<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets\combo;

use hiqdev\combo\Combo;
use yii\helpers\ArrayHelper;

class ConfigProfileCombo extends Combo
{
    /** {@inheritdoc} */
    public $name = 'profiles';

    /** {@inheritdoc} */
    public $type = 'config/profile';

    /** {@inheritdoc} */
    public $url = '/server/config-profile/search';

    /** {@inheritdoc} */
    public $_return = ['id', 'name', 'type'];

    /** {@inheritdoc} */
    public $primaryFilter = 'name_like';

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'type' => ['format' => 'config'],
            'limit' => ['format' => '50'],
        ]);
    }
}
