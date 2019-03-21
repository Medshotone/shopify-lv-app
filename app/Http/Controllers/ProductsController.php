<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\library\Product;

class ProductsController extends Controller
{
    public function products_list(){
        Product::getAllProduct_list($_GET);
    }
}
