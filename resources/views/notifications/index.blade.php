@extends('layouts.dashboard')

@section('title', 'Thông báo')

@section('content')
  <h2>Thông báo</h2>
  <p class="text-muted">Xem các thông báo của bạn dưới đây.</p>

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

  <!-- Nút để đánh dấu tất cả thông báo là đã đọc -->
  <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-primary">Đánh dấu tất cả là đã đọc</button>
  </form>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">Nội dung</th>
          <th scope="col">Thời gian</th>
          <th scope="col">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($notifications as $notification)
          <tr 
            @if($notification->url) 
              onclick="window.location='{{ $notification->url }}'" 
              style="cursor: pointer;"
            @endif
          >
            <td>
              <!-- If a URL is provided, show notification text normally, but row is clickable -->
              @if($notification->url)
                {{ $notification->text }}
              @else
                {{ $notification->text }}
              @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($notification->time)->setTimezone('Asia/Bangkok')->diffForHumans() }}</td>
            <td>
              <!-- Nút đánh dấu là đã đọc -->
              @if(!$notification->isRead)
                <form action="{{ url('/api/notifications/' . $notification->id . '/read') }}" method="POST">
                  @csrf
                  @method('POST') <!-- Bạn có thể sử dụng 'PUT' hoặc 'PATCH' tùy theo route của bạn -->
                  <button type="submit" class="btn btn-sm btn-primary">Đánh dấu là đã đọc</button>
                </form>
              @else
                <span class="text-muted">Đã đọc</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center">Không có thông báo nào.</td>
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
            @if ($notifications->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Trang trước</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $notifications->previousPageUrl() }}" rel="prev">Trang trước</a></li>
            @endif

            <!-- Các số trang -->
            @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                <li class="page-item {{ ($notifications->currentPage() == $page) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Liên kết Trang tiếp theo -->
            @if ($notifications->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $notifications->nextPageUrl() }}" rel="next">Trang sau</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Trang sau</span></li>
            @endif
        </ul>
    </nav>
  </div>
@endsection

@section('scripts')
  <!-- JavaScript riêng cho trang có thể thêm ở đây nếu cần -->
@endsection
