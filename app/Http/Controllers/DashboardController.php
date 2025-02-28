<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserInfo;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate the total quantity for all products and colors
        $totalQuantity = Product::all()->reduce(function ($carry, $product) {
            if (isset($product->color) && is_array($product->color)) {
                $productQuantity = array_sum(array_map(function ($color) {
                    return isset($color['quantity']) ? $color['quantity'] : 0;
                }, $product->color));

                return $carry + $productQuantity;
            }

            return $carry;
        }, 0);

        // Calculate the total number of products sold
        $totalSoldQuantity = Order::all()->reduce(function ($carry, $order) {
            if (isset($order->orderInfo) && isset($order->orderInfo->products)) {
                $soldQuantity = array_sum(array_map(function ($product) {
                    return isset($product['quantity']) ? $product['quantity'] : 0;
                }, $order->orderInfo->products));

                return $carry + $soldQuantity;
            }

            return $carry;
        }, 0);

        // Get the total number of users
        $totalUsers = UserInfo::count();

        // Get the total number of orders
        $totalOrders = Order::count();

        // Pass the values to the view
        return view('dashboard', compact('totalQuantity', 'totalSoldQuantity', 'totalUsers', 'totalOrders'));
    }
}
