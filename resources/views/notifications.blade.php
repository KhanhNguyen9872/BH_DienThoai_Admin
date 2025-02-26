@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')
  <h2>Notifications</h2>
  <p class="text-muted">Review your notifications below.</p>
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Text</th>
          <th scope="col">Time</th>
        </tr>
      </thead>
      <tbody>
        @forelse($notifications as $notification)
          <tr>
            <td>{{ $notification->id }}</td>
            <td>{{ $notification->text }}</td>
            <td>{{ \Carbon\Carbon::parse($notification->time)->diffForHumans() }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="text-center">No notifications found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@section('scripts')
  <!-- Page-specific JavaScript can be added here if needed -->
@endsection
