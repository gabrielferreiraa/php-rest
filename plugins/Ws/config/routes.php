<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin('Ws', ['path' => '/ws'], function (RouteBuilder $routes) {
    $routes->resources('Users');
    $routes->resources('Companies');
    $routes->fallbacks(DashedRoute::class);
});
