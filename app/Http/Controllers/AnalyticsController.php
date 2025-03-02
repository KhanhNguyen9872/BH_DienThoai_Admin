<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
{
    // 1) Các chỉ số cơ bản
    $tongDonHang    = Order::count();            // Tổng số đơn hàng (tất cả)
    $tongNguoiDung  = UserInfo::count();         // Tổng số người dùng

    // Tổng doanh thu chỉ những đơn hàng "Đã giao"
    $tongDoanhThu   = OrderInfo::where('status', 'Đã giao')
                                ->sum('totalPrice');
    
    // Ví dụ: Tỷ lệ chuyển đổi -> thay đổi tùy mô hình
    $tyLeChuyenDoi  = 4.5;

    // 2) Đơn hàng hàng tháng (nếu vẫn muốn thống kê mọi đơn hàng, không lọc status):
    $donHangTheoThang = OrderInfo::select(
            DB::raw('MONTH(orderAt) as thang'),
            DB::raw('COUNT(*) as soLuong')
        )
        ->groupBy('thang')
        ->orderBy('thang')
        ->take(6)
        ->get();

    $nhanDonHang = [];
    $duLieuDonHang = [];
    foreach ($donHangTheoThang as $item) {
        $label = date("M", mktime(0, 0, 0, $item->thang, 1));
        $nhanDonHang[]    = $label;
        $duLieuDonHang[]  = $item->soLuong;
    }

    // 3) Người dùng mới hàng tháng
    $nguoiDungTheoThang = UserInfo::select(
            DB::raw('MONTH(created_at) as thang'),
            DB::raw('COUNT(*) as soLuong')
        )
        ->groupBy('thang')
        ->orderBy('thang')
        ->take(6)
        ->get();

    $nhanNguoiDung = [];
    $duLieuNguoiDung = [];
    foreach ($nguoiDungTheoThang as $item) {
        $label = date("M", mktime(0, 0, 0, $item->thang, 1));
        $nhanNguoiDung[]   = $label;
        $duLieuNguoiDung[] = $item->soLuong;
    }

    // 4) Đơn hàng gần đây (có thể lấy tất cả đơn hoặc cũng lọc status nếu muốn)
    $donHangGanDay = Order::with(['orderInfo', 'user'])
        ->latest()
        ->take(5)
        ->get();

    return view('analytics.index', [
        'tongDonHang'      => $tongDonHang,
        'tongNguoiDung'    => $tongNguoiDung,
        'tongDoanhThu'     => $tongDoanhThu,
        'tyLeChuyenDoi'    => $tyLeChuyenDoi,

        'nhanDonHang'      => $nhanDonHang,
        'duLieuDonHang'    => $duLieuDonHang,

        'nhanNguoiDung'    => $nhanNguoiDung,
        'duLieuNguoiDung'  => $duLieuNguoiDung,

        'donHangGanDay'    => $donHangGanDay,
    ]);
}

}
