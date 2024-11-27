<?php declare(strict_types=1);

namespace hipanel\modules\server\controllers;

use Exception;
use hipanel\actions\IndexAction;
use hipanel\actions\SearchAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\server\helpers\HardwareSummary;
use hipanel\modules\server\forms\IRSOrder;
use hipanel\modules\server\helpers\HardwareType;
use hipanel\modules\server\models\Irs;
use Yii;
use yii\base\Event;
use yii\helpers\Url;
use yii\web\Response;

class IrsController extends CrudController
{
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => 'server.pay',
                ],
            ],
        ]);
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var SearchAction $action */
                    $action = $event->sender;
                    $action->getDataProvider()->query->joinWith(['bindings']);
                },
            ],
        ]);
    }

    public function actionOrder(string $id): string|Response
    {
        /** @var Irs $irsServer */
        $irsServer = Irs::find()->where(['id' => $id])->joinWith(['bindings'])->one();
        $order = new IRSOrder();
        if ($irsServer) {
            $order->setIrs($irsServer);
        }
        $formData = $this->request->post($order->formName());

        if ($this->request->isAjax && $order->load($formData, '') && $order->validate()) {
            try {
                $payload = [
                    'id' => $irsServer->id,
                    'type' => $order->getServerType()->value,
                    'tariff_id' => $order->needUpgrade() ? null : $order->getIrs()?->getActualSale()->tariff_id,
                    'client_id' => Yii::$app->user->id,
                    'sale_time' => '',
                    'ignoreIpMonitoring' => $order->needToIgnoreIpMonitoring(),
                ];
                $this->syncTrafficPrices($irsServer, $order, $formData);
                Irs::perform('sell', $payload);
                $ticket = $order->createTicket($formData);
            } catch (Exception $e) {
                return $this->asJson(['error' => $e->getMessage()]);
            }

            return $this->asJson(isset($ticket) ? [
                'ticketLink' => Url::toRoute(['@ticket/view', 'id' => $ticket->id]),
                'ticketId' => $ticket->id,
            ] : []);
        }

        return $this->render('order', ['order' => $order, 'ticket' => null]);
    }

    public function actionGenerateSummary(): Response
    {
        $order = new IRSOrder();
        if ($this->request->isAjax && $order->load($this->request->post(), '')) {
            $summary = new HardwareSummary($order->config);
            foreach (['ram', 'hdd', 'ssd'] as $type) {
                if (!str_contains($order->{$type}, 'Included')) {
                    $summary->setPart(HardwareType::from($type), $order->{$type});
                }
            }

            return $this->asJson(['summary' => $summary->getSummary()]);
        }

        return $this->asJson([]);
    }

    private function syncTrafficPrices(Irs $irsServer, IRSOrder $order, mixed $formData): void
    {
        $is95 = array_key_exists('traffic_mbps', $formData);
        $key = 'traffic_' . ($is95 ? 'mbps' : 'tb');
        $searchValue = $formData[$key];
        $trafficOptions = array_filter($irsServer->irsOptions[$key], function ($item) use ($searchValue) {
            return in_array($searchValue, $item);
        });
        $traffic = reset($trafficOptions);
        $payload = [
            'id' => $irsServer->id,
            'tariff_id' => $order->irs->getActualSale()->tariff_id,
            'unit' => $this->identifyUnitBySelectedOption($traffic['Dropdowns']),
            'currency' => $order->currency,
            'included' => preg_match('/\d+(\.\d+)?/', $traffic['Dropdowns'], $matches) ? $matches[0] : null,
            'overuse_price' => preg_match('/\d+(\.\d+)?/', $traffic['Hint'], $matches) ? $matches[0] : null,
            'monthly_price' => $traffic['AH price'] !== 'Included' ? $traffic['AH price'] : 0,
        ];
        try {
            if (!$order->needUpgrade()) {
                Irs::perform('sync-traffic-prices', $payload);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function identifyUnitBySelectedOption(string $option): string
    {
        return match(true) {
            str_contains($option, 'Mbps') => 'mbps',
            str_contains($option, 'Gbps') => 'gbps',
            str_contains($option, 'TB') => 'tb',
        };
    }
}
