<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

interface BaseInterface
{
    public function all(): Collection;
    public function findById(int $id): ?Model;
    public function create(array $data): Authenticatable|Model;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
