<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\SphinxSearch;

use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Builder;

/**
 * Class SphinxSearchServiceProvider
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SphinxSearchServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Container::getInstance()->make(EngineManager::class)->extend('sphinxsearch', function ($app) {
            $options = $app['config']['scout']['sphinxsearch'];
            if (empty($options['socket'])){
                unset($options['socket']);
            }
            $connection = new Connection();
            $connection->setParams($options);

            return new SphinxEngine(new SphinxQL($connection));
        });
        Builder::macro('whereIn', function (string $attribute, array $arrayIn) {
            $this->engine()->addWhereIn($attribute, $arrayIn);
            return $this;
        });
    }
}