<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $categoryId
 * @property int    $createDate
 * @property int    $updateDate
 * @property string $name
 * @property string $itemId
 * @property string $description
 * @property string $url
 * @property string $urlImagePreviewOne
 * @property string $urlImagePreviewTwo
 * @property string $urlImageDesignOne
 * @property string $urlImageDesignTwo
 * @property string $color
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
       'itemId', 'categoryId', 'name', 'description', 'url', 'urlImagePreviewOne', 'urlImagePreviewTwo', 'urlImageDesignOne', 'urlImageDesignTwo', 'isFileDesign', 'createDate', 'createBy', 'updateDate', 'updateBy','color'
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
        'categoryId' => 'int', 'itemId' => 'string','name' => 'string', 'description' => 'string', 'url' => 'string', 'urlImagePreviewOne' => 'string', 'urlImagePreviewTwo' => 'string', 'urlImageDesignOne' => 'string', 'urlImageDesignTwo' => 'string','isFileDesign'=>'boolean', 'createDate' => 'timestamp', 'updateDate' => 'timestamp','color'=>'string'
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

    function getUrlImgDesign1Attribute(){
        if(!Helper::IsNullOrEmptyString($this->urlImageDesignOne)){
            return Helper::getImageUrlPath($this->urlImageDesignOne,'thumbnail', true);
        }
        return '';
    }
    function getUrlImgDesign2Attribute(){
        if(!Helper::IsNullOrEmptyString($this->urlImageDesignTwo)){
            return Helper::getImageUrlPath($this->urlImageDesignTwo,'thumbnail', true);
        }
        return '';
    }
    function getUrlImgOriginalDesign1Attribute(){
        if(!Helper::IsNullOrEmptyString($this->urlImageDesignOne)){
            return Helper::getImageUrlPath($this->urlImageDesignOne,'original', true);
        }
        return '';
    }
    function getUrlImgOriginalDesign2Attribute(){
        if(!Helper::IsNullOrEmptyString($this->urlImageDesignTwo)){
            return Helper::getImageUrlPath($this->urlImageDesignTwo,'original', true);
        }
        return '';
    }
    // Relations ...
}
