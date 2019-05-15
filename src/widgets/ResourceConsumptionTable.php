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

use hipanel\modules\finance\logic\bill\QuantityFormatterFactory;
use hipanel\modules\finance\logic\bill\QuantityFormatterFactoryInterface;
use hipanel\modules\server\models\Consumption;
use hipanel\modules\server\models\Server;
use yii\base\Widget;

class ResourceConsumptionTable extends Widget
{
    /**
     * @var Server
     */
    public $model;

    /**
     * @var QuantityFormatterFactoryInterface|QuantityFormatterFactory
     */
    private $quantityFormatterFactory;

    public function __construct(QuantityFormatterFactoryInterface $quantityFormatterFactory, array $config = [])
    {
        parent::__construct($config);
        $this->quantityFormatterFactory = $quantityFormatterFactory;
    }

    public function run(): string
    {
        return $this->render('ResourceConsumptionTable', [
            'model' => $this->model,
        ]);
    }

    public function getFormatted(Consumption $model, ?string $currentQuantity): string
    {
        if (strpos($model->type, 'monthly,') === 0) {
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
