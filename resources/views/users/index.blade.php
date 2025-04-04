@extends('layouts.dashboard')

@section('title', 'Quản lý Người Dùng')

@section('content')
  <h2>Quản lý Người Dùng</h2>
  <p class="text-muted">Quản lý thông tin người dùng tại đây. Bạn có thể tạo, chỉnh sửa, hoặc xóa người dùng.</p>

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

  <!-- Nút Thêm Người Dùng Mới -->
  <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Thêm Người Dùng Mới</a>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Họ</th>
          <th>Tên</th>
          <th>Email</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Chỉnh sửa</a>
              <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">Không có người dùng nào.</td>
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
            @if ($users->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Trang trước</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">Trang trước</a></li>
            @endif

            <!-- Các số trang -->
            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <li class="page-item {{ ($users->currentPage() == $page) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Liên kết Trang tiếp theo -->
            @if ($users->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">Trang sau</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Trang sau</span></li>
            @endif
        </ul>
    </nav>
  </div>

  <!-- Liên kết phân trang -->
  <div class="d-flex justify-content-center mt-3">
    {{ $users->links() }}
  </div>
@endsection

@section('scripts')
<script>
  // Hàm xác nhận khi xóa người dùng
  function confirmDelete() {
    return confirm('Bạn chắc chắn muốn xóa người dùng này?');
  }
</script>
@endsection
