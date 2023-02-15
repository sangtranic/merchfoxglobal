<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $categoryId
 * @property int    $createDate
 * @property int    $updateDate
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $urlImagePreviewOne
 * @property string $urlImagePreviewTwo
 * @property string $urlImageDesignOne
 * @property string $urlImageDesignTwo
 */
class Products extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
        'categoryId', 'name', 'description', 'url', 'urlImagePreviewOne', 'urlImagePreviewTwo', 'urlImageDesignOne', 'urlImageDesignTwo', 'isFileDesign', 'createDate', 'createBy', 'updateDate', 'updateBy'
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
        'categoryId' => 'int', 'name' => 'string', 'description' => 'string', 'url' => 'string', 'urlImagePreviewOne' => 'string', 'urlImagePreviewTwo' => 'string', 'urlImageDesignOne' => 'string', 'urlImageDesignTwo' => 'string', 'createDate' => 'timestamp', 'updateDate' => 'timestamp'
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
