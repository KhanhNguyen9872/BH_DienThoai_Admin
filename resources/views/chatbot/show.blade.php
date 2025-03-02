@extends('layouts.dashboard')

@section('title', 'Lịch sử Chatbot')

@section('content')
<div class="container d-flex flex-column" style="">
    <!-- Button to Clear Chat History -->
    <div class="p-3 d-flex align-items-center justify-content-between">
        <h2 class="m-0">Lịch sử Chatbot</h2>
        @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

        <form action="{{ route('chatbot.clear', $id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa lịch sử trò chuyện?');">
            @csrf
            @method('DELETE')
            @if(count($history) > 0)
            <button type="submit" class="material-button">Xóa lịch sử trò chuyện</button>
            @else
            
            @endif
        </form>
    </div>

    <div class="px-3 pb-3">
        @forelse($history as $message)
            @if($message->isBot)
                <!-- BOT MESSAGE (left) with HTML content -->
                <div class="d-flex justify-content-start align-items-end mb-3">
                    <!-- Bot Avatar on left -->
                    <div class="me-2">
                        <img src="{{ $chatbotAvatar }}" alt="Bot Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                    </div>
                    <!-- Message bubble -->
                    <div>
                        <div class="p-3 bg-light border rounded">
                            {{-- Render bot messages as HTML --}}
                            {!! $message->message !!}
                        </div>
                        <!-- Timestamp -->
                        <div class="small text-muted mt-1">
                            {{ \Carbon\Carbon::parse($message->time)->format('H:i:s - d/m/y') }}
                        </div>
                    </div>
                </div>
            @else
                <!-- USER MESSAGE (also left) with plain text content -->
                <div class="d-flex justify-content-start align-items-end mb-3">
                    <!-- User Avatar on left -->
                    <div class="me-2">
                        <img src="{{ $userAvatar }}" alt="User Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                    </div>
                    <!-- Message bubble -->
                    <div>
                        <div class="p-3 bg-primary text-white border rounded">
                            {{ $message->message }}
                        </div>
                        <!-- Timestamp -->
                        <div class="small text-muted mt-1">
                            {{ \Carbon\Carbon::parse($message->time)->format('H:i:s - d/m/y') }}
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-center text-muted">Không có lịch sử chat nào.</p>
        @endforelse
    </div>
</div>

<style>
.material-button {
    background-color: #00b3e5;
    margin: 5px;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Hover effect */
.material-button:hover {
    background-color: #3700b3;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

/* Focus effect */
.material-button:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(98, 0, 234, 0.5);
}

/* Active effect */
.material-button:active {
    background-color: #6200ea;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
@endsection
