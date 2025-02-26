<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - PhoneStore</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      height: 100vh;
    }
    .login-card {
      max-width: 400px;
      margin: auto;
      margin-top: 10%;
      padding: 2rem;
      background-color: #fff;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .login-card .form-control {
      border-radius: 0.5rem;
    }
    .login-card .btn {
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-card">
      <h2 class="text-center mb-4">Admin Login</h2>
      
      <!-- Display error messages -->
      @if($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
      @endif
      
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required autofocus>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Log In</button>
        </div>
      </form>
      
      <div class="mt-3 text-center">
        <a href="#">Forgot Password?</a>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
