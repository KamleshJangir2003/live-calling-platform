@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Monthly Revenue</h6>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Top Earning Models</h6>
            @foreach($topModels as $model)
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ $model->avatar_url }}" width="36" height="36" class="rounded-circle" style="object-fit:cover">
                    <div class="flex-grow-1">
                        <div class="small fw-bold">{{ $model->name }}</div>
                        <div class="text-success small fw-bold">₹{{ number_format($model->modelProfile->total_earnings ?? 0, 2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyRevenue->map(fn($r) => date('M Y', mktime(0,0,0,$r->month,1,$r->year)))->toArray()) !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! json_encode($monthlyRevenue->pluck('total')->toArray()) !!},
                backgroundColor: 'rgba(0,184,148,0.7)',
                borderColor: '#00b894',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
