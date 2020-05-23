<?php

use Minicli\Output\CliThemeInterface;
use Minicli\Output\CliTheme;
use Minicli\Output\CliColors;
use Minicli\Output\CliPrinter;

function getDefaultOutput($text): string
{
    return sprintf("\e[%sm%s\e[0m", CliColors::FG_WHITE, $text);
}

function getAltOutput($text): string
{
    return sprintf("\e[%s;%sm%s\e[0m", CliColors::FG_BLACK, CliColors::BG_WHITE, $text);
}

function getErrorOutput($text): string
{
    return sprintf("\e[%sm%s\e[0m", CliColors::FG_RED, $text);
}

function getInfoOutput($text): string
{
    return sprintf("\e[%sm%s\e[0m", CliColors::FG_CYAN, $text);
}

function getSuccessOutput($text): string
{
    return sprintf("\e[%sm%s\e[0m", CliColors::FG_GREEN, $text);
}

it('asserts that CliPrinter sets default theme upon instantiation', function () {
    $printer = new CliPrinter();

    assertInstanceOf(CliTheme::class, $printer->getTheme());
});

it('asserts that CliPrinter correctly sets custom theme', function () {
    $printer = new CliPrinter();
    $printer->setTheme(CliTheme::unicorn());

    assertInstanceOf(CliThemeInterface::class, $printer->getTheme());
});

it('asserts that CliPrinter outputs in color', function () {
    $printer = new CliPrinter();

    $text = $printer->format("testing minicli", "alt");
    $expected = getAltOutput("testing minicli");

    assertEquals($expected, $text);
});

it('asserts that CliPrinter outputs correct style', function () {
    $printer = new CliPrinter();
    $printer->setStyle("alt");
    $printer->out("testing minicli");

})->expectOutputString(getAltOutput("testing minicli"));

it('asserts that CliPrinter outputs newline', function () {
    $printer = new CliPrinter();
    $printer->newline();
})->expectOutputString("\n");

it('asserts that CliPrinter displays content wrapped in newlines', function () {
    $printer = new CliPrinter();
    $printer->display("testing minicli");
})->expectOutputString("\n" . getDefaultOutput("testing minicli") . "\n\n");

it('asserts that CliPrinter displays error with expected style', function() {
    $printer = new CliPrinter();
    $printer->error("error minicli");
})->expectOutputString("\n" . getErrorOutput("error minicli") . "\n\n");

it('asserts that CliPrinter displays info with expected style', function() {
    $printer = new CliPrinter();
    $printer->info("info minicli");
})->expectOutputString("\n" . getInfoOutput("info minicli") . "\n\n");

it('asserts that CliPrinter displays success with expected style', function() {
    $printer = new CliPrinter();
    $printer->success("success minicli");
})->expectOutputString("\n" . getSuccessOutput("success minicli") . "\n\n");
