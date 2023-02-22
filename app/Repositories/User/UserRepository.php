<?php
namespace App\Repositories\User;

use App\Models\Users;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Users::class;
    }

    public function getAllUser()
    {
        return \App\Models\Users::all();
    }
    public function getByUserName($userName)
    {
        return Users::where('userName', $userName)->get();
    }
}
