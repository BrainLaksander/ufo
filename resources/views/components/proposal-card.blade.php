{{--
    Komponen Proposal Card untuk menampilkan proposal dengan detail
    
    Props:
    - $proposal: Proposal model/object
    - $showActions: Show action buttons? (default: false)
    - $compact: Compact layout? (default: false)
--}}

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="card-title mb-1 fw-bold">{{ $proposal->title }}</h6>
                <p class="text-muted small mb-2">
                    <i class="bi bi-building"></i> {{ $proposal->organization->name }}
                </p>

                @if(!($compact ?? false))
                    <p class="card-text small text-secondary mb-3">
                        {{ Str::limit($proposal->description, 150) }}
                    </p>

                    <div class="d-flex gap-3 small text-muted">
                        @if($proposal->budget)
                            <span><i class="bi bi-cash-coin"></i> Rp {{ number_format($proposal->budget, 0, ',', '.') }}</span>
                        @endif
                        @if($proposal->timeline)
                            <span><i class="bi bi-calendar"></i> {{ $proposal->timeline->format('d M Y') }}</span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="col-md-4 text-end">
                <div class="mb-2">
                    <span class="badge" style="background-color: #FFC107; color: black;">
                        {{ ucfirst($proposal->type) }}
                    </span>
                </div>
                @include('components.status-badge', ['status' => $proposal->status])
            </div>
        </div>

        @if($showActions ?? false)
            <hr class="my-2">
            <div class="d-flex gap-2 justify-content-between">
                <div>
                    <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-soft-primary">
                        <i class="bi bi-eye"></i> Lihat
                    </a>
                    @if(auth()->user()->can('update', $proposal) && $proposal->status === 'draft')
                        <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endif
                </div>

                @if(auth()->user()->can('update', $proposal) && $proposal->status === 'draft')
                    <form action="{{ route('proposals.submit', $proposal) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-send"></i> Submit untuk Review
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>
