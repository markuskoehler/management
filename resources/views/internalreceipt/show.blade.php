@extends('layouts.app')

@section('content')
    @component('components.internalreceipt', [
        'internalreceipt' => $internalreceipt,
        'address' => $address,
        'print' => 1
    ])
    @endcomponent
@endsection