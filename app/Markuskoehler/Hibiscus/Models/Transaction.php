<?php

namespace App\Markuskoehler\Hibiscus\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'hibiscus';

    protected $table = 'umsatz';

    public function account() {
        return $this->hasOne(Account::class, 'id', 'konto_id');
    }
}
