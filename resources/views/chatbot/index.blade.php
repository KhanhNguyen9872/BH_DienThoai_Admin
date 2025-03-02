@extends('layouts.dashboard')

@section('title', 'Lịch sử Chatbot')

@section('content')
  <h2>Lịch sử Chatbot</h2>
  <p class="text-muted">Hiển thị lịch sử các cuộc trò chuyện của chatbot.</p>

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

  <!-- Removed the Add, Edit and Delete buttons -->

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
              <a href="{{ route('chatbot.show', $user->id) }}" class="btn btn-sm btn-info">Xem</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">Không có dữ liệu.</td>
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
@endsection

@section('scripts')
<!-- No delete confirmation needed as delete button is removed -->
@endsection
