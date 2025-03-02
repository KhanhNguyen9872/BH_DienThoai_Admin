@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa Tài Khoản')

@section('content')
  <h2>Chỉnh sửa Tài Khoản</h2>
  <p class="text-muted">Chỉnh sửa thông tin tài khoản.</p>

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

  <form action="{{ route('accounts.update', $account->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="username" class="form-label">Tên đăng nhập</label>
      <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $account->username) }}" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Mật khẩu <small>(Để trống nếu không thay đổi)</small></label>
      <input type="password" class="form-control" id="password" name="password">
    </div>

    <div class="mb-3">
      <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>

    <div class="mb-3">
      <label for="lock_acc" class="form-label">Trạng thái</label>
      <select name="lock_acc" id="lock_acc" class="form-control">
        <option value="0" {{ old('lock_acc', $account->lock_acc) == "0" ? 'selected' : '' }}>Đang hoạt động</option>
        <option value="1" {{ old('lock_acc', $account->lock_acc) == "1" ? 'selected' : '' }}>Bị khóa</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="user_id" class="form-label">Người Dùng</label>
      @if(isset($users))
        <select name="user_id" id="user_id" class="form-control">
          <option value="">-- Chọn Người Dùng --</option>
          @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $account->user_id) == $user->id ? 'selected' : '' }}>
              (ID: {{ $user->id }}) {{ $user->full_name }}
            </option>
          @endforeach
        </select>
      @else
        <input type="number" class="form-control" id="user_id" name="user_id" value="{{ old('user_id', $account->user_id) }}" required>
      @endif
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật Tài Khoản</button>
  </form>
@endsection
