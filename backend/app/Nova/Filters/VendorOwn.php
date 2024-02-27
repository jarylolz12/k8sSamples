<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Carbon\Carbon;
class VendorOwn extends Filter
{

    public $name = 'Filter by Ownership';

    public function default()
    {
        return "me";
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if ( $value=='me' ) {
            $query->where('realm_id', $request->user()->quickbookstoken->realm_id);
        }
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'All Vendors' => 'all',
            'My Vendors' => 'me',
        ];
    }
}
