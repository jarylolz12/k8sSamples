<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TRUCKING_Trucker extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'truckers';

    // database connection

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */

    protected $connection = 'mysql-trucking';
}
