<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Interfaces\User\UserInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(protected User $user) {}
}
