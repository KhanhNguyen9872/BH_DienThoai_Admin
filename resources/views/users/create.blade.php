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

    <!-- Addresses Section -->
    <div class="mb-3">
      <label class="form-label">Địa chỉ</label>
      <div id="addressContainer" class="row"></div>
    </div>
    <button type="button" id="addAddressButton" class="btn btn-secondary mb-3">Thêm địa chỉ mới</button>
    <br>
    <button type="submit" class="btn btn-primary">Tạo Người Dùng</button>
  </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxAddresses = 3;
    const addressContainer = document.getElementById('addressContainer');
    const addAddressButton = document.getElementById('addAddressButton');

    // Disable add button if maximum addresses reached.
    function updateAddButtonState() {
        const addressCards = addressContainer.getElementsByClassName('address-card-wrapper');
        addAddressButton.disabled = addressCards.length >= maxAddresses;
    }

    // Create an address card wrapped in a Bootstrap column.
    function createAddressCard() {
        const col = document.createElement('div');
        col.classList.add('col-md-6', 'address-card-wrapper');
        col.innerHTML = `
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="addresses[][name]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="addresses[][address]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="addresses[][phone]" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-danger remove-address">Xóa</button>
                </div>
            </div>
        `;
        return col;
    }

    addAddressButton.addEventListener('click', function() {
        const addressCards = addressContainer.getElementsByClassName('address-card-wrapper');
        if (addressCards.length < maxAddresses) {
            const card = createAddressCard();
            addressContainer.appendChild(card);
            updateAddButtonState();
        }
    });

    // Add warning alert on delete address button click.
    addressContainer.addEventListener('click', function(e) {
        if (e.target && e.target.matches('.remove-address')) {
            if (confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
                e.target.closest('.address-card-wrapper').remove();
                updateAddButtonState();
            }
        }
    });
});
</script>
@endsection
