<?php

namespace App\Http\Controllers;

use App\Markuskoehler\Hibiscus\Models\Transaction;
use DateTime;
use Illuminate\Http\Request;

class TransactionAssignmentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get all transactions
        $month = explode('-', $request->input('month'));
        $month_start = $month[0] . '-' . $month[1] . '-01';
        $d = new DateTime( $month_start );
        $days_in_month = $d->format( 't' );
        $accounts = explode(',', $request->input('accounts'));
        return Transaction::whereIn('konto_id', $accounts)->whereBetween('datum', [$month_start, $month[0] . '-' . $month[1] . '-' . $days_in_month])->with('account')->get();
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
