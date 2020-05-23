<?php

declare(strict_types=1);

namespace Minicli\Output;

use Minicli\OutputInterface;

class CliPrinter implements OutputInterface
{
    /**
     * @var  CliThemeInterface
     */
    private $theme;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string
     */
    private $style;

    public function __construct(?OutputInterface $output = null, ?CliThemeInterface $theme = null, string $style = 'default')
    {
        $this->output = $output ?? new BasicPrinter();
        $this->theme = $theme ?? CliTheme::default();
        $this->style = $style;
    }

    public function getTheme(): CliThemeInterface
    {
        return $this->theme;
    }

    public function setTheme(CliThemeInterface $theme): void
    {
        $this->theme = $theme;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function getStyle(string $style): void
    {
        $this->style = $style;
    }

    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    public function newline(): void
    {
       $this->output->newline();
    }

    public function out(string $message): void
    {
        $message = $this->format($message, $this->style);

        $this->output->out($message);
    }

    public function display(string $message): void
    {
        $message = $this->format($message, $this->style);

        $this->output->display($message);
    }

    public function rawOutput(string $message): void
    {
        $this->output->out($message);
    }

    public function format(string $message, string $style): string
    {
        if ('' === $style) {
            return $message;
        }

        $style_colors = $this->theme->getColor($style);
        $bg = '';
        if (isset($style_colors[1])) {
            $bg = ';' . $style_colors[1];
        }

        $output = sprintf("\e[%s%sm%s\e[0m", $style_colors[0], $bg, $message);

        return $output;
    }

    public function error(string $message): void
    {
        $message = $this->format($message, "error");

        $this->output->display($message);
    }

    public function info(string $message): void
    {
        $message = $this->format($message, "info");

        $this->output->display($message);
    }

    public function success(string $message): void
    {
        $message = $this->format($message, "success");

        $this->output->display($message);
    }

    public function printTable(array $table, int $min_col_size = 10, bool $with_header = true, bool $spacing = true): void
    {
        if ($spacing) {
            $this->output->newline();
        }

        $first = true;
        foreach ($table as $index => $row) {

            $style = "default";
            if ($first && $with_header) {
                $style = "info_alt";
            }

            $this->printRow($table, $index, $style, $min_col_size);
            $first = false;
        }

        if ($spacing) {
            $this->newline();
        }
    }

    public function printRow(array $table, int $row, string $style = "default", int $min_col_size = 5): void
    {
        foreach ($table[$row] as $column => $table_cell) {
            $col_size = $this->calculateColumnSize($column, $table, $min_col_size);

            $this->printCell($table_cell, $style, $col_size);
        }

        $this->newline();
    }

    protected function printCell(string $table_cell, string $style = "default", int $col_size = 5): void
    {
        $table_cell = str_pad($table_cell, $col_size);
        $message = $this->format($table_cell, $style);

        $this->output->out($message);
    }

    protected function calculateColumnSize(string $column, array $table, int $min_col_size = 5): int
    {
        $size = $min_col_size;

        foreach ($table as $row) {
            $size = strlen($row[$column]) > $size ? strlen($row[$column]) + 2 : $size;
        }

        return $size;
    }
}
