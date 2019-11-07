<?php

use hipanel\modules\server\models\Config;
use hipanel\modules\server\models\Osimage;
use hipanel\server\order\yii\widgets\ServerOrder;
use yii\web\View;

/** @var Config[] $configs */
/** @var Osimage[] $osimages */

$this->title = Yii::t('hipanel:server:panel', 'Dedicated servers');
$this->params['breadcrumbs'][] = $this->title;

?>
<!-- Test switcher -->
<?php $this->registerJs("
    var radios = document.querySelectorAll('.location-switcher-input'), x = radios.length;
    while (x--) {
        radios[x].addEventListener('click', (evt) => {
            hipanel_server_order_app.setLocation(evt.target.value);
        })
    }
    ", View::POS_LOAD) ?>
<div class="use-bootstrap">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5 location-switcher">
                <div class="form-check">
                    <input class="form-check-input location-switcher-input" type="radio" name="locationRadios"
                           id="locationRadios1" value="nl" checked>
                    <label class="form-check-label" for="locationRadios1">Netherlands</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input location-switcher-input" type="radio" name="locationRadios"
                           id="locationRadios2" value="us">
                    <label class="form-check-label" for="locationRadios2">USA</label>
                </div>
            </div>
        </div>
    </div>
</div>

<?= ServerOrder::widget(compact('configs', 'osimages')) ?>
