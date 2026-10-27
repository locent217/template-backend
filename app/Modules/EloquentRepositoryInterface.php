<?php

namespace App\Modules;

use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param string|int $id
     * @param
     * @return Model
     */
    public function getById(string|int $id, array $relations): ?Model;
}
