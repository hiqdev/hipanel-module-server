<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

class State extends \hipanel\widgets\Type
{
    /** {@inheritdoc} */
    public $model = [];
    public $values = [];
    public $defaultValues = [
        'info'      => ['ok', 'active', 'disabled'],
        'danger'    => ['expired'],
        'warning'   => ['blocked'],
    ];
    public $field = 'state';
    public $i18nDictionary = 'hipanel:server';
}
