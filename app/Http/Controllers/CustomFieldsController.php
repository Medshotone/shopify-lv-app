<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\library\Product;

class CustomFieldsController extends Controller
{
    public function index(){
        var_dump(Product::getAllProduct($_GET));
    }
}

//exaple uses

//$test = new Product;
// var_dump($test->getAllProduct($_GET));
//or
//var_dump(Product::getAllProduct($_GET));