@extends('layouts.dashboard')

@section('title', 'Quản lý mã giảm giá')

@section('content')
  <h2>Quản lý mã giảm giá</h2>
  <p class="text-muted">Quản lý mã giảm giá của bạn tại đây. Bạn có thể tạo, cập nhật, hoặc xóa voucher khi cần thiết.</p>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <!-- Nút tạo voucher mới -->
  <a href="{{ route('vouchers.create') }}" class="btn btn-success mb-3">Tạo mã giảm giá mới</a>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Mã giảm giá</th>
          <th>Giảm giá</th>
          <th>Số lượng</th>
          <th>Đã nhập</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($vouchers as $voucher)
          <tr>
            <td>{{ $voucher->id }}</td>
            <td>{{ $voucher->code }}</td>
            <td>{{ $voucher->discount }}</td>
            <td>{{ $voucher->count }}</td>
            <td>
                {{ count($voucher->user_id) }}
            </td>
            <td>
              <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-primary">Chỉnh sửa</a>
              <form action="{{ route('vouchers.delete', $voucher->id) }}" method="POST" class="d-inline-block" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">Không tìm thấy mã giảm giá nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Liên kết phân trang -->
  <div class="d-flex justify-content-center mt-3">
    {{ $vouchers->links() }}
  </div>
@endsection

@section('scripts')
<script>
  // Xác nhận hành động xóa
  function confirmDelete() {
    return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này không?');
  }
</script>
@endsection
