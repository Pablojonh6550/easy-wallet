<?php

namespace App\Services\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\User\UserInterface;
use App\Models\User;

class UserService
{
    public function __construct(protected UserInterface $userRepository) {}

    /**
     * Retrieves all users.
     *
     * @return Collection A collection of user models.
     */
    public function all(): Collection
    {
        return $this->userRepository->all();
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id The ID of the user to find.
     *
     * @return ?User The found user model instance, or null if no user is found.
     */
    public function find(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create a new user with the provided data.
     *
     * @param array $data The data to be used for creating the user.
     * @return User The created user model instance.
     */
    public function create(array $data): Model
    {
        return $this->userRepository->create($data);
    }

    /**
     * Updates an existing user with the specified ID using the provided data.
     *
     * @param int $id The ID of the user to update.
     * @param array $data The data to update the user with.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }

    /**
     * Delete a user by their ID.
     *
     * @param int $id The ID of the user to delete.
     *
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
