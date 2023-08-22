<?php
declare(strict_types=1);
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\modules\finance\logic\bill\QuantityFormatterFactoryInterface;
use hipanel\modules\server\helpers\ServerSort;
use hipanel\modules\server\models\Consumption;
use hiqdev\hiart\ActiveRecord;
use yii\base\Widget;

class ResourceConsumptionTable extends Widget
{
    public ActiveRecord $model;

    public function __construct(
        readonly private QuantityFormatterFactoryInterface $quantityFormatterFactory,
        array $config = []
    )
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        $consumptions = $this->model->consumptions;
        $consumptions = ServerSort::byConsumptionType()->values($consumptions);

        return $this->render('ResourceConsumptionTable', ['consumptions' => $consumptions]);
    }

    public function getFormatted(Consumption $model, ?string $currentQuantity): string
    {
        if (str_starts_with($model->type, 'monthly,')) {
            return '';
        }

        if ($currentQuantity === null) {
            return '';
        }

        $consumption = new class($currentQuantity, $model->getAttributes()) extends Consumption {
            /**
             * @var string
             */
            public $quantity;

            public function __construct(string $quantity, array $config = [])
            {
                parent::__construct($config);

                $this->quantity = $quantity;
            }
        };

        $formatter = $this->quantityFormatterFactory->forConsumption($consumption);

        if ($formatter === null) {
            return '';
        }

        return $formatter->format();
    }
}
