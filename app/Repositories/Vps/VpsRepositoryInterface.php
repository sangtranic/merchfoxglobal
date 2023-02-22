<?php
namespace App\Repositories\Vps;

use App\Repositories\RepositoryInterface;

interface VpsRepositoryInterface extends RepositoryInterface
{
    public function get($id);
}
