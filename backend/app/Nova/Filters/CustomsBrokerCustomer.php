<?php

namespace App\Nova\Filters;

use App\Customer;
use App\CustomsBroker;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CustomsBrokerCustomer extends Filter
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
        return $query->where('customer_id', $value);return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $customers = [];
        foreach (CustomsBroker::get() as $broker) {
            $customers[Customer::find($broker->customer_id)->company_name] = $broker->customer_id;
        }
        return $customers;
    }
}
