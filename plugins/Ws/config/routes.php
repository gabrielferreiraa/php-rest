<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin('Ws', ['path' => '/ws'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->resources('Users');
    $routes->resources('Companies');
    $routes->resources('Products');
    $routes->resources('Orders');
    $routes->fallbacks(DashedRoute::class);
});
