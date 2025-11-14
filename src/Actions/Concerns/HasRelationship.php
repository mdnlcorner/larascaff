<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Mulaidarinull\Larascaff\Forms\Components\Field;
use Mulaidarinull\Larascaff\Forms\Components\Layout;

trait HasRelationship
{
    protected array $relationship = [];

    public function addRelationshipToBeHandled(mixed $field): void
    {
        if (method_exists($field, 'getRelationship') && $field->getRelationship()) {
            $this->relationship[] = $field;
        }
    }

    public function getRelationship(): array
    {
        return $this->relationship;
    }

    public function relationshipHandler(Field|Layout $input, Model $model)
    {
        $relationship = $model->{$input->getRelationship()}();
        switch (true) {
            case $relationship instanceof Relations\MorphToMany:
            case $relationship instanceof Relations\BelongsToMany:
                $relationship->sync($this->getFormData()[str_replace('[]', '', $input->getName())] ?? []);

                break;
            case $relationship instanceof Relations\MorphOne:
            case $relationship instanceof Relations\HasOne:
                if ($this->getInstance() instanceof \Mulaidarinull\Larascaff\Tables\Actions\DeleteAction) {
                    return $model->{$input->getRelationship()}()->delete();
                }

                if ($model->{$input->getRelationship()} && ($this->getInstance() instanceof \Mulaidarinull\Larascaff\Tables\Actions\EditAction)) {
                    $relation = $model->{$input->getRelationship()}->fill($this->getFormData()[$input->getRelationship()]);
                    $relation->save();
                } else {
                    $relationship->create($this->getFormData()[$input->getRelationship()]);
                }

                break;
            case $relationship instanceof Relations\HasMany:

                break;
        }
    }
}
