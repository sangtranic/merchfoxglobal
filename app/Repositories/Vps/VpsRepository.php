<?php
namespace App\Repositories\Vps;

use App\Repositories\BaseRepository;

class VpsRepository extends BaseRepository implements VpsRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Vps::class;
    }

    public function get($id)
    {
        return \App\Models\Vps::find($id);
    }
}
