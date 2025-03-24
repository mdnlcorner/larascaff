<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;

class Uploader extends Field
{
    protected string $path = 'storage';

    protected string $accept = 'image/png, image/jpeg, image/jpg, image/svg';

    protected string $disk = 'local';

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

    public function path(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function avatar()
    {
        $this->config['stylePanelLayout'] = 'compact circle';

        return $this;
    }

    public function allowReorder(bool $allowReorder = true)
    {
        $this->config['allowReorder'] = $allowReorder;

        return $this;
    }

    public function allowImagePreview(bool $allowImagePreview = true)
    {
        $this->config['allowImagePreview'] = $allowImagePreview;

        return $this;
    }

    public function imageCropAspectRatio(string $imageCropAspectRatio)
    {
        $this->config['imageCropAspectRatio'] = $imageCropAspectRatio;

        return $this;
    }

    public function cropperOptions($cropperOptions)
    {
        $this->config['cropperOptions'] = $cropperOptions;

        return $this;
    }

    public function disk(string $disk)
    {
        $this->disk = $disk;

        return $this;
    }

    public function linkPreview(bool $linkPreview = true)
    {
        $this->config['linkPreview'] = $linkPreview;

        return $this;
    }

    public function accept(string $accept)
    {
        $this->accept = $accept;

        return $this;
    }

    public function config(string $config)
    {
        $this->config = $config;

        return $this;
    }

    public function multiple(bool $multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function imageResizeTargetHeight(int $imageResizeTargetHeight)
    {
        $this->config['imageResizeTargetHeight'] = $imageResizeTargetHeight;

        return $this;
    }

    public function imageResizeTargetWidth(int $imageResizeTargetWidth)
    {
        $this->config['imageResizeTargetWidth'] = $imageResizeTargetWidth;

        return $this;
    }

    public function imagePreviewHeight(int $imagePreviewHeight)
    {
        $this->config['imagePreviewHeight'] = $imagePreviewHeight;

        return $this;
    }

    public function imageResizeMode(string $imageResizeMode)
    {
        $this->config['imageResizeMode'] = $imageResizeMode;

        return $this;
    }

    public function allowRemove(bool $allowRemove = true)
    {
        $this->config['allowRemove'] = $allowRemove;

        return $this;
    }

    public function imageEditor(bool $editor = true)
    {
        $this->config['imageEditor'] = $editor;

        return $this;
    }

    public function files(array $files)
    {
        $this->files = $files;

        return $this;
    }

    public function field(?string $field = null)
    {
        $this->field = $field ?? $this->name;

        return $this;
    }

    public function getField()
    {
        return $this->field;
    }

    public function view()
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
