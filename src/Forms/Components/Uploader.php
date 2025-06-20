<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns\HasMedia;

class Uploader extends Field
{
    use HasMedia;

    protected string $accept = 'image/png, image/jpeg, image/jpg, image/svg';

    protected ?string $field = null;

    protected bool $multiple = false;

    protected array $config = [
        'imageEditor' => false,
        'allowReorder' => false,
        'allowRemove' => true,
        'allowImagePreview' => true,
        'linkPreview' => false,
        'imageResizeTargetHeight' => 600,
        'imageResizeTargetWidth' => 600,
        'imagePreviewHeight' => 170,
        'imageResizeMode' => 'cover',
        'imageCropAspectRatio' => '1:1',
    ];

    protected array $cropperOptions = [
        'ascpectRatio' => '16:9',
    ];

    public function avatar(): static
    {
        $this->config['stylePanelLayout'] = 'compact circle';

        return $this;
    }

    public function allowReorder(bool $allowReorder = true): static
    {
        $this->config['allowReorder'] = $allowReorder;

        return $this;
    }

    public function allowImagePreview(bool $allowImagePreview = true): static
    {
        $this->config['allowImagePreview'] = $allowImagePreview;

        return $this;
    }

    public function imageCropAspectRatio(string $imageCropAspectRatio): static
    {
        $this->config['imageCropAspectRatio'] = $imageCropAspectRatio;

        return $this;
    }

    public function cropperOptions($cropperOptions): static
    {
        $this->config['cropperOptions'] = $cropperOptions;

        return $this;
    }

    public function linkPreview(bool $linkPreview = true): static
    {
        $this->config['linkPreview'] = $linkPreview;

        return $this;
    }

    public function accept(string $accept): static
    {
        $this->accept = $accept;

        return $this;
    }

    public function config(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function multiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function imageResizeTargetHeight(int $imageResizeTargetHeight): static
    {
        $this->config['imageResizeTargetHeight'] = $imageResizeTargetHeight;

        return $this;
    }

    public function imageResizeTargetWidth(int $imageResizeTargetWidth): static
    {
        $this->config['imageResizeTargetWidth'] = $imageResizeTargetWidth;

        return $this;
    }

    public function imagePreviewHeight(int $imagePreviewHeight): static
    {
        $this->config['imagePreviewHeight'] = $imagePreviewHeight;

        return $this;
    }

    public function imageResizeMode(string $imageResizeMode): static
    {
        $this->config['imageResizeMode'] = $imageResizeMode;

        return $this;
    }

    public function allowRemove(bool $allowRemove = true): static
    {
        $this->config['allowRemove'] = $allowRemove;

        return $this;
    }

    public function imageEditor(bool $editor = true): static
    {
        $this->config['imageEditor'] = $editor;

        return $this;
    }

    public function files(array $files): static
    {
        $this->files = $files;

        return $this;
    }

    public function field(?string $field = null): static
    {
        $this->field = $field ?? $this->name;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function view(): string
    {
        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.uploader 
                :name="$name" 
                :label="$label" 
                :multiple="$multiple"
                :accept="$accept"
                :columnSpan="$columnSpan"
                :path="$path"
                :config="$config"
                :files="$files"
                :disk="$disk"
                :cropperOptions="$cropperOptions"
            />
            HTML,
            [
                'name' => $this->name,
                'label' => $this->label,
                'value' => $this->value,
                'multiple' => $this->multiple,
                'accept' => $this->accept,
                'columnSpan' => $this->columnSpan,
                'path' => $this->path,
                'config' => $this->config,
                'files' => $this->files ?? getRecord()->getMediaUrl($this->field),
                'disk' => $this->disk,
                'cropperOptions' => $this->cropperOptions,
            ]
        );
    }
}
