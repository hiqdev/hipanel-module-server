<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use dosamigos\chartjs\ChartJs;
use Yii;
use yii\helpers\Html;

class ResourceConsumption extends TrafficConsumption
{
    public static function types()
    {
        return [
            'ip_num' => Yii::t('hipanel:server', 'Number of IP addresses'),
            'server_du' => Yii::t('hipanel:server', 'CDN disk usage'),
            'server_files' => Yii::t('hipanel:server', 'CDN files count'),
            'backup_du' => Yii::t('hipanel:server', 'Backup disk usage'),
            'backup_traf' => Yii::t('hipanel:server', 'Backup traffic'),
            'server_sata' => Yii::t('hipanel:server', 'CDN cache HDD'),
            'server_ssd' => Yii::t('hipanel:server', 'CDN cache SSD'),
            'power' => Yii::t('hipanel:server', 'Power'),
        ];
    }

    public function init()
    {
        foreach ($this->data as $k => $item) {
            $this->data[$k] = array_map(function ($n) {
                return (int)Yii::$app->formatter->asShortSize($n, 2);
            }, $item);
        }

        parent::init();

        $this->emptyMessage = array_merge($this->emptyMessage, [
            'ip_num' => Yii::t('hipanel:server', 'IP number history is not available for this server.'),
            'server_du' => Yii::t('hipanel:server', 'Server usage consumption history is not available for this server.'),
            'server_files' => Yii::t('hipanel:server', 'Server files consumption history is not available for this server.'),
            'backup_du' => Yii::t('hipanel:server', 'Backup disk usage consumption history is not available for this server.'),
            'backup_traf' => Yii::t('hipanel:server', ' consumption history is not available for this server.'),
            'server_sata' => Yii::t('hipanel:server', ' consumption history is not available for this server.'),
            'server_ssd' => Yii::t('hipanel:server', ' consumption history is not available for this server.'),
            'power' => Yii::t('hipanel:server', ' power consumption history is not available for this server.'),
        ]);

        $this->legends = array_merge($this->legends, [
            'ip_num' => Yii::t('hipanel:server', 'Total of IP, PC'),
            'server_du' => Yii::t('hipanel:server', 'Total incoming traffic, Gb'),
            'server_files' => Yii::t('hipanel:server', 'Server files, Gb'),
            'backup_du' => Yii::t('hipanel:server', 'Backup disk usage, Gb'),
            'backup_traf' => Yii::t('hipanel:server', 'Buckup traffic, Gb'),
            'server_sata' => Yii::t('hipanel:server', 'Server SATA, Gb'),
            'server_ssd' => Yii::t('hipanel:server', 'Server SSD, Gb'),
            'power' => Yii::t('hipanel:server', 'Power, W'),
        ]);
    }

    protected function renderCanvasData()
    {
        return Html::tag('div',
            ChartJs::widget([
                'id' => $this->id,
                'type' => $this->consumptionBase === 'power' ? 'bar' : $this->chartType,
                'data' => [
                    'labels' => $this->labels,
                    'datasets' => [
                        [
                            'label' => $this->legends[$this->consumptionBase],
                            'backgroundColor' => 'rgba(139, 195, 74, 0.5)',
                            'borderColor' => 'rgba(139, 195, 74, 1)',
                            'pointBackgroundColor' => 'rgba(139, 195, 74, 1)',
                            'pointBorderColor' => '#fff',
                            'data' => (array)($this->data[$this->consumptionBase] ?? []),
                        ],
                    ],
                ],
                'clientOptions' => [
                    'bezierCurve' => false,
                    'responsive' => true,
                    'maintainAspectRatio' => true,
                    'scales' => ['y' => ['beginAtZero' => true]],
                ],
            ]));
    }
}
