<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

class Rrd extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['graphs'], 'safe'],
        ];
    }

    public function getServer()
    {
        return $this->hasOne(Server::class, ['id' => 'id']);
    }

    public function getImages()
    {
        return $this->hasMany(RrdImage::class, ['id' => 'id']);
    }
}
