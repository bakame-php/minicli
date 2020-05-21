<?php

use Minicli\App;
use Minicli\Command\CommandRegistry;
use Minicli\Config;
use Minicli\Output\CliPrinter;

$config = [
    'app_path' => __DIR__ . '/../Assets/Command',
    'theme' => 'unicorn',
];

$app = new App($config);


it('asserts App is created', function () use($app) {
    assertTrue($app instanceof \Minicli\App);
});

it('asserts App sets and gets signature', function () use($app) {
    assertStringContainsString("minicli", $app->getSignature());

    $app->setSignature("Testing minicli");
    assertEquals("Testing minicli", $app->getSignature());
});

it('asserts App has Config Service', function () use($app) {

    $config = $app->config;

    assertTrue($config instanceof Config);
});

it('asserts App has CommandRegistry Service', function () use($app) {

    $registry = $app->command_registry;

    assertTrue($registry instanceof CommandRegistry);
});

it('asserts App has Printer service', function () use($app) {

    $printer = $app->printer;

    assertTrue($printer instanceof CliPrinter);
});