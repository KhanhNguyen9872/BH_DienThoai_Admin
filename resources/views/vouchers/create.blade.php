@extends('layouts.dashboard')

@section('title', 'Tạo mã giảm giá Mới')

@section('content')
  <h2>Tạo mã giảm giá Mới</h2>
  <p class="text-muted">Nhập thông tin cho mã giảm giá mới.</p>

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

  @php
      // Use old input values if available; otherwise default to empty arrays.
      $selectedLimitUsers = old('limit_user', []);
      $selectedUsers = old('user_id', []);
  @endphp

  <form action="{{ route('vouchers.store') }}" method="POST">
    @csrf
    
    <div class="mb-3">
      <label for="code" class="form-label">Mã Voucher</label>
      <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
    </div>
    
    <div class="mb-3">
      <label for="discount" class="form-label">Giảm Giá (0 -> 100)%</label>
      <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="{{ old('discount') }}" required>
    </div>
    
    <div class="mb-3">
      <label for="count" class="form-label">Số Lượng (0 -> 100000)</label>
      <input type="number" class="form-control" id="count" name="count" value="{{ old('count') }}" required>
    </div>
    
    <div class="mb-3">
      <label for="limit_user" class="form-label">Giới Hạn Người Dùng</label>
      <select class="form-control" id="limit_user" name="limit_user[]" multiple>
          @foreach ($users as $user)
              <option value="{{ $user->id }}" {{ in_array($user->id, $selectedLimitUsers) ? 'selected' : '' }}>
                  {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
              </option>
          @endforeach
      </select>
    </div>
    
    <div class="mb-3">
      <label for="user_id" class="form-label">Người Dùng Đã Nhập</label>
      <select class="form-control" id="user_id" name="user_id[]" multiple>
          @foreach ($users as $user)
              <option value="{{ $user->id }}" {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>
                  ID: {{ $user->id }} - {{ $user->email }}
              </option>
          @endforeach
      </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
  </form>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
      $('#limit_user').select2({
        placeholder: "Chọn người dùng",
        allowClear: true,
        width: '100%'
      });
      $('#user_id').select2({
        placeholder: "Chọn người dùng",
        allowClear: true,
        width: '100%'
      });
    });
  </script>
@endsection
