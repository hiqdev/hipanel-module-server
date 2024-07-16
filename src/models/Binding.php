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

/**
 * Class Binding.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Binding extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['device_id', 'switch_id', 'no', 'base_device_id', 'obj_id'], 'integer'],
            [
                [
                    'switch_name', 'device_name', 'base_device_name', 'port', 'type', 'switch', 'switch_label',
                    'switch_inn', 'device_ip', 'switch_ip', 'web_iface_only',
                ],
                'string',
            ],
        ];
    }

    public function getTypeWithNo()
    {
        return $this->type . $this->no;
    }
}
