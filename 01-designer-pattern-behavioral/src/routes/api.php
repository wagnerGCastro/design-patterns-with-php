<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Database\Bootstrap as BootstrapEloquent;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImpostController;

return function (App $app) {
    $container = $app->getContainer();
    BootstrapEloquent::load($container);

    $app->get('/hello', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world! 1');
        return $response;
    });

    $app->group('/api', function (Group $group) {
        $group->group('/test', function (Group $group) {
            $group->get('', TestController::class . ':getTest');
        });

        $group->group('/users', function (Group $group) {
            $group->get('', UserController::class . ':index');
        });

        $group->group('/impost', function (Group $group) {
            $group->get('', ImpostController::class . ':index');
        });
    });
};
