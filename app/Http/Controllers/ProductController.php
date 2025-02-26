<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Retrieve products matching the search query
        $products = DB::table('product')
            ->where('name', 'LIKE', "%{$query}%")
            ->get();

        // Loop through each product to set the default_img property
        foreach ($products as $product) {
            // Decode the JSON stored in the "color" column
            $colors = json_decode($product->color, true);

            // Check if the decoded value is an array and has at least one element
            if (is_array($colors) && count($colors) > 0) {
                // Use the "img" field of the first object as default_img
                $product->default_img = isset($colors[0]['img']) ? $colors[0]['img'] : null;
            } else {
                $product->default_img = null;
            }
        }

        return response()->json(['products' => $products]);
    }

    public function products()
    {
        // Retrieve products from the 'product' table
        $products = \DB::table('product')->get();

        // Loop through each product to set default_img, price, and colors properties
        foreach ($products as $product) {
            // Decode the JSON stored in the "color" column
            $colors = json_decode($product->color, true);

            if (is_array($colors) && count($colors) > 0) {
                // Use the "img" field of the first object as default_img
                $product->default_img = isset($colors[0]['img']) ? $colors[0]['img'] : null;
                
                // Get price from the first element.
                // If your JSON key is 'price' then use that, otherwise use 'money' if applicable.
                $product->price = isset($colors[0]['price']) ? $colors[0]['price'] : (isset($colors[0]['money']) ? $colors[0]['money'] : null);
                
                // Collect all names from the colors array
                $colorNames = [];
                foreach ($colors as $color) {
                    if (isset($color['name'])) {
                        $colorNames[] = $color['name'];
                    }
                }
                // Join the names as a comma-separated string
                $product->colors = implode(', ', $colorNames);
            } else {
                $product->default_img = null;
                $product->price = null;
                $product->colors = null;
            }
        }
        
        // Pass the retrieved products to the view
        return view('products', compact('products'));
    }
}
