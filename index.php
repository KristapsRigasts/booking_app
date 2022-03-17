<?php

use App\Redirect;
use App\View;

require_once 'vendor/autoload.php';

session_start();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/', ['App\Controllers\UsersController', 'home']);

    $r->addRoute('GET', '/users', ['App\Controllers\UsersController', 'index']);
    $r->addRoute('GET', '/users/{id:\d+}', ['App\Controllers\UsersController', 'show']);

    $r->addRoute('POST', '/users', ['App\Controllers\UsersController', 'store']);
    $r->addRoute('GET', '/users/register', ['App\Controllers\UsersController', 'register']);

    $r->addRoute('GET', '/users/login', ['App\Controllers\UsersController', 'logIn']);
    $r->addRoute('POST', '/users/login', ['App\Controllers\UsersController', 'validateLogIn']);
    $r->addRoute('GET', '/users/logout', ['App\Controllers\UsersController', 'logOut']);

    //Apartments
    $r->addRoute('GET', '/apartments', ['App\Controllers\ApartmentsController', 'index']);
    $r->addRoute('GET', '/apartments/{id:\d+}', ['App\Controllers\ApartmentsController', 'show']);

    $r->addRoute('GET', '/apartments/create', ['App\Controllers\ApartmentsController', 'create']);
    $r->addRoute('POST', '/apartments/create', ['App\Controllers\ApartmentsController', 'store']);
    $r->addRoute('POST', '/apartments/{id:\d+}/delete', ['App\Controllers\ApartmentsController', 'delete']);

    $r->addRoute('GET', '/apartments/{id:\d+}/edit', ['App\Controllers\ApartmentsController', 'edit']);
    $r->addRoute('POST', '/apartments/{id:\d+}', ['App\Controllers\ApartmentsController', 'update']);

    $r->addRoute('POST', '/apartments/availability', ['App\Controllers\ApartmentsController', 'check']);
    $r->addRoute('GET', '/apartments/availability', ['App\Controllers\ApartmentsController', 'availability']);
    $r->addRoute('POST', '/apartments/{id:\d+}/reservations', ['App\Controllers\ApartmentsController', 'reservation']);
    $r->addRoute('GET', '/apartments/{id:\d+}/confirmation', ['App\Controllers\ApartmentsController', 'confirmation']);
    $r->addRoute('GET', '/apartments/{id:\d+}/apartmentsreservations', ['App\Controllers\ApartmentsReservationController', 'index']);
    $r->addRoute('POST', '/apartments/{id:\d+}/apartmentsreservations', ['App\Controllers\ApartmentsReservationController', 'show']);

    //Reviews
    $r->addRoute('POST', '/apartments/{id:\d+}/reviews', ['App\Controllers\ReviewsController', 'store']);
    $r->addRoute('POST', '/apartments/{id:\d+}/reviews/{uid:\d+}/delete', ['App\Controllers\ReviewsController', 'delete']);

    //Rating
    $r->addRoute('POST', '/apartments/{id:\d+}/ratings', ['App\Controllers\ApartmentsRatingsController', 'rate']);

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars

        $controller = $handler[0];
        $method = $handler[1];

        /** @var View $response */
        $response = (new $controller)->$method($vars);

        $loader = new \Twig\Loader\FilesystemLoader('app/Views');
        $twig = new \Twig\Environment($loader);

        if($response instanceof View) {
            echo $twig->render($response->getPath() . '.html', $response->getVariables());
        }

        if ($response instanceof Redirect)
        {
            header('Location: ' . $response->getLocation());
            exit;
        }

        break;
}

if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

if (isset($_SESSION['inputs'])) {
    unset($_SESSION['inputs']);
}

if (isset($_SESSION['wrong']))
{
    unset($_SESSION['wrong']);
}