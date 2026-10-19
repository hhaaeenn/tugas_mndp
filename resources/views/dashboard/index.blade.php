@extends('layouts.main')

@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <i class="bi bi-box-seam-fill fs-2 text-primary mb-2"></i>
            <h6 class="text-muted">Total Inventories</h6>
            <h2 class="fw-bold">{{ $inventories_count }}</h2>
            <a href="{{ route('inventories.index') }}" class="btn btn-outline-primary btn-sm mt-2">Lihat Inventories</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <i class="bi bi-cart-dash-fill fs-2 text-success mb-2"></i>
            <h6 class="text-muted">Total Sales</h6>
            <h2 class="fw-bold">{{ $sales_count }}</h2>
            <a href="{{ route('sales.index') }}" class="btn btn-outline-success btn-sm mt-2">Lihat Sales</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <i class="bi bi-cart-plus-fill fs-2 text-warning mb-2"></i>
            <h6 class="text-muted">Total Purchases</h6>
            <h2 class="fw-bold">{{ $purchases_count }}</h2>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-warning btn-sm mt-2">Lihat Purchases</a>
        </div>
    </div>
</div>

<div class="card p-3">
    <h5 class="fw-bold"><i class="bi bi-info-circle text-primary me-2"></i>System Info</h5>
    <hr>
    <table class="table table-sm table-borderless">
        <tr><td class="text-muted">Environment</td><td><span class="badge bg-secondary">{{ app()->environment() }}</span></td></tr>
        <tr><td class="text-muted">Database</td><td><span class="badge bg-success">{{ config('database.default') }}</span></td></tr>
        <tr><td class="text-muted">PHP Version</td><td><span class="badge bg-info text-dark">{{ phpversion() }}</span></td></tr>
        <tr><td class="text-muted">Laravel Version</td><td><span class="badge bg-danger">{{ app()->version() }}</span></td></tr>
    </table>
</div>
@endsection
