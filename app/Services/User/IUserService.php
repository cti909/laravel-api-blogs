<?php

namespace App\Services\User;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;

interface IUserService
{
    public static function getAllUsers(Request $request);
    public static function getUserById($id);
    public static function updateUser(UpdateUserRequest $request, $id);
    // public static function updateUser(UpdateUserRequest $request, $id);
    public static function deleteUser($id);
}
