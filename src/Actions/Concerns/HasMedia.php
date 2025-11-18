<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Mulaidarinull\Larascaff\Forms\Components\FileUpload;

trait HasMedia
{
    protected array $media = [];

    protected ?Model $oldModelValue = null;

    protected function addMediaToBeHandled(mixed $input)
    {
        if ($input instanceof FileUpload) {
            $this->media[] = $input;
        }
    }

    protected function getMedia(): array
    {
        return $this->media;
    }

    protected function uploadMediaHandler(FileUpload $input, Model $model)
    {
        $data = $this->getFormData();

        if ($input instanceof FileUpload) {
            if (request()->post('_id')) {
                $model->oldModelValue = $this->oldModelValue;
                $model->updateMedia($input->getPath(), $data[$input->getName()] ?? null, $input->getName());
            } elseif (isset($data[$input->getName()])) {
                $model->storeMedia($input->getPath(), $data[$input->getName()] ?? null, $input->getName());
            }
        }
    }

    protected function deleteMediaHandler(FileUpload $input, Model $model)
    {
        if ($input instanceof FileUpload) {
            $filename = null;
            if ($input->getField()) {
                $filename = $input->getPath() . '/' . $model->{$input->getField()};
            }
            $model->deleteMedia($filename, $input->getField());
        }
    }
}
