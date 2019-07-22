<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\controllers;

use hipanel\base\CrudController;
use hipanel\modules\server\cart\ServerOrderDedicatedProduct;
use hipanel\modules\server\cart\ServerOrderProduct;
use hipanel\modules\server\models\Config;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\Module;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class OrderController extends CrudController
{
    /**
     * OrderController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param array $config
     * @throws ForbiddenHttpException
     */
    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        if (!$module->orderIsAllowed) {
            throw new ForbiddenHttpException('Server order is not allowed');
        }
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['dedicated'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add-to-cart', 'add-to-cart-dedicated', 'index', 'xen-ssd', 'open-vz'],
                        'roles' => ['server.pay'],
                    ],
                ]
            ],
        ]);
    }

    public function beforeAction($action)
    {
        if ($action->id == 'add-to-cart-dedicated') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'add-to-cart' => [
                'class' => AddToCartAction::class,
                'productClass' => ServerOrderProduct::class,
                'redirectToCart' => true,
            ],
            'add-to-cart-dedicated' => [
                'class' => AddToCartAction::class,
                'productClass' => ServerOrderDedicatedProduct::class,
                'redirectToCart' => true,
            ],
        ];
    }

    public function actionOrder($id)
    {
        return $this->redirectOutside();
        /***
        $package = ServerHelper::getAvailablePackages(null, $id);
        $osImages = ServerHelper::getOsimages($package->tariff->type);

        return $this->render('order', [
            'package' => $package,
            'product' => new ServerOrderProduct(['tariff_id' => $package->tariff->id]),
            'groupedOsimages' => ServerHelper::groupOsimages($osImages),
            'panels' => ServerHelper::getPanels(),
        ]);
        ***/
    }

    public function actionIndex()
    {
        return $this->redirectOutside();
        /***
        return $this->render('index');
         ***/
    }

    public function actionXenSsd()
    {
        return $this->redirectOutside();
        /***
        return $this->render('xen_ssd', [
            'packages' => ServerHelper::getAvailablePackages(Tariff::TYPE_XEN),
            'tariffTypes' => Yii::$app->params['vdsproduct'],
        ]);
        ***/
    }

    public function actionOpenVz()
    {
        return $this->redirectOutside();
        /***
        return $this->render('open_vz', [
            'packages' => ServerHelper::getAvailablePackages(Tariff::TYPE_OPENVZ),
            'tariffTypes' => Yii::$app->params['vdsproduct'],
        ]);
        ***/
    }

    public function actionDedicated()
    {
        Yii::$app->get('hiart')->disableAuth();
        $this->layout = '@hipanel/server/order/yii/views/layouts/advancedhosting';
        $configs = Config::find()->getAvailable()->withSellerOptions()->withPrices()->addOption('batch', true)->createCommand()->send()->getData();
        $osimages = Osimage::find()->where(['type' => 'dedicated'])->all();

        return $this->render('dedicated', compact('configs', 'osimages'));
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

    protected function redirectOutside()
    {
        $language = Yii::$app->language;
        $template = Yii::$app->params['module.server.order.redirect.url'];
        $url = preg_replace('/{language}/', $language, $template);
        return $this->redirect($url);
    }
}
