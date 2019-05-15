<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

/**
 * Class RenewCalculation performs calculation for server renewal operation.
 */
class RenewCalculation extends \hipanel\modules\finance\cart\Calculation
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->client = $this->position->getModel()->client;
        $this->seller = $this->position->getModel()->seller;
        $this->object = 'server';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['server', 'expires'], 'safe'],
            [['id'], 'integer'],
        ]);
    }
}
