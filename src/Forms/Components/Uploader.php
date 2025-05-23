<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

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

    public function path(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

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

    public function disk(string $disk): static
    {
        $this->disk = $disk;

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

    public function tempUploadHandler(Request $request)
    {
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        $requestKey = array_key_first($files);

        /**
         * @var UploadedFile $file
         */
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        // Store the file in a temporary location and return the location
        // for FilePond to use.
        $filename = $file->store(
            path: 'tmp'
        );

        return response()->json(['filename' => $filename]);
    }

    public function uploadHandler(Request $request)
    {
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        foreach ($files as $name => $file) {
            $request->validate([
                $name => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            ]);
        }
        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        $requestKey = array_key_first($files);

        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        $filename = Storage::disk(config('larascaff.default_filesystem_disk'))->put($request->path, $file);

        return response()->json(['filename' => $filename]);
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
