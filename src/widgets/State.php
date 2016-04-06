<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\widgets;

class State extends \hipanel\widgets\Type
{
    /** {@inheritdoc} */
    public $model = [];
    public $values = [];
    public $defaultValues = [
        'info'      => ['ok'],
        'danger'    => ['expired'],
        'warning'   => ['blocked'],
    ];
    public $field = 'state';
}
