@extends('layouts.app')
@section('title', 'Privacy Policy')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1"><i class="bi bi-file-earmark-lock-fill text-primary me-2"></i>Privacy Policy</h4>
            <p class="text-muted small mb-4">Last updated: January 1, 2026</p>

            @foreach([
                ['1. Information We Collect', 'We collect information you provide when registering (name, email, phone number), profile information, payment transaction data, call history and duration, and device/browser information for security purposes.'],
                ['2. How We Use Your Information', 'Your information is used to provide and improve our services, process payments securely, verify identity (KYC for models), send important account notifications, and prevent fraud and ensure platform safety.'],
                ['3. Payment Information', 'All payments are processed securely through Razorpay. LiveCall does not store your card or bank account details. Transaction records are maintained for billing and dispute resolution purposes.'],
                ['4. Call Privacy', 'Audio and video calls are encrypted using Agora\'s secure infrastructure. LiveCall does not record calls. Call metadata (duration, cost) is stored for billing purposes only.'],
                ['5. Data Sharing', 'We do not sell your personal data to third parties. We may share data with payment processors (Razorpay), communication providers (Agora, Pusher), and law enforcement when legally required.'],
                ['6. Data Retention', 'Account data is retained as long as your account is active. Transaction records are kept for 7 years as required by law. You may request deletion of your account and associated data at any time.'],
                ['7. Cookies', 'We use essential cookies for session management and security. No third-party tracking cookies are used without your consent.'],
                ['8. Your Rights', 'You have the right to access, correct, or delete your personal data. To exercise these rights, contact us at support@livecall.com.'],
                ['9. Changes to This Policy', 'We may update this policy from time to time. Significant changes will be notified via email or in-app notification.'],
                ['10. Contact', 'For privacy-related queries, contact us at support@livecall.com.'],
            ] as [$title, $body])
            <div class="mb-4">
                <h6 class="fw-bold text-dark">{{ $title }}</h6>
                <p class="text-muted small mb-0">{{ $body }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
