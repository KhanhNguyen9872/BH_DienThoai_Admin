<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade

class ColorController extends Controller
{
    // Function to get all colors
    public function getAllColors()
    {
        // Fetch all colors with id, name, and hex
        $colors = DB::table('colors')
                    ->select('id', 'name', 'hex')
                    ->get();

        // Return response as JSON
        return response()->json($colors);
    }
}
