@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa Sản phẩm')

@section('content')
  <h1>Chi tiết Sản phẩm</h1>
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

    <!-- Thông báo lỗi -->
    @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <!-- Khung cuộn -->
  <div class="scroll-container" style="max-height: 92vh; overflow-y: auto; max-width: 165vh;">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <!-- Mã sản phẩm (chỉ đọc) -->
      <div class="mb-3">
        <label for="product-id" class="form-label">Mã sản phẩm</label>
        <input type="text" id="product-id" class="form-control" value="{{ $product->id }}" disabled>
      </div>
      <!-- Tên sản phẩm -->
      <div class="mb-3">
        <label for="product-name" class="form-label">Tên sản phẩm</label>
        <input type="text" id="product-name" name="name" class="form-control" value="{{ old('name', $product->name) }}">
      </div>
      <!-- Mô tả sản phẩm -->
      <div class="mb-3">
        <label for="product-description" class="form-label">Mô tả sản phẩm</label>
        <textarea id="product-description" name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
      </div>
      <!-- Mục yêu thích (JSON) -->
      <div class="mb-3">
        <label for="product-favorite" class="form-label">Yêu thích</label>
        <select class="form-control" id="product-favorite" name="favorite[]" multiple>
          @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedFavorites) ? 'selected' : '' }}>
              ID: {{ $user->id }} - {{ $user->email }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Các biến thể màu sắc -->
      <div class="mb-3">
        <label class="form-label">Màu sắc</label>
        <div class="row" id="colors-container">
          @if(is_array($product->color))
            @foreach($product->color as $index => $color)
              <div class="col-md-6 mb-3 color-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title mb-0">Biến thể Màu #{{ $index + 1 }}</h5>
                      <button type="button" class="btn btn-danger btn-sm remove-color-btn">Xóa</button>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <img id="preview-image-{{ $index }}" src="{{ asset('storage/' . ($color['img'] ?? 'images/default-phone.png')) }}" alt="Hình ảnh Màu" class="img-fluid rounded mb-2" style="width: 225px; height: 225px; object-fit: cover;">
                        <div class="mb-2">
                          <label class="form-label">Đổi Hình ảnh</label>
                          <input type="file" name="color[{{ $index }}][img]" class="form-control color-img-input" accept=".jpg,.jpeg,.png,.webp" data-preview="preview-image-{{ $index }}" onchange="handleImagePreview(this, document.getElementById('preview-image-{{ $index }}'))">
                          <input type="hidden" name="color[{{ $index }}][existing_img]" value="{{ $color['img'] ?? '' }}">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="mb-2">
                          <label class="form-label">Tên</label>
                          <select name="color[{{ $index }}][name]" class="form-control" data-selected="{{ $color['name'] }}">
                            <!-- Các tùy chọn sẽ được điền động -->
                          </select>
                        </div>
                        <div class="mb-2">
                          <label class="form-label">Giá tiền</label>
                          <input type="number" name="color[{{ $index }}][money]" class="form-control" value="{{ $color['money'] ?? 0 }}">
                        </div>
                        <div class="mb-2">
                          <label class="form-label">Số lượng</label>
                          <input type="number" name="color[{{ $index }}][quantity]" class="form-control" value="{{ $color['quantity'] ?? 0 }}">
                        </div>
                        <div>
                          <label class="form-label">Giảm giá tiền</label>
                          <input type="number" name="color[{{ $index }}][moneyDiscount]" class="form-control" value="{{ $color['moneyDiscount'] ?? 0 }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          @endif
        </div>
        <!-- Nút Thêm Màu Mới -->
        <button type="button" id="add-color-btn" class="btn btn-secondary mt-2">Thêm Màu Mới</button>
      </div>
      <!-- Nút Lưu -->
      <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
  </div> <!-- Kết thúc khung cuộn -->

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
      $('#product-favorite').select2({
        placeholder: "Chọn người dùng yêu thích",
        allowClear: true,
        width: '100%'
      });
    });
  </script>
<script>
    function handleImagePreview(input, previewImage) {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;  // Hiển thị hình ảnh xem trước từ tệp đã chọn
        };
        reader.readAsDataURL(file);
      } else {
        previewImage.src = "{{ asset('storage/images/default-phone.png') }}";  // Hiển thị hình ảnh mặc định nếu không chọn tệp
      }
    }
  document.addEventListener('DOMContentLoaded', function() {
    const colorsContainer = document.getElementById('colors-container');
    const addColorBtn = document.getElementById('add-color-btn');
    
    // Hàm điền dropdown chọn màu sắc
    function populateColorSelect(selectElement) {
  const selectedColor = selectElement.dataset.selected; // Lấy tên màu đã chọn

  axios.get('{{ route("colors") }}')
    .then(response => {
      const colors = response.data;
      colors.forEach(color => {
        const option = document.createElement('option');
        option.value = color.name;
        option.textContent = color.name;
        selectElement.appendChild(option);
      });

      // Thiết lập giá trị đã chọn sau khi các tùy chọn được thêm vào
      if (selectedColor) {
        selectElement.value = selectedColor;
      }
    })
    .catch(error => {
      console.error("Lỗi khi lấy dữ liệu màu sắc:", error);
    });
}

    // Điền dropdown màu sắc cho các màu đã có
    document.querySelectorAll('select[name^="color["]').forEach(selectElement => {
        console.log(selectElement.name);
      populateColorSelect(selectElement);
    });

    // Xử lý thêm biến thể màu mới
    let colorIndex = {{ is_array($product->color) ? count($product->color) : 0 }};
    addColorBtn.addEventListener('click', function(e) {
      e.preventDefault();

      if (colorIndex > 7) {
        return;
      }

      let newColorCard = `
        <div class="col-md-6 mb-3 color-card">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Biến thể Màu #${colorIndex + 1}</h5>
                <button type="button" class="btn btn-danger btn-sm remove-color-btn">Xóa</button>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <img id="preview-image-${colorIndex}" src="{{ asset('storage/images/default-phone.png') }}" alt="Hình ảnh Màu" class="img-fluid rounded mb-2" style="width: 225px; height: 225px; object-fit: cover;">
                  <div class="mb-2">
                    <label class="form-label">Đổi Hình ảnh</label>
                    <input type="file" name="color[${colorIndex}][img]" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="handleImagePreview(this, document.getElementById('preview-image-${colorIndex}'))">
                    <input type="hidden" name="color[${colorIndex}][existing_img]" value="{{ $color['img'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-2">
                    <label class="form-label">Tên</label>
                    <select name="color[${colorIndex}][name]" class="form-control">
                      <!-- Các tùy chọn sẽ được điền động -->
                    </select>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Giá tiền</label>
                    <input type="number" name="color[${colorIndex}][money]" class="form-control" value="0">
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="color[${colorIndex}][quantity]" class="form-control" value="0">
                  </div>
                  <div>
                    <label class="form-label">Giảm giá tiền</label>
                    <input type="number" name="color[${colorIndex}][moneyDiscount]" class="form-control" value="0">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      colorsContainer.insertAdjacentHTML('beforeend', newColorCard);
      
      // Điền các phần tử select mới
      const newSelectElement = document.querySelector(`select[name="color[${colorIndex}][name]"]`);
      populateColorSelect(newSelectElement);
      
      colorIndex++;
    });

    // Xử lý chức năng xóa biến thể màu
    function attachDeleteHandlers() {
      document.querySelectorAll('.remove-color-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const confirmed = confirm("Bạn chắc chắn muốn xóa biến thể màu này?");
          if (confirmed) {
            this.closest('.color-card').remove();  // Xóa biến thể màu
          }
        });
      });
    }

    attachDeleteHandlers();
  });
</script>
@endsection
