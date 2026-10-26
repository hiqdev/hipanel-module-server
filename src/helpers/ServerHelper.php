<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\helpers;

use hipanel\models\Ref;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\models\ServerUse;
use Yii;
use yii\helpers\ArrayHelper;

class ServerHelper
{
    /**
     * Method groups array of [[ServerUse]] in order to use it in charts.
     * @param ServerUse[] $uses
     * @return array of two items:
     *  0 - labels (like months)
     *  1 - values (value for each month)
     */
    public static function groupUsesForChart(array $uses): array
    {
        $labels = [];
        $data = [];
        ArrayHelper::multisort($uses, 'date');
        foreach ($uses as $use) {
            /** @var ServerUse $use */
            $labels[$use->date] = $use->getDisplayDate();
            $data[$use->type] = [];
        }
        foreach (array_keys($labels) as $date) {
            foreach (array_keys($data) as $type) {
                $found = array_filter($uses, fn($entry) => $entry->type === $type && $entry->date === $date);
                if (!empty($found)) {
                    $data[$type][] = reset($found)->getDisplayAmount();
                } else {
                    $data[$type][] = null;
                }
            }
        }

        return [$labels, $data];
    }

    /**
     * Gets array of [[Osimage]] for $type.
     *
     * @param string $type
     * @return Osimage[]|null
     */
    public static function getOsimages($type = null)
    {
        return Yii::$app->cache->getOrSet([__METHOD__, $type], function () use ($type) {
            return Osimage::find()->andFilterWhere(['type' => $type])->all();
        }, 3600);
    }

    /**
     * Regroups array of $images into 3 arrays of unique OS vendors (Ubuntu, FreeBSD, ...),
     * OSeses (Ubuntu 16.04, FreeBSD 10.3, ...) and softpacks (LAMP, no, ...).
     *
     * @param Osimage[] $images
     * @param bool $ispSupported whether ISP manager panel is supported
     * @return array of 3 elements:
     *  0 - vendors,
     *  1 - oses,
     *  2 - softpacks
     */
    public static function groupOsimages($images, $ispSupported = false)
    {
        $softpacks = [];
        $oses = [];
        $vendors = [];
        foreach ($images as $image) {
            $os = $image->os;
            $name = $image->getFullOsName();
            $panel = $image->getPanelName();
            $system = $image->getFullOsName('');
            $softpack_name = $image->getSoftPackName();
            $softpack = $image->getSoftPack();

            if (!array_key_exists($system, $oses)) {
                $vendors[$os]['name'] = $os;
                $vendors[$os]['oses'][$system] = $name;
                $oses[$system] = ['vendor' => $os, 'name' => $name];
            }

            if ($panel !== 'isp' || ($panel === 'isp' && $ispSupported)) {
                $data = [
                    'name' => $softpack_name,
                    'description' => preg_replace('/^ISPmanager - /', '', $softpack['description'] ?? ''),
                    'osimage' => $image->osimage,
                ];

                if (isset($softpack['soft'])) {
                    $html_desc = [];
                    foreach ($softpack['soft'] as $soft => $soft_info) {
                        $soft_info['description'] = preg_replace('/,([^\s])/', ', $1', $soft_info['description'] ?? '');

                        $html_desc[] = "<b>{$soft_info['name']} {$soft_info['version']}</b>: <i>{$soft_info['description']}</i>";
                        $data['soft'][$soft] = [
                            'name' => $soft_info['name'],
                            'version' => $soft_info['version'],
                            'description' => $soft_info['description'],
                        ];
                    }
                    $data['html_desc'] = implode('<br />', $html_desc);
                }
                $oses[$system]['panel'][$panel]['softpack'][$softpack_name] = $data;
                $softpacks[$panel][$softpack_name] = $data;
            } else {
                $oses[$system]['panel'][$panel] = false;
            }
        }

        foreach ($oses as $system => $os) {
            $delete = true;
            foreach ($os['panel'] as $panel => $info) {
                if ($info !== false) {
                    $delete = false;
                }
            }
            if ($delete) {
                unset($vendors[$os['vendor']]['oses'][$system]);
            }
        }

        return compact('vendors', 'oses', 'softpacks');
    }

    /**
     * @return array
     */
    public static function getPanels()
    {
        return Ref::getList('type,panel', 'hipanel:server:panel', []);
    }

    /**
     * Getting used current user server types
     *
     * @return array
     */
    public static function getUsedTypes(): array
    {
        $types = [];
        $usedTypes = Server::perform('get-used-types');
        array_walk($usedTypes, static function ($row) use (&$types): void {
            $types[] = $row['type'];
        });

        return $types;
    }
}
