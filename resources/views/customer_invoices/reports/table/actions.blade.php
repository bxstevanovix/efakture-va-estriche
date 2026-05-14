@if($entity->status == 0)
<div class="btn-group">
    <i class="fa fa-times text-danger" aria-hidden="true"></i>
</div>
@else
    @if($entity->date_done)
    {{-- <div class="btn-group">
        <button 
                type="button"
                class="btn btn-light"
                disabled
            >
            {{ \Carbon\Carbon::parse($entity->date_done)->format('d-m-Y') }}
            </button>
    </div> --}}
    @endif
    <i class="fa fa-check-circle text-success" aria-hidden="true"></i>
@endif
