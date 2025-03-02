@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa Tài Khoản')

@section('content')
  <h2>Chỉnh sửa Tài Khoản</h2>
  <p class="text-muted">Chỉnh sửa thông tin tài khoản admin.</p>

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

  <form action="{{ route('admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="full_name" class="form-label">Họ và Tên</label>
      <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $admin->full_name) }}" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
    </div>

    <div class="mb-3">
      <label for="username" class="form-label">Tên đăng nhập</label>
      <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $admin->username) }}" required>
    </div>

    <div class="mb-3">
      <label for="img" class="form-label">Ảnh Đại Diện</label>
      <input type="file" class="form-control" id="img" name="img">
      <div class="mt-2">
        @if($admin->img)
          <img id="imgPreview" src="{{ asset('storage/' . $admin->img) }}" alt="Ảnh đại diện" class="img-thumbnail" style="max-width:150px;">
        @else
          <img id="imgPreview" src="{{ asset('storage/images/default-profile.png') }}" alt="Ảnh đại diện" class="img-thumbnail" style="max-width:150px;">
        @endif
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Cập Nhật Tài Khoản</button>
  </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var imgInput = document.getElementById('img');
  var imgPreview = document.getElementById('imgPreview');
  var originalSrc = imgPreview.src; // Save the original image source

  imgInput.addEventListener('change', function() {
    var file = this.files[0];
    if (file) {
      // Update the preview with the new image
      imgPreview.src = URL.createObjectURL(file);
    } else {
      // No file selected; revert to the original image
      imgPreview.src = originalSrc;
    }
  });
});
</script>
@endsection
