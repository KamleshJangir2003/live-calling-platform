@extends('layouts.app')
@section('title', 'Refund Policy')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1"><i class="bi bi-arrow-counterclockwise text-primary me-2"></i>Refund Policy</h4>
            <p class="text-muted small mb-4">Last updated: January 1, 2026</p>

            <div class="alert alert-info small mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                Wallet recharges are generally non-refundable. Refunds are only issued in specific circumstances listed below.
            </div>

            @foreach([
                ['Eligible Refund Cases', [
                    'Call dropped within the first 30 seconds due to a technical error on our platform.',
                    'Duplicate payment charged for the same transaction.',
                    'Payment deducted but wallet not credited due to a payment gateway error.',
                    'Account terminated by LiveCall without a policy violation by the user.',
                ]],
                ['Non-Refundable Cases', [
                    'Wallet balance used for completed calls.',
                    'Calls ended voluntarily by the user.',
                    'Refund requests made after 7 days of the transaction.',
                    'Accounts suspended due to policy violations.',
                    'Dissatisfaction with a model\'s performance or behavior.',
                ]],
            ] as [$heading, $items])
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">{{ $heading }}</h6>
                <ul class="list-unstyled">
                    @foreach($items as $item)
                    <li class="d-flex gap-2 mb-2">
                        <i class="bi bi-{{ str_contains($heading, 'Eligible') ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} mt-1 flex-shrink-0"></i>
                        <span class="text-muted small">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach

            <div class="mb-4">
                <h6 class="fw-bold text-dark">Refund Process</h6>
                <ol class="text-muted small ps-3">
                    <li class="mb-1">Submit a refund request via the <a href="{{ route('contact') }}">Contact Us</a> page within 7 days of the transaction.</li>
                    <li class="mb-1">Include your registered email, transaction ID, and reason for the refund.</li>
                    <li class="mb-1">Our team will review your request within 3 business days.</li>
                    <li class="mb-1">Approved refunds are credited back to your original payment method within 5–7 business days.</li>
                </ol>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold text-dark">Wallet Balance Refunds</h6>
                <p class="text-muted small mb-0">Unused wallet balance refunds (to original payment method) are available only upon account closure requests. Processing takes 7–10 business days. A minimum balance of ₹50 is required for wallet refund requests.</p>
            </div>

            <div class="p-3 rounded-3 text-center" style="background:#e8f8f5">
                <p class="mb-2 fw-semibold small">Have a refund request?</p>
                <a href="{{ route('contact') }}" class="btn btn-primary btn-sm px-4" style="border-radius:25px">
                    <i class="bi bi-envelope me-2"></i>Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
