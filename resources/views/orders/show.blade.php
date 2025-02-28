@extends('layouts.dashboard')

@section('title', 'Xem đơn hàng')

@section('content')
<div class="container-fluid mt-4">
    <h1 class="mb-4">Chi tiết đơn hàng</h1>

    @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm w-100">
        <div class="card-body">
            <h4 class="card-title">Đơn hàng #{{ $order->id }}</h4>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Khách hàng:</strong> {{ $order->user->full_name }}</p>
                    <p><strong>Trạng thái:</strong> {{ $order->orderInfo->status }}</p>
                    <p><strong>Thanh toán:</strong> 
                        @if($order->orderInfo)
                            @switch($order->orderInfo->payment)
                                @case('tienmat') Tiền mặt @break
                                @case('momo') MoMo @break
                                @case('nganhang') Ngân hàng @break
                                @default {{ ucfirst($order->orderInfo->payment) }}
                            @endswitch
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tổng giá trị:</strong> {{ number_format($order->orderInfo->totalPrice, 0) . ' VNĐ' }}</p>
                    <p><strong>Ngày đặt hàng:</strong> 
                        @if($order->orderInfo && $order->orderInfo->orderAt)
                            {{ \Carbon\Carbon::parse($order->orderInfo->orderAt)->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </p>
                </div>
            </div>

            <h5 class="mt-4">Địa chỉ</h5>
            <div class="card p-3 mb-4">
                <p><strong>Họ tên:</strong> {{ json_decode($order->orderInfo->address)->name }}</p>
                <p><strong>Số điện thoại:</strong> {{ json_decode($order->orderInfo->address)->phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ json_decode($order->orderInfo->address)->address }}</p>
            </div>

            <h5 class="mt-4">Sản phẩm</h5>
            <ul class="list-group">
                @foreach($order->orderInfo->products as $product)
                    <li class="list-group-item">
                        {{ $product['name'] }} - {{ number_format($product['price'], 0) . ' VNĐ' }} x {{ $product['quantity'] }}
                    </li>
                @endforeach
            </ul>

            @if($order->orderInfo->status == 'Đang chờ xác nhận')
                <form action="{{ route('orders.confirm', $order->id) }}" method="POST" id="confirm-order-form">
                    @csrf
                    <button type="button" class="btn btn-success mt-4" onclick="confirmOrder()">Xác nhận đơn hàng</button>
                </form>
            @endif

        </div>
    </div>

    <a href="{{ route('orders') }}" class="btn btn-secondary mt-4">Quay lại danh sách đơn hàng</a>
</div>

@section('scripts')
<script>
  // Hàm xử lý yêu cầu xác nhận đơn hàng
  function confirmOrder() {
    if (confirm('Bạn chắc chắn muốn xác nhận đơn hàng này?')) {
      // Nếu người dùng nhấn OK, gửi yêu cầu POST để xác nhận đơn hàng
      document.getElementById('confirm-order-form').submit();
    }
  }
</script>
@endsection

@endsection
