<?php

namespace hipanel\modules\server\controllers;

use hipanel\base\CrudController;
use hipanel\modules\finance\models\Tariff;
use hipanel\modules\server\cart\ServerOrderProduct;
use hipanel\modules\server\helpers\ServerHelper;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;

class OrderController extends CrudController
{
    public function actions()
    {
        return [
            'add-to-cart' => [
                'class' => AddToCartAction::class,
                'productClass' => ServerOrderProduct::class,
                'redirectToCart' => true,
            ]
        ];
    }

    public function actionOrder($id)
    {
        $package = ServerHelper::getAvailablePackages(null, $id);
        $osImages = ServerHelper::getOsimages($package->tariff->type);

        return $this->render('order', [
            'package' => $package,
            'product' => new ServerOrderProduct(['tariff_id' => $package->tariff->id]),
            'groupedOsimages' => ServerHelper::groupOsimages($osImages),
            'panels' => ServerHelper::getPanels(),
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionXenSsd()
    {
        return $this->render('xen_ssd', [
            'packages' => ServerHelper::getAvailablePackages(Tariff::TYPE_XEN),
            'tariffTypes' => Yii::$app->params['vdsproduct'],
        ]);
    }

    public function actionOpenVz()
    {
        return $this->render('open_vz', [
            'packages' => ServerHelper::getAvailablePackages(Tariff::TYPE_OPENVZ),
            'tariffTypes' => Yii::$app->params['vdsproduct'],
        ]);
    }

    public function actionTariffsDetails()
    {
        return $this->render('tariffs_details');
    }

    public function actionAdvantages()
    {
        return $this->render('advantages');
    }

    public function actionWhatIsVds()
    {
        return $this->render('what_is_vds');
    }
}
