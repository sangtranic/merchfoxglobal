<?php
namespace App\Repositories\Role;

use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Roles::class;
    }

    public function get($id)
    {
        return \App\Models\Roles::find($id);
    }
}
