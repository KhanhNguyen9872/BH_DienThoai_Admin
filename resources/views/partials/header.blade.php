<!-- resources/views/partials/header.blade.php -->
<nav class="navbar navbar-expand-md navbar-light bg-light border-bottom p-3">
  <div class="container-fluid">
    <!-- Bên trái: Thương hiệu & Toggler Thanh bên -->
    <div class="d-flex align-items-center">
      <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" 
              aria-controls="sidebarMenu" aria-expanded="false" aria-label="Chuyển hướng điều hướng">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand fw-bold text-uppercase" href="/dashboard">Admin PhoneStore</a>
    </div>

    <!-- Trung tâm: Thanh tìm kiếm (hiển thị trên màn hình md+) -->
    <div class="d-none d-md-block mx-auto position-relative" style="width: 40%;">
      <form id="searchForm" onsubmit="return false;">
        <input type="search" name="q" id="searchInput" class="form-control" placeholder="Tìm kiếm sản phẩm..." aria-label="Tìm kiếm">
      </form>
      <!-- Overlay dropdown cho kết quả tìm kiếm -->
      <div id="searchDropdown" class="dropdown-menu" style="width: 100%; max-height: 300px; overflow-y: auto;"></div>
    </div>

    <!-- Bên phải: Thông báo và Menu người dùng -->
    <div class="d-flex align-items-center">
      <!-- Dropdown Thông báo -->
<div class="dropdown me-3">
  <button class="btn btn-light position-relative dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi-bell fs-5"></i>
    @if($notifications->where('isRead', false)->count() > 0)
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ $notifications->where('isRead', false)->count() }}
        <span class="visually-hidden">Thông báo chưa đọc</span>
      </span>
    @endif
  </button>
  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
    @if($notifications->isEmpty())
      <li class="dropdown-item text-center">Không có thông báo</li>
    @else
      @foreach($notifications as $notification)
        <li class="dropdown-item {{ !$notification->isRead ? 'bg-light' : '' }}">
          <a href="{{ $notification->url ?? '#' }}" class="text-decoration-none" target="_blank">
            <div>
              <small class="text-muted">
                {{ \Carbon\Carbon::parse($notification->time)->setTimezone('Asia/Bangkok')->diffForHumans() }}
              </small>
            </div>
            <div>{{ $notification->text }}</div>
          </a>
        </li>
      @endforeach
    @endif
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-center" href="/notifications">Xem tất cả thông báo</a></li>
  </ul>
</div>


      <!-- Dropdown Người dùng -->
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-person-circle me-1"></i> {{ auth()->user()->full_name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="/profile">Cài đặt hồ sơ</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Include Axios -->
<script>
  const searchInput = document.getElementById('searchInput');
  const searchDropdown = document.getElementById('searchDropdown');

  // Ẩn dropdown khi nhấn ngoài form tìm kiếm
  document.addEventListener('click', function(e) {
    if (!document.getElementById('searchForm').contains(e.target)) {
      searchDropdown.classList.remove('show');
    }
  });

  // Lắng nghe sự kiện nhập liệu trên trường tìm kiếm
  searchInput.addEventListener('input', function() {
    let query = this.value.trim();
    if (!query) {
      searchDropdown.classList.remove('show');
      return;
    }

    axios.get("{{ route('search') }}", {
      params: { q: query },
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(response) {
      const products = response.data.products;
      let html = '';
      if (products.length > 0) {
        html += '<ul class="list-group">';
        products.forEach(function(product) {
          html += `<a href="/products/${product.id}" class="list-group-item list-group-item-action d-flex align-items-center">
            ${ product.default_img ? `<img src="/storage/${product.default_img}" alt="${product.name}" class="img-thumbnail me-2" style="width:40px; height:40px; object-fit: cover;">` : '' }
            <span>${product.name}</span>
          </a>`;
        });
        html += '</ul>';
      } else {
        html = '<p class="p-2 mb-0">Không tìm thấy sản phẩm nào.</p>';
      }
      searchDropdown.innerHTML = html;
      searchDropdown.classList.add('show');
    })
    .catch(function(error) {
      console.error('Lỗi khi lấy kết quả tìm kiếm:', error);
    });
  });
</script>
