<?php
declare(strict_types=1);

use DI\Container;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Salle\LSCoins\Controller\HomeController;
use Salle\LSCoins\Controller\FlashController;
use Salle\LSCoins\Controller\UserController;
use Salle\LSCoins\Controller\ProfileController;
use Salle\LSCoins\Model\MysqlUserRepository;
use Salle\LSCoins\Model\PDOSingleton;
use Salle\LSCoins\Middleware\AuthorizationMiddleware;
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
    AuthorizationMiddleware::class,
    function (ContainerInterface $c) {
        $middleware = new AuthorizationMiddleware($c->get("flash"));
        return $middleware;
    }
);

$container->set(
    HomeController::class,
    function (ContainerInterface $c) {
        $controller = new HomeController($c->get("view"));
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
    UserController::class,
    function (Container $c) {
        $controller = new UserController($c->get("view"), $c->get(UserRepository::class), $c->get("flash"));
        return $controller;
    }
);

$container->set(
    ProfileController::class,
    function (Container $c) {
        $controller = new ProfileController($c->get("view"));
        return $controller;
    }
);

