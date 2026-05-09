@extends('layouts.app')
@section('title', 'Safety')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1"><i class="bi bi-shield-check-fill text-success me-2"></i>Safety</h4>
            <p class="text-muted small mb-4">Your safety is our top priority. Here's how we keep LiveCall safe for everyone.</p>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#e8f8f5">
                        <div class="fw-bold mb-1"><i class="bi bi-person-check-fill text-primary me-2"></i>Verified Models</div>
                        <p class="text-muted small mb-0">All models go through KYC verification before being approved on the platform.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#e8f8f5">
                        <div class="fw-bold mb-1"><i class="bi bi-lock-fill text-primary me-2"></i>Encrypted Calls</div>
                        <p class="text-muted small mb-0">All audio and video calls are encrypted end-to-end using Agora's secure infrastructure.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#e8f8f5">
                        <div class="fw-bold mb-1"><i class="bi bi-eye-slash-fill text-primary me-2"></i>Privacy Protected</div>
                        <p class="text-muted small mb-0">Your personal information is never shared with models or third parties without consent.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 h-100" style="background:#e8f8f5">
                        <div class="fw-bold mb-1"><i class="bi bi-flag-fill text-primary me-2"></i>Report & Block</div>
                        <p class="text-muted small mb-0">You can report or block any user at any time. Our team reviews all reports within 24 hours.</p>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Safety Guidelines</h6>
            <ul class="list-unstyled">
                @foreach([
                    'Never share your personal contact details (phone, address, social media) during calls.',
                    'Do not make payments outside the LiveCall platform.',
                    'Report any inappropriate behavior immediately using the report button.',
                    'Do not share financial information like bank account or card details.',
                    'Minors (under 18) are strictly prohibited from using this platform.',
                    'All calls are subject to our community guidelines. Violations may result in account suspension.',
                ] as $tip)
                <li class="d-flex gap-2 mb-2">
                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                    <span class="text-muted small">{{ $tip }}</span>
                </li>
                @endforeach
            </ul>

            <div class="mt-3 p-3 rounded-3" style="background:#fff3cd">
                <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Report an Issue</div>
                <p class="text-muted small mb-2">If you witness or experience any safety concern, please contact us immediately.</p>
                <a href="{{ route('contact') }}" class="btn btn-warning btn-sm px-4" style="border-radius:25px">
                    Report Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
