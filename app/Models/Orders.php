<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $orderNumber
 * @property string $channelId
 * @property string $shipToAddressID
 * @property string $shipToAddressName
 * @property string $shipToAddressPhone
 * @property string $shipToAddressLine1
 * @property string $shipToAddressLine2
 * @property string $shipToAddressCity
 * @property string $shipToAddressCounty
 * @property string $shipToAddressStateOrProvince
 * @property string $shipToAddressPostalCode
 * @property string $shipToAddressCountry
 * @property string $note
 * @property string $itemId
 * @property string $sku
 * @property string $style
 * @property string $size
 * @property string $color
 * @property string $productType
 * @property string $fulfillCode
 * @property string $fulfillUserFullName
 * @property string $trackingCode
 * @property string $carrier
 * @property int    $vpsId
 * @property int    $sellerId
 * @property int    $statusId
 * @property int    $categoryId
 * @property int    $quantity
 * @property int    $fulfillStatusId
 * @property int    $trackingStatusId
 * @property int    $carrierStatusId
 * @property int    $syncStoreStatusId
 * @property int    $created_at
 * @property int    $createBy
 * @property int    $updated_at
 * @property int    $updateBy
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
        'orderNumber', 'channelId', 'vpsId', 'sellerId', 'shipToAddressID', 'shipToAddressName', 'shipToAddressPhone', 'shipToAddressLine1', 'shipToAddressLine2', 'shipToAddressCity', 'shipToAddressCounty', 'shipToAddressStateOrProvince', 'shipToAddressPostalCode', 'shipToAddressCountry', 'statusId', 'note', 'categoryId', 'productId', 'itemId', 'sku', 'style', 'size', 'color', 'productType', 'quantity', 'price', 'cost', 'profit', 'isFB', 'fulfillStatusId', 'fulfillCode', 'fulfillUserFullName', 'trackingStatusId', 'trackingCode', 'carrier', 'carrierStatusId', 'syncStoreStatusId', 'created_at', 'createBy', 'updated_at', 'updateBy'
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
        'orderNumber' => 'string', 'channelId' => 'string', 'vpsId' => 'int', 'sellerId' => 'int', 'shipToAddressID' => 'string', 'shipToAddressName' => 'string', 'shipToAddressPhone' => 'string', 'shipToAddressLine1' => 'string', 'shipToAddressLine2' => 'string', 'shipToAddressCity' => 'string', 'shipToAddressCounty' => 'string', 'shipToAddressStateOrProvince' => 'string', 'shipToAddressPostalCode' => 'string', 'shipToAddressCountry' => 'string', 'statusId' => 'int', 'note' => 'string', 'categoryId' => 'int', 'itemId' => 'string', 'sku' => 'string', 'style' => 'string', 'size' => 'string', 'color' => 'string', 'productType' => 'string', 'quantity' => 'int', 'price' => 'double', 'cost' => 'double', 'profit' => 'double', 'fulfillStatusId' => 'int', 'fulfillCode' => 'string', 'fulfillUserFullName' => 'string', 'trackingStatusId' => 'int', 'trackingCode' => 'string', 'carrier' => 'string', 'carrierStatusId' => 'int', 'syncStoreStatusId' => 'int', 'created_at' => 'timestamp', 'createBy' => 'int', 'updated_at' => 'timestamp', 'updateBy' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
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
