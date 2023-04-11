<?php
declare(strict_types=1);

use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Controller\VisitsController;
use Salle\LSCoins\Controller\CookieMonsterController;
use Salle\LSCoins\Controller\FlashController;
use Salle\LSCoins\Controller\CreateUserController;
use Salle\LSCoins\Controller\SimpleFormController;
use Salle\LSCoins\Controller\FileController;
use Salle\LSCoins\Controller\LoginController;
use Salle\LSCoins\Controller\RegisterController;
use Salle\LSCoins\Middleware\StartSessionMiddleware;


$app->get(
    '/', 
    HomeController::class . ':apply')
    ->setName('home');

$app->get(
    '/messages',
    FlashController::class . ":addMessage")
    ->setName('messages');

$app->post(
    '/user',
    CreateUserController::class . ":apply"
)->setName('create_user');

$app->get(
    '/sign-in', 
    LoginController::class . ':showLoginFormAction'
)->setName('show-sign-in');

$app->post(
    '/sign-in', 
    LoginController::class . ':loginAction'
)->setName('sign-in');

$app->get(
    '/sign-up', 
    RegisterController::class . ':showRegisterFormAction'
)->setName('show-sign-up');

$app->post(
    '/sign-up', 
    RegisterController::class . ':registerAction'
)->setName('sign-up');