@extends('layouts.dashboard')

@section('title', 'Quản lý Đơn hàng')

@section('content')
  <h2>Quản lý Đơn hàng</h2>
  <p class="text-muted">Quản lý các đơn hàng của cửa hàng bạn tại đây. Bạn có thể xem hoặc xóa đơn hàng khi cần thiết.</p>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Thông báo lỗi -->
  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Khách hàng</th>
          <th scope="col">Tổng giá trị</th>
          <th scope="col">Phương thức thanh toán</th>
          <th scope="col">Trạng thái</th>
          <th scope="col">Ngày đặt hàng</th> 
          <th scope="col">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->last_name . ' ' . $order->user->first_name }}</td> <!-- Hiển thị tên người dùng -->
            <td>
              @if($order->orderInfo && $order->orderInfo->totalPrice)
                {{ number_format($order->orderInfo->totalPrice, 0) . ' VNĐ' }}
              @else
                <span class="text-muted">Không có</span>
              @endif
            </td>
            <td>
              @if($order->orderInfo)
                @switch($order->orderInfo->payment)
                  @case('tienmat')
                    Tiền mặt
                    @break
                  @case('momo')
                    MoMo
                    @break
                  @case('nganhang')
                    Ngân hàng
                    @break
                  @default
                    {{ ucfirst($order->orderInfo->payment) }}
                @endswitch
              @else
                <span class="text-muted">Không có</span>
              @endif
            </td>
            <td>{{ ucfirst($order->orderInfo->status ?? 'Không có') }}</td>
            <td>
              @if($order->orderInfo && $order->orderInfo->orderAt)
                {{ \Carbon\Carbon::parse($order->orderInfo->orderAt)->format('d/m/Y H:i') }} <!-- Định dạng Ngày đặt hàng -->
              @else
                <span class="text-muted">Không có</span>
              @endif
            </td>
            <td>
              <a href="{{ url('/orders/' . $order->id) }}" class="btn btn-sm btn-primary">Xem</a>
              <!-- <a href="{{ url('/orders/' . $order->id . '/edit') }}" class="btn btn-sm btn-warning">Sửa</a> -->

              <!-- Thêm form xóa đơn hàng -->
              <form action="{{ route('orders.delete', $order->id) }}" method="POST"  class="d-inline-block" id="delete-order-form-{{ $order->id }}">
                @csrf
                @method('DELETE') <!-- Sử dụng DELETE cho việc xóa -->
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $order->id }})">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">Không tìm thấy đơn hàng nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Liên kết phân trang -->
  <div class="d-flex justify-content-center mt-3">
    <nav>
        <ul class="pagination">
            <!-- Liên kết trang trước -->
            @if ($orders->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Trang trước</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">Trang trước</a></li>
            @endif

            <!-- Các số trang -->
            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                <li class="page-item {{ ($orders->currentPage() == $page) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Liên kết trang tiếp theo -->
            @if ($orders->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Trang sau</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Trang sau</span></li>
            @endif
        </ul>
    </nav>
  </div>
@endsection

@section('scripts')
<script>
  // Hàm xử lý yêu cầu xóa
  function confirmDelete(orderId) {
    if (confirm('Bạn chắc chắn muốn xóa đơn hàng này?')) {
      // Gửi yêu cầu DELETE
      document.getElementById('delete-order-form-' + orderId).submit();
    }
  }
</script>
@endsection
