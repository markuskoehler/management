<?php

use Illuminate\Http\Request;
use App\Markuskoehler\Hibiscus\Models\Account;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/transactionassignment/accounts', function (Request $request) {
    // get bank accounts for multi-select on transaction assignment
    return Account::whereBetween('id', [1, 3])->get();
});

Route::middleware('auth:api')->resource('/transactionassignment', 'TransactionAssignmentApiController', ['except' => [
    'create', 'edit'
]

]);

/*Route::resource('photo', 'PhotoController', ['only' => [
    'index', 'show'
]]);

Route::resource('photo', 'PhotoController', ['except' => [
    'create', 'store', 'update', 'destroy'
]]);*/