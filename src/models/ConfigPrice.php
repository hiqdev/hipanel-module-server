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
            [['location', 'currency', 'values', 'value', 'price', 'discounted_price', 'services'], 'string'],
            [['eur', 'usd'], 'string'],
        ]);
    }

    public function getFirstAvailable()
    {
        foreach (['eur', 'usd'] as $attribute) {
            if (!empty($this->values[$attribute])) {
                foreach ($this->values[$attribute] as $field => $value) {
                    $this->{$field} = $value;
                }
                break;
            }
        }

        return $this;
    }

    public function getSupportPrice(): ?string
    {
        if (!empty($this->services) && isset($this->services['monthly,support_time'])) {
            return $this->services['monthly,support_time']['price'];
        }

        return null;
    }
}
