@extends('layouts.dashboard')

@section('title', 'Quản lý Tài Khoản')

@section('content')
  <h2>Quản lý Tài Khoản</h2>
  <p class="text-muted">Quản lý các tài khoản hệ thống.</p>

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

  <!-- Button to create a new account -->
  <a href="{{ route('accounts.create') }}" class="btn btn-success mb-3">Thêm Tài Khoản Mới</a>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Tên đăng nhập</th>
          <th>Người dùng</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($accounts as $account)
          <tr>
            <td>{{ $account['id'] }}</td>
            <td>{{ $account['username'] }}</td>
            <td>{{ $account['full_name_user'] }}</td>
            <td>{{ $account['lock_acc'] }}</td>
            <td>
              <a href="{{ route('accounts.edit', $account['id']) }}" class="btn btn-sm btn-primary">Chỉnh sửa</a>
              <form action="{{ route('accounts.delete', $account['id']) }}" method="POST" class="d-inline-block" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">Không có tài khoản nào.</td>
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
            @if ($accounts->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Trang trước</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $accounts->previousPageUrl() }}" rel="prev">Trang trước</a></li>
            @endif

            <!-- Các số trang -->
            @foreach ($accounts->getUrlRange(1, $accounts->lastPage()) as $page => $url)
                <li class="page-item {{ ($accounts->currentPage() == $page) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Liên kết Trang tiếp theo -->
            @if ($accounts->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $accounts->nextPageUrl() }}" rel="next">Trang sau</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Trang sau</span></li>
            @endif
        </ul>
    </nav>
  </div>
@endsection

@section('scripts')
<script>
  function confirmDelete() {
    return confirm('Bạn chắc chắn muốn xóa tài khoản này?');
  }
</script>
@endsection
