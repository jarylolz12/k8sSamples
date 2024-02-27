<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class MBL extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

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
        if($value == 0){
            return $query->whereHas('customer', function ($q) use ($request, $value) {
                $q->where(function ($qq) use ($value) {
                    $qq->where('mbl_num', '!=', '')->where('mbl_num', '!=', null);
                });
            });
        }else{
            return $query->whereHas('customer', function ($q) use ($request, $value) {
                $q->where(function ($qq) use ($value) {
                    $qq->where('mbl_num', '')->orWhere('mbl_num', null);
                });
            });
        }
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
            'Yes MBL' => 0,
            'No MBL' => 1,
        ];
    }
}
