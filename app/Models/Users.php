<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 */
class Users extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'userName', 'password', 'fullName', 'email', 'mobile', 'statusId', 'roleId', 'createDate', 'createBy', 'updateDate', 'updateBy'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'userName' => 'string', 'password' => 'string', 'fullName' => 'string', 'email' => 'string', 'mobile' => 'string', 'statusId' => 'int', 'roleId' => 'int', 'createDate' => 'timestamp', 'updateDate' => 'timestamp'
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

    // Relations ...
}
