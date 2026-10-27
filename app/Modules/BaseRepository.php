<?php

namespace App\Modules;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param string $id
     * @param array $attributes
     *
     * @return Model
     */
    public function update(string $id, array $attributes): Model
    {
        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * @param string|int $id
     * @param array $relations
     * @return Model|null
     */
    public function getById(string|int $id, array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    /**
     * @param string|int $id
     *
     * @return Boolean
     */
    public function delete(string|int $id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * @param array $conditions
     * @return Boolean
     */
    public function deleteByCondition(array $conditions): bool
    {
        $finalQuery = $this->model->query();

        foreach ($conditions as $condition){
            $finalQuery = $finalQuery->where($condition->column, $condition->value);
        }

        $finalQuery = $finalQuery->delete();

        return $finalQuery;
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function insertMany(array $data): bool
    {
        return $this->model->insert($data);
    }
}
