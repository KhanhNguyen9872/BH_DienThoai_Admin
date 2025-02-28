<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Import Str class
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product; // Make sure this line is present

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

    public function index()
    {
        // Retrieve products from the 'product' table
        $products = \DB::table('product')
            ->paginate(10);  // Paginate the query to show 10 products per page

        // Loop through each product to set default_img, price, and colors properties
        foreach ($products as $product) {
            // Decode the JSON stored in the "color" column
            $colors = json_decode($product->color, true);
            $favorites = json_decode($product->favorite, true);

            $product->favorites = $favorites;

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
        return view('products.index', compact('products'));
    }

    /**
     * Display the specified product details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Load the product record. The 'favorite' and 'color' fields will automatically be cast to arrays.
        $product = Product::findOrFail($id);
        
        return view('products.show', compact('product'));
    }

    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'favorite' => 'nullable|json',
        'color.*.name' => 'required|string',
        'color.*.money' => 'required|numeric',
        'color.*.quantity' => 'required|numeric',
        'color.*.moneyDiscount' => 'nullable|numeric',
        'color.*.img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
    ]);

    $product->name = $validatedData['name'];
    $product->description = $validatedData['description'];
    $product->favorite = json_decode($validatedData['favorite'], true) ?? [];

    $colors = [];

    foreach ($request->input('color', []) as $index => $colorData) {
        // Remove moneyDiscount if <= 0
        if (isset($colorData['moneyDiscount']) && $colorData['moneyDiscount'] <= 0) {
            unset($colorData['moneyDiscount']);
        }

        // Handle image
        if (!$request->hasFile("color.{$index}.img")) {
            // Use existing image if available
            if (isset($colorData['existing_img'])) {
                $colorData['img'] = $colorData['existing_img'];
            } else {
                // Assign default image
                $defaultImage = public_path('storage/images/default-phone.png');
                $randomFileName = Str::random(40) . '.png';
                $destinationPath = public_path('storage/img/' . $randomFileName);

                if (file_exists($defaultImage)) {
                    copy($defaultImage, $destinationPath);
                    $colorData['img'] = '/img/' . $randomFileName;
                } else {
                    return back()->with('error', 'Hình ảnh mặc định không tồn tại');
                }
            }
        } else {
            // Handle new image upload
            $image = $request->file("color.{$index}.img");
            $randomFileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/img/' . $randomFileName);
            
            $image->move(public_path('storage/img'), $randomFileName);
            
            if (file_exists($destinationPath)) {
                $colorData['img'] = '/img/' . $randomFileName;
            } else {
                return back()->with('error', 'Đã xảy ra lỗi khi lưu tệp');
            }
        }

        // Remove temporary field from data
        unset($colorData['existing_img']);

        $colors[] = $colorData;
    }

    $product->color = $colors;
    $product->save();

    return redirect()->route('products')->with('success', `Sản phẩm đã được cập nhật [ID: ${id}`);
}

    public function create()
    {
        return view('products.create');  // Or your view file for creating products
    }

    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'favorite' => 'nullable|json',
        'color.*.name' => 'required|string',
        'color.*.money' => 'required|numeric',
        'color.*.quantity' => 'required|numeric',
        'color.*.moneyDiscount' => 'nullable|numeric',
        'color.*.img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096', // Image validation
    ]);

    // Store the product details
    $product = new Product();
    $product->name = $validatedData['name'];
    $product->description = $validatedData['description'];

    if (!$product->favorite) {
        $product->favorite = '[]';
    }

    $product->favorite = json_decode($validatedData['favorite'], true) ?? [];

    // Process the colors and images
    $colors = [];
    foreach ($request->input('color', []) as $index => $color) {
        if (isset($color['moneyDiscount']) && $color['moneyDiscount'] <= 0) {
            unset($color['moneyDiscount']);
        }

        // If no new image is uploaded, assign the default image
        if (!$request->hasFile("color.{$index}.img")) {
            // Assign the default image if no image was uploaded
            $defaultImage = public_path('storage/images/default-phone.png');
            $randomFileName = Str::random(40) . '.png';
            $destinationPath = public_path('storage/img/' . $randomFileName);

            // Move the default image to the img folder with a random name
            if (file_exists($defaultImage)) {
                copy($defaultImage, $destinationPath);  // Copy the default image
                $color['img'] = '/img/' . $randomFileName;  // Assign the new image URL
            } else {
                // Handle the case where the default image does not exist
                return back()->with('error', 'Hình ảnh mặc định không tồn tại');
            }
        } else {
            // Handle the uploaded image (if any)
            $image = $request->file("color.{$index}.img");

            // Generate a random file name
            $randomFileName = Str::random(40) . '.' . $image->getClientOriginalExtension();

            // Define the destination path for the file in public/img
            $destinationPath = public_path('storage/img/' . $randomFileName);

            // Move the uploaded file manually to the destination
            $image->move(public_path('storage/img'), $randomFileName);

            // Check if the file was successfully moved
            if (file_exists($destinationPath)) {
                // Store the relative URL in the color array
                $color['img'] = '/img/' . $randomFileName;
            } else {
                // Handle the case where the file wasn't moved successfully
                return back()->with('error', 'Đã xảy ra lỗi khi lưu tệp');
            }
        }

        // Push the updated color to the colors array
        $colors[] = $color;
    }

    $product->color = $colors;
    $product->save();

    return redirect()->route('products')->with('success', `Sản phẩm đã được tạo thành công!`);
}

public function delete($id)
{
    // Try to find the product by ID
    try {
        $product = Product::findOrFail($id);
        
        // Optional: Handle related data (e.g., images or other relationships)
        if ($product->color) {
            foreach ($product->color as $color) {
                if (isset($color['img']) && Storage::exists(public_path('storage' . $color['img']))) {
                    // Delete the image if it exists
                    Storage::delete(public_path('storage' . $color['img']));
                }
            }
        }

        // Delete the product record
        $product->delete();

        // Redirect to the products list with a success message
        return redirect()->route('products')->with('success', "Sản phẩm đã được xóa [ID: $id]");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // If the product is not found, show an error message
        return redirect()->route('products')->with('error', "Không tìm thấy sản phẩm [ID: $id]");
    }
}

}