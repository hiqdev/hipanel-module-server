<?php declare(strict_types=1);

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SearchAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\server\helpers\HardwareSummary;
use hipanel\modules\server\forms\IRSOrder;
use hipanel\modules\server\helpers\HardwareType;
use hipanel\modules\server\models\Irs;
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
                    '*' => 'hub.read',
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
                    $query = $action->getDataProvider()->query;
                    $query->withBindings()->withSales()->withIRSOptions();
                },
                'data' => function () {
                    return [];
                },
            ],
        ]);
    }

    public function actionOrder(string $id): string|Response
    {
        $irsServer = Irs::find()->where(['id' => $id])->withBindings()->withSales()->withIRSOptions()->one();
        $order = new IRSOrder();
        $order->setServer($irsServer);
        $c = new HardwareSummary($irsServer->hwsummary_auto);

        if ($this->request->isAjax && $order->load($this->request->post()) && $order->validate()) {
            $ticket = $order->createTicket();

            return $this->asJson([
                'ticketLink' => Url::toRoute(['@ticket/view', 'id' => $ticket->id]),
                'ticketId' => $ticket->id,
            ]);
        }

        return $this->render('order', [
            'order' => $order,
            'ticket' => null,
        ]);
    }

    public function actionGenerateSummary()
    {
        $order = new IRSOrder();
        if ($this->request->isAjax && $order->load($this->request->post())) {
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
