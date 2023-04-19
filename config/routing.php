<?php
declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;

use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Controller\UserController;
use Salle\LSCoins\Controller\ProfileController;
use Salle\LSCoins\Middleware\StartSessionMiddleware;
use Salle\LSCoins\Middleware\AuthorizationMiddleware;


$app->get(
    '/', 
    HomeController::class . ':showHome')
    ->setName('home');

$app->get(
    '/messages',
    FlashController::class . ":addMessage")
    ->setName('messages');

$app->get(
    '/sign-in', 
    UserController::class . ':showLoginFormAction'
)->setName('show-sign-in');

$app->post(
    '/sign-in', 
    UserController::class . ':loginAction'
)->setName('sign-in');

$app->get(
    '/sign-up', 
    UserController::class . ':showRegisterFormAction'
)->setName('show-sign-up');

$app->post(
    '/sign-up', 
    UserController::class . ':registerAction'
)->setName('sign-up');

$app->group('', function() use ($app) {

    $app->get('/profile', ProfileController::class . ':showProfile');
    $app->get('/profile/changePassword', ProfileController::class . ':showChangePassword');
    $app->get('/market', ProfileController::class . ':showMarket');

})->add(AuthorizationMiddleware::class);