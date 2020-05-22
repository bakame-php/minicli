<?php

use Minicli\App;

it('asserts controller extracts parameters from command call', function () {
    $app = getBasicApp();


});

it('asserts controller extracts parameters from command call', function () {
    $app = getBasicApp();

    $app->runCommand(['minicli', 'test', 'help', 'name=erika']);
})->expectOutputString('Hello erika');

it('asserts controller extracts flags from command call', function () {
    $app = getBasicApp();

    $app->runCommand(['minicli', 'test', 'help', 'name=erika', '--shout']);
})->expectOutputString('HELLO ERIKA');