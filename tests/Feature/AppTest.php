<?php

use Minicli\App;
use Minicli\Command\CommandRegistry;
use Minicli\Config;
use Minicli\Output\CliPrinter;
use Minicli\Output\BasicPrinter;
use Minicli\Output\OutputService;


it('asserts App is created', function () {
    $app = getBasicApp();

    assertTrue($app instanceof App);
});

it('asserts App sets, gets and prints signature', function () {

    $app = getBasicApp();
    $app->addService('printer', new OutputService(new BasicPrinter()));

    assertStringContainsString("minicli", $app->getSignature());

    $app->setSignature("Testing minicli");
    assertEquals("Testing minicli", $app->getSignature());

    $app->printSignature();
})->expectOutputString("\nTesting minicli\n\n");

it('asserts App has Config Service', function () {

    $app = getBasicApp();

    $config = $app->config;

    assertTrue($config instanceof Config);
});

it('asserts App has CommandRegistry Service', function () {

    $app = getBasicApp();

    $registry = $app->command_registry;

    assertTrue($registry instanceof CommandRegistry);
});

it('asserts App has Printer service', function () {

    $app = getBasicApp();

    $printer = $app->printer;

    assertTrue($printer instanceof OutputService);
});

it('asserts App returns null when a service is not found', function () {

    $app = getBasicApp();

    $service = $app->inexistent_service;

    assertNull($service);
});

it('asserts App registers and executes single command', function () {

    $app = getBasicApp();

    $app->registerCommand('minicli-test', function() use ($app){
        $app->getPrinter()->rawOutput("testing minicli");
    });

    $command = $app->command_registry->getCallable('minicli-test');
    assertIsCallable($command);

    $app->runCommand(['minicli', 'minicli-test']);
})->expectOutputString("testing minicli");

it('asserts App executes command from namespace', function () {
    $app = getBasicApp();
    /** @var CliPrinter $printer */
    $printer = $app->getPrinter();
    $printer->setStyle('');

    $app->runCommand(['minicli', 'test']);
})->expectOutputString("test default");

it('asserts App prints signature when no command is specified', function () {
    $app = getBasicApp();
    $app->addService('printer', new OutputService(new BasicPrinter()));

    $app->runCommand(['minicli']);
})->expectOutputString("\n./minicli help\n\n");
