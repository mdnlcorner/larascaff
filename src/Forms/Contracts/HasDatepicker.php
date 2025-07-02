<?php

namespace Mulaidarinull\Larascaff\Forms\Contracts;

interface HasDatepicker
{
    public function config(array $config): static;

    public function getFormatPhp(): string;

    public function icon(bool $icon): static;

    public function format(string $format): static;

    public function unformat(): array;
}
