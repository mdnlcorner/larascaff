<?php

namespace Mulaidarinull\Larascaff\Forms\Contracts;

interface HasDatepicker
{
    public function config(array $config): self;

    /**
     * @return string
     */
    public function getFormatPhp();

    public function icon(bool $icon);

    public function format($format);

    public function unformat();
}
