@extends('layouts.app')

@section('content')
    {{ dd($internalreceipt, request()) }}
    <table class="table" border="1">
        <tbody>
        <tr>
            <td><strong>Eigenbeleg</strong></td>
            <td>Nr. {{ date('Y', strtotime($internalreceipt->expenditure_date)) . '-' . str_pad($internalreceipt->serial_no, 3, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td colspan="2">Betrag</td>
        </tr>
        <tr>
            <td colspan="2">Empfänger</td>
        </tr>
        <tr>
            <td colspan="2">Verwendungszweck</td>
        </tr>
        <tr>
            <td colspan="2">Grund für Eigenbeleg</td>
        </tr>
        <tr>
            <td>Ort, Datum</td>
            <td>Unterschrift</td>
        </tr>
        <tr>

        </tr>
        </tbody>
    </table>
@endsection