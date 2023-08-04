<?php

namespace App\Repositories\Auth;

use App\Repositories\IBaseRepository;

interface IAuthRepository extends IBaseRepository
{
    function me();
    function register($data);
}
