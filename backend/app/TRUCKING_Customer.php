<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TRUCKING_Customer extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'customers';

    // database connection

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */

    protected $connection = 'mysql-trucking';

    public function truckers(){
        return $this->belongsTo(TRUCKING_Trucker::class, 'trucker_id', 'id');
    }
}
