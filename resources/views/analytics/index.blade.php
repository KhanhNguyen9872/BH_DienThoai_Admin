@extends('layouts.dashboard')

@section('title', 'Phân tích & Báo cáo')

@section('content')
<div class="container-fluid py-4">
    <!-- Tiêu đề trang -->
    <div class="d-flex align-items-center mb-4">
        <h2 class="mb-0">Phân tích & Báo cáo</h2>
    </div>

    <!-- Dòng thẻ: Các chỉ số -->
    <div class="row">
        <!-- Tổng số đơn hàng -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tổng số đơn hàng</h5>
                    <p class="card-text fs-4 fw-bold">{{ $tongDonHang }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng số người dùng -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tổng số người dùng</h5>
                    <p class="card-text fs-4 fw-bold">{{ $tongNguoiDung }}</p>
                </div>
            </div>
        </div>

        <!-- Tổng doanh thu (VND) -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tổng doanh thu (VND)</h5>
                    <p class="card-text fs-4 fw-bold">
                        {{ number_format($tongDoanhThu, 0, ',', '.') }} VND
                    </p>
                </div>
            </div>
        </div>

        <!-- Tỷ lệ chuyển đổi -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tỷ lệ chuyển đổi</h5>
                    <p class="card-text fs-4 fw-bold">
                        {{ $tyLeChuyenDoi }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dòng: Biểu đồ -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng hàng tháng</h5>
                    <canvas id="ordersChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tăng trưởng người dùng</h5>
                    <canvas id="usersChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng: Đơn hàng gần đây -->
    <div class="row mt-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng gần đây</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Người mua</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($donHangGanDay as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>
                                        @if($order->user)
                                            {{ $order->user->full_name }}
                                        @else
                                            Không xác định
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->orderInfo)
                                            {{ number_format($order->orderInfo->totalPrice, 0, ',', '.') }} VND
                                        @else
                                            0 VND
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->orderInfo)
                                            @php $status = $order->orderInfo->status; @endphp
                                            @if($status === 'completed')
                                                <span class="badge bg-success">Hoàn tất</span>
                                            @elseif($status === 'pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($status) }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->orderInfo && $order->orderInfo->orderAt)
                                            {{ \Carbon\Carbon::parse($order->orderInfo->orderAt)->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có đơn hàng gần đây</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Sử dụng Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dữ liệu từ controller (JSON)
    const nhanDonHang    = @json($nhanDonHang);
    const duLieuDonHang  = @json($duLieuDonHang);

    const nhanNguoiDung  = @json($nhanNguoiDung);
    const duLieuNguoiDung= @json($duLieuNguoiDung);

    // Biểu đồ đơn hàng hàng tháng
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: nhanDonHang,
            datasets: [{
                label: 'Đơn hàng',
                data: duLieuDonHang,
            }]
        },
    });

    // Biểu đồ tăng trưởng người dùng
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: nhanNguoiDung,
            datasets: [{
                label: 'Người dùng mới',
                data: duLieuNguoiDung,
            }]
        },
    });
});
</script>
@endsection
