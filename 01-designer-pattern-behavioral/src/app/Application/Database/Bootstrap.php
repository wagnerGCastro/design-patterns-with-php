<?php

namespace App\Application\Database;

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

final class Bootstrap extends AbstractMigration
{
    public static function load($container)
    {
        $settings = $container->get('settings');

        $capsule = new Capsule();
        $capsule->addConnection($settings['db']);
        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->setAsGlobal();
        $capsule->schema();
        return $capsule->bootEloquent();
    }
}
