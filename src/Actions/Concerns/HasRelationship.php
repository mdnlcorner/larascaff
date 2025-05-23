<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Mulaidarinull\Larascaff\Forms\Components\Field;
use Mulaidarinull\Larascaff\Forms\Components\Layout;
use Illuminate\Database\Eloquent\Relations;

trait HasRelationship
{
    protected array $relationship = [];

    public function addRelationshipToBeHandled(Field | Layout $form): void
    {
        if ($form->getRelationship()) {
            $this->relationship[] = $form;
        }
    }

    public function getRelationship(): array
    {
        return $this->relationship;
    }

    public function relationshipHandler(Field | Layout $input, Model $model)
    {
        $relationship = $model->{$input->getRelationship()}();
        switch (true) {
            case $relationship instanceof Relations\MorphToMany:
            case $relationship instanceof Relations\BelongsToMany:
                $relationship->sync($this->getFormData()[str_replace('[]', '', $input->getName())]);

                break;
            case $relationship instanceof Relations\MorphOne:
            case $relationship instanceof Relations\HasOne:
                
                break;
            case $relationship instanceof Relations\HasMany:

                break;
        }
    }
}
