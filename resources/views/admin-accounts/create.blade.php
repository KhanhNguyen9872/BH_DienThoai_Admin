@extends('layouts.dashboard')

@section('title', 'Tạo Tài Khoản')

@section('content')
  <h2>Tạo Tài Khoản Mới</h2>
  <p class="text-muted">Nhập thông tin để tạo tài khoản admin mới.</p>

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

  <!-- Display validation errors -->
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admins.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="full_name" class="form-label">Họ và Tên</label>
      <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div class="mb-3">
      <label for="username" class="form-label">Tên đăng nhập</label>
      <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Mật khẩu</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="mb-3">
      <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div class="mb-3">
      <label for="img" class="form-label">Ảnh Đại Diện</label>
      <input type="file" class="form-control" id="img" name="img">
      <div class="mt-2">
        <img id="imgPreview" src="{{ asset('storage/images/default-profile.png') }}" alt="Ảnh đại diện" class="img-thumbnail" style="max-width:150px;">
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Tạo Tài Khoản</button>
  </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var imgInput = document.getElementById('img');
  var imgPreview = document.getElementById('imgPreview');
  var originalSrc = imgPreview.src; // Save the default image source

  imgInput.addEventListener('change', function() {
    var file = this.files[0];
    if (file) {
      imgPreview.src = URL.createObjectURL(file);
    } else {
      imgPreview.src = originalSrc;
    }
  });
});
</script>
@endsection
