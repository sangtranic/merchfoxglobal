<?php
namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getAllUser();
    public function getByUserName($userName);
    public function getByUserNameAndStatus($userName,$statusId);
    public function getByEmail($email);
}
