@extends('layouts.dashboard')

@section('title', 'Edit Product')

@section('content')
  <h1>Product Details</h1>
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Scrollable container -->
  <div class="scroll-container" style="max-height: 92vh; overflow-y: auto; max-width: 165vh;">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <!-- Product ID (read-only) -->
      <div class="mb-3">
        <label for="product-id" class="form-label">Product ID</label>
        <input type="text" id="product-id" class="form-control" value="{{ $product->id }}" disabled>
      </div>
      <!-- Product Name -->
      <div class="mb-3">
        <label for="product-name" class="form-label">Name</label>
        <input type="text" id="product-name" name="name" class="form-control" value="{{ old('name', $product->name) }}">
      </div>
      <!-- Product Description -->
      <div class="mb-3">
        <label for="product-description" class="form-label">Description</label>
        <textarea id="product-description" name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
      </div>
      <!-- Favorite (JSON) -->
      <div class="mb-3">
        <label for="product-favorite" class="form-label">Favorite (JSON Array of User IDs)</label>
        <textarea id="product-favorite" name="favorite" class="form-control" rows="2">{{ old('favorite', json_encode($product->favorite)) }}</textarea>
      </div>
      <!-- Color Variants -->
      <div class="mb-3">
        <label class="form-label">Colors</label>
        <div class="row" id="colors-container">
          @if(is_array($product->color))
            @foreach($product->color as $index => $color)
              <div class="col-md-6 mb-3 color-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <h5 class="card-title mb-0">Color Variant #{{ $index + 1 }}</h5>
                      <button type="button" class="btn btn-danger btn-sm remove-color-btn">Delete</button>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <img id="preview-image-{{ $index }}" src="{{ asset('storage/' . ($color['img'] ?? 'images/default-phone.png')) }}" alt="Color Image" class="img-fluid rounded mb-2" style="width: 225px; height: 225px; object-fit: cover;">
                        <div class="mb-2">
                          <label class="form-label">Change Image</label>
                          <input type="file" name="color[{{ $index }}][img]" class="form-control color-img-input" accept=".jpg,.jpeg,.png,.webp" data-preview="preview-image-{{ $index }}" onchange="handleImagePreview(this, document.getElementById('preview-image-{{ $index }}'))">
                          <input type="hidden" name="color[{{ $index }}][existing_img]" value="{{ $color['img'] ?? '' }}">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="mb-2">
                          <label class="form-label">Name</label>
                          <select name="color[{{ $index }}][name]" class="form-control" data-selected="{{ $color['name'] }}">
                            <!-- Options will be populated dynamically -->
                          </select>
                        </div>
                        <div class="mb-2">
                          <label class="form-label">Money</label>
                          <input type="number" name="color[{{ $index }}][money]" class="form-control" value="{{ $color['money'] ?? 0 }}">
                        </div>
                        <div class="mb-2">
                          <label class="form-label">Quantity</label>
                          <input type="number" name="color[{{ $index }}][quantity]" class="form-control" value="{{ $color['quantity'] ?? 0 }}">
                        </div>
                        <div>
                          <label class="form-label">Money Discount</label>
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
        <!-- Add New Color Button -->
        <button type="button" id="add-color-btn" class="btn btn-secondary mt-2">Add New Color</button>
      </div>
      <!-- Save Button -->
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div> <!-- End of scrollable container -->

@endsection

@section('scripts')
<script>
    function handleImagePreview(input, previewImage) {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;  // Set the image preview to the selected file
        };
        reader.readAsDataURL(file);
      } else {
        previewImage.src = "{{ asset('storage/images/default-phone.png') }}";  // Show default image if no file is selected
      }
    }
  document.addEventListener('DOMContentLoaded', function() {
    const colorsContainer = document.getElementById('colors-container');
    const addColorBtn = document.getElementById('add-color-btn');
    
    // Function to populate the color select dropdown
    function populateColorSelect(selectElement) {
  const selectedColor = selectElement.dataset.selected; // Get stored color name

  axios.get('{{ route("colors") }}')
    .then(response => {
      const colors = response.data;
      colors.forEach(color => {
        const option = document.createElement('option');
        option.value = color.name;
        option.textContent = color.name;
        selectElement.appendChild(option);
      });

      // Set selected value after options are added
      if (selectedColor) {
        selectElement.value = selectedColor;
      }
    })
    .catch(error => {
      console.error("Error fetching colors:", error);
    });
}

    // Populate color select dropdowns for existing colors
    document.querySelectorAll('select[name^="color["]').forEach(selectElement => {
        console.log(selectElement.name);
      populateColorSelect(selectElement);
    });

    // Handle adding new color variants
    let colorIndex = {{ is_array($product->color) ? count($product->color) : 0 }};
    addColorBtn.addEventListener('click', function(e) {
      e.preventDefault();

      let newColorCard = `
        <div class="col-md-6 mb-3 color-card">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Color Variant #${colorIndex + 1}</h5>
                <button type="button" class="btn btn-danger btn-sm remove-color-btn">Delete</button>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <img id="preview-image-${colorIndex}" src="{{ asset('storage/images/default-phone.png') }}" alt="Color Image" class="img-fluid rounded mb-2" style="width: 225px; height: 225px; object-fit: cover;">
                  <div class="mb-2">
                    <label class="form-label">Change Image</label>
                    <input type="file" name="color[${colorIndex}][img]" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="handleImagePreview(this, document.getElementById('preview-image-${colorIndex}'))">
                    <input type="hidden" name="color[${colorIndex}][existing_img]" value="{{ $color['img'] }}">
                    </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-2">
                    <label class="form-label">Name</label>
                    <select name="color[${colorIndex}][name]" class="form-control">
                      <!-- Options will be populated dynamically -->
                    </select>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Money</label>
                    <input type="number" name="color[${colorIndex}][money]" class="form-control" value="0">
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="color[${colorIndex}][quantity]" class="form-control" value="0">
                  </div>
                  <div>
                    <label class="form-label">Money Discount</label>
                    <input type="number" name="color[${colorIndex}][moneyDiscount]" class="form-control" value="0">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      colorsContainer.insertAdjacentHTML('beforeend', newColorCard);
      
      // Populate the new select element
      const newSelectElement = document.querySelector(`select[name="color[${colorIndex}][name]"]`);
      populateColorSelect(newSelectElement);
      
      colorIndex++;
    });

    // Delete color variant functionality
    function attachDeleteHandlers() {
      document.querySelectorAll('.remove-color-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const confirmed = confirm("Are you sure you want to delete this color variant?");
          if (confirmed) {
            this.closest('.color-card').remove();  // Remove the color variant card
          }
        });
      });
    }

    attachDeleteHandlers();
  });
</script>
@endsection
