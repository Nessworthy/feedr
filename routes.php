<?php declare(strict_types=1);

namespace PeeHaa\AwesomeFeed;

use Auryn\Injector;
use PeeHaa\AwesomeFeed\Authentication\GateKeeper;
use PeeHaa\AwesomeFeed\Presentation\Controller\Dashboard;
use PeeHaa\AwesomeFeed\Presentation\Controller\Design;
use PeeHaa\AwesomeFeed\Presentation\Controller\Error;
use PeeHaa\AwesomeFeed\Presentation\Controller\LogIn;
use PeeHaa\AwesomeFeed\Router\Manager as RouteManager;

/** @var Injector $auryn */
$gateKeeper = $auryn->make(GateKeeper::class);
$router     = $auryn->make(RouteManager::class);

$router->get('renderNotFound', '/not-found', [Error::class, 'notFound']);
$router->get('renderMethodNotAllowed', '/method-not-allowed', [Error::class, 'methodNotAllowed']);

$router->get('renderDesign', '/design', [Design::class, 'render']);

if (!$gateKeeper->isAuthorized()) {
    $router->get('', '/', [LogIn::class, 'render']);
    $router->get('renderLogin', '/login', [LogIn::class, 'render']);
    $router->post('processGitHubLogin', '/github/login', [LogIn::class, 'processGitHubLogIn']);
    $router->get('processGitHubRedirectUri', '/github/login', [LogIn::class, 'processGitHubLogInRedirectUri']);
}

if ($gateKeeper->isAuthorized()) {
    $router->get('', '/', [Dashboard::class, 'render']);
}