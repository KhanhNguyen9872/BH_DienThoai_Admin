@extends('layouts.dashboard')

@section('title', 'Cài đặt')

@section('content')
  <h2>Cài đặt</h2>
  <p class="text-muted">Cập nhật các sở thích của bạn tại đây.</p>
  
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
  <form action="{{ route('settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Tính năng TELEGRAM BOT -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">TELEGRAM BOT | GỬI THÔNG BÁO SAU KHI ĐẶT HÀNG</span>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="telegramStatus" name="telegram_status"
                 @if(old('telegram_status', $settings['BOT_SEND_NOTIFICATION_AFTER_ORDER'] ?? false)) checked @endif>
          <label class="form-check-label" for="telegramStatus"></label>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label for="telegramUsername" class="form-label">TÊN NGƯỜI DÙNG</label>
          <input type="text" class="form-control" id="telegramUsername" name="telegram_username"
                 value="{{ old('telegram_username', $settings['BOT_USERNAME'] ?? '') }}" placeholder="Nhập Tên người dùng Telegram">
        </div>
        <div class="mb-3">
          <label for="telegramToken" class="form-label">MÃ TOKEN</label>
          <input type="text" class="form-control" id="telegramToken" name="telegram_token"
                 value="{{ old('telegram_token', $settings['BOT_TOKEN'] ?? '') }}" placeholder="Nhập Mã Token Telegram">
        </div>
        <div class="mb-3">
          <label for="telegramChatId" class="form-label">CHAT_ID</label>
          <input type="text" class="form-control" id="telegramChatId" name="telegram_chat_id"
                 value="{{ old('telegram_chat_id', $settings['BOT_CHAT_ID'] ?? '') }}" placeholder="Nhập Telegram Chat ID">
        </div>
      </div>
    </div>
    
    <!-- Tính năng Web Client (Chuyển đổi bảo trì) -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">WEB CLIENT | CHẾ ĐỘ BẢO TRÌ</span>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="maintenanceStatus" name="maintenance"
                 @if(old('maintenance', $settings['MAINTENANCE'] ?? false)) checked @endif>
          <label class="form-check-label" for="maintenanceStatus"></label>
        </div>
      </div>
      <div class="card-body">
        <p>Chuyển đổi để bật hoặc tắt chế độ bảo trì cho web client.</p>
      </div>
    </div>

    <!-- Tính năng Web Client (Bật Chatbot) -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">WEB CLIENT | BẬT CHATBOT</span>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="chatbotStatus" name="chatbot_enable"
                 @if(old('chatbot_enable', $settings['CHATBOT_ENABLE'] ?? false)) checked @endif>
          <label class="form-check-label" for="chatbotStatus"></label>
        </div>
      </div>
      <div class="card-body">
        <p>Bật hoặc tắt chức năng chatbot cho web client.</p>
      </div>
    </div>

    <!-- Nút Lưu -->
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Lưu</button>
    </div>
    
  </form>
@endsection

@section('scripts')
  <!-- Bạn có thể thêm JavaScript riêng cho trang ở đây nếu cần -->
@endsection
