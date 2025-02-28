@extends('layouts.dashboard')

@section('title', 'Create New Product')

@section('content')
  <h1>Create New Product</h1>
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <!-- Scrollable container -->
  <div class="scroll-container" style="max-height: 92vh; overflow-y: auto; max-width: 165vh;">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <!-- Product Name -->
      <div class="mb-3">
        <label for="product-name" class="form-label">Name</label>
        <input type="text" id="product-name" name="name" class="form-control" value="{{ old('name') }}">
      </div>
      <!-- Product Description -->
      <div class="mb-3">
        <label for="product-description" class="form-label">Description</label>
        <textarea id="product-description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
      </div>
      <!-- Favorite (JSON) -->
      <div class="mb-3">
        <label for="product-favorite" class="form-label">Favorite (JSON Array of User IDs)</label>
        <textarea id="product-favorite" name="favorite" class="form-control" rows="2">{{ old('favorite', '[]') }}</textarea>
      </div>
      <!-- Color Variants -->
      <div class="mb-3">
        <label class="form-label">Colors</label>
        <div class="row" id="colors-container">
          <!-- New Colors will be added here -->
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
    let colorIndex = 0;  // Start from index 0 for new colors
    const colorsContainer = document.getElementById('colors-container');
    const addColorBtn = document.getElementById('add-color-btn');

    // Function to replace text input with select dropdown
    function populateColorsSelect(selectElement) {
      // Fetch colors from the server using Axios
      axios.get('{{ route("colors") }}')
        .then(response => {
          const colors = response.data;
          // Loop through colors and create option elements
          colors.forEach(color => {
            const option = document.createElement('option');
            option.value = color.name;
            option.textContent = color.name;
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error("Error fetching colors: ", error);
        });
    }
    
    // Function to handle the color addition
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
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-2">
                    <label class="form-label">Name</label>
                    <select name="color[${colorIndex}][name]" class="form-control" onchange="replaceColorInputWithSelect(${colorIndex})">
                      <!-- The options will be populated dynamically with axios -->
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
      
      // Get the new select element and populate it with colors
      const selectElement = document.querySelector(`select[name="color[${colorIndex}][name]"]`);
      populateColorsSelect(selectElement);

      colorIndex++;

      // Attach the delete button functionality again for the newly added color variant
      attachDeleteHandlers();
    });

    // Handle delete button functionality
    function attachDeleteHandlers() {
      document.querySelectorAll('.remove-color-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (confirm("Are you sure you want to delete this color variant?")) {
            this.closest('.color-card').remove();
          }
        });
      });
    }

    // Initially attach delete handlers to any existing delete buttons (if any)
    attachDeleteHandlers();
  });
</script>
@endsection
