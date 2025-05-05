<?php

namespace App\Services\DataBank;

use App\Interfaces\DataBank\DataBankInterface;
use App\Models\DataBank;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DataBankService
{
    public function __construct(protected DataBankInterface $dataBankRepository) {}

    /**
     * Retrieves all DataBanks.
     *
     * @return Collection A collection of DataBank models.
     */
    public function all(): Collection
    {
        return $this->dataBankRepository->all();
    }

    /**
     * Finds a DataBank by its ID.
     *
     * @param int $id The ID of the DataBank to find.
     *
     * @return DataBank The found DataBank model instance.
     */
    public function find(int $id): DataBank
    {
        return $this->dataBankRepository->findById($id);
    }

    /**
     * Finds a DataBank by its account number.
     *
     * @param int $account The account number to search for.
     *
     * @return ?DataBank The found DataBank model instance, or null if no record is found.
     */
    public function findByAccount(int $account): ?DataBank
    {
        return $this->dataBankRepository->findByAccount($account);
    }

    /**
     * Creates a new DataBank record for a given user.
     *
     * @param User $user The user for whom the DataBank is being created.
     * @return DataBank The created DataBank model instance.
     */
    public function create(User $user): DataBank
    {
        return $this->dataBankRepository->create(
            DataBank::factory()->make(['user_id' => $user->id])->toArray()
        );
    }

    /**
     * Updates an existing DataBank record with the specified ID using the provided data.
     *
     * @param int $id The ID of the DataBank to update.
     * @param array $data The data to update the DataBank with.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        return $this->dataBankRepository->update($id, $data);
    }

    /**
     * Deletes a DataBank by its ID.
     *
     * @param int $id The ID of the DataBank to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        return $this->dataBankRepository->delete($id);
    }
}
