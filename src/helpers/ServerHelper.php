<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\helpers;

use hipanel\models\Ref;
use hipanel\modules\finance\logic\TariffCalculator;
use hipanel\modules\finance\models\Calculation;
use hipanel\modules\finance\models\Tariff;
use hipanel\modules\server\models\OpenvzPackage;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\Package;
use hipanel\modules\server\models\ServerUse;
use hiqdev\hiart\ErrorResponseException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class ServerHelper
{
    /**
     * Method groups array of [[ServerUse]] in order to use it in charts.
     * @param ServerUse[] $uses
     * @return array of two items:
     *  0 - labels (like months)
     *  1 - values (value for each month)
     */
    public static function groupUsesForChart($uses)
    {
        $labels = [];
        $data = [];

        ArrayHelper::multisort($uses, 'date');

        foreach ($uses as $use) {
            /** @var ServerUse $use */
            $labels[$use->date] = $use;
            $data[$use->type][] = $use->getDisplayAmount();
        }

        foreach ($labels as $date => $use) {
            $labels[$date] = $use->getDisplayDate();
        }

        return [$labels, $data];
    }

    /**
     * Gets array of [[Osimage]] for $type
     *
     * @param string $type
     * @return Osimage[]|null
     */
    public static function getOsimages($type = null)
    {
        return Yii::$app->cache->getTimeCached(3600, [$type], function ($type) {
            return Osimage::find()->andFilterWhere(['type' => $type])->all();
        });
    }


    /**
     * Regroups array of $images into 3 arrays of unique OS vendors (Ubuntu, FreeBSD, ...),
     * OSeses (Ubuntu 16.04, FreeBSD 10.3, ...) and softpacks (LAMP, no, ...)
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
                    'description' => preg_replace('/^ISPmanager - /', '', $softpack['description']),
                    'osimage' => $image->osimage,
                ];

                if ($softpack['soft']) {
                    $html_desc = [];
                    foreach ($softpack['soft'] as $soft => $soft_info) {
                        $soft_info['description'] = preg_replace('/,([^\s])/', ', $1', $soft_info['description']);

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
        return Ref::getList('type,panel', 'hipanel/server/panel', []);
    }

    /**
     * @param string $type (svds|ovds)
     * @param integer $tariff_id
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @return Package|array
     */
    public static function getAvailablePackages($type = null, $tariff_id = null)
    {
        $part_ids = [];

        $cacheKeys = [
            Yii::$app->params['seller'],
            Yii::$app->user->id,
            $type,
            $tariff_id,
        ];

        /** @var Tariff[] $tariffs */
        $tariffs = Yii::$app->getCache()->getTimeCached(3600, $cacheKeys, function ($seller, $client_id, $type, $tariff_id) {
            return Tariff::find(['scenario' => 'get-available-info'])
                ->details()
                ->andWhere(['seller' => $seller])
                ->andFilterWhere(['id' => $tariff_id])
                ->andFilterWhere(['type' => $type])
                ->all();
        });

        $calculator = new TariffCalculator($tariffs);

        $packages = [];
        foreach ($tariffs as $tariff) {
            $packages[] = Yii::createObject([
                'class' => static::buildPackageClass($tariff),
                'tariff' => $tariff,
                'calculation' => $calculator->getCalculation($tariff->id)
            ]);
        }

        ArrayHelper::multisort($packages, 'price', SORT_ASC, SORT_NUMERIC);

        if (empty($packages)) {
            throw new NotFoundHttpException('Requested tariff is not available');
        }

        if (isset($tariff_id) && !is_array($tariff_id)) {
            return reset($packages);
        }

        return $packages;
    }

    /**
     * @param $tariff
     * @return mixed
     */
    public static function buildPackageClass($tariff)
    {
        if ($tariff->type === Tariff::TYPE_OPENVZ) {
            return OpenvzPackage::class;
        }

        return Package::class;
    }
}
