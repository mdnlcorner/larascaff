<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Mulaidarinull\Larascaff\Forms\Components\Uploader;

trait HasMedia
{
    protected array $media = [];

    protected ?Model $oldModelValue = null;

    protected function addMediaToBeHandled(mixed $input)
    {
        if ($input instanceof Uploader) {
            $this->media[] = $input;
        }
    }

    protected function getMedia(): array
    {
        return $this->media;
    }

    protected function uploadMediaHandler(Uploader $input, Model $model)
    {
        $data = $this->getFormData();

        if ($input instanceof Uploader) {
            if (request()->post('_id')) {
                $model->oldModelValue = $this->oldModelValue;
                $model->updateMedia($input->getPath(), $data[$input->getName()] ?? null, $input->getField());
            } elseif (isset($data[$input->getName()])) {
                $model->storeMedia($input->getPath(), $data[$input->getName()] ?? null, $input->getField());
            }
        }
    }

    protected function deleteMediaHandler(Uploader $input, Model $model)
    {
        if ($input instanceof Uploader) {
            $filename = null;
            if ($input->getField()) {
                $filename = $input->getPath() . '/' . $model->{$input->getField()};
            }
            $model->deleteMedia($filename, $input->getField());
        }
    }
}
