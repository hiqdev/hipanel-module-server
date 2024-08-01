<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server;

use hipanel\modules\server\models\Irs;
use yii\caching\CacheInterface;

class Module extends \hipanel\base\Module
{
    /**
     * Whether server order is allowed
     */
    public bool $orderIsAllowed = true;

    public function __construct($id, $parent, readonly private CacheInterface $cache, $config = [])
    {
        parent::__construct($id, $parent, $config);
    }

    public function hasServersForRent(): bool
    {
        $rows = $this->cache->getOrSet(
            ['client-has-servers-for-rent', $this->user->id],
            fn() => Irs::perform('for-rent', ['client_id' => $this->user->id]),
            14_400 // 4 hours
        );

        return is_array($rows) && count($rows) > 0;
    }
}
