<?php

namespace App\Services\DataBank;

use App\Interfaces\DataBank\DataBankInterface;
use App\Models\DataBank;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DataBankService
{
    public function __construct(protected DataBankInterface $dataBankRepository) {}

    public function all(): Collection
    {
        return $this->dataBankRepository->all();
    }

    public function find(int $id): DataBank
    {
        return $this->dataBankRepository->findById($id);
    }

    public function findByAccount(int $account): ?DataBank
    {
        return $this->dataBankRepository->findByAccount($account);
    }

    public function create(User $user): DataBank
    {
        return $this->dataBankRepository->create(
            DataBank::factory()->make(['user_id' => $user->id])->toArray()
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->dataBankRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->dataBankRepository->delete($id);
    }
}
