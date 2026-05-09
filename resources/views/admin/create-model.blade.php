@extends('layouts.admin')

@section('title', 'Add New Model')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Add New Model</h6>
    <a href="{{ route('admin.models') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
</div>

<div class="card p-4" style="max-width:600px">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.models.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone *</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Audio Price (₹/min) *</label>
                <input type="number" name="audio_price" class="form-control" value="{{ old('audio_price', 10) }}" min="0" step="0.01" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Video Price (₹/min) *</label>
                <input type="number" name="video_price" class="form-control" value="{{ old('video_price', 20) }}" min="0" step="0.01" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="{{ old('country') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Profile Photo</label>
                <input type="file" name="profile_photo" class="form-control" accept="image/*" onchange="previewPhoto(this)">
                <img id="photoPreview" src="" class="mt-2 rounded-circle d-none" width="80" height="80" style="object-fit:cover">
            </div>
            <div class="col-12">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control" rows="3">{{ old('bio') }}</textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create Model</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    }
}
</script>
@endpush
