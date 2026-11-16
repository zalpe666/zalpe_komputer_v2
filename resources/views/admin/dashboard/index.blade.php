@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid px-0">

        <!-- Header & Greeting -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--text-dark);">Welcome back, {{ auth()->user()->name }}</h3>
                <p class="text-muted mb-0">Here's what's happening with your store today.</p>
            </div>
            <div class="text-end d-none d-md-block">
                <p class="mb-0 fw-medium text-muted">
                    <i class="bi bi-calendar3 me-1"></i> {{ date('l, d F Y') }}
                </p>
            </div>
        </div>

        <!-- 1. STATISTIC CARDS -->
        <div class="row g-4 mb-4">
            <!-- Total Revenue -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 fw-medium" style="font-size: 0.9rem;">Total Revenue</p>
                            <h4 class="fw-bold mb-0">Rp 12.500.000</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-cart-check fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 fw-medium" style="font-size: 0.9rem;">Total Orders</p>
                            <h4 class="fw-bold mb-0">342</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-box-seam fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 fw-medium" style="font-size: 0.9rem;">Active Products</p>
                            <h4 class="fw-bold mb-0">128</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks/Transactions -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-hourglass-split fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 fw-medium" style="font-size: 0.9rem;">Pending Orders</p>
                            <h4 class="fw-bold mb-0">15</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. CHARTS & RECENT ACTIVITY -->
        <div class="row g-4 mb-4">

            <!-- Left: Chart Placeholder -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div
                        class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Revenue Analytics</h5>
                        <select class="form-select form-select-sm w-auto rounded-3 shadow-none border-light bg-light">
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="this_year">This Year</option>
                        </select>
                    </div>
                    <div class="card-body p-4 d-flex align-items-center justify-content-center" style="min-height: 300px;">
                        <!-- Ini adalah area statis untuk chart -->
                        <div class="text-center text-muted">
                            <i class="bi bi-bar-chart-line" style="font-size: 60px; color: #e0e0e0;"></i>
                            <p class="mt-2 mb-0 fw-medium">Chart Area</p>
                            <small>(Bisa diintegrasikan dengan Chart.js / ApexCharts nantinya)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Top Selling Products -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0">Top Selling</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded p-2 text-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-controller fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Valorant Points</h6>
                                    <small class="text-muted">Games</small>
                                </div>
                            </div>
                            <span class="fw-bold text-success">+85 Orders</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded p-2 text-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-steam fs-4 text-dark"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Steam Wallet IDR 100k</h6>
                                    <small class="text-muted">Steam Wallet</small>
                                </div>
                            </div>
                            <span class="fw-bold text-success">+62 Orders</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded p-2 text-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-phone fs-4 text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Pulsa Telkomsel 50k</h6>
                                    <small class="text-muted">Digital</small>
                                </div>
                            </div>
                            <span class="fw-bold text-success">+40 Orders</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. RECENT TRANSACTIONS TABLE -->
        <div class="card border-0 shadow-sm rounded-4">
            <div
                class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Transactions</h5>
                <a href="{{ route('admin.transaction.index') ?? '#' }}"
                    class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">
                    View All
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light" style="font-size: 0.85rem; text-transform: uppercase;">
                            <tr>
                                <th class="text-secondary fw-semibold py-3 rounded-start-3">Order ID</th>
                                <th class="text-secondary fw-semibold py-3">Customer</th>
                                <th class="text-secondary fw-semibold py-3">Product</th>
                                <th class="text-secondary fw-semibold py-3">Amount</th>
                                <th class="text-secondary fw-semibold py-3">Status</th>
                                <th class="text-secondary fw-semibold py-3 rounded-end-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <!-- Static Row 1 -->
                            <tr>
                                <td class="fw-bold text-dark">#TRX-88210</td>
                                <td class="text-muted">Budi Santoso</td>
                                <td>Mobile Legends 86 Diamonds</td>
                                <td class="fw-medium">Rp 25.000</td>
                                <td><span
                                        class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Success</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-info rounded-3"><i
                                            class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            <!-- Static Row 2 -->
                            <tr>
                                <td class="fw-bold text-dark">#TRX-88209</td>
                                <td class="text-muted">Andi Wijaya</td>
                                <td>Steam Wallet IDR 50.000</td>
                                <td class="fw-medium">Rp 55.000</td>
                                <td><span
                                        class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Pending</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-info rounded-3"><i
                                            class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            <!-- Static Row 3 -->
                            <tr>
                                <td class="fw-bold text-dark">#TRX-88208</td>
                                <td class="text-muted">Siti Aminah</td>
                                <td>Netflix Premium 1 Bulan</td>
                                <td class="fw-medium">Rp 45.000</td>
                                <td><span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Failed</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-info rounded-3"><i
                                            class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
