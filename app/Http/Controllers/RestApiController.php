<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class RestApiController extends BaseController
{

    public function orderImport(Request $request)
    {
        $data =[
            "orderId"=>'20-09672-83912',
            'channelID'=>'EBAY_US',
            'createdDate' => '2023-02-07T03:56:24.000Z',
            'sellerID'=>'cynglo-47',
            'shipToAddressID'=>'',
            'shipToAddressName'=>'',
            'shipToAddressPhone'=>'',
            'shipToAddressLine1'=>'',
            'shipToAddressLine2'=>'',
            'shipToAddressCity'=>'',
            'shipToAddressStateOrProvince'=>'',
            'shipToAddressPostalCode'=>'',
            'shipToAddressCountry'=>'',
            'itemID'=>'',
            'SKU'=>'',
            'title'=>'',
            'quantity'=>0,
            'unitPrice'=>0.0,
            'lineItemSumTotal'=>0.0,
            'note'=>''
        ];
        dump($request);
        //return response()->json($request);
    }
}
