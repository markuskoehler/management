<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalReceipt extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'internalreceipt';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'internal_receipts';
}
