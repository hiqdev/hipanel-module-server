<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use dosamigos\chartjs\ChartJs;
use yii\base\InvalidConfigException;
use Yii;

class TrafficConsumption extends Widget
{

    /**
     * @var array. Chart labels
     */
    public $labels;

    /**
     * @var array. Chart data
     */
    public $data = [];

    /**
     * @var string
     */
    public $chartType = 'line';


    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $consumptionBase = 'server_traf';

    /**
     * @var array. Messages which would be shown if data is empty
     */
    protected $emptyMessage;

    /**
     * @var array. Legends for datasets
     */
    protected $legends;

    /**
     * @var boolean
     */
    public $isClientRegisterCss;

    public function init()
    {
        parent::init();

        if ($this->labels === null) {
            throw new InvalidConfigException('Please specify the "labels" property.');
        }

        if ($this->id === null) {
            $this->id = $this->consumptionBase . "_consumption_chart";
        }

        $this->emptyMessage = [
            'server_traf' => Yii::t('hipanel:server', 'Traffic consumption history is not available for this server'),
            'server_traf95' => Yii::t('hipanel:server', 'Bandwidth consumption history is not available for this server'),
        ];

        $this->legends = [
            'server_traf' => Yii::t('hipanel:server', 'Total outgoing traffic, Gb'),
            'server_traf_in' => Yii::t('hipanel:server', 'Total incoming traffic, Gb'),
            'server_traf95' => Yii::t('hipanel:server', '95th percentile for outgoing bandwidth, Mbit/s'),
            'server_traf95_in' => Yii::t('hipanel:server', '95th percentile for incoming bandwidth, Mbit/s'),
        ];

        $this->labels = array_values($this->labels);
    }

    public function run()
    {
        if ($this->isClientRegisterCss === true) {
            $this->clientRegisterCss();
        }

        echo $this->renderBlockHeader();

        if ($this->data === []) {
            echo $this->emptyMessage[$this->consumptionBase];
        } else {
            echo $this->renderCanvasData();
        }
        echo $this->renderBlockFooter();
    }

    protected function renderBlockHeader()
    {
        return "<div class='row {$this->consumptionBase}-chart-wrapper'><div class='col-md-12'>";
    }

    protected function renderCanvasData()
    {
        return Html::tag('div', ChartJs::widget([
            'id' => $this->id,
            'type' => $this->chartType,
            'data' => [
                'labels' => $this->labels,
                'datasets' => [
                    [
                        'label' => $this->legends[$this->consumptionBase],
                        'backgroundColor' => 'rgba(139, 195, 74, 0.5)',
                        'borderColor' => 'rgba(139, 195, 74, 1)',
                        'pointBackgroundColor' => 'rgba(139, 195, 74, 1)',
                        'pointBorderColor' => '#fff',
                        'data' => $this->data[$this->consumptionBase],
                    ],
                    [
                        'label' => $this->legends["{$this->consumptionBase}_in"],
                        'backgroundColor' => 'rgba(151,187,205,0.5)',
                        'borderColor' => 'rgba(151,187,205,1)',
                        'pointBackgroundColor' => 'rgba(151,187,205,1)',
                        'pointBorderColor' => '#fff',
                        'data' => $this->data["{$this->consumptionBase}_in"],
                    ],
                ],
            ],
            'clientOptions' => [
                'bezierCurve' => false,
                'responsive' => true,
                'maintainAspectRatio' => true,
            ],
        ]));
    }

    protected function renderBlockFooter()
    {
        return '</div></div>';
    }

    protected function clientRegisterCss()
    {
        $view = $this->getView();
        $view->registerCss('
            ul.line-legend {
                list-style: none;
                margin: 0;
                padding: 0;
            }
        ');
    }
}
