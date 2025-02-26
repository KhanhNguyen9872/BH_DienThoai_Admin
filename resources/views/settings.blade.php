@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')
  <h2>Settings</h2>
  <p class="text-muted">Update your preferences here.</p>
  
  @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  
  <form action="{{ route('settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- TELEGRAM BOT Feature -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">TELEGRAM BOT | SEND NOTIFICATION AFTER ORDER</span>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="telegramStatus" name="telegram_status"
                 @if(old('telegram_status', $settings['BOT_SEND_NOTIFICATION_AFTER_ORDER'] ?? false)) checked @endif>
          <label class="form-check-label" for="telegramStatus"></label>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label for="telegramUsername" class="form-label">USERNAME</label>
          <input type="text" class="form-control" id="telegramUsername" name="telegram_username"
                 value="{{ old('telegram_username', $settings['BOT_USERNAME'] ?? '') }}" placeholder="Enter Telegram Username">
        </div>
        <div class="mb-3">
          <label for="telegramToken" class="form-label">TOKEN</label>
          <input type="text" class="form-control" id="telegramToken" name="telegram_token"
                 value="{{ old('telegram_token', $settings['BOT_TOKEN'] ?? '') }}" placeholder="Enter Telegram Token">
        </div>
        <div class="mb-3">
          <label for="telegramChatId" class="form-label">CHAT_ID</label>
          <input type="text" class="form-control" id="telegramChatId" name="telegram_chat_id"
                 value="{{ old('telegram_chat_id', $settings['BOT_CHAT_ID'] ?? '') }}" placeholder="Enter Telegram Chat ID">
        </div>
      </div>
    </div>
    
    <!-- Web Client Feature (Maintenance Toggle) -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">WEB CLIENT | MAINTENANCE</span>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="maintenanceStatus" name="maintenance"
                 @if(old('maintenance', $settings['MAINTENANCE'] ?? false)) checked @endif>
          <label class="form-check-label" for="maintenanceStatus"></label>
        </div>
      </div>
      <div class="card-body">
        <p>Toggle to enable or disable maintenance mode for the web client.</p>
      </div>
    </div>

    <!-- Web Client Feature (Chatbot Enable) -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">WEB CLIENT | CHATBOT ENABLE</span>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="chatbotStatus" name="chatbot_enable"
                 @if(old('chatbot_enable', $settings['CHATBOT_ENABLE'] ?? false)) checked @endif>
          <label class="form-check-label" for="chatbotStatus"></label>
        </div>
      </div>
      <div class="card-body">
        <p>Enable or disable the chatbot functionality for the web client.</p>
      </div>
    </div>

    <!-- Save Button -->
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
    
  </form>
@endsection

@section('scripts')
  <!-- You can add page-specific JavaScript here if needed -->
@endsection
