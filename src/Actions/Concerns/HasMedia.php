<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Mulaidarinull\Larascaff\Forms\Components\Field;
use Mulaidarinull\Larascaff\Forms\Components\Layout;
use Mulaidarinull\Larascaff\Forms\Components\Uploader;

trait HasMedia
{
    protected array $media = [];

    protected ?Model $oldModelValue = null;

    protected function addMediaToBeHandled(Field | Layout $input)
    {
        if ($input instanceof Uploader) {
            $this->media[] = $input;
        }
    }

    protected function getMedia(): array
    {
        return $this->media;
    }

    protected function uploadMediaHandler(Uploader $media, Model $model)
    {
        $data = $this->getFormData();

        if ($media instanceof Uploader) {
            if (request()->post('_id')) {
                $model->oldModelValue = $this->oldModelValue;
                $model->updateMedia($media->getPath(), $data[$media->getName()] ?? null, $media->getField());
            } elseif (isset($data[$media->getName()])) {
                $model->storeMedia($media->getPath(), $data[$media->getName()] ?? null, $media->getField());
            }
        }
    }
}
