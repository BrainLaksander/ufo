{{--
    Komponen Event Card untuk menampilkan informasi event
    
    Props:
    - $event: Event model/object
    - $showActions: Show action buttons? (default: false)
    - $compact: Compact layout? (default: false)
--}}

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="flex-grow-1">
                <h6 class="card-title mb-1 fw-bold">{{ $event->title }}</h6>
                <p class="text-muted small mb-2">
                    <i class="bi bi-calendar3"></i> 
                    {{ $event->event_date->format('d M Y, H:i') }}
                </p>
            </div>
            <span class="badge" style="background-color: {{ $event->getCategoryColor() }}; color: white;">
                {{ $event->getCategoryLabel() }}
            </span>
        </div>

        @if(!($compact ?? false))
            <p class="card-text small text-secondary mb-3">
                {{ Str::limit($event->description, 100) }}
            </p>

            <div class="d-flex justify-content-between align-items-center small text-muted">
                <span>
                    <i class="bi bi-geo-alt"></i> {{ $event->location }}
                </span>
                <span>
                    <i class="bi bi-people"></i> 
                    {{ $event->getAvailableSlots() }} / {{ $event->capacity }}
                </span>
            </div>
        @endif

        @if($showActions ?? false)
            <hr class="my-2">
            <div class="d-flex gap-2 justify-content-between">
                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-soft-primary">
                    <i class="bi bi-eye"></i> Lihat
                </a>
                @if(auth()->user()->can('update', $event))
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
