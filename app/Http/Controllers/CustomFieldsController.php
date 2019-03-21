<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\library\Product;
use App\library\ProductMetaFields;

class CustomFieldsController extends Controller
{
    public function index(){
        $all_products = Product::getAllProduct($_GET);
        //add metafields to product
        foreach ($all_products['products'] as $key => $product){
            $all_products['products'][$key]['metafields'] = ProductMetaFields::getAllProductMetaFields($_GET,$product['id']);
        }
        //compact before send to view
        return view('products',compact(['all_products']));
    }

    public function metafields_update(){
        ProductMetaFields::updateProductMetaFields($_GET,$_POST['owner_id'], $_POST['value']);
    }

    public function metafield_delete(){
        ProductMetaFields::deleteProductMetaFields($_GET,$_POST['owner_id'], $_POST['metafield_id']);
    }

    public function metafield_create(){

        ProductMetaFields::createProductMetaFields($_GET,$_POST['owner_id'], 'related', 'productHandlers', $_POST['value']);
    }
}

//exaple uses

//$test = new Product;
// var_dump($test->getAllProduct($_GET));
//or
//var_dump(Product::getAllProduct($_GET));