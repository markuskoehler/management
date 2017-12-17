@extends('layouts.app')

@section('content')
    Create

    <table class="table">
        <thead>
        <tr>
            {{--<th>ID</th>--}}
            <th width="100px">Receipt No.<br>Date of <abbr title="Expenditure">Exp.</abbr></th>
            <th width="200px">Creditor &amp; Address</th>
            <th>Type of Expenditure</th>
            <th width="100px">Costs</th>
            <th width="190px">Reason</th>
            <th width="130px">Actions</th>
        </tr>
        </thead>
        <tbody>
    @foreach($internalreceipts as $internalreceipt)
        <tr>
            {{--<td>{{ $internalreceipt->id }}</td>--}}
            <td>{{ date('Y', strtotime($internalreceipt->expenditure_date)) . '-' . str_pad($internalreceipt->serial_no, 3, '0', STR_PAD_LEFT) }}<br>
                {{ $internalreceipt->expenditure_date }}</td>
            <td>{!! nl2br(app(\App\Markuskoehler\Billomat\Creditors::class)->get($internalreceipt->billomat_supplier_id)->address) !!}</td>
            <td>{{ $internalreceipt->expenditure_type }}</td>
            <td>{{ number_format($internalreceipt->expenditure_costs, 2, ',', '.') . ' EUR' }}</td>
            <td>{{ $internalreceipt->reason }}</td>
            <td><div class="row">
                    <div class="col-md-4"><a href="{{url()->route('internalreceipts.show', ['id' => $internalreceipt->id])}}" class="btn"><i class="fa fa-eye" aria-hidden="true"></i></a></div>
                    <div class="col-md-4"><a disabled class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                    <div class="col-md-4"><a disabled class="btn"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>
                </div>
                <a href="{{url()->route('internalreceipts.pdf', ['id' => $internalreceipt->id])}}" class="btn btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;
                    @if(!is_null($internalreceipt->unsigned_document))
                        View PDF
                    @else
                        Generate PDF
                    @endif
</a><br>
                    @if(!is_null($internalreceipt->signed_document))
                        <a href="{{url()->route('internalreceipts.signed', ['id' => $internalreceipt->id])}}" class="btn btn-sm"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;View Signed</a>
                    @else
                        <a onclick="document.getElementById('selectedFile{{$internalreceipt->id}}').click();" class="btn btn-sm"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload Signed</a>
                    <form action="{{url()->route('internalreceipts.store', ['id' => $internalreceipt->id])}}" method="POST" enctype="multipart/form-data" id="form{{$internalreceipt->id}}">
                        {{ csrf_field() }}
                        <input type="file" onchange="document.getElementById('form{{$internalreceipt->id}}').submit();" id="selectedFile{{$internalreceipt->id}}" name="selectedFile" style="display: none;" />
                    </form>
                    @endif
</td>
        </tr>
    @endforeach
        </tbody>
    </table>
@endsection