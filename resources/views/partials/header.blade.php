<!-- resources/views/partials/header.blade.php -->
<nav class="navbar navbar-expand-md navbar-light bg-light border-bottom p-3">
  <div class="container-fluid">
    <!-- Left: Brand & Sidebar Toggler -->
    <div class="d-flex align-items-center">
      <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" 
              aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand fw-bold text-uppercase" href="/dashboard">PhoneStore Admin</a>
    </div>

    <!-- Center: Search Bar (visible on md+ screens) -->
    <div class="d-none d-md-block mx-auto position-relative" style="width: 40%;">
      <form id="searchForm" onsubmit="return false;">
        <input type="search" name="q" id="searchInput" class="form-control" placeholder="Search products..." aria-label="Search">
      </form>
      <!-- Dropdown overlay for search results -->
      <div id="searchDropdown" class="dropdown-menu" style="width: 100%; max-height: 300px; overflow-y: auto;"></div>
    </div>

    <!-- Right: Notification and User Dropdown -->
    <div class="d-flex align-items-center">
      <!-- Notification Dropdown -->
      <div class="dropdown me-3">
        <button class="btn btn-light position-relative dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-bell fs-5"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $notifications->count() }}
            <span class="visually-hidden">unread alerts</span>
          </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
          @if($notifications->isEmpty())
            <li class="dropdown-item text-center">No notifications</li>
          @else
            @foreach($notifications as $notification)
              <li class="dropdown-item">
                <div>
                  <small class="text-muted">
                    {{ \Carbon\Carbon::parse($notification->time)->diffForHumans() }}
                  </small>
                </div>
                <div>{{ $notification->text }}</div>
              </li>
            @endforeach
          @endif
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-center" href="/notifications">View All Notifications</a></li>
        </ul>
      </div>

      <!-- User Dropdown -->
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-person-circle me-1"></i> {{ auth()->user()->full_name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="/profile">Profile Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/logout">Sign Out</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Include Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  const searchInput = document.getElementById('searchInput');
  const searchDropdown = document.getElementById('searchDropdown');

  // Hide dropdown when clicking outside the search form
  document.addEventListener('click', function(e) {
    if (!document.getElementById('searchForm').contains(e.target)) {
      searchDropdown.classList.remove('show');
    }
  });

  // Listen for input events on the search field
  searchInput.addEventListener('input', function() {
    let query = this.value.trim();
    if (!query) {
      searchDropdown.classList.remove('show');
      return;
    }

    axios.get("{{ route('search') }}", {
      params: { q: query },
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(response) {
      const products = response.data.products;
      let html = '';
      if (products.length > 0) {
        html += '<ul class="list-group">';
        products.forEach(function(product) {
          html += `<a href="/dashboard/products/${product.id}" class="list-group-item list-group-item-action d-flex align-items-center">
            ${ product.default_img ? `<img src="/storage/${product.default_img}" alt="${product.name}" class="img-thumbnail me-2" style="width:40px; height:40px; object-fit: cover;">` : '' }
            <span>${product.name}</span>
          </a>`;
        });
        html += '</ul>';
      } else {
        html = '<p class="p-2 mb-0">No products found.</p>';
      }
      searchDropdown.innerHTML = html;
      searchDropdown.classList.add('show');
    })
    .catch(function(error) {
      console.error('Error fetching search results:', error);
    });
  });
</script>
