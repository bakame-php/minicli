<?php

use Minicli\Output\BasicPrinter;


it('asserts that BasicPrinter outputs expected text', function () {
    $printer = new BasicPrinter();
    $printer->out("testing minicli");

})->expectOutputString("testing minicli");

it('asserts that BasicPrinter outputs newline', function () {
    $printer = new BasicPrinter();
    $printer->newline();
})->expectOutputString("\n");

it('asserts that BasicPrinter displays content wrapped in newlines', function () {
    $printer = new BasicPrinter();
    $printer->display("testing minicli");
})->expectOutputString("\ntesting minicli\n\n");
