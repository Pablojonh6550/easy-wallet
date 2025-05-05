<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\BaseInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class BaseRepository implements BaseInterface
{
    /**
     * BaseRepository constructor.
     *
     * @param Model $model The model to be used for repository operations.
     */
    public function __construct(protected Model $model) {}

    /**
     * Returns all records from the model.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find a model by its ID.
     *
     * @param int $id The ID to search for.
     * @return ?Model The found model instance, or null if no record is found.
     */
    public function findById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Create a new record in the model.
     *
     * @param array $data The data to be inserted.
     * @return Model The created model instance.
     */
    public function create(array $data): Authenticatable|Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a model record with the given data.
     *
     * @param int $id The ID of the model to update.
     * @param array $data The data to update the model with.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete a model record by its ID.
     *
     * @param int $id The ID of the model to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }
}
