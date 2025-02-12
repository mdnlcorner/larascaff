<?php

namespace Mulaidarinull\Larascaff\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected Model | null $record = null;

    public function setRecord(Model $record)
    {
        $this->record = $record;
    }

    public function getRecord($key = null)
    {
        if (!$this->record) {
            $this->record = new self;
        }
        if ($key) {
            if (str_contains($key, '.')) {
                $relations = explode('.', $key);
                $name = array_pop($relations);
                $this->record->loadMissing(implode('.', $relations));
                $withRelation = $this->record;
                foreach ($relations as $relation) {
                    $withRelation = $withRelation->{$relation};
                }
                if ($withRelation instanceof \Illuminate\Database\Eloquent\Collection) {
                    $arrayValue = [];
                    foreach ($withRelation as $relation) {
                        $arrayValue[] = $relation->{$name};
                    }
                    return $arrayValue;
                }
                return $withRelation->{$name};
            }
            return $this->record->{$key};
        }
        return $this->record;
    }
}
