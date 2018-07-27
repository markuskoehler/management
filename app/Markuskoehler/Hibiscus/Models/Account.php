<?php

namespace App\Markuskoehler\Hibiscus\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'hibiscus';

    protected $table = 'konto';
}
