@extends('layouts.dashboard')

@section('title', 'Cài đặt Hồ sơ')

@section('content')
<div class="container">
  <h2 class="mb-3">Cài đặt Hồ sơ</h2>
  <p class="text-muted">Cập nhật thông tin cá nhân và mật khẩu của bạn dưới đây.</p>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Hiển thị lỗi xác thực nếu có -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row mb-4">
      <!-- Cột Ảnh Hồ sơ -->
      <div class="col-md-4 text-center">
        <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('storage/images/default-profile.png') }}" 
             alt="Ảnh hồ sơ" class="img-thumbnail mb-3" style="max-width:150px;">
        <div class="mb-3">
          <label for="profile_picture" class="form-label">Đổi Ảnh Hồ sơ</label>
          <input class="form-control" type="file" id="profile_picture" name="profile_picture">
        </div>
      </div>

      <!-- Cột Thông tin Hồ sơ -->
      <div class="col-md-8">
        <div class="mb-3">
          <label for="name" class="form-label">Họ và Tên</label>
          <input type="text" class="form-control" id="name" name="name" 
                 value="{{ old('name', auth()->user()->name) }}" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Địa chỉ Email</label>
          <input type="email" class="form-control" id="email" name="email" 
                 value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu mới <small class="text-muted">(để trống nếu không thay đổi mật khẩu)</small></label>
          <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật Hồ sơ</button>
      </div>
    </div>
  </form>
</div>
@endsection
