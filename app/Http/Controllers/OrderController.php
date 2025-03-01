<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Method to display the orders index page
    public function index()
    {
        // Retrieve all orders (you can paginate if needed)
        $orders = Order::with('user', 'orderInfo')->paginate(10); // Or use all() if you don't need pagination

        // Render the orders.index view and pass the orders data
        return view('orders.index', compact('orders'));
    }

    public function delete($id)
{
    // Find the order by ID
    $order = Order::find($id);

    // Check if the order exists
    if ($order) {
        // Delete the related OrderInfo first (ensure cascading delete)
        if ($order->orderInfo) {
            $order->orderInfo->delete(); // Delete the OrderInfo related to this order
        }

        // Now delete the order
        $order->delete();

        // Redirect with a success message
        return redirect()->route('orders')->with('success', "Đơn hàng đã được xóa [ID: $id]");
    }

    // If order doesn't exist, return an error message
    return redirect()->route('orders')->with('error', "Đơn hàng không tồn tại [ID: $id]");
}


    public function show($id)
    {
        // Find the order by ID
        $order = Order::find($id);

        // Check if the order exists
        if ($order) {
            // Return the view and pass the order data
            return view('orders.show', compact('order'));
        }

        // If the order doesn't exist, return a 404 page
        return abort(404, 'Order not found');
    }

    public function confirm($id)
    {
        // Find the order by ID
        $order = Order::find($id);

        // Check if order exists
        if ($order && $order->orderInfo->status == 'Đang chờ xác nhận') {
            // Update the status to confirmed
            $order->orderInfo->status = 'Đang chờ giao hàng';
            $order->orderInfo->save();

            // Redirect to the order details page with success message
            return redirect()->route('orders.show', $id)->with('success', 'Đơn hàng đã được xác nhận!');
        }

        // If the order is not found or already confirmed
        return redirect()->route('orders.show', $id)->with('error', 'Đơn hàng không tồn tại hoặc đã xác nhận từ trước!');
    }


    public function cancel($id)
{
    // Retrieve the order along with its orderInfo and products (adjust relations as needed)
    $order = Order::with('orderInfo')->findOrFail($id);

    // Check if order status is 'Đang giao hàng' or 'Đã giao hàng'; if so, do not allow cancellation.
    if (in_array($order->orderInfo->status, ['Đang giao hàng', 'Đã giao hàng'])) {
        return redirect()->route('orders')->with('error', 'Không thể hủy đơn hàng khi đang giao hàng hoặc đã giao hàng.');
    }

    // Change order status to 'Đã hủy'
    $order->orderInfo->status = 'Đã hủy';
    $order->orderInfo->save();

    // Increase product quantities for each product in the order.
    // Assuming $order->orderInfo->products is an array of product details.
    foreach ($order->orderInfo->products as $productDetail) {
        // Retrieve the product from the database
        $product = Product::find($productDetail['id']);
        if ($product) {
            // Assuming $product->color is stored as JSON and cast to array in the model.
            $colors = $product->color; 
            // Update the corresponding color variant by increasing its quantity.
            $updatedColors = array_map(function($color) use ($productDetail) {
                // Compare the color name with the one in the product order details.
                if ($color['name'] === $productDetail['color']) {
                    $color['quantity'] = $color['quantity'] + $productDetail['quantity'];
                }
                return $color;
            }, $colors);

            // Save the updated color array back to the product.
            $product->color = $updatedColors;
            $product->save();
        }
    }

    return redirect()->route('orders')->with('success', 'Đơn hàng đã được hủy và số lượng sản phẩm đã được cập nhật.');
}
}