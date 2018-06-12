<?php

namespace App\Http\Controllers;

use App\Markuskoehler\Billomat\Creditors;
use App\Models\InternalReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

class InternalReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('internalreceipt.index', ['internalreceipts' => InternalReceipt::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('internalreceipt.show', [
            'internalreceipt' => InternalReceipt::find($id),
            'address' => app(Creditors::class)->get(InternalReceipt::find($id)->billomat_supplier_id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InternalReceipt  $internalReceipt
     * @return \Illuminate\Http\Response
     */
    public function edit(InternalReceipt $internalReceipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InternalReceipt  $internalReceipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InternalReceipt $internalReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InternalReceipt  $internalReceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(InternalReceipt $internalReceipt)
    {
        //
    }

    public function generatePdf($id) {
        return view('internalreceipt.generate_pdf', [
            'internalreceipt' => InternalReceipt::find($id),
            'address' => app(Creditors::class)->get(InternalReceipt::find($id)->billomat_supplier_id)
        ]);
    }

    public function storeSignedDoc(Request $request, $id) {
        //Log::info("reached upload method");
        $link = Storage::disk('spaces')->putFile('management/internalreceipts', $request->file('selectedFile'));
        $internalreceipt = InternalReceipt::find($id);
        $internalreceipt->signed_document = $link;
        $internalreceipt->save();
        return redirect()->back();
    }

    public function showSignedDoc(Request $request, $id) {
        header("Content-type:application/pdf");
        $internalreceipt = InternalReceipt::find($id);
        return response(Storage::cloud()->get($internalreceipt->signed_document))->header('Content-Type', 'application/pdf');
    }
}
