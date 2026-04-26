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
                    <span class="badge ufo-badge-accent">
                        {{ ucfirst($proposal->type) }}
                    </span>
                </div>
                <x-shared.status-badge :status="$proposal->status" />
            </div>
        </div>

        @if($showActions ?? false)
            <hr class="my-2">
            <div class="d-flex gap-2 justify-content-between">
                <div>
                    <x-shared.action-button
                        :link="route('proposals.show', $proposal)"
                        label="Lihat"
                        style="soft-primary"
                        size="sm"
                        icon="bi bi-eye"
                    />
                    @if(auth()->user()->can('update', $proposal) && $proposal->status === 'draft')
                        <x-shared.action-button
                            :link="route('proposals.edit', $proposal)"
                            label="Edit"
                            style="primary"
                            size="sm"
                            icon="bi bi-pencil"
                        />
                    @endif
                </div>

                @if(auth()->user()->can('update', $proposal) && $proposal->status === 'draft')
                    <x-shared.action-button
                        :link="route('proposals.submit', $proposal)"
                        method="POST"
                        label="Submit untuk Review"
                        style="success"
                        size="sm"
                        icon="bi bi-send"
                    />
                @endif
            </div>
        @endif
    </div>
</div>
