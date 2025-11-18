<?php

namespace Mulaidarinull\Larascaff\Assets;

class Js extends Asset
{
    protected bool $isModule = false;

    public function module(bool $condition = true): static
    {
        $this->isModule = $condition;

        return $this;
    }

    public function isModule(): bool
    {
        return $this->isModule;
    }

    public function renderHtml(): string
    {
        $module = $this->isModule() ? 'type="module"' : '';

        $modulePreload = $this->isModule() ? "<link rel=\"modulepreload\" href=\"{$this->getPath()}\"></link>" : '';

        return "{$modulePreload}" . PHP_EOL . "<script {$module} src=\"{$this->getPath()}\"></script>";
    }
}
