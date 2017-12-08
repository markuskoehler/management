<?php

namespace App\Http\Controllers;

use App\Models\InternalReceipt;
use Illuminate\Http\Request;

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
        return view('internalreceipt.show', ['internalreceipt' => InternalReceipt::find($id)]);
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
}
