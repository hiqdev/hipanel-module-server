<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\MainColumn;

class ServerGridView extends \hipanel\grid\BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'server' => [
                'class'                 => MainColumn::className(),
                'filterAttribute'       => 'server_like',
            ],
        ];
    }
}
