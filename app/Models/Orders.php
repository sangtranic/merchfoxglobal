<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $orderNumber
 * @property string $channekId
 * @property string $customerInfor
 * @property string $note
 * @property string $itemId
 * @property string $sku
 * @property string $style
 * @property string $size
 * @property string $color
 * @property string $productType
 * @property string $fulfillCode
 * @property string $fullfillUserFullName
 * @property string $trackingCode
 * @property string $carrier
 * @property string $jsonData
 * @property int    $vpsId
 * @property int    $statusId
 * @property int    $quantity
 * @property int    $fulfillStatusId
 * @property int    $trackingStatusId
 * @property int    $syncStoreStatusId
 * @property int    $createDate
 * @property int    $updateDate
 * @property float  $price
 * @property float  $cost
 * @property float  $profit
 */
class Orders extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
        'orderNumber', 'channekId', 'vpsId', 'userId', 'customerInfor', 'statusId', 'note', 'productId', 'itemId', 'sku', 'style', 'size', 'color', 'productType', 'quantity', 'price', 'cost', 'profit', 'fulfillStatusId', 'fulfillCode', 'fullfillUserFullName', 'trackingStatusId', 'trackingCode', 'carrier', 'syncStoreStatusId', 'jsonData', 'createDate', 'createBy', 'updateDate', 'updateBy'
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
        'orderNumber' => 'string', 'channekId' => 'string', 'vpsId' => 'int', 'customerInfor' => 'string', 'statusId' => 'int', 'note' => 'string', 'itemId' => 'string', 'sku' => 'string', 'style' => 'string', 'size' => 'string', 'color' => 'string', 'productType' => 'string', 'quantity' => 'int', 'price' => 'double', 'cost' => 'double', 'profit' => 'double', 'fulfillStatusId' => 'int', 'fulfillCode' => 'string', 'fullfillUserFullName' => 'string', 'trackingStatusId' => 'int', 'trackingCode' => 'string', 'carrier' => 'string', 'syncStoreStatusId' => 'int', 'jsonData' => 'string', 'createDate' => 'timestamp', 'updateDate' => 'timestamp'
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
