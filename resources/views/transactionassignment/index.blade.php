@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}" type="text/css"/>
@endpush

@section('content')
    <transaction-assignment>

    </transaction-assignment>
@endsection

@push('foot')
    <script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
@endpush