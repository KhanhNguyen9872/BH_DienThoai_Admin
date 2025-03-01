@extends('layouts.dashboard')

@section('title', 'Cài đặt')

@section('content')
  <!-- Container cuộn: full width, full height và cuộn dọc khi nội dung quá dài -->
  <div style="width: 100%; height: 94vh; overflow-y: auto; padding: 20px;">
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
          <span class="fw-bold">WEB CLIENT | CHATBOT</span>
          <div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="chatbot_enable" id="chatbot_off" value="0"
                {{ old('chatbot_enable', $settings['CHATBOT_ENABLE'] ?? 0) == 0 ? 'checked' : '' }}>
              <label class="form-check-label" for="chatbot_off">Tắt</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="chatbot_enable" id="chatbot_local" value="1"
                {{ old('chatbot_enable', $settings['CHATBOT_ENABLE'] ?? 0) == 1 ? 'checked' : '' }}>
              <label class="form-check-label" for="chatbot_local">Cục bộ</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="chatbot_enable" id="chatbot_gemini" value="2"
                {{ old('chatbot_enable', $settings['CHATBOT_ENABLE'] ?? 0) == 2 ? 'checked' : '' }}>
              <label class="form-check-label" for="chatbot_gemini">Gemini</label>
            </div>
          </div>
        </div>
        <div class="card-body">
          <p>Bật hoặc tắt chức năng chatbot cho web client.</p>

          <div class="mb-3">
            <label for="localChatbotURL" class="form-label">URL Chatbot cục bộ</label>
            <input type="text" class="form-control" id="localChatbotURL" name="local_chatbot_url"
                   value="{{ old('local_chatbot_url', $settings['LOCAL_CHATBOT_URL'] ?? '') }}" placeholder="Nhập URL Chatbot cục bộ">
          </div>
          
          <div class="mb-3">
            <label for="localChatbotModel" class="form-label">Tên mô hình Chatbot cục bộ</label>
            <input type="text" class="form-control" id="localChatbotModel" name="local_chatbot_model"
                   value="{{ old('local_chatbot_model', $settings['LOCAL_CHATBOT_MODEL'] ?? '') }}" placeholder="Nhập tên mô hình Chatbot cục bộ">
          </div>

          <div class="mb-3">
            <label for="localChatbotTemperature" class="form-label">Mức độ ngẫu nhiên Chatbot cục bộ (0.0 -> 1.0)</label>
            <input type="text" class="form-control" id="localChatbotTemperature" name="local_chatbot_temperature"
                   value="{{ old('local_chatbot_temperature', $settings['LOCAL_CHATBOT_TEMPERATURE'] ?? '') }}" placeholder="Nhập mức độ ngẫu nhiên Chatbot cục bộ">
          </div>

          <div class="mb-3">
            <label for="geminiApiKey" class="form-label">Gemini API Key</label>
            <input type="text" class="form-control" id="geminiApiKey" name="gemini_api_key"
                   value="{{ old('gemini_api_key', $settings['GEMINI_API_KEY'] ?? '') }}" placeholder="Nhập Gemini API Key">
          </div>
        </div>
      </div>

      <!-- Nút Lưu -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
      
    </form>
  </div>
@endsection

@section('scripts')
  <!-- Bạn có thể thêm JavaScript riêng cho trang ở đây nếu cần -->
@endsection
