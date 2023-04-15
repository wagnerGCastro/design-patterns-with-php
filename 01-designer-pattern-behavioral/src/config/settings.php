<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'  => true,
                'logErrorDetails' => true,
                'logger'    => [
                    'name'  => 'slim-app',
                    'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../../.logs/app.log',
                    'level' => Logger::DEBUG,
                ]
            ]);
        },
        'settings' =>
            [
                'db' => [
                    'driver' => 'mysql',
                    'host' => isset($_ENV['DB_HOST']) ??  'localhost',
                    'port' => isset($_ENV['DB_PORT']) ?? '3389',
                    'database' => isset($_ENV['DB_NAME']) ?? 'db_test_2',
                    'username' => isset($_ENV['DB_USER']) ?? 'root',
                    'password' => isset($_ENV['DB_PASS']) ?? 'root',
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                ],
            ]
    ]);
};
