@extends('layouts.dashboard')

@section('title', 'Thêm Người Dùng Mới')

@section('content')
  <h2>Thêm Người Dùng Mới</h2>
  <p class="text-muted">Nhập thông tin người dùng mới.</p>

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

  <form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label for="first_name" class="form-label">Họ</label>
      <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
    </div>

    <div class="mb-3">
      <label for="last_name" class="form-label">Tên</label>
      <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
    </div>

    <!-- New select field using Select2 for user role -->
    <div class="mb-3">
      <label for="role" class="form-label">Vai trò</label>
      <select class="form-control" id="role" name="role">
        <option value="">Chọn vai trò</option>
        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Tạo Người Dùng</button>
  </form>
@endsection

@section('scripts')
  <!-- Include Select2 JS if not already included in your layout -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#role').select2({
        placeholder: "Chọn vai trò",
        allowClear: true,
        width: '100%'
      });
    });
  </script>
@endsection
