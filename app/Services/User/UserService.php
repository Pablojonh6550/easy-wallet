<?php

namespace App\Services\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\User\UserInterface;
use App\Services\DataBank\DataBankService;
use App\Models\User;

class UserService
{
    public function __construct(protected UserInterface $userRepository, protected DataBankService $dataBankService) {}

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

    public function create(array $data): User
    {
        $user = $this->userRepository->create($data);

        $this->dataBankService->create($user);

        return $user;
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
