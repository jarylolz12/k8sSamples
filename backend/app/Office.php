<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;



//use Laravel\Nova\Http\Requests\NovaRequest;
class Office extends Model
{
	 protected $table = 'shifl_offices_managers';

	 protected $fillable = ['shifl_office_id', 'manager_id'];

}