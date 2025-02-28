@extends('layouts.dashboard')

@section('content')
<div class="container my-4">
    <h1>Product Details</h1>
    @if(session('success'))
      <div class="alert alert-success">
         {{ session('success') }}
      </div>
    @endif
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
        <!-- Color Variants (JSON) -->
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
                                        <img src="{{ asset('storage/' . ($color['img'] ?? 'placeholder.png')) }}" alt="Color Image" class="img-fluid rounded mb-2">
                                        <div class="mb-2">
                                            <label class="form-label">Change Image</label>
                                            <input type="file" name="color[{{ $index }}][img]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="color[{{ $index }}][name]" class="form-control" value="{{ $color['name'] ?? '' }}">
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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let colorIndex = {{ is_array($product->color) ? count($product->color) : 0 }};
    const colorsContainer = document.getElementById('colors-container');
    const addColorBtn = document.getElementById('add-color-btn');
    
    function attachDeleteHandlers() {
        document.querySelectorAll('.remove-color-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.closest('.color-card').remove();
            });
        });
    }
    
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
                                <img src="{{ asset('storage/default-phone.png') }}" alt="Color Image" class="img-fluid rounded mb-2">
                                <div class="mb-2">
                                    <label class="form-label">Change Image</label>
                                    <input type="file" name="color[${colorIndex}][img]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="color[${colorIndex}][name]" class="form-control" value="">
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
        colorIndex++;
        attachDeleteHandlers();
    });
    
    attachDeleteHandlers();
});
</script>
@endsection
