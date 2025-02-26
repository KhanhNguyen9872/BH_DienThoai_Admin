<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>@yield('title', 'Dashboard') - Phone Store Admin</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    /* Use flex layout for container so main content expands when sidebar is minimized */
    #dashboardContainer {
      display: flex;
      transition: all 0.3s ease;
    }
    /* Sidebar default: full height and width */
    .sidebar {
      width: 250px;
      height: 100vh; /* Full viewport height */
      transition: width 0.3s ease;
    }
    /* Minimal sidebar style: shrink width and hide text labels */
    .sidebar.sidebar-minimized {
      width: 80px;
    }
    .sidebar.sidebar-minimized .link-text {
      display: none;
    }
    .sidebar.sidebar-minimized .nav-link {
      justify-content: center;
    }
    /* Main content takes the remaining space */
    main {
      flex-grow: 1;
      padding: 2rem;
      transition: margin-left 0.3s ease;
    }
    /* Active sidebar link: set background color and text color */
    .sidebar .nav-link.active {
      background-color: #0d6efd !important; /* Bootstrap primary */
      color: #fff !important;
      border-radius: 0.25rem;
    }
    .sidebar .nav-link.active i {
      color: #fff !important;
    }
  </style>
</head>
<body>
  @include('partials.header')
  <div id="dashboardContainer">
    @include('partials.sidebar')
    <main>
      @yield('content')
    </main>
  </div>
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle sidebar minimal mode and automatically expand main content
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebarMenu');
      sidebar.classList.toggle('sidebar-minimized');
      
      // Toggle the chevron icon
      const icon = this.querySelector('i');
      if (sidebar.classList.contains('sidebar-minimized')) {
        icon.classList.remove('bi-chevron-left');
        icon.classList.add('bi-chevron-right');
      } else {
        icon.classList.remove('bi-chevron-right');
        icon.classList.add('bi-chevron-left');
      }
    });
  </script>
  @yield('scripts')
</body>
</html>
