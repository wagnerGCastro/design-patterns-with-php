<?php

declare(strict_types=1);

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\Settings\SettingsInterface;
use App\Application\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../.env.php';

echo "wagner";

/**
 * DotEnv to enviroments
 */
define("ENVIRONMENT", getenv('ENVIRONMENT'));

$repositoryDotEnv = RepositoryBuilder::createWithNoAdapters()
    ->addAdapter(EnvConstAdapter::class)
    ->addWriter(PutenvAdapter::class)
    ->immutable()
    ->make();

try {
    $env = ENVIRONMENT ? '.env.' . ENVIRONMENT  : '.env';
    $dotenv = Dotenv::create($repositoryDotEnv, __DIR__ . '/../', $env);
    $dotenv->load();
} catch (\Throwable $th) {
    echo 'File .env is requerid: ', $th->getMessage(), "\n";
}

/**
 * Instantiate PHP-DI ContainerBuilder
 */
$containerBuilder = new ContainerBuilder();

/**
 * Set up settings
 */
$settings = require __DIR__ . '/../src/config/settings.php';
$settings($containerBuilder);

/**
 * Set up dependencies
 */
$dependencies = require __DIR__ . '/../src/config/dependencies.php';
$dependencies($containerBuilder);

/**
 * Build PHP - DI Container instance
 */
$container = $containerBuilder->build();

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

/**
 * Register middleware
 */
$middleware = require __DIR__ . '/../src/config/middleware.php';
$middleware($app);

/**
 * Register routes
 *
 */
$routes_api = require __DIR__ . '/../src/routes/api.php';
$routes_api($app);

// Define app routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello My Friend my APi em Slim Framework!");
    return $response;
});

// $app->get('/hello/{name}', function (Request $request, Response $response, $args) {
//     $name = $args['name'];
//     $response->getBody()->write("Hello, $name");
//     return $response;
// });


/**
 * @var SettingsInterface $settings
 */
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

/**
 * Create Request object from globals
 */
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();


/**
 * Create Error Handler
 */
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

/**
 * Create Shutdown Handler
 */
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Add Body Parsing Middleware
 */
$app->addBodyParsingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
// $errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);


/**
 * Run App & Emit Response
 */
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
