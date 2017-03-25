<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

/**
 * @see    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use Yii;

/**
 * @property string osimage
 * @property string os
 * @property string version
 * @property string bitwise
 * @property string panel
 * @property array softpack
 */
class Osimage extends \hiqdev\hiart\ActiveRecord
{
    const NO_PANEL = 'no';

    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return ['osimage', 'os', 'version', 'bitwise', 'panel', 'softpack'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [[['osimage'], 'required']];
    }

    /**
     * @param string $delimiter defines delimiter to separate os, version and bitwise of OS
     * @return string
     */
    public function getFullOsName($delimiter = ' ')
    {
        return implode($delimiter, [$this->os, $this->version, $this->bitwise]);
    }

    public function getSoftPackName()
    {
        return $this->hasSoftPack() ? $this->softpack['name'] : 'clear';
    }

    public function getDisplaySoftPackName()
    {
        return Yii::t('hipanel:server:os', $this->getSoftPackName());
    }

    public function hasSoftPack()
    {
        return !empty($this->softpack);
    }

    public function getPanelName()
    {
        return $this->panel ?: static::NO_PANEL;
    }

    public function getSoftPack()
    {
        return $this->hasSoftPack() ? $this->softpack : [];
    }

    public function getDisplayPanelName()
    {
        $panel = $this->getPanelName();
        if ($panel === static::NO_PANEL) {
            $panel = 'No panel';
        }
        return Yii::t('hipanel:server:panel', $panel);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'osimagae' => Yii::t('hipanel:server:os', 'System name of image'),
            'os' => Yii::t('hipanel:server:os', 'OS'),
            'softpack' => Yii::t('hipanel:server:os', 'Soft package'),
            'panel' => Yii::t('hipanel:server:os', 'Panel'),
        ];
    }
}
