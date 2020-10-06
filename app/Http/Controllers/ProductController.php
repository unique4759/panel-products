<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller {

    /**
     * Middleware for access to admin panel products.
     *
     * ProductController constructor.
     */
    public function __construct() {
    }

    /**
     * Display admin panel products.
     *
     * @return mixed
     */
    public function adminPage() {
        return view('products');
    }
}
