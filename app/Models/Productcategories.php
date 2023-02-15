<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $createDate
 * @property int    $updateDate
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $sizes
 * @property string $colors
 * @property string $styles
 * @property string $productTypes
 */
class Productcategories extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'productcategories';

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
        'name', 'description', 'url', 'sizes', 'colors', 'styles', 'productTypes', 'createDate', 'createBy', 'updateDate', 'updateBy'
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
        'id' => 'int', 'name' => 'string', 'description' => 'string', 'url' => 'string', 'sizes' => 'string', 'colors' => 'string', 'styles' => 'string', 'productTypes' => 'string', 'createDate' => 'timestamp', 'updateDate' => 'timestamp'
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
