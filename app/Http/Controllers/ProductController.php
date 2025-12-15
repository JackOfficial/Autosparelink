<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   function products(){
     return view('products');
   }

   function product(){
     return view('product');
   }
}
