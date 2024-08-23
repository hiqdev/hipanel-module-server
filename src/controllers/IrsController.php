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
use yii\web\NotFoundHttpException;
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
                'data' => function () {
                    return [];
                },
            ],
        ]);
    }

    public function actionOrder(string $id): string|Response
    {
        /** @var Irs $irsServer */
        $irsServer = Irs::find()->where(['id' => $id])->joinWith(['bindings'])->one();
        if (!$irsServer) {
            throw new NotFoundHttpException('IRS for order not found');
        }
        $order = new IRSOrder();
        $order->setIrs($irsServer);

        if ($this->request->isAjax && $order->load($this->request->post()) && $order->validate()) {
            try {
                Irs::perform('sell', [
                    'id' => $irsServer->id,
                    'tariff_id' => $order->irs->getActualSale()->tariff_id,
                    'client_id' => Yii::$app->user->id,
                    'sale_time' => '',
                    'tags' => implode(',', array_diff($irsServer->getTags(), ['irs'])),
                ]);
                $ticket = $order->createTicket();
            } catch (Exception $e) {
                return $this->asJson([
                    'error' => $e->getMessage(),
                ]);
            }

            return $this->asJson($ticket ? [
                'ticketLink' => Url::toRoute(['@ticket/view', 'id' => $ticket->id]),
                'ticketId' => $ticket->id,
            ] : []);
        }

        return $this->render('order', [
            'order' => $order,
            'ticket' => null,
        ]);
    }

    public function actionGenerateSummary()
    {
        $order = new IRSOrder();
        if ($this->request->isAjax && $order->load($this->request->post(), '')) {
            $summary = new HardwareSummary($order->config);
            foreach (['ram', 'hdd', 'ssd'] as $type) {
                if (!str_contains($order->{$type}, 'Included')) {
                    $summary->setPart(HardwareType::from($type), $order->{$type});
                }
            }

            return $this->asJson([
                'summary' => $summary->getSummary(),
            ]);
        }
    }
}
