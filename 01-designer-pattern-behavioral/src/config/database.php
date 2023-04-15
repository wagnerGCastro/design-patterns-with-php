<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

return function (Container $container) {
    try {
        $settings = $container->get('settings');
        $capsule = new Capsule();
        $capsule->addConnection($settings['db']);
        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    } catch (Exception $e) {
        echo "connected successful";
    }
};
