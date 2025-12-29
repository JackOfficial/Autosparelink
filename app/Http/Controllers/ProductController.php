<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   function products(){
     $parts = Part::with('partBrand')->latest()->get();
     return view('products', compact('parts'));
   }

   function product(){
     return view('product');
   }
}
