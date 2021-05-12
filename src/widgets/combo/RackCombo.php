<?php
declare(strict_types=1);

namespace hipanel\modules\server\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

class RackCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'server/rack';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/server/hub/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'type_in'  => ['format' => 'switch,rack'],
            'limit' => ['format' => '50'],
        ]);
    }
}
