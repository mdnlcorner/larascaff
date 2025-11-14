<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns\HasMedia;

class Uploader extends Field
{
    use HasMedia;

    protected string $accept = 'image/png, image/jpeg, image/jpg, image/svg';

    protected bool $multiple = false;

    protected array $config = [
        'allowImageCrop' => false,
        'allowImageResize' => false,
        'imageResizeUpscale' => true,
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

    public function allowImageCrop(bool $status): static
    {
        $this->config['allowImageCrop'] = $status;

        return $this;
    }

    public function allowReorder(bool $status = true): static
    {
        $this->config['allowReorder'] = $status;

        return $this;
    }

    public function allowImageResize(bool $status = true): static
    {
        $this->config['allowImageResize'] = $status;

        return $this;
    }

    public function imageResizeUpscale(bool $status = true): static
    {
        $this->config['imageResizeUpscale'] = $status;

        return $this;
    }

    public function allowImagePreview(bool $status = true): static
    {
        $this->config['allowImagePreview'] = $status;

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

    public function linkPreview(bool $status = true): static
    {
        $this->config['linkPreview'] = $status;

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

    public function multiple(bool $status = true): static
    {
        $this->multiple = $status;

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

    public function allowRemove(bool $status = true): static
    {
        $this->config['allowRemove'] = $status;

        return $this;
    }

    public function imageEditor(bool $status = true): static
    {
        $this->config['imageEditor'] = $status;

        return $this;
    }

    public function files(array $files): static
    {
        $this->files = $files;

        return $this;
    }

    protected function resolvePath(): string
    {
        $path = str($this->path)->ltrim('/')->finish('/');

        if ($this->disk == 'public') {
            $path = $path->start('storage/');
        }

        return $path->toString();
    }

    protected function resolveConfig(): array
    {
        $config = $this->config;

        // avatar
        if (isset($config['stylePanelLayout']) && $config['stylePanelLayout'] == 'compact circle') {
            if (! isset($config['imageResizeTargetHeight'])) {
                $config['imageResizeTargetHeight'] = 200;
            }
            if (! isset($config['imageResizeTargetWidth'])) {
                $config['imageResizeTargetWidth'] = 200;
            }
            if (! isset($config['imageCropAspectRatio'])) {
                $config['imageCropAspectRatio'] = '1:1';
            }
        }

        return $config;
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
                'path' => $this->resolvePath(),
                'config' => $this->resolveConfig(),
                'files' => $this->files ?? getRecord()->getMedia($this->name),
                'disk' => $this->disk,
                'cropperOptions' => $this->cropperOptions,
            ]
        );
    }
}
