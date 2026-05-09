@extends('layouts.app')
@section('title', 'Contact Us')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        border-radius: 20px;
        padding: 40px 32px;
        color: white;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,.1);
        border-radius: 50%;
    }
    .contact-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -20px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,.07);
        border-radius: 50%;
    }
    .info-card {
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        background: white;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transition: transform .2s;
    }
    .info-card:hover { transform: translateY(-3px); }
    .info-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 12px;
    }
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 24px rgba(0,0,0,.07);
    }
    .form-control, .form-select {
        border-radius: 12px;
        border: 1.5px solid #e8f5f0;
        padding: 10px 14px;
        font-size: .9rem;
        background: #f8fffe;
    }
    .form-control:focus, .form-select:focus {
        background: white;
        border-color: #00b894;
    }
    .subject-option {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px;
        border: 1.5px solid #e8f5f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s;
        font-size: .85rem;
        background: #f8fffe;
    }
    .subject-option:hover, .subject-option.selected {
        border-color: #00b894;
        background: #e8f8f5;
        color: #00a381;
    }
    .subject-option input { display: none; }
    .btn-send {
        background: linear-gradient(135deg, #00b894, #00cec9);
        border: none;
        border-radius: 14px;
        padding: 13px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: .3px;
        transition: opacity .2s, transform .2s;
    }
    .btn-send:hover { opacity: .9; transform: translateY(-1px); color: white; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">

        {{-- Hero --}}
        <div class="contact-hero">
            <div style="position:relative;z-index:1">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="background:rgba(255,255,255,.2);border-radius:14px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;font-size:1.5rem">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">How can we help?</h3>
                        <p class="mb-0" style="opacity:.85;font-size:.9rem">We typically respond within 24 hours</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="info-card">
                    <div class="info-icon" style="background:#e8f8f5;color:#00b894">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="fw-semibold small">Email</div>
                    <div class="text-muted" style="font-size:.75rem">support@livecall.com</div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-card">
                    <div class="info-icon" style="background:#fff3f8;color:#fd79a8">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="fw-semibold small">Response</div>
                    <div class="text-muted" style="font-size:.75rem">Within 24 hours</div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-card">
                    <div class="info-icon" style="background:#f0f4ff;color:#6c5ce7">
                        <i class="bi bi-globe2"></i>
                    </div>
                    <div class="fw-semibold small">Available</div>
                    <div class="text-muted" style="font-size:.75rem">Worldwide</div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="form-card">
            <h5 class="fw-bold mb-1">Send us a message</h5>
            <p class="text-muted small mb-4">Fill in the details below and our team will get back to you.</p>

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small mb-1">Your Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius:12px 0 0 12px;border:1.5px solid #e8f5f0">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="name"
                                class="form-control border-start-0 @error('name') is-invalid @enderror"
                                style="border-radius:0 12px 12px 0"
                                value="{{ old('name', auth()->user()->name ?? '') }}"
                                placeholder="Enter your name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small mb-1">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius:12px 0 0 12px;border:1.5px solid #e8f5f0">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            <input type="email" name="email"
                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                style="border-radius:0 12px 12px 0"
                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                placeholder="Enter your email" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Subject Chips --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold small mb-2">Subject</label>
                    <div class="row g-2">
                        @foreach([
                            ['Payment Issue', 'bi-credit-card'],
                            ['Call Problem', 'bi-telephone-x'],
                            ['Account Issue', 'bi-person-x'],
                            ['Report a User', 'bi-flag'],
                            ['Withdrawal Issue', 'bi-cash-stack'],
                            ['Other', 'bi-three-dots'],
                        ] as [$label, $icon])
                        <div class="col-6 col-md-4">
                            <label class="subject-option w-100 {{ old('subject') === $label ? 'selected' : '' }}">
                                <input type="radio" name="subject" value="{{ $label }}" {{ old('subject') === $label ? 'checked' : '' }} required>
                                <i class="bi {{ $icon }}"></i>
                                <span>{{ $label }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('subject')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small mb-1">Message</label>
                    <textarea name="message" rows="4"
                        class="form-control @error('message') is-invalid @enderror"
                        placeholder="Describe your issue in detail..." required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-send text-white w-100">
                    <i class="bi bi-send-fill me-2"></i>Send Message
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.subject-option').forEach(el => {
    el.addEventListener('click', () => {
        document.querySelectorAll('.subject-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
    });
});
</script>
@endpush
