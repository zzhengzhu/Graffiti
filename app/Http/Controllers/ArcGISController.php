<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArcGISController extends Controller
{
    public function index() {
        return view('arcgis.map');
    }
}
