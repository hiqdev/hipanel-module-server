<?php declare(strict_types=1);

namespace hipanel\modules\server\models\query;

class IrsQuery extends ServerQuery
{
    public function init(): void
    {
        $this->andWhere(['tags' => 'irs']); // todo: change to `irs`
    }

    public function withIRSOptions(): self
    {
        $this->andWhere(['with_irsOptions' => true]);

        return $this;
    }
}
