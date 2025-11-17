<?php

namespace Mulaidarinull\Larascaff\Forms\Contracts;

interface HasDatepicker
{
    public function options(array $options): static;

    public function getFormatPicker(): string;

    public function icon(bool $icon): static;

    public function format(string $format): static;

    public function getFormat(): string;

    public function unformat(): array;
}
