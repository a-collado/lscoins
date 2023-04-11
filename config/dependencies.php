<?php
declare(strict_types=1);

use DI\Container;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Controller\VisitsController;
use Salle\LSCoins\Controller\CookieMonsterController;
use Salle\LSCoins\Controller\FlashController;
use Salle\LSCoins\Controller\CreateUserController;
use Salle\LSCoins\Controller\SimpleFormController;
use Salle\LSCoins\Controller\LoginController;
use Salle\LSCoins\Controller\RegisterController;
use Salle\LSCoins\Model\MysqlUserRepository;
use Salle\LSCoins\Model\PDOSingleton;
use Psr\Container\ContainerInterface;


$container = new Container();

$container->set('db', function () {
    return PDOSingleton::getInstance(
        $_ENV['MYSQL_ROOT_USER'],
        $_ENV['MYSQL_ROOT_PASSWORD'],
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_PORT'],
        $_ENV['MYSQL_DATABASE']
    );
});

$container->set(
    'view',
    function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    }
);

$container->set(
    'flash',
    function () {
        return new Messages();
    }
);


$container->set(
    HomeController::class,
    function (ContainerInterface $c) {
        $controller = new HomeController($c->get("view"), $c->get("flash"));
        return $controller;
    }
);

$container->set(
    VisitsController::class,
    function (ContainerInterface $c) {
        $controller = new VisitsController($c->get("view"));
        return $controller;
    }
);


$container->set(
    CookieMonsterController::class,
    function (ContainerInterface $c) {
        $controller = new CookieMonsterController($c->get("view"));
        return $controller;
    }
);

$container->set(
    FlashController::class,
    function (Container $c) {
        $controller = new FlashController($c->get("view"), $c->get("flash"));
        return $controller;
    }
);

$container->set(UserRepository::class, function (ContainerInterface $container) {
    return new MySQLUserRepository($container->get('db'));
});

$container->set(
    CreateUserController::class,
    function (Container $c) {
        $controller = new CreateUserController($c->get("view"), $c->get(UserRepository::class));
        return $controller;
    }
);

$container->set(
    SimpleFormController::class,
    function (Container $c) {
        $controller = new SimpleFormController($c->get("view"));
        return $controller;
    }
);

$container->set(
    FileController::class,
    function (Container $c) {
        $controller = new FileController($c->get("view"));
        return $controller;
    }
);

$container->set(
    LoginController::class,
    function (Container $c) {
        $controller = new LoginController($c->get("view"));
        return $controller;
    }
);

$container->set(
    RegisterController::class,
    function (Container $c) {
        $controller = new RegisterController($c->get("view"), $c->get(UserRepository::class));
        return $controller;
    }
);