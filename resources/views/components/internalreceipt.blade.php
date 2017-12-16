@php
    function fn($data) {
        return $data;
    }
    $fn = 'fn';

        $content = <<<EOL
        <table class="table" border="1" style="font-family: Times New Roman, serif">
            <tbody>
            <tr>
                <td><strong>Eigenbeleg</strong></td>
                <td>
                    Nr. {$fn(date('Y', strtotime($internalreceipt->expenditure_date)) . '-' . str_pad($internalreceipt->serial_no, 3, '0', STR_PAD_LEFT))}</td>
            </tr>
            <tr>
                <td>Betrag (EURO, Cent):</td>
                <td>{$fn(number_format($internalreceipt->expenditure_costs, 2, ',', '.') . ' EUR')}</td>
            </tr>
            <tr>
                <td width="33.3%">Empfänger:</td>
                <td>
                    {$fn(nl2br($address->address))}
                </td>
            </tr>
            <tr>
                <td>Verwendungszweck:</td>
                <td>{$fn($internalreceipt->expenditure_type)}</td>
            </tr>
            <tr>
                <td>Grund für Eigenbeleg:</td>
                <td>{$fn($internalreceipt->reason)}</td>
            </tr>
            <tr>
                <td>Ort, Datum:<br><br>
                    Altleiningen, {$fn(date('d.m.Y', strtotime($internalreceipt->expenditure_date)))}</td>
                <td>Unterschrift:<br><br><br></td>
            </tr>
            </tbody>
        </table>
EOL;
@endphp
@if(!empty($print))
    {!! $content !!}
@else
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
        $configuration->setUsername(env('DOCRAPTOR_USERNAME'));
        //$configuration->setDebug(true);
        $docraptor = new DocRaptor\DocApi();
        try {
        $doc = new DocRaptor\Doc();
        $doc->setTest(false);                                                   # test documents are free but watermarked
        $doc->setDocumentContent("<html>
        <head>
        <style>
        td, th {
            border: 1px solid black;
            padding: 5px;
        }
        table {
            border-collapse: collapse;
        }
        </style>
        </head>
        <body>" . $content . "</body></html>");     # supply content directly
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
            $link = Storage::disk('spaces')->putFile('management/internalreceipts', new Illuminate\Http\File($temp_file));
            unlink($temp_file);
            //echo "Wrote PDF to " . Storage::cloud()->url($link);
            //dd(Storage::cloud()->url($link)); // dont need full url
            $internalreceipt->unsigned_document = $link;
            $internalreceipt->save();
            $done = true;
            echo redirect()->route('internalreceipts.index'); // todo make redirect more user-friendly
        break;
        case "failed":
            echo "FAILED\n";
            echo $status_response;
            //var_dump('default');
            $done = true;
        break;
        default:
            sleep(1);
        }
        }
        //dd(redirect()->route('internalreceipts.index'));
        //header('Location: ' . url()->route('internalreceipts.index'));
        } catch (DocRaptor\ApiException $exception) {
        echo $exception . "\n";
        echo $exception->getMessage() . "\n";
        echo $exception->getCode() . "\n";
        echo $exception->getResponseBody() . "\n";
        }
    @endphp
@endif