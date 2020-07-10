<?php

namespace App\Traits;

trait SoftDeletes
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected function runSoftDelete()
    {
        $query = $this->newModelQuery()->where($this->getKeyName(), $this->getKey());

        $entity = $query->first();

        $time = $this->freshTimestamp();

        if (is_null($this->softDeleteColumns)) {
            $this->softDeleteColumns = [];
        }

        $columns = [];
        if (count($this->softDeleteColumns)) {
            foreach ($this->softDeleteColumns as $softDeleteColumn) {
                $columns[$softDeleteColumn] = "{$entity->$softDeleteColumn}_{$entity->id}_deleted";
            }
        }

        $columns = array_merge($columns, [
            $this->getDeletedAtColumn() => $this->fromDateTime($time),
        ]);

        $this->{$this->getDeletedAtColumn()} = $time;

        if ($this->timestamps && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);
    }
}