<?php

declare(strict_types=1);

namespace Minicli\Output;

use function in_array;

final class CliTheme implements CliThemeInterface
{
    private $default;
    private $alt;
    private $error;
    private $error_alt;
    private $success;
    private $success_alt;
    private $info;
    private $info_alt;

    public function __construct(
        array $default,
        array $alt,
        array $error,
        array $error_alt,
        array $success,
        array $success_alt,
        array $info,
        array $info_alt
    ) {
        $this->default = $this->filterColor($default);
        $this->alt = $this->filterColor($alt);
        $this->error = $this->filterColor($error);
        $this->error_alt = $this->filterColor($error_alt);
        $this->success = $this->filterColor($success);
        $this->success_alt = $this->filterColor($success_alt);
        $this->info = $this->filterColor($info);
        $this->info_alt = $this->filterColor($info_alt);
    }

    private function filterColor(array $colors): array
    {
        static $cliColors;
        $cliColors = $cliColors ?? (new \ReflectionClass(CliColors::class))->getConstants();

        $res = [];
        $first = $colors[0] ?? null;
        if (!isset($first) || !in_array($first, $cliColors, true)) {
            throw new \Exception('The foreground color is invalid, unsupported or undefined.');
        }
        $res[] = $first;

        $second = $colors[1] ?? null;
        if (null === $second) {
            return $res;
        }

        if (!in_array($second, $cliColors, true)) {
            throw new \Exception('The defined background color is invalid or unsupported.');
        }

        $res[] = $second;

        return $res;
    }

    public static function default(): self
    {
        return new self(
            [ CliColors::FG_WHITE ],
            [ CliColors::FG_BLACK, CliColors::BG_WHITE ],
            [ CliColors::FG_RED ],
            [ CliColors::FG_WHITE, CliColors::BG_RED ],
            [ CliColors::FG_GREEN ],
            [ CliColors::FG_WHITE, CliColors::BG_GREEN ],
            [ CliColors::FG_CYAN],
            [ CliColors::FG_WHITE, CliColors::BG_CYAN ]
        );
    }

    public static function unicorn(): self
    {
        return new self(
            [ CliColors::FG_CYAN ],
            [ CliColors::FG_BLACK, CliColors::BG_CYAN ],
            [ CliColors::FG_RED ],
            [ CliColors::FG_CYAN, CliColors::BG_RED ],
            [ CliColors::FG_GREEN ],
            [ CliColors::FG_BLACK, CliColors::BG_GREEN ],
            [ CliColors::FG_MAGENTA],
            [ CliColors::FG_WHITE, CliColors::BG_MAGENTA ]
        );
    }

    public function getDefault(): array
    {
        return $this->default;
    }

    public function getAlt(): array
    {
        return $this->alt;
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function getErrorAlt(): array
    {
        return $this->error_alt;
    }

    public function getSuccess(): array
    {
        return $this->success;
    }

    public function getSuccessAlt(): array
    {
        return $this->success_alt;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function getInfoAlt(): array
    {
        return $this->info_alt;
    }

    public function getColor(string $property): array
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return $this->default;
    }
}
