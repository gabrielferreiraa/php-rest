<?php

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    $routes->setExtensions(['json']);
    $routes->resources('Companies');
    $routes->connect('/pages/*', ['controller' => 'Initial', 'action' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});

Plugin::routes();
