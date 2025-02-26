<!-- resources/views/partials/sidebar.blade.php -->
<nav id="sidebarMenu" class="sidebar bg-dark">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
          <i class="bi-speedometer2 me-2"></i>
          <span class="link-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('products')) active @endif" href="{{ route('products') }}">
          <i class="bi-phone me-2"></i>
          <span class="link-text">Product Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('orders')) active @endif" href="{{ route('orders') }}">
          <i class="bi-cart me-2"></i>
          <span class="link-text">Order Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('users')) active @endif" href="{{ route('users') }}">
          <i class="bi-people me-2"></i>
          <span class="link-text">User Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('accounts')) active @endif" href="{{ route('accounts') }}">
          <i class="bi-person me-2"></i>
          <span class="link-text">Account Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('admins')) active @endif" href="{{ route('admins') }}">
          <i class="bi-shield-lock me-2"></i>
          <span class="link-text">Admin Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('vouchers')) active @endif" href="{{ route('vouchers') }}">
          <i class="bi-tag me-2"></i>
          <span class="link-text">Voucher Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('analytics')) active @endif" href="{{ route('analytics') }}">
          <i class="bi-graph-up me-2"></i>
          <span class="link-text">Analytics &amp; Reports</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white @if(request()->routeIs('settings')) active @endif" href="{{ route('settings') }}">
          <i class="bi-gear me-2"></i> Settings
        </a>
      </li>
    </ul>
    <!-- Toggle Button for collapsing/expanding the sidebar -->
    <div class="mt-3 text-center">
      <button id="sidebarToggle" class="btn btn-outline-light btn-sm">
        <i class="bi-chevron-left"></i>
      </button>
    </div>
  </div>
</nav>
