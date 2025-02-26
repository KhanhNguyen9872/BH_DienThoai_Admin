@extends('layouts.dashboard')

@section('title', 'Product Management')

@section('content')
  <h2>Product Management</h2>
  <p class="text-muted">Manage your store's products here. You can add, edit, or remove products as needed.</p>
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
              <a href="{{ url('/dashboard/products/' . $product->id . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
              <a href="{{ url('/dashboard/products/' . $product->id . '/delete') }}" class="btn btn-sm btn-danger">Delete</a>
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
  <!-- Page-specific JavaScript can be added here if needed -->
@endsection
