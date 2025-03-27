<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index_compras()
    {
        return view('compras.compras');
    }

    
}