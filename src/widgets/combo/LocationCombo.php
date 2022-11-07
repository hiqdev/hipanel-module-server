<?php
declare(strict_types=1);

namespace hipanel\modules\server\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

class LocationCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'server/location';

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
            'type_in'  => ['format' => 'switch,location,region'],
            'limit' => ['format' => '50'],
        ]);
    }
}
