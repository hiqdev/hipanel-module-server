<?php

namespace hipanel\modules\server\widgets\combo;

use hiqdev\combo\Combo;
use yii\web\JsExpression;

class ConfigCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'config/configId';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/server/config/index';

    /** {@inheritdoc} */
    public $_return = ['id', 'name', 'label', 'descr'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    public function getPluginOptions($options = [])
    {
        return parent::getPluginOptions([
            'select2Options' => [
                'templateResult' => new JsExpression('function (data) {
                    if (data.loading) {
                        return data.text;
                    }

                    return `<b>${data.name} </b> - ${data.label}<br/><span class="text-muted" style="font-size: smaller">${data.descr}</span>`;
                }'),
                'escapeMarkup' => new JsExpression('function (markup) {
                    return markup; // Allows HTML
                }'),
            ],
        ]);
    }
}
