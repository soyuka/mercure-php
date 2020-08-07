<?php

use App\Controller\PublishController;
use App\Controller\SubscribeController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('mercure_publish', '/.well-known/mercure')
        ->controller(PublishController::class)
        ->methods(['POST'])
    ;

    $routes->add('mercure_subscribe', '/.well-known/mercure')
        ->controller(SubscribeController::class)
        ->methods(['GET'])
    ;
};
