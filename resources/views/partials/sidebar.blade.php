<!-- resources/views/partials/sidebar.blade.php -->
<nav id="sidebarMenu" class="sidebar bg-dark">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('dashboard*')) active @endif" href="{{ route('dashboard') }}">
          <i class="bi-speedometer2 me-2"></i>
          <span class="link-text">Bảng điều khiển</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('products*')) active @endif" href="{{ route('products') }}">
          <i class="bi-phone me-2"></i>
          <span class="link-text">Quản lý sản phẩm</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('orders*')) active @endif" href="{{ route('orders') }}">
          <i class="bi-cart me-2"></i>
          <span class="link-text">Quản lý đơn hàng</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('users*')) active @endif" href="{{ route('users') }}">
          <i class="bi-people me-2"></i>
          <span class="link-text">Quản lý người dùng</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('accounts*')) active @endif" href="{{ route('accounts') }}">
          <i class="bi-person me-2"></i>
          <span class="link-text">Quản lý tài khoản</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('admins*')) active @endif" href="{{ route('admins') }}">
          <i class="bi-shield-lock me-2"></i>
          <span class="link-text">Quản lý quản trị viên</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('vouchers*')) active @endif" href="{{ route('vouchers') }}">
          <i class="bi-tag me-2"></i>
          <span class="link-text">Quản lý mã giảm giá</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('analytics*')) active @endif" href="{{ route('analytics') }}">
          <i class="bi-graph-up me-2"></i>
          <span class="link-text">Phân tích &amp; Báo cáo</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('settings*')) active @endif" href="{{ route('settings') }}">
          <i class="bi-gear me-2"></i>
          <span class="link-text">Cài đặt</span>
        </a>
      </li>
    </ul>
    <!-- Nút chuyển đổi để thu gọn/mở rộng thanh bên -->
    <div class="mt-3 text-center">
      <button id="sidebarToggle" class="btn btn-outline-light btn-sm">
        <i class="bi-chevron-left"></i>
      </button>
    </div>
  </div>
</nav>
