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
use hipanel\modules\finance\models\Calculation;
use hipanel\modules\server\cart\Tariff;
use hipanel\modules\server\models\OpenvzPackage;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\Package;
use hipanel\modules\server\models\ServerUse;
use hipanel\modules\stock\models\Part;
use hiqdev\hiart\ErrorResponseException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class ServerHelper
{
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

    public static function getOsimages($type = null)
    {
        return Yii::$app->cache->getTimeCached(3600, [$type], function ($type) {
            return Osimage::find()->andFilterWhere(['type' => $type])->all();
        });
    }

    public static function groupOsimages($images, $ispSupported = false)
    {
        $softpacks = [];
        $oses = [];
        $vendors = [];
        foreach ($images as $image) {
            /** @var Osimage $image */
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

    public static function getPanels()
    {
        return Ref::getList('type,panel');
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
        $parts = [];
        /** @var Calculation[] $calculations */
        $calculations = [];

        $cacheKeys = [
            Yii::$app->params['seller'],
            Yii::$app->user->id,
            $type,
            $tariff_id,
        ];

        /** @var Tariff[] $tariffs */
        $tariffs = Yii::$app->getCache()->getTimeCached(3600, $cacheKeys, function ($seller, $client_id, $type, $tariff_id) {
            return Tariff::find(['scenario' => 'get-available-info'])
                ->joinWith('resources')
                ->where(['seller' => $seller])
                ->andFilterWhere(['id' => $tariff_id])
                ->andFilterWhere(['type' => $type])
                ->all();
        });

        foreach ($tariffs as $tariff) {
            $part_ids = ArrayHelper::merge($part_ids, array_filter(ArrayHelper::getColumn($tariff->resources, 'object_id')));
            $calculations[] = $tariff->getCalculationModel();
        }

        if (!empty($part_ids)) {
            $parts = Part::find()->where(['id' => $part_ids])->indexBy('id')->all();
        }

        $calculationData = [];
        foreach ((array) $calculations as $calculation) {
            $calculationData[$calculation->getPrimaryKey()] = $calculation->getAttributes();
        }

        try {
            $prices = Calculation::perform('CalcValue', $calculationData, true);
        } catch (ErrorResponseException $e) {
            $prices = $e->errorInfo['response'];
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException('Failed to calculate tariff value', 0, $e);
        }

        $packages = [];

        foreach ($tariffs as $tariff) {
            $tariffParts = [];
            $tariffPartsIds = array_filter(ArrayHelper::getColumn($tariff->resources, 'object_id'));

            foreach ($parts as $id => $part) {
                if (in_array($id, $tariffPartsIds, true)) {
                    $tariffParts[$id] = $part;
                }
            }

            $packages[] = Yii::createObject([
                'class' => static::buildPackageClass($tariff),
                'tariff' => $tariff,
                'parts' => $tariffParts,
                'calculation' => $prices[$tariff->id],
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

    public static function buildPackageClass($tariff)
    {
        if ($tariff->type === Tariff::TYPE_OPENVZ) {
            return OpenvzPackage::class;
        }

        return Package::class;
    }
}
