<?php

use Minicli\Output\CliTheme;
use Minicli\Output\CliColors;

it('asserts that Default CLI theme sets all default styles', function () {
   $theme = CliTheme::default();

   assertIsArray($theme->getDefault());
   assertIsArray($theme->getAlt());
   assertIsArray($theme->getError());
   assertIsArray($theme->getErrorAlt());
   assertIsArray($theme->getSuccess());
   assertIsArray($theme->getSuccessAlt());
   assertIsArray($theme->getInfo());
   assertIsArray($theme->getInfoAlt());
});

it('asserts that default theme returns expected colors for default text', function () {
    assertContains(CliColors::FG_WHITE, CliTheme::default()->getDefault());
});

it('asserts that Unicorn CLI theme sets all default styles', function () {
    $theme = CliTheme::unicorn();

    assertIsArray($theme->getDefault());
    assertIsArray($theme->getAlt());
    assertIsArray($theme->getError());
    assertIsArray($theme->getErrorAlt());
    assertIsArray($theme->getSuccess());
    assertIsArray($theme->getSuccessAlt());
    assertIsArray($theme->getInfo());
    assertIsArray($theme->getInfoAlt());
});
