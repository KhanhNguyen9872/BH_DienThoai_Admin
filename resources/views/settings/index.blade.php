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

    <!-- Ensure enctype="multipart/form-data" to handle file uploads -->
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
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
              <!-- Display default "not tested" icon -->
              <span class="input-group-text" id="connectStatus" style="background: transparent; border: none;">
                <i class="bi bi-question-circle-fill text-secondary"></i>
              </span>
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
              <!-- Display default "not tested" icon -->
              <span class="input-group-text" id="testAPIStatus" style="background: transparent; border: none;">
                <i class="bi bi-question-circle-fill text-secondary"></i>
              </span>
            </div>
          </div>

          <div class="row">
            <!-- Bot Avatar Section -->
            <div class="col-6 mb-3">
              <label for="botAvatar" class="form-label">Bot Avatar</label>
              <div class="d-flex align-items-center">
                <img 
                  id="botAvatarPreview" 
                  src="{{ old('bot_avatar', '/storage/' . $settings['CHATBOT_AVATAR'] ?? asset('storage/images/default_bot_avatar.png')) }}"
                  alt="Bot Avatar Preview" 
                  style="width: 80px; height: 80px; object-fit: cover; margin-right: 10px; border: 1px solid #ccc;"
                >
                <input type="file" class="form-control" id="botAvatar" name="bot_avatar">
              </div>
            </div>

            <!-- User Avatar Section -->
            <div class="col-6 mb-3">
              <label for="userAvatar" class="form-label">User Avatar</label>
              <div class="d-flex align-items-center">
                <img 
                  id="userAvatarPreview" 
                  src="{{ old('user_avatar', '/storage/' . $settings['CHATBOT_USER_AVATAR'] ?? asset('storage/images/default_user_avatar.png')) }}"
                  alt="User Avatar Preview" 
                  style="width: 80px; height: 80px; object-fit: cover; margin-right: 10px; border: 1px solid #ccc;"
                >
                <input type="file" class="form-control" id="userAvatar" name="user_avatar">
              </div>
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
          // Now response.data.data is an array of model IDs.
          modelsData = response.data.data;

          // Populate the dropdown menu with model IDs
          const modelDropdown = document.getElementById('modelDropdown');
          modelDropdown.innerHTML = '';
          modelsData.forEach(modelId => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.className = 'dropdown-item';
            a.href = '#';
            a.textContent = modelId;
            a.addEventListener('click', function(e) {
              e.preventDefault();
              // Set the input field to the selected model's id
              document.getElementById('localChatbotModel').value = modelId;
            });
            li.appendChild(a);
            modelDropdown.appendChild(li);
          });
        } else {
          connectStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        }
      } catch (error) {
        connectStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
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
      } catch (error) {
        testAPIStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
        console.error('Gemini API test error:', error);
      }
    });

    // Preview function for avatars
    function previewImage(input, previewId) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById(previewId).setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    // Bot avatar preview
    const botAvatarInput = document.getElementById('botAvatar');
    if (botAvatarInput) {
      botAvatarInput.addEventListener('change', function() {
        previewImage(this, 'botAvatarPreview');
      });
    }

    // User avatar preview
    const userAvatarInput = document.getElementById('userAvatar');
    if (userAvatarInput) {
      userAvatarInput.addEventListener('change', function() {
        previewImage(this, 'userAvatarPreview');
      });
    }
  </script>
@endsection
