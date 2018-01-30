@if(is_null($internalreceipt->unsigned_document))
    @component('components.internalreceipt', [
        'internalreceipt' => $internalreceipt,
        'address' => $address
    ])
    @endcomponent
    {{--redirect()->back()--}}
@else
    @php
        header("Content-type:application/pdf");
        echo Storage::cloud()->get($internalreceipt->unsigned_document);
    exit;
    @endphp
    {{-- unsigned document was already created, return pdf from DO spaces --}}
@endif