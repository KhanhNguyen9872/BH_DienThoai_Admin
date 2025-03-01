@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa Người Dùng')

@section('content')
  <h2>Chỉnh sửa Người Dùng</h2>
  <p class="text-muted">Chỉnh sửa thông tin người dùng.</p>

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

  <form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="first_name" class="form-label">Họ</label>
      <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
    </div>

    <div class="mb-3">
      <label for="last_name" class="form-label">Tên</label>
      <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Cập Nhật Người Dùng</button>
  </form>
@endsection
