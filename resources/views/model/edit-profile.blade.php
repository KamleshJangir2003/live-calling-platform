@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<h5 class="fw-bold mb-4">Edit Profile</h5>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Profile Information</h6>
            <form action="{{ route('model.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 text-center">
                    <img id="profilePreview" src="{{ $user->modelProfile->profile_photo_url }}" width="100" height="100" class="rounded-circle mb-2" style="object-fit:cover">
                    <div>
                        <label class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-camera me-1"></i>Change Photo
                            <input type="file" name="profile_photo" accept="image/*" class="d-none" onchange="document.getElementById('profilePreview').src=URL.createObjectURL(this.files[0])">
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Bio</label>
                    <textarea name="bio" class="form-control" rows="3" maxlength="500" placeholder="Tell users about yourself...">{{ old('bio', $user->modelProfile->bio) }}</textarea>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $user->modelProfile->country) }}" placeholder="e.g. India">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Languages (comma separated)</label>
                        <input type="text" name="languages" class="form-control" value="{{ old('languages', $user->modelProfile->languages) }}" placeholder="e.g. English, Hindi">
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-mic-fill text-primary me-1"></i>Audio Price (₹/min)</label>
                        <input type="number" name="audio_price" class="form-control" value="{{ old('audio_price', $user->modelProfile->audio_price) }}" min="0.5" max="100" step="0.5" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-camera-video-fill text-warning me-1"></i>Video Price (₹/min)</label>
                        <input type="number" name="video_price" class="form-control" value="{{ old('video_price', $user->modelProfile->video_price) }}" min="0.5" max="100" step="0.5" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="bi bi-check-circle me-2"></i>Save Changes
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">KYC Verification</h6>
            @php $kyc = $user->modelProfile->kyc_status; @endphp
            <div class="mb-3">
                <span class="badge fs-6
                    @if($kyc === 'approved') bg-success
                    @elseif($kyc === 'pending') bg-warning text-dark
                    @else bg-danger @endif">
                    @if($kyc === 'approved') ✓ Verified
                    @elseif($kyc === 'pending') ⏳ Pending Review
                    @else ✗ Rejected @endif
                </span>
            </div>
            @if($kyc !== 'approved')
                <p class="text-muted small">Upload a government-issued ID (Aadhaar, PAN, Passport) to get verified.</p>
                <form action="{{ route('model.kyc.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted">JPG, PNG or PDF. Max 5MB.</small>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold w-100">
                        <i class="bi bi-upload me-2"></i>Upload KYC Document
                    </button>
                </form>
            @else
                <p class="text-success small"><i class="bi bi-check-circle me-1"></i>Your identity has been verified.</p>
            @endif
        </div>
    </div>
</div>
@endsection
