<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tire;

class TireController extends Controller
{
    public function index()
    {
        $tires = Tire::latest()->paginate(9);
        return view('tires.index', compact('tires'));
    }

    public function show(Tire $tire)
    {
        return view('tires.show', compact('tire'));
    }
}
