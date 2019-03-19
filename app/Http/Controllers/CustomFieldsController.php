<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\library\Product;
use App\library\ProductMetaFields;

class CustomFieldsController extends Controller
{
    public function index(){
        $all_products = Product::getAllProduct($_GET);
        foreach ($all_products['products'] as $key => $product){
            $all_products['products'][$key]['metafields'] = ProductMetaFields::getAllProductMetaFields($_GET,$product['id']);
        }
        //dd($all_products);
        return view('products',compact(['all_products']));
    }
}

//exaple uses

//$test = new Product;
// var_dump($test->getAllProduct($_GET));
//or
//var_dump(Product::getAllProduct($_GET));