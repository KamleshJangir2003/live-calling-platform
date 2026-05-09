@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<h5 class="fw-bold mb-4"><i class="bi bi-heart-fill text-danger me-2"></i>My Favorites</h5>

@if($favorites->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-heart fs-1 text-muted"></i>
        <p class="text-muted mt-2">No favorites yet. Browse models and add them!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Browse Models</a>
    </div>
@else
    <div class="row g-3">
        @foreach($favorites as $fav)
            @php $model = $fav->model; @endphp
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card model-card h-100">
                    <div class="position-relative">
                        <img src="{{ $model->modelProfile->profile_photo_url }}" class="card-img-top" style="height:180px;object-fit:cover" alt="{{ $model->name }}">
                        <span class="position-absolute top-0 end-0 m-2 {{ $model->modelProfile->online_status ? 'badge-online' : 'badge-offline' }}">
                            {{ $model->modelProfile->online_status ? '● Online' : '○ Offline' }}
                        </span>
                    </div>
                    <div class="card-body p-2">
                        <h6 class="fw-bold mb-1 text-truncate">{{ $model->name }}</h6>
                        <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $model->modelProfile->country ?? 'Global' }}</small>
                        <div class="d-flex gap-1">
                            <a href="{{ route('model.profile', $model->id) }}" class="btn btn-sm btn-outline-primary flex-fill">View</a>
                            <a href="{{ route('chat.conversation', $model->id) }}" class="btn btn-sm btn-primary flex-fill">
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $favorites->links() }}</div>
@endif
@endsection
