<?php

namespace hipanel\modules\server\models;

use hipanel\base\Model;

class ConfigPrice extends Model
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['location', 'currency', 'value', 'price', 'discounted_price'], 'string'],
            [['eur', 'usd'], 'string'],
        ]);
    }

    public function getFirstAvailable()
    {
        foreach (['eur', 'usd'] as $attribute) {
            if ($this->hasProperty($attribute) && !empty($this->{$attribute})) {
                foreach ($this->{$attribute} as $field => $value) {
                    $this->{$field} = $value;
                }
                break;
            }
        }

        return $this;
    }
}
