@extends('layouts.app')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Receipt No.</th>
            <th>Creditor &amp; Address</th>
            <th>Type of Expenditure</th>
            <th>Date of Expenditure</th>
            <th>Costs</th>
            <th>Reason</th>
        </tr>
        </thead>
        <tbody>
    @foreach($internalreceipts as $internalreceipt)
        <tr>
            <td>{{ $internalreceipt->id }}</td>
            <td>{{ date('Y', strtotime($internalreceipt->expenditure_date)) . '-' . str_pad($internalreceipt->serial_no, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $internalreceipt->creditor_name }}<br>
                @empty($internalreceipt->creditor_address_1)
                @else
                    {{ $internalreceipt->creditor_address_1 }}<br>
                @endempty
                {{ $internalreceipt->creditor_address_2 }}<br>
                {{ $internalreceipt->creditor_place }}
            </td>
            <td>{{ $internalreceipt->expenditure_type }}</td>
            <td>{{ $internalreceipt->expenditure_date }}</td>
            <td>{{ $internalreceipt->expenditure_costs }} EUR</td>
            <td>{{ $internalreceipt->reason }}</td>
        </tr>
    @endforeach
        </tbody>
    </table>

{{--
    @php
    # This example demonstrates creating a PDF using common options and saving it
    # to a place on the filesystem.
    #
    # It is created asynchronously, which means DocRaptor will render it for up to
    # 10 minutes. This is useful when creating many documents in parallel, or very
    # large documents with lots of assets.
    #
    # DocRaptor supports many options for output customization, the full list is
    # https://docraptor.com/documentation/api#api_general
    #
    # You can run this example with: php async.rb

    $configuration = DocRaptor\Configuration::getDefaultConfiguration();
    //$configuration->setUsername("YOUR_API_KEY_HERE"); # this key works for test documents
    $configuration->setUsername("qjB2fFVg5mWqigcZERY");
    # $configuration->setDebug(true);
    $docraptor = new DocRaptor\DocApi();
    try {
    $doc = new DocRaptor\Doc();
    $doc->setTest(false);                                                   # test documents are free but watermarked
    $doc->setDocumentContent("<html><body>Hello World</body></html>");     # supply content directly
    # $doc->setDocumentUrl("http://docraptor.com/examples/invoice.html");  # or use a url
    $doc->setName("docraptor-php.pdf");                                    # help you find a document later
    $doc->setDocumentType("pdf");                                          # pdf or xls or xlsx
    # $doc->setJavascript(true);                                           # enable JavaScript processing
    # $prince_options = new DocRaptor\PrinceOptions();                     # pdf-specific options
    # $doc->setPrinceOptions($prince_options);
    # $prince_options->setMedia("screen");                                 # use screen styles instead of print styles
    # $prince_options->setBaseurl("http://hello.com");                     # pretend URL when using document_content
    $create_response = $docraptor->createAsyncDoc($doc);
    $done = false;
    while (!$done) {
    $status_response = $docraptor->getAsyncDocStatus($create_response->getStatusId());
    //echo "doc status: " . $status_response->getStatus() . "\n";
    switch ($status_response->getStatus()) {
    case "completed":
    $doc_response = $docraptor->getAsyncDoc($status_response->getDownloadId());
    $temp_file = tempnam(sys_get_temp_dir(), 'tmp');
    $file = fopen($temp_file, "wb");
    fwrite($file, $doc_response);
    fclose($file);
    Storage::disk('spaces')->putFile('management/internalreceipts', new Illuminate\Http\File($temp_file));
    unlink($temp_file);
    echo "Wrote PDF\n";
    $done = true;
    break;
    case "failed":
    echo "FAILED\n";
    echo $status_response;
    $done = true;
    break;
    default:
    sleep(1);
    }
    }
    } catch (DocRaptor\ApiException $exception) {
    echo $exception . "\n";
    echo $exception->getMessage() . "\n";
    echo $exception->getCode() . "\n";
    echo $exception->getResponseBody() . "\n";
    }
    @endphp
    --}}
@endsection