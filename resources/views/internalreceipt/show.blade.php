@extends('layouts.app')

@section('content')
    {{-- dd($internalreceipt, request()) --}}
    <table class="table" border="1" style="font-family: Times New Roman, serif">
        <tbody>
        <tr>
            <td><strong>Eigenbeleg</strong></td>
            <td>Nr. {{ date('Y', strtotime($internalreceipt->expenditure_date)) . '-' . str_pad($internalreceipt->serial_no, 3, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Betrag (EURO, Cent):</td>
            <td>{{ $internalreceipt->expenditure_costs }}</td>
        </tr>
        <tr>
            <td width="33.3%">Empfänger:</td>
            <td>
                {{ $internalreceipt->creditor_name }}<br>
                @empty($internalreceipt->creditor_address_1)
                @else
                    {{ $internalreceipt->creditor_address_1 }}<br>
                @endempty
                {{ $internalreceipt->creditor_address_2 }}<br>
                {{ $internalreceipt->creditor_place }}
            <!-- todo: same as in index, might be better component? -->
            </td>
        </tr>
        <tr>
            <td>Verwendungszweck:</td>
            <td>{{ $internalreceipt->expenditure_type }}</td>
        </tr>
        <tr>
            <td>Grund für Eigenbeleg:</td>
            <td>{{ $internalreceipt->reason }}</td>
        </tr>
        <tr>
            <td>Ort, Datum:<br><br>
                Altleiningen, {{ $internalreceipt->expenditure_date }}</td>
            <td>Unterschrift:<br><br></td>
        </tr>
        <tr>

        </tr>
        </tbody>
    </table>
@endsection