<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $userName
 * @property string $password
 * @property string $fullName
 * @property string $email
 * @property string $mobile
 * @property int    $statusId
 * @property int    $roleId
 * @property int    $createDate
 * @property int    $updateDate
 * @property string $remember_token
 * @property string $role
 */
class Users extends Authenticatable
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    const CREATED_AT = 'createDate';
    const UPDATED_AT = 'updateDate';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userName', 'password', 'fullName', 'email', 'mobile', 'statusId', 'roleId', 'createDate', 'createBy', 'updateDate', 'updateBy','remember_token', 'role'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'userName' => 'string', 'password' => 'string', 'fullName' => 'string', 'email' => 'string', 'mobile' => 'string',
        'statusId' => 'int', 'roleId' => 'int', 'createDate' => 'timestamp', 'updateDate' => 'timestamp',
        'remember_token' => 'string' , 'role' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'createDate', 'updateDate'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Scopes...

    // Functions ...
    public function role()
    {
        return $this->hasOne('App\Models\Roles');
    }
    // Relations ...
}

