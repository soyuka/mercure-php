<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
            ->bind('$receiverLocator', service('messenger.receiver_locator'))
    ;

    $services->load('App\\', '../src/*')
        ->exclude('../src/{DependencyInjection,Entity,Tests,Kernel.php}');

    $services->load('App\\Controller\\', '../src/Controller/')->tag('controller.service_arguments');
};

