<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

class Binding extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['device_id', 'switch_id'], 'integer'],
            [['port', 'type', 'switch', 'switch_label', 'switch_inn', 'device_ip', 'switch_ip', 'web_iface_only'], 'string'],
        ];
    }
}
