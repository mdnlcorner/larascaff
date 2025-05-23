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
}
