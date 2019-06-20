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
            [['location', 'currency', 'values', 'value', 'price', 'discounted_price'], 'string'],
            [['eur', 'usd'], 'string'],
        ]);
    }

    public function getFirstAvailable()
    {
        foreach (['eur', 'usd'] as $attribute) {
            if (isset($this->values[$attribute]) && !empty($this->values[$attribute])) {
                foreach ($this->values[$attribute] as $field => $value) {
                    $this->{$field} = $value;
                }
                break;
            }
        }

        return $this;
    }
}
