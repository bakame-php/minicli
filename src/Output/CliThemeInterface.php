<?php

declare(strict_types=1);

namespace Minicli\Output;

interface CliThemeInterface
{
    /**
     * @return array<string>
     */
    public function getDefault(): array;

    /**
     * @return array<string>
     */
    public function getAlt(): array;

    /**
     * @return array<string>
     */
    public function getError(): array;

    /**
     * @return array<string>
     */
    public function getErrorAlt(): array;

    /**
     * @return array<string>
     */
    public function getSuccess(): array;

    /**
     * @return array<string>
     */
    public function getSuccessAlt(): array;

    /**
     * @return array<string>
     */
    public function getInfo(): array;

    /**
     * @return array<string>
     */
    public function getInfoAlt(): array;

    /**
     * @return array<string>
     */
    public function getColor(string $name): array;
}
