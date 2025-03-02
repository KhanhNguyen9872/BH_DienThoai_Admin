@extends('layouts.dashboard')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Title -->
    <div class="d-flex align-items-center mb-4">
        <h2 class="mb-0">Analytics</h2>
    </div>

    <!-- Cards Row: Basic Metrics -->
    <div class="row">
        <!-- Example Metric 1 -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text fs-4 fw-bold">1,234</p>
                </div>
            </div>
        </div>
        <!-- Example Metric 2 -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-4 fw-bold">567</p>
                </div>
            </div>
        </div>
        <!-- Example Metric 3 -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Revenue (USD)</h5>
                    <p class="card-text fs-4 fw-bold">$12,345</p>
                </div>
            </div>
        </div>
        <!-- Example Metric 4 -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Conversion Rate</h5>
                    <p class="card-text fs-4 fw-bold">4.5%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Row: Charts -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Monthly Orders</h5>
                    <!-- Placeholder canvas for chart.js or another chart library -->
                    <canvas id="ordersChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">User Growth</h5>
                    <canvas id="usersChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Example Table of Recent Orders (optional) -->
    <div class="row mt-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>#1001</td>
                                <td>Alice</td>
                                <td>$99.99</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>2023-01-01</td>
                            </tr>
                            <tr>
                                <td>#1002</td>
                                <td>Bob</td>
                                <td>$45.00</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>2023-01-02</td>
                            </tr>
                            <tr>
                                <td>#1003</td>
                                <td>Charlie</td>
                                <td>$120.50</td>
                                <td><span class="badge bg-danger">Failed</span></td>
                                <td>2023-01-03</td>
                            </tr>
                            <!-- Add more rows or dynamically load them -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- If you want to include chart logic, for instance using Chart.js, you can do so here. Example: --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Example line chart for monthly orders
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'Orders',
                data: [100, 120, 90, 150, 200, 170],
                // you can style the chart as desired
            }]
        },
    });

    // Example bar chart for user growth
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'New Users',
                data: [10, 20, 15, 25, 30, 40],
            }]
        },
    });
});
</script>
@endsection
