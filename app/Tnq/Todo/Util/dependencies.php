<?php
    // DIC configuration
    $container = $app->getContainer();

    // view renderer
    $container['view'] = function($c) {
        $settings = $c->get('settings')['view'];
        return new Slim\Views\PhpRenderer($settings['templatePath']);
    };

    // monolog
    $container['logger'] = function($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new Monolog\Logger($settings['name']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(new Monolog\Handler\StreamHandler(
            $settings['path'], $settings['level']
        ));
        return $logger;
    };