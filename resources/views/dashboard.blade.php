@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
  <h2>Dashboard</h2>
  <p class="text-muted">Welcome to the PhoneStore Admin Dashboard!</p>
  <div class="row">
    <!-- Example Card for Total Products -->
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card text-white bg-primary h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h6 class="card-title">Total Products</h6>
            <h3 class="card-text">1,248</h3>
          </div>
          <i class="bi-phone fs-1 opacity-25"></i>
        </div>
      </div>
    </div>
    <!-- Add more cards as needed... -->
  </div>
@endsection

@section('scripts')
  <!-- Page-specific JavaScript if needed -->
@endsection
