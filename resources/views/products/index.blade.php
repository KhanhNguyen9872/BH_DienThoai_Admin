@extends('layouts.dashboard')

@section('title', 'Quản lý Sản phẩm')

@section('content')
  <h2>Quản lý Sản phẩm</h2>
  <p class="text-muted">Quản lý các sản phẩm của cửa hàng bạn tại đây. Bạn có thể thêm, chỉnh sửa hoặc xóa sản phẩm khi cần thiết.</p>

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

  <!-- Nút Thêm Sản phẩm Mới -->
  <a href="{{ url('/products/create') }}" class="btn btn-success mb-3">Thêm Sản phẩm Mới</a>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Ảnh</th>
          <th scope="col">Tên</th>
          <th scope="col">Yêu thích</th>
          <th scope="col">Giá</th>
          <th scope="col">Màu sắc</th>
          <th scope="col">Hành động</th>
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
                <span class="text-muted">Không có ảnh</span>
              @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ count($product->favorites) }}</td>
            <td>
              @if($product->price)
                {{ number_format($product->price, 0) . ' VNĐ' }}
              @else
                <span class="text-muted">Không có</span>
              @endif
            </td>
            <td>{{ $product->colors ?? 'Không có' }}</td>
            <td>
              <a href="{{ url('/products/' . $product->id) }}" class="btn btn-sm btn-primary">Chỉnh sửa</a> 

              <!-- Xóa Sản phẩm bằng Form -->
              <form action="{{ route('products.delete', $product->id) }}" method="POST" class="d-inline-block" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE') <!-- Method DELETE -->
                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">Không có sản phẩm nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Liên kết phân trang -->
  <div class="d-flex justify-content-center mt-3">
    <nav>
        <ul class="pagination">
            <!-- Liên kết Trang trước -->
            @if ($products->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Trang trước</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">Trang trước</a></li>
            @endif

            <!-- Các số trang -->
            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                <li class="page-item {{ ($products->currentPage() == $page) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Liên kết Trang tiếp theo -->
            @if ($products->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">Trang sau</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Trang sau</span></li>
            @endif
        </ul>
    </nav>
  </div>
@endsection

@section('scripts')
<script>
  // Hàm xác nhận khi xóa sản phẩm
  function confirmDelete() {
    return confirm('Bạn chắc chắn muốn xóa sản phẩm này?');
  }
</script>
@endsection
