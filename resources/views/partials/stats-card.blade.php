<div class="col-md-6 col-lg-3 mb-4">
    <div class="card stats-card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">{{ $title }}</h6>
                    <h2 class="stats-number">{{ $value }}</h2>
                </div>
                <div class="stats-icon bg-{{ $color }}-subtle">
                    <i class="bi bi-{{ $icon }} text-{{ $color }}"></i>
                </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
                <div class="progress-bar bg-{{ $color }}" style="width: {{ $progress }}%"></div>
            </div>
            <p class="stats-text mt-2">
                <i class="bi bi-arrow-{{ $trend }}-short text-{{ $trend == 'up' ? 'success' : 'warning' }}"></i>
                {{ $trendValue }}
            </p>
        </div>
    </div>
</div>
