@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h6 class="fw-bold mb-4">Platform Settings</h6>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Commission Rate (%)</label>
                    <div class="input-group">
                        <input type="number" name="commission_rate" class="form-control" value="{{ $settings['commission_rate'] }}" min="0" max="100" step="0.5" required>
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="text-muted">Platform commission deducted from model earnings per call.</small>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Minimum Withdrawal Amount (₹)</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="min_withdrawal" class="form-control" value="{{ $settings['min_withdrawal'] }}" min="0" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Chat Message Price (₹)</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="chat_price" class="form-control" value="{{ $settings['chat_price'] }}" min="0" step="0.5" required>
                    </div>
                    <small class="text-muted">Amount deducted per chat message from user wallet.</small>
                </div>
                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="bi bi-check-circle me-2"></i>Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
