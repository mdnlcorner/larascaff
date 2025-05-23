<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Mulaidarinull\Larascaff\Forms\Components\Field;
use Mulaidarinull\Larascaff\Forms\Components\Layout;

trait HasRelationship
{
    protected array $relationship = [];

    public function addRelationshipToBeHandled(Field | Layout $form)
    {
        if ($form->getRelationship()) {
            $this->relationship[] = $form;
        }
    }
}
