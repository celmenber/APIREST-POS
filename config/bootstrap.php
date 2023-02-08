<?php
use DI\Container;
use DI\Bridge\Slim\Bridge as SlimAppFactory;
use Selective\BasePath\BasePathMiddleware;

require_once __DIR__  .'/../vendor/autoload.php';

$container = new Container();

$settings = require_once __DIR__.'/settings.php';

$settings($container);

$app = SlimAppFactory::create($container);

$middleware = require_once __DIR__ . '/middleware.php';

$middleware($app);

// Add Slim routing middleware
$app->addRoutingMiddleware();

// Set the base path to run the app in a subdirectory.
// This path is used in urlFor().
$app->add(new BasePathMiddleware($app));

$app->addErrorMiddleware(true, true, true);

$routes = require_once  __DIR__ .'/routes.php';
$routes($app);

$app->run();