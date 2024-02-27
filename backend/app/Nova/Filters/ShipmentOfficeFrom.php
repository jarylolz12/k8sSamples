<?php
namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ShipmentOfficeFrom extends Filter
{
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
        return $query->whereHas('customer', function ($q) use ($request, $value) {

           $q->where(function ($qq) use ($value) {
                $qq->where('shifl_office_origin_from_id', $value);
           });
            
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $models = \App\ShiflOffice::all();
        return $models->pluck('id', 'name')->all();
    }
}
