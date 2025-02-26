@extends('layouts.dashboard')

@section('title', 'Profile Settings')

@section('content')
<div class="container">
  <h2 class="mb-3">Profile Settings</h2>
  <p class="text-muted">Update your personal information and password below.</p>

  <!-- Display validation errors if any -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row mb-4">
      <!-- Profile Picture Column -->
      <div class="col-md-4 text-center">
        <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('storage/images/default-profile.png') }}" 
             alt="Profile Picture" class="img-thumbnail mb-3" style="max-width:150px;">
        <div class="mb-3">
          <label for="profile_picture" class="form-label">Change Profile Picture</label>
          <input class="form-control" type="file" id="profile_picture" name="profile_picture">
        </div>
      </div>

      <!-- Profile Info Column -->
      <div class="col-md-8">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="name" name="name" 
                 value="{{ old('name', auth()->user()->name) }}" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" 
                 value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">New Password <small class="text-muted">(leave blank to keep current password)</small></label>
          <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm New Password</label>
          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
      </div>
    </div>
  </form>
</div>
@endsection
