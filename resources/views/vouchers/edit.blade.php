@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
  <h2>Chỉnh sửa mã giảm giá</h2>
  <p class="text-muted">Chỉnh sửa thông tin của mã giảm giá.</p>
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
      // Retrieve selected values for the multiple select fields.
      $selectedLimitUsers = old('limit_user', is_array($voucher->limit_user) ? $voucher->limit_user : json_decode($voucher->limit_user, true) ?? []);
      $selectedUsers = old('user_id', is_array($voucher->user_id) ? $voucher->user_id : json_decode($voucher->user_id, true) ?? []);
  @endphp

  <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
      <label for="code" class="form-label">Mã giảm giá</label>
      <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $voucher->code) }}" required>
    </div>
    
    <div class="mb-3">
      <label for="discount" class="form-label">Giảm Giá (0 -> 100)%</label>
      <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="{{ old('discount', $voucher->discount) }}" required>
    </div>
    
    <div class="mb-3">
      <label for="count" class="form-label">Số Lượng (0 -> 100000)</label>
      <input type="number" class="form-control" id="count" name="count" value="{{ old('count', $voucher->count) }}" required>
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
    
    <button type="submit" class="btn btn-primary">Cập nhật mã giảm giá</button>
  </form>
  <script>
  $(document).ready(function() {
      $('#user_id').select2({
          placeholder: "Chọn người dùng",
          allowClear: true
      });
      $('#limit_user').select2({
      placeholder: "Chọn người dùng",
      allowClear: true,
      width: '100%'
    });
  });
</script>
@endsection

