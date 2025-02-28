@extends('layouts.dashboard')

@section('title', 'Product Management')

@section('content')
  <h2>Product Management</h2>
  <p class="text-muted">Manage your store's products here. You can add, edit, or remove products as needed.</p>

  <!-- Add New Product Button -->
  <a href="{{ url('/products/create') }}" class="btn btn-success mb-3">Add New Product</a>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Image</th>
          <th scope="col">Name</th>
          <th scope="col">Price</th>
          <th scope="col">Colors</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>
              @if($product->default_img)
                <img src="{{ asset('storage/' . $product->default_img) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width:80px; height:80px; object-fit:cover;">
              @else
                <span class="text-muted">No image</span>
              @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>
              @if($product->price)
                {{ number_format($product->price, 0) . ' VNĐ' }}
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td>{{ $product->colors ?? 'N/A' }}</td>
            <td>
              <a href="{{ url('/products/' . $product->id) }}" class="btn btn-sm btn-primary">Edit</a>              
              <button onclick="deleteProduct({{ $product->id }})" class="btn btn-sm btn-danger">Delete</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">No products found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@section('scripts')
<script>
  // Function to handle the delete request
  function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
      // Send the DELETE request using Axios
      axios.delete(`/api/products/${productId}`)
        .then(response => {
          window.location.href = '/products';
        })
        .catch(error => {
          // Handle any errors
          console.error('There was an error deleting the product!', error);
          alert('An error occurred while deleting the product.');
        });
    }
  }
</script>
  <!-- Page-specific JavaScript can be added here if needed -->
@endsection
