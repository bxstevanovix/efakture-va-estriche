@if($entity->status == 0)
<div class="btn-group">
    <button 
        id="openModalBtn" 
        type="button"
        class="btn btn-success invoice-paid-btn"
        data-id="{{$entity->id}}"
        data-id-invoice="{{$entity->id_invoice}}"
        data-price="{{$entity->price}}"
        data-debt="{{$entity->debt}}"
        data-price-part="{{$entity->price_part}}"
        data-currency="{{$entity->currency}}"
        > 
        @lang('Potvrdi placanje')
    </button>
</div>
@else
    @if($entity->date_done)
    <div class="btn-group">
        <button 
                type="button"
                class="btn btn-light invoice-paid-btn"
                disabled
            >
            @lang('Placeno'): {{ \Carbon\Carbon::parse($entity->date_done)->format('d-m-Y') }}
            </button>
    </div>
    @endif
@endif