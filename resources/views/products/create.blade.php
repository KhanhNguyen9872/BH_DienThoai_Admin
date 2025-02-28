@extends('layouts.dashboard')

@section('title', 'Tạo Sản phẩm Mới')

@section('content')
  <h1>Tạo Sản phẩm Mới</h1>
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
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <!-- Tên sản phẩm -->
      <div class="mb-3">
        <label for="product-name" class="form-label">Tên sản phẩm</label>
        <input type="text" id="product-name" name="name" class="form-control" value="{{ old('name') }}">
      </div>
      <!-- Mô tả sản phẩm -->
      <div class="mb-3">
        <label for="product-description" class="form-label">Mô tả sản phẩm</label>
        <textarea id="product-description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
      </div>
      <!-- Mục yêu thích (JSON) -->
      <div class="mb-3">
        <label for="product-favorite" class="form-label">Yêu thích (Mảng JSON của ID người dùng)</label>
        <textarea id="product-favorite" name="favorite" class="form-control" rows="2">{{ old('favorite', '[]') }}</textarea>
      </div>
      <!-- Các biến thể màu sắc -->
      <div class="mb-3">
        <label class="form-label">Màu sắc</label>
        <div class="row" id="colors-container">
          <!-- Các màu mới sẽ được thêm vào đây -->
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
  function handleImagePreview(input, previewImage) {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;  // Hiển thị ảnh xem trước từ tệp đã chọn
        };
        reader.readAsDataURL(file);
      } else {
        previewImage.src = "{{ asset('storage/images/default-phone.png') }}";  // Hiển thị ảnh mặc định nếu không chọn tệp
      }
    }
  document.addEventListener('DOMContentLoaded', function() {
    let colorIndex = 0;  // Bắt đầu từ chỉ số 0 cho các màu mới
    const colorsContainer = document.getElementById('colors-container');
    const addColorBtn = document.getElementById('add-color-btn');

    // Hàm thay thế trường nhập liệu bằng dropdown chọn
    function populateColorsSelect(selectElement) {
      // Lấy các màu từ server sử dụng Axios
      axios.get('{{ route("colors") }}')
        .then(response => {
          const colors = response.data;
          // Lặp qua các màu và tạo phần tử option
          colors.forEach(color => {
            const option = document.createElement('option');
            option.value = color.name;
            option.textContent = color.name;
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error("Lỗi khi lấy dữ liệu màu sắc: ", error);
        });
    }
    
    // Hàm xử lý thêm màu mới
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
                  <img id="preview-image-${colorIndex}" src="{{ asset('storage/images/default-phone.png') }}" alt="Hình ảnh màu" class="img-fluid rounded mb-2" style="width: 225px; height: 225px; object-fit: cover;">
                  <div class="mb-2">
                    <label class="form-label">Đổi hình ảnh</label>
                    <input type="file" name="color[${colorIndex}][img]" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="handleImagePreview(this, document.getElementById('preview-image-${colorIndex}'))">
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-2">
                    <label class="form-label">Tên</label>
                    <select name="color[${colorIndex}][name]" class="form-control" onchange="replaceColorInputWithSelect(${colorIndex})">
                      <!-- Các tùy chọn sẽ được điền động bằng axios -->
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
      
      // Lấy phần tử select mới và điền màu sắc vào
      const selectElement = document.querySelector(`select[name="color[${colorIndex}][name]"]`);
      populateColorsSelect(selectElement);

      colorIndex++;

      // Gắn lại chức năng nút xóa cho các biến thể màu mới
      attachDeleteHandlers();
    });

    // Xử lý chức năng nút xóa
    function attachDeleteHandlers() {
      document.querySelectorAll('.remove-color-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (confirm("Bạn chắc chắn muốn xóa biến thể màu này?")) {
            this.closest('.color-card').remove();
          }
        });
      });
    }

    // Gắn lại chức năng xóa cho bất kỳ nút xóa nào đã tồn tại
    attachDeleteHandlers();
  });
</script>
@endsection
