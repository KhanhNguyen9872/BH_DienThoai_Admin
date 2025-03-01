@extends('layouts.dashboard')

@section('title', 'Cài đặt')

@section('content')
  <!-- Container cuộn: full width, full height và cuộn dọc khi nội dung quá dài -->
  <div style="width: 100%; height: 94vh; overflow-y: auto; padding: 5px;">
    <h2>Cài đặt</h2>
    <p class="text-muted">Tùy chỉnh các cài đặt tại đây.</p>
    
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

          <div class="mb-3">
            <label for="localChatbotURL" class="form-label">URL Chatbot cục bộ</label>
            <div class="input-group">
              <input type="text" class="form-control" id="localChatbotURL" name="local_chatbot_url"
                     value="{{ old('local_chatbot_url', $settings['LOCAL_CHATBOT_URL'] ?? '') }}" placeholder="Nhập URL Chatbot cục bộ">
              <button type="button" class="btn btn-outline-secondary" id="btnConnect">Kết nối</button>
              <!-- Span to display the connection status icon -->
              <span class="input-group-text" id="connectStatus" style="display: none; background: transparent; border: none;"></span>
            </div>
          </div>

          <div class="mb-3">
            <label for="localChatbotModel" class="form-label">Tên mô hình Chatbot cục bộ</label>
            <div class="input-group">
              <input type="text" class="form-control" id="localChatbotModel" name="local_chatbot_model"
                     value="{{ old('local_chatbot_model', $settings['LOCAL_CHATBOT_MODEL'] ?? '') }}" placeholder="Nhập tên mô hình Chatbot cục bộ">
              <!-- Dropdown toggle button for model selection -->
              <button type="button" class="btn btn-outline-secondary dropdown-toggle" id="btnSelectModel" data-bs-toggle="dropdown" aria-expanded="false">Chọn model</button>
              <ul class="dropdown-menu" id="modelDropdown"></ul>
            </div>
          </div>

          <div class="mb-3">
            <label for="localChatbotTemperature" class="form-label">Mức độ ngẫu nhiên Chatbot cục bộ (0.0 -> 1.0)</label>
            <input type="text" class="form-control" id="localChatbotTemperature" name="local_chatbot_temperature"
                   value="{{ old('local_chatbot_temperature', $settings['LOCAL_CHATBOT_TEMPERATURE'] ?? '') }}" placeholder="Nhập mức độ ngẫu nhiên Chatbot cục bộ">
          </div>

          <div class="mb-3">
            <label for="geminiApiKey" class="form-label">Gemini API Key</label>
            <div class="input-group">
              <input type="text" class="form-control" id="geminiApiKey" name="gemini_api_key"
                     value="{{ old('gemini_api_key', $settings['GEMINI_API_KEY'] ?? '') }}" placeholder="Nhập Gemini API Key">
              <button type="button" class="btn btn-outline-secondary" id="btnTestAPI">Test API</button>
              <!-- Span to display the test API status icon -->
              <span class="input-group-text" id="testAPIStatus" style="display: none; background: transparent; border: none;"></span>
            </div>
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
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    // Global variable to hold the fetched models
    let modelsData = [];

    // Event listener for the "Kết nối" button on Local Chatbot URL
    document.getElementById('btnConnect').addEventListener('click', async function() {
      const urlInput = document.getElementById('localChatbotURL');
      const connectStatus = document.getElementById('connectStatus');
      const url = urlInput.value.trim();
      
      // Clear previously fetched models and dropdown items
      modelsData = [];
      document.getElementById('modelDropdown').innerHTML = '';

      if (!url) {
        connectStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        connectStatus.style.display = 'flex';
        return;
      }
      
      try {
        // Call the proxy endpoint with the URL in the request body.
        const response = await axios.post('/api/get-models', { url: url }, {
          headers: {
            'Content-Type': 'application/json'
          }
        });
        
        if (response.data.success) {
          connectStatus.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
          // Extract models from the response
          let allModels = [];
          if (response.data.data && response.data.data.data) {
            allModels = response.data.data.data;
          }
          // Filter only items where object === 'model'
          modelsData = allModels.filter(item => item.object === 'model');
          
          // Populate the dropdown menu with model IDs
          const modelDropdown = document.getElementById('modelDropdown');
          modelDropdown.innerHTML = '';
          modelsData.forEach(model => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.className = 'dropdown-item';
            a.href = '#';
            a.textContent = model.id;
            a.addEventListener('click', function(e) {
              e.preventDefault();
              // Set the input field to the selected model's id
              document.getElementById('localChatbotModel').value = model.id;
            });
            li.appendChild(a);
            modelDropdown.appendChild(li);
          });
          
        } else {
          connectStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        }
        connectStatus.style.display = 'flex';
      } catch (error) {
        connectStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        connectStatus.style.display = 'flex';
        console.error('Error:', error);
      }
    });

    // Event listener for the "Test API" button for Gemini API.
    // This now calls the Laravel endpoint /api/test-gemini.
    document.getElementById('btnTestAPI').addEventListener('click', async function() {
      const geminiKeyInput = document.getElementById('geminiApiKey');
      const testAPIStatus = document.getElementById('testAPIStatus');
      const apiKey = geminiKeyInput.value.trim();
      
      if (!apiKey) {
        testAPIStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        testAPIStatus.style.display = 'flex';
        return;
      }
      
      try {
        const response = await axios.post('/api/test-gemini', { api_key: apiKey }, {
          headers: {
            'Content-Type': 'application/json'
          }
        });
        
        if (response.data.success) {
          testAPIStatus.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
        } else {
          testAPIStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        }
        testAPIStatus.style.display = 'flex';
      } catch (error) {
        testAPIStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        testAPIStatus.style.display = 'flex';
        console.error('Gemini API test error:', error);
      }
    });
  </script>
@endsection
