@extends('layouts.app')
@section('title', 'Help Center')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card p-4 mb-4">
            <h4 class="fw-bold mb-1"><i class="bi bi-question-circle-fill text-primary me-2"></i>Help Center</h4>
            <p class="text-muted small mb-4">Find answers to common questions about LiveCall.</p>

            <div class="accordion" id="helpAccordion">
                <div class="accordion-item border-0 mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#h1">
                            How do I start a call with a model?
                        </button>
                    </h2>
                    <div id="h1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted small">
                            Browse models on the home page, click the 🎙 audio or 📹 video button on any model card. Make sure you have sufficient wallet balance before initiating a call.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#h2">
                            How do I recharge my wallet?
                        </button>
                    </h2>
                    <div id="h2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted small">
                            Click the wallet icon in the top navbar or go to Wallet page. Choose a quick amount or enter a custom amount and pay securely via Razorpay using UPI, cards, or net banking.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#h3">
                            How is call billing calculated?
                        </button>
                    </h2>
                    <div id="h3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted small">
                            Calls are billed per minute based on the model's rate. The amount is deducted from your wallet in real-time. The call ends automatically if your balance runs out.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#h4">
                            How do I become a model on LiveCall?
                        </button>
                    </h2>
                    <div id="h4" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted small">
                            Register an account and select "Model" as your role. Complete your profile, upload KYC documents, and wait for admin approval. Once approved, you can go online and receive calls.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#h5">
                            How do I withdraw my earnings?
                        </button>
                    </h2>
                    <div id="h5" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted small">
                            Go to your Model Dashboard → Earnings and submit a withdrawal request. Withdrawals are processed within 3–5 business days to your registered bank account.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#h6">
                            What if I face a technical issue during a call?
                        </button>
                    </h2>
                    <div id="h6" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted small">
                            If a call drops due to a technical issue on our end, the unused balance will be refunded to your wallet. You can also contact our support team via the Contact Us page.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 rounded-3 text-center" style="background:#e8f8f5">
                <p class="mb-2 fw-semibold">Still need help?</p>
                <a href="{{ route('contact') }}" class="btn btn-primary btn-sm px-4" style="border-radius:25px">
                    <i class="bi bi-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
