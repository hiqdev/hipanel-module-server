<?php

namespace hipanel\modules\server\cart;

use DateTime;
use hipanel\modules\server\models\Server;
use hipanel\modules\finance\cart\AbstractCartPosition;
use Yii;

abstract class AbstractServerProduct extends AbstractCartPosition
{
    /**
     * @var Server
     */
    protected $_model;

    /**
     * @var string the operation name
     */
    protected $_operation;

    /** {@inheritdoc} */
    protected $_calculationModel = Calculation::class;

    /** {@inheritdoc} */
    public function getIcon()
    {
        return '<i class="fa fa-server"></i>';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        $result = [];
        foreach ([1, 3, 6, 12] as $n) {
            $date = (new DateTime($this->_model->expires))->add(new \DateInterval("P{$n}M"));

            $result[$n] = Yii::t('hipanel/server', '{n, plural, one{# month} other{# months}} till {date}', [
                'n' => $n,
                'date' => Yii::$app->formatter->asDate($date)
            ]);
        }

        return $result;
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'type' => $this->_operation,
            'server' => $this->name,
            'expires' => $this->_model->expires,
        ], $options));
    }
}
