<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        // Sample cars data
        $cars = [
            ['name' => 'Perodua Bezza', 'seats' => 5, 'image' => 'car1.png'],
            ['name' => 'Perodua Aruz', 'seats' => 7, 'image' => 'car2.png'],
            ['name' => 'Proton Saga', 'seats' => 5, 'image' => 'car3.png'],
        ];

        return view('browse');
    }
}

