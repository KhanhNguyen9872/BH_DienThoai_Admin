<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập Quản trị - PhoneStore</title>
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
      <h2 class="text-center mb-4">Đăng nhập Quản trị</h2>
      
      <!-- Hiển thị thông báo lỗi -->
      @if($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
      @endif
      
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Tên người dùng</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng" required autofocus>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>
      </form>
      
      <!-- <div class="mt-3 text-center">
        <a href="#">Quên mật khẩu?</a>
      </div> -->
    </div>
  </div>
  
</body>
</html>
