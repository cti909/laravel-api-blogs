<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements IUserRepository
{

    public function getModel()
    {
        return User::class;
    }
    function updateUser(mixed $data, mixed $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $data["name"],
            'email' => $data["email"],
            'password' => bcrypt($data["password"])
        ]);
        return $user;
    }

}
