@extends('layouts.dashboard')

@section('title', 'Bảng điều khiển')

@section('content')
  <h2>Bảng điều khiển</h2>
  <p class="text-muted">Chào mừng bạn đến với Bảng điều khiển Quản trị PhoneStore!</p>
  <div class="row">
    <!-- Thẻ ví dụ cho Tổng số sản phẩm -->
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card text-white bg-primary h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h6 class="card-title">Tổng sản phẩm</h6>
            <!-- Dynamically display the total number of products -->
            <h3 class="card-text">{{ \App\Models\Product::count() }}</h3>
          </div>
          <i class="bi-phone fs-1 opacity-25"></i>
        </div>
      </div>
    </div>

    <!-- Thẻ ví dụ cho Tổng số lượng sản phẩm còn lại -->
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card text-white bg-success h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h6 class="card-title">Tổng số lượng sản phẩm còn lại</h6>
            <!-- Dynamically display the total remaining quantity of all products and colors -->
            <h3 class="card-text">{{ $totalQuantity }}</h3>
          </div>
          <i class="bi-boxes fs-1 opacity-25"></i>
        </div>
      </div>
    </div>

    <!-- Thẻ ví dụ cho Tổng số lượng sản phẩm đã bán -->
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card text-white bg-warning h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h6 class="card-title">Tổng số lượng sản phẩm đã bán</h6>
            <!-- Display the total quantity of products sold -->
            <h3 class="card-text">{{ $totalSoldQuantity }}</h3>
          </div>
          <i class="bi-arrow-up-right fs-1 opacity-25"></i>
        </div>
      </div>
    </div>

        <!-- Thẻ ví dụ cho Tổng số đơn hàng -->
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card text-white bg-secondary h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h6 class="card-title">Tổng số đơn hàng</h6>
            <!-- Display the total number of orders -->
            <h3 class="card-text">{{ $totalOrders }}</h3>
          </div>
          <i class="bi-box-arrow-up fs-1 opacity-25"></i>
        </div>
      </div>
    </div>
  </div>

    <!-- Thẻ ví dụ cho Tổng số người dùng -->
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card text-white bg-info h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h6 class="card-title">Tổng số người dùng</h6>
            <!-- Display the total number of users -->
            <h3 class="card-text">{{ $totalUsers }}</h3>
          </div>
          <i class="bi-person-fill fs-1 opacity-25"></i>
        </div>
      </div>
    </div>

@endsection

@section('scripts')
  <!-- JavaScript riêng cho trang nếu cần -->
@endsection
