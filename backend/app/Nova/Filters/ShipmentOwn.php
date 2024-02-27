<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Carbon\Carbon;
class ShipmentOwn extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public $name = 'General Filter';

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
        $getFilters = $request->filters();

        $shipmentStatusFilter = null;

        if (count($getFilters)>0) {
            foreach ($getFilters as $key => $filter) {
                $className = get_class($filter->filter);
                if ($className=='App\Nova\Filters\ShipmentStatus') {
                    $shipmentStatusFilter = $filter->value;
                }
            }
        }




        if ($request->user()->has('roles')) {
            $checkRoles = $request->user()->roles;
            $roles = ['Super Admin','Sales Representative','Account Manager'];
            $assigned_role = null;
            foreach ($checkRoles as $key => $checkRole) {
                foreach ($roles as $key_second => $role) {
                    if ($checkRole->name==$role) {
                        $assigned_role = $role;
                    }
                }
            }
            if ($assigned_role=='Super Admin') {
                if ($value=='me') {
                    if ($shipmentStatusFilter==null) {
                        return $query->where('shipment_status', '<>', 'Completed');
                    } else {
                        if ($shipmentStatusFilter!='Completed') {
                            return $query->where('shipment_status', '<>', 'Completed');
                        }
                    }
                }
                //  elseif ($value=='unassigned') {
                //     if ($shipmentStatusFilter==null) {
                //         return $query->where('customer_id', null)
                //                      ->where('shipment_status', '<>', 'Completed');
                //     } else {
                //         if ($shipmentStatusFilter!='Completed') {
                //             return $query->where('shipment_status', '<>', 'Completed')
                //                          ->where('customer_id', null);
                //         } else {
                //             return $query->where('customer_id', null);
                //         }
                //     }
                // }

                elseif ($value=='all') {
                    if ($shipmentStatusFilter==null) {
                        return $query->where('shipment_status', '<>', 'Completed');
                    } else {
                        if ($shipmentStatusFilter!='Completed') {
                            return $query->where('shipment_status', '<>', 'Completed');
                        }
                    }
                }
            } elseif ($assigned_role=='Account Manager') {
                if ($value=='me') {

                    if ($shipmentStatusFilter==null) {
                        return $query->whereHas('customer', function ($q) use ($request) {
                            //$q->orWhere('managers', $request->user()->id);
                           
                            //$q->orWhere('managers', $request->user()->id);
                            $q->whereJsonContains('offices_managers', [['manager_id' => intval($request->user()->id)]]);
                            
                        })
                        ->where(function($q){
                            $q->where('shipment_status', '<>', 'Completed');
                        });
                       
                    } else {
                        if ($shipmentStatusFilter!='Completed') {
                            return $query->whereHas('customer', function ($q) use ($request) {
                               // $q->orWhere('managers', $request->user()->id);
                                $q->whereJsonContains('offices_managers', [['manager_id' => intval($request->user()->id)]]);
                            })
                            ->where(function($q){
                                $q->where('shipment_status', '<>', 'Completed');
                            });
                        } else {
                            return $query->whereHas('customer', function ($q) use ($request) {
                                //$q->orWhere('managers', $request->user()->id);
                                $q->whereJsonContains('offices_managers', [['manager_id' => intval($request->user()->id)]]);
                            });
                        }
                    }
                }

                //  elseif ($value=='unassigned') {
                //     if ($shipmentStatusFilter==null) {
                //         return $query->where('customer_id', null)
                //                      ->where('shipment_status', '<>', 'Completed');
                //     } else {
                //         if ($shipmentStatusFilter!='Completed') {
                //             return $query->where('shipment_status', '<>', 'Completed')
                //                          ->where('customer_id', null);
                //         } else {
                //             return $query->where('customer_id', null);
                //         }
                //     }
                // }

                elseif ($value=='all') {
                    if ($shipmentStatusFilter==null) {
                        return $query->where('shipment_status', '<>', 'Completed');
                    } else {
                        if ($shipmentStatusFilter!='Completed') {
                            return $query->where('shipment_status', '<>', 'Completed');
                        }
                    }
                }
            } elseif ($assigned_role=='Sales Representative') {
                if ($value=='me') {
                    if ($shipmentStatusFilter==null) {
                        return $query->whereHas('customer', function ($q) use ($request) {
                            $q->where('sale_persons', $request->user()->id);
                        })
                        ->where('shipment_status', '<>', 'Completed');
                    } else {
                        if ($shipmentStatusFilter!='Completed') {
                            return $query->whereHas('customer', function ($q) use ($request) {
                                $q->where('sale_persons', $request->user()->id);
                            })
                                ->where('shipment_status', '<>', 'Completed');
                        } else {
                            return $query->whereHas('customer', function ($q) use ($request) {
                                $q->where('sale_persons', $request->user()->id);
                            });
                        }
                    }
                }



                //  elseif ($value=='unassigned') {
                //     if ($shipmentStatusFilter==null) {
                //         return $query->where('customer_id', null)
                //                      ->where('shipment_status', '<>', 'Completed');
                //     } else {
                //         if ($shipmentStatusFilter!='Completed') {
                //             return $query->where('shipment_status', '<>', 'Completed')
                //                          ->where('customer_id', null);
                //         } else {
                //             return $query->where('customer_id', null);
                //         }
                //     }
                // }



                elseif ($value=='all') {
                    if ($shipmentStatusFilter==null) {
                        return $query->where('shipment_status', '<>', 'Completed');
                    } else {
                        if ($shipmentStatusFilter!='Completed') {
                            return $query->where('shipment_status', '<>', 'Completed');
                        }
                    }
                }
            }
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
            'All Shipments' => 'all',
            'My Shipments' => 'me',
            // 'Unassigned' => "unassigned", // -- removed unassigned filter from shipments as per requirements.
        ];
    }
}
