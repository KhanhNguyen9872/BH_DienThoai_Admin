@extends('layouts.dashboard')

@section('title', 'Quản lý quản trị viên')

@section('content')
  <h2>Quản lý quản trị viên</h2>
  <p class="text-muted">Quản lý thông tin tài khoản admin tại đây. Bạn có thể tạo, chỉnh sửa, hoặc xóa tài khoản.</p>

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

  <!-- Nút Thêm Tài Khoản Admin Mới -->
  <a href="{{ route('admins.create') }}" class="btn btn-success mb-3">Thêm Tài Khoản Mới</a>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Họ và Tên</th>
          <th>Email</th>
          <th>Ảnh</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($admins as $admin)
          <tr>
            <td>{{ $admin->id }}</td>
            <td>{{ $admin->username }}</td>
            <td>{{ $admin->full_name }}</td>
            <td>{{ $admin->email }}</td>
            <td>
              @if($admin->img)
                <img src="{{ asset('storage/' . $admin->img) }}" alt="Ảnh" class="img-thumbnail" style="max-width:80px;">
              @else
                <img src="{{ asset('storage/images/default-profile.png') }}" alt="Ảnh" class="img-thumbnail" style="max-width:80px;">
              @endif
            </td>
            <td>
              <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-sm btn-primary">Chỉnh sửa</a>
              <form action="{{ route('admins.delete', $admin->id) }}" method="POST" class="d-inline-block" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">Không có tài khoản nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Liên kết phân trang -->
  <div class="d-flex justify-content-center mt-3">
    <nav>
        <ul class="pagination">
            <!-- Liên kết Trang trước -->
            @if ($admins->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Trang trước</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $admins->previousPageUrl() }}" rel="prev">Trang trước</a></li>
            @endif

            <!-- Các số trang -->
            @foreach ($admins->getUrlRange(1, $admins->lastPage()) as $page => $url)
                <li class="page-item {{ ($admins->currentPage() == $page) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Liên kết Trang tiếp theo -->
            @if ($admins->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $admins->nextPageUrl() }}" rel="next">Trang sau</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Trang sau</span></li>
            @endif
        </ul>
    </nav>
  </div>
@endsection

@section('scripts')
<script>
  // Hàm xác nhận khi xóa tài khoản admin
  function confirmDelete() {
    return confirm('Bạn chắc chắn muốn xóa tài khoản này?');
  }
</script>
@endsection
