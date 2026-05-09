@extends('layouts.app')
@section('title', 'Terms of Service')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Terms of Service</h4>
            <p class="text-muted small mb-4">Last updated: January 1, 2026. By using LiveCall, you agree to these terms.</p>

            @foreach([
                ['1. Eligibility', 'You must be at least 18 years of age to use LiveCall. By registering, you confirm that you meet this requirement. Accounts found to belong to minors will be immediately terminated.'],
                ['2. Account Responsibility', 'You are responsible for maintaining the confidentiality of your account credentials. You are liable for all activities that occur under your account. Notify us immediately of any unauthorized access.'],
                ['3. Wallet & Payments', 'Wallet balance is non-transferable between accounts. Minimum recharge is ₹10. Wallet balance has no expiry. Payments are processed securely via Razorpay. LiveCall is not responsible for payment failures caused by your bank or payment provider.'],
                ['4. Call Services', 'Calls are billed per minute at the model\'s listed rate. Calls end automatically when wallet balance is insufficient. LiveCall is not responsible for call quality issues caused by your internet connection.'],
                ['5. Model Guidelines', 'Models must complete KYC verification before going live. Models must not engage in illegal activities during calls. Models are independent contractors and not employees of LiveCall. LiveCall takes a platform commission on all earnings.'],
                ['6. Prohibited Conduct', 'The following are strictly prohibited: sharing personal contact information to bypass the platform, harassment or abusive behavior, impersonation of other users, any illegal activity, and attempting to circumvent billing systems.'],
                ['7. Content Standards', 'All interactions must comply with applicable laws. Explicit content is only permitted where legally allowed and both parties are verified adults. LiveCall reserves the right to remove content and suspend accounts that violate these standards.'],
                ['8. Termination', 'LiveCall reserves the right to suspend or terminate accounts that violate these terms without prior notice. Remaining wallet balance may be refunded at our discretion upon account termination.'],
                ['9. Limitation of Liability', 'LiveCall is not liable for any indirect, incidental, or consequential damages arising from use of the platform. Our total liability is limited to the amount you paid in the last 30 days.'],
                ['10. Governing Law', 'These terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of courts in India.'],
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
