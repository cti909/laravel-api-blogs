<?php

namespace App\Repositories\User;

use App\Repositories\IBaseRepository;

interface IUserRepository extends IBaseRepository
{
    function updateUser(mixed $data, mixed $id);
}
