<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use OptimistDigtal\NovaMultiselectFilter\MultiselectFilter;

class AccountManager extends MultiselectFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */
   // public $component = 'select-filter';

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
        $office_from_id = [];
        $office_to_id = [];
        $customer_ = [];
        $checkOffices = \App\Customer::all();
        foreach ($checkOffices as $key => $checkOffice) {
            $findOfficesManagers = json_decode($checkOffice->offices_managers);
            foreach ($findOfficesManagers as $key_second => $office_manager){
                if(!empty($value)) {
                    foreach ($value as $key_third => $val) {
                        if ($office_manager->manager_id == $val) {
                            if (!in_array($office_manager->office_id, $office_from_id)){
                                array_push($office_from_id, $office_manager->office_id);
                            }
                            if (!in_array($office_manager->office_id, $office_to_id)) {
                                array_push($office_to_id, $office_manager->office_id);
                            }
                            if (!in_array($office_manager->manager_id, $customer_)) {
                                array_push($customer_, $checkOffice->id);
                            }
                        }
                    }
                }
            }
        }

        return $query->whereHas('customer', function ($q) use ($request, $customer_, $office_from_id, $office_to_id) {
            $q->where(function ($qq) use ($customer_, $office_from_id, $office_to_id) {
                \Log::info(['office to id'=> $office_to_id ]);
                $qq->whereIn('customer_id',$customer_)->whereIn('shifl_office_origin_to_id',$office_to_id)
                    ->orWhereIn('customer_id',$customer_)->whereIn('shifl_office_origin_from_id',$office_from_id)
                    ->orWhereIn('customer_id',$customer_)->whereIn('shifl_office_origin_from_id',$office_to_id)->whereIn('shifl_office_origin_to_id',$office_to_id);
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
        return \App\AccountManager::whereHas('roles', function ($q) {
            $q->where('name', 'Account Manager');
        })->orderBy('name')->pluck('name', 'id')->all();
    }
}
