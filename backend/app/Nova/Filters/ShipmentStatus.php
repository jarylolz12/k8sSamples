<?php

namespace App\Nova\Filters;

use App\Shipment;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Filters\Filter;
use App\Terminal49Shipment;
use Carbon\Carbon;

//ini_set('memory_limit', '512M');

class ShipmentStatus extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $commercialInvoice = '';
    public $packingList = '';
    public $invoicePackingList = '';
    public $newDoc = [];

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
        switch ($value) {
        //   bokking confirmed -> not selected, canceled not selected,
            case 'Pending Approval':
                return $query->where('booking_confirmed', 0)
                        ->where(function($q) {
                            /*
                            $q->where('schedules_group_bookings','<>','')
                            ->where('schedules_group_bookings','<>','[]')
                            ->whereNotNull('schedules_group_bookings')
                            ->whereJsonContains('schedules_group_bookings', [['is_confirmed' => 0]]);*/
                        })
                        ->where(function($q) {
                            /*
                            $q->where(function($qq) {
                                $qq->whereNotNull('services_section')
                                ->where('services_section','<>','')
                                ->where(function ($qqq) {
                                    $qqq->where(function($qqqq) {
                                        $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                        ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                        ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                        ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                        ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                    });
                                });
                            })
                            ->orWhereNull('services_section')
                            ->orWhere('services_section','');*/
                        })
                       ->where('cancelled', 0);
          // code...
            break;
            case 'Pending SO/BC released':
                return $query->where('booking_confirmed', 1)
                            ->where('so_released', 0)
                            ->where(function($q) {
                                /*
                                $q->where(function($qq) {
                                    $qq->whereNotNull('services_section')
                                    ->where('services_section','<>','')
                                    ->where(function ($qqq) {
                                        $qqq->where(function($qqqq) {
                                            $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                        });
                                    });
                                })
                                ->orWhereNull('services_section')
                                ->orWhere('services_section','');*/
                            })
                             ->whereDate('created_at', '>', '2023-01-01');
                             //->where('updated_at', '>=', '2021-07-08')
                             //->whereDate('created_at', '>=', '2021-11-16');

            break;
            case 'Pending HBL Released':
                return $query->where(function($q) {
                    $q->whereHas('shipmentSuppliers',function($qq) {
                        $qq->where('bl_status','<>','Telex Released')
                            ->where('cancelled','<>',1);
                    })
                    ->where(function($q) {
                        $q->where(function($qq) {
                            /*
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });*/
                        });
                        //->orWhereNull('services_section')
                        //->orWhere('services_section','');
                    });
               });
            case 'Pending Freights/Customs Release':
                return $query->where('freight_released_processed', 1)
                   ->where('customs_processed', 1)
                   ->where(function ($q) {
                       $q->where('freight_confirmed', 0);
                        $q->orWhere('customs_released', 0);
                    })
                    ->where(function($q) {
                        $q->where(function($qq) {
                            /*
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });*/
                        });
                        //->orWhereNull('services_section')
                        //->orWhere('services_section','');
                    })
                    ->where('cancelled', 0);

                //And freight confirmed Customs confirmed not selected (both should be selected to remove)
            case 'Approved':
            //approved - booking confirmed, selected etd has not passed, cancelled not selected
                return $query->where('booking_confirmed', 1)
                    ->where('etd', '>=', date('Y-m-d'))
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });
                        })
                        ->orWhereNull('services_section')
                        ->orWhere('services_section','');*/
                    })    
                    ->where('cancelled', 0);
            break;

            // ISF Pending  -> so released is true , isf done is false and cancelled is false

            case 'Pending MBL Released':
                return $query->where('mbl_released_confirmed', 0)
                            ->where('cancelled', 0)
                            ->where(function($q) {
                                /*
                                $q->where(function($qq) {
                                    $qq->whereNotNull('services_section')
                                    ->where('services_section','<>','')
                                    ->where(function ($qqq) {
                                        $qqq->where(function($qqqq) {
                                            $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                        });
                                    });
                                })
                                ->orWhereNull('services_section')
                                ->orWhere('services_section','');*/
                            }) 
                            ->where('created_at', '>=', '2022-01-05');
            case 'Pending ISF':
                return $query->where('so_released', 1)
                             ->where('isf_done', 0)
                             ->where('cancelled', 0)
                            ->where(function($q) {
                                $q->where(function($qq) {
                                    /*
                                    $qq->whereNotNull('services_section')
                                    ->where('services_section','<>','')
                                    ->where(function ($qqq) {
                                        $qqq->where(function($qqqq) {
                                            $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                        });
                                    });*/
                                });
                               // ->orWhereNull('services_section')
                                //->orWhere('services_section','');
                            })
                             ->whereDate('created_at', '>', '2023-01-01');
                             //->whereDate('created_at', '>=', '2021-11-16');
            break;

            // Ams Pending  -> so released is true , ams done is false and cancelled is false
            case 'Pending AMS/ENS':
                return $query->where('so_released', 1)
                             ->where('ams_done', 0)
                             ->where('cancelled', 0)
                             ->where(function($q) {
                                $q->where(function($qq) {
                                    /*
                                    $qq->whereNotNull('services_section')
                                    ->where('services_section','<>','')
                                    ->where(function ($qqq) {
                                        $qqq->where(function($qqqq) {
                                            $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                        });
                                    });*/
                                });
                                //->orWhereNull('services_section')
                                //->orWhere('services_section','');
                            })
                             ->whereDate('created_at', '>', '2023-01-01');
                             //->whereDate('created_at', '>=', '2021-11-16');

            case 'In Transit - Pending AN/Customs/Freight Payment':
            //etd has passed, bookin confirmed selected, cancelled not selected
                return $query->where('etd', '<', date('Y-m-d'))
                       ->where('booking_confirmed', 1)
                       ->where('cancelled', 0)
                       ->where(function ($q) {
                           $q->where('freight_released_processed', 0)
                                 ->orWhere('customs_processed', 0)
                                 ->orWhere(function ($q) {
                                     $q->where('delivery_order_left', 0)
                                       ->where('DO_confirmed', 0);
                                 });
                       });
            break;
            case 'Pending DO Confirmation':
              //do sent, do confirmed and cancelled not selected
                return $query->where('delivery_order_left', 1)
                       ->where('cancelled', 0)
                       ->where('DO_confirmed', 0);
            break;
            case 'Pending Delivery':
              //do confirmed selected, delivered not selected, canceled not selected
                return $query->where('DO_confirmed', 1)
                     ->where('delivered', 0)
                     ->where('freight_released_processed', 1)
                     ->where('customs_processed', 1)
                     ->where('cancelled', 0);
            break;
            case 'Pending Billing':
              //billed not selected, cancelled not selected
                return $query->where('billed', 0)
                        ->where('booking_confirmed', 1)
                        ->where('arrival_notice_confirmed', 1)
                        ->where('freight_released_processed', 1)
                        ->where('customs_processed', 1)
                        ->where('DO_confirmed', 1)
                        ->where('freight_confirmed', 1)
                        ->where('customs_released', 1)
                        ->where('delivered', 1)
                        ->where('cancelled', 0);
            break;
            case 'Pending Refund':
              // pending refund selected
                return $query->where('pending_refund', 1);
            break;
            case 'Pending Rate Confirmed':
              // pending Rate selected
                return $query->where('booking_confirmed', 1)
                             ->where('rate_confirmed', 0)
                             ->where('cancelled', 0)
                            ->where(function($q) {
                                /*
                                $q->where(function($qq) {
                                    $qq->whereNotNull('services_section')
                                    ->where('services_section','<>','')
                                    ->where(function ($qqq) {
                                        $qqq->where(function($qqqq) {
                                            $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                            ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                        });
                                    });
                                })
                                ->orWhereNull('services_section')
                                ->orWhere('services_section','');*/
                            })
                             ->whereDate('created_at', '>', '2023-01-01');
                             //->whereDate('created_at', '>=', '2021-11-16');
            break;
            case 'Cancelled':
                return $query->where('cancelled', 1);
            break;
            case 'lfd_not_out':
                //
                $terminal49_shipments = Terminal49Shipment::whereIn('mbl_num', $query->get('mbl_num')->pluck('mbl_num'))->get();
                $lfd_shipments = [];
                if ($terminal49_shipments) {
                    foreach ($terminal49_shipments as $key => $shipment) {
                        $released_at_terminal = false;
                        $containers = json_decode($shipment->containers);
                        $ignore_demurrage = json_decode($shipment->ignore_demurrage ?? '[]', true);

                        if (!empty($shipment->containers) && !empty($containers)) {
                            foreach ($containers as $key => $container) {
                                $passed_lfd = false;
                                $skip_container = false;
                                // code...
                                //
                                // check lfd
                                $lfd = $container->attributes->pickup_lfd;
                                if (empty($lfd)) {
                                    continue;
                                }
                                $lfd = \Carbon\Carbon::parse($lfd);
                                if (now()->gt($lfd)) {
                                    $passed_lfd = true;
                                }
                                //
                                $released_at_terminal = $container->attributes->pod_full_out_at ?? $container->attributes->empty_terminated_at;

                                if (isset($ignore_demurrage[$container->attributes->number]) && $ignore_demurrage[$container->attributes->number]) {
                                    $skip_container = true;
                                }

                                if ($passed_lfd && empty($released_at_terminal) && !$skip_container) {
                                    array_push($lfd_shipments, $shipment->mbl_num);
                                    break;
                                }
                            }
                        }
                    }
                }
                return $query->whereIn('mbl_num', $lfd_shipments)->where('cancelled', 0);
                //
            break;
            //Pending Basic Information
            case 'Pending Basic Information':
                return $query
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->where('shifl_office_origin_from_id', '<=', 0)
                            ->orWhere('shifl_office_origin_to_id', '<=', 0)
                        })*/
                        $q->where(function ($qq) {
                            $qq->where(function($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('suppliers_group_bookings', '=', '')
                                        ->orWhere('suppliers_group_bookings', '=', '[]')
                                        ->orWhere('suppliers_group_bookings', '=', '[{}]')
                                        ->orWhereNull('suppliers_group_bookings');
                                })
                                ->orWhere(function ($qqqq) {
                                    $qqqq->whereNotNull('suppliers_group_bookings')
                                    ->where('suppliers_group_bookings', '<>', '')
                                    ->where('suppliers_group_bookings', '<>', '[{}]')
                                    ->where(function($qqqqq) {
                                        $qqqqq->whereJsonContains('suppliers_group_bookings', [['buyer_id' => null,'supplier_id' => null]]);
                                    });
                                });
                            })
                            ->where(function($qqq) {
                                $qqq->where('schedules_group_bookings', '=', '')
                                    ->orWhere('schedules_group_bookings', '=', '[]')
                                    ->orWhere('schedules_group_bookings', '=', '[{}]')
                                    ->orWhereNull('schedules_group_bookings');
                            })
                            ->where(function($qqq) {
                                $qqq->where('containers_group_bookings', '=', '')
                                    ->orWhere('containers_group_bookings', '=', '[]')
                                    ->orWhere('containers_group_bookings', '=', '[{}]')
                                    ->orWhereNull('containers_group_bookings');
                            });
                        });
                        //->orWhereRaw('JSON_CONTAINS(suppliers_group_bookings, \'{"kg": ""}\', \'$\')')
                        //->orWhereRaw('JSON_CONTAINS(suppliers_group_bookings, \'{"cbm": ""}\', \'$\')');
                    })
                    ->where(function($query) {
                        $query->whereNotNull('services_section')
                          ->where('services_section','<>','')
                          ->where(function($q) {
                            $q->where(function($qq) {
                                $qq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                ->orWhereJsonContains('services_section',[
                                        'trucking' => 0,
                                        'customs' => 0,
                                        'isf' => 0,
                                        'pier_pass' => 0,
                                        'duty_layout' => 0,
                                        'freight_forwarding' => 0
                                ]);    
                            })->orWhere(function($qq) {
                                $qq->whereNull('services_section')
                                ->orWhere('services_section','');
                            });
                            
                        });
                    })
                    ->where(function($q) {
                        $q->where('cancelled', 0);
                        //$query->where('created_at', '<', Carbon::now());
                    })
                    ->whereDate('created_at', '>', '2023-01-01');

                break;
            case 'Trucking Only':
                return $query->where(function($qq) {
                    $qq->whereNotNull('services_section')
                      ->where('services_section','<>','')
                      ->where(function($qqq) {
                        $qqq->whereJsonContains('services_section',['trucking' => 1,'freight_forwarding' => 0, 'customs' => 0, 'duty_layout' => 0, 'pier_pass' => 0]);
                    });
                });
                break;
            //Pending Quote/Schedule
            case 'Pending Quote/Schedule':
                return $query->where(function($q) {
                    /*
                    $q->whereHas('shipmentSchedules', function($qq) {
                        $qq->whereDoesntHave('shipmentScheduleSellRates');
                    })->orWhereDoesntHave('shipmentSchedules')*/

                    $q->where(function($qq) {
                        $qq->where(function($qqq) {
                            $qqq->where(function($qqqq) {
                                $qqqq->where(function($qqqqq) {
                                    $qqqqq->whereNull('schedules_group_bookings')
                                    ->orWhere('schedules_group_bookings', "[]")
                                    ->orWhere('schedules_group_bookings', "[{}]");
                                })->where(function($qqqq) {
                                    $qqqq->where(function($qqqqq) {
                                        $qqqqq->where('containers_group_bookings', '<>', '')
                                        ->where('containers_group_bookings', '<>', '[]')
                                        ->where('containers_group_bookings', '<>', '[{}]')
                                        ->whereNotNull('containers_group_bookings');
                                    })->orWhere(function($qqqqq) {
                                        $qqqqq->where('suppliers_group_bookings', '<>', '')
                                        ->where('suppliers_group_bookings', '<>', '[]')
                                        ->where('suppliers_group_bookings', '<>', '[{}]')
                                        ->whereNotNull('suppliers_group_bookings');
                                    });
                                });
                            });
                        })->orWhere(function($qqq) {
                            $qqq->where('schedules_group_bookings','<>','')
                            ->whereNotNull('schedules_group_bookings')
                            ->where('schedules_group_bookings', '<>', "[]")
                            ->where('schedules_group_bookings', '<>', "[{}]")
                            ->where('schedules_group_bookings', 'LIKE', '%"sell_rates":[]%');
                            /*
                            ->where(function($qqqq) {

                                $qqqq->whereHas('shipmentSchedules', function($qqqqq) {
                                    $qqqqq->whereDoesntHave('shipmentScheduleSellRates');
                                });
                                /*
                                $qqqq->where(function($qqqqq) {
                                    $qqqqq->whereJsonContains('schedules_group_bookings',[['sell_rates' => null]])
                                    ->orWhereJsonContains('schedules_group_bookings',[['sell_rates' => '[]']]);
                                })
                                ->orWhereJsonDoesntContain('schedules_group_bookings',[['sell_rates']]);*/
                            //});
                        });
                    });
                })->where(function($q) {
                    //$q->where('is_booking_email_sent', '0')
                    $q->where('booking_confirmed', 0);
                })->where(function($q) {
                    $q->where('cancelled', 0);
                })
                ->where(function($query) {
                    $query->whereNotNull('services_section')
                      ->where('services_section','<>','')
                      ->where(function($q) {
                        $q->where(function($qq) {
                            $qq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                            ->orWhereJsonContains('services_section',[
                                    'trucking' => 0,
                                    'customs' => 0,
                                    'isf' => 0,
                                    'pier_pass' => 0,
                                    'duty_layout' => 0,
                                    'freight_forwarding' => 0
                            ]);    
                        })->orWhere(function($qq) {
                            $qq->whereNull('services_section')
                            ->orWhere('services_section','');
                        });
                        
                    });
                })
                ->whereDate('created_at', '>', '2023-01-01');
                break;
            //Pending Customer Approval
            case 'Pending Customer Approval':
                return $query->where('cancelled', 0)
                    ->where(function($q) {
                        $q->whereHas('shipmentSchedules', function($qq) {
                            $qq->whereHas('shipmentScheduleSellRates');
                        })->orWhere(function($qq) {
                            $qq->where('schedules_group_bookings','<>','')
                            ->whereNotNull('schedules_group_bookings')
                            ->where('schedules_group_bookings', '<>', "[]")
                            ->where('schedules_group_bookings', '<>', "[{}]")
                            ->where('schedules_group_bookings', 'LIKE', '%"sell_rates":[{%');
                            //->where('schedules_group_bookings', '<>','[{sell_rates: null}]');
                            //->whereJsonContains('schedules_group_bookings',[['sell_rates'])
                            //->whereNotNull('schedules_group_bookings->sell_rates');
                        });
                    })
                    ->where('booking_confirmed', 0)
                    ->where(function($query) {
                        $query->whereNotNull('services_section')
                          ->where('services_section','<>','')
                          ->where(function($q) {
                            $q->where(function($qq) {
                                $qq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                ->orWhereJsonContains('services_section',[
                                        'trucking' => 0,
                                        'customs' => 0,
                                        'isf' => 0,
                                        'pier_pass' => 0,
                                        'duty_layout' => 0,
                                        'freight_forwarding' => 0
                                ]);    
                            })->orWhere(function($qq) {
                                $qq->whereNull('services_section')
                                ->orWhere('services_section','');
                            });
                            
                        });
                    })
                    ->whereDate('created_at', '>', '2023-01-01');
                break;
          //   bokking confirmed -> not selected, canceled not selected,
            case 'Pending Gate In':
                return $query->where('cancelled', 0)
                    ->where('gate_full_in', 0)
                    ->where('so_received', '1')
                    ->where(function($query) {
                        /*
                        $query->whereNotNull('services_section')
                          ->where('services_section','<>','')
                          ->where(function($q) {
                            $q->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1]);   
                        });*/  
                    })
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });
                        })
                        ->orWhereNull('services_section')
                        ->orWhere('services_section','');*/
                    })
                    ->whereDate('created_at', '>', '2023-01-01');
                    //->where('created_at', '>=', '2022-12-01 00:00:00');
                break;
            case 'Pending HBL Telex Release & Past ETA':
                $now = Carbon::now();
                $date = Carbon::parse($now)->toDateString();

                return $query->where(function($query) {
                    $query->where('suppliers_group_bookings', '!=', '')
                        ->whereJsonContains('suppliers_group_bookings', ['bl_status' => "Pending"]);
                })->where(function($query) use ($date) {
                    $query->where('cancelled', 0)
                        ->where('eta', '<=', $date);
                })
                ->where(function($q) {
                    /*
                    $q->where(function($qq) {
                        $qq->whereNotNull('services_section')
                        ->where('services_section','<>','')
                        ->where(function ($qqq) {
                            $qqq->where(function($qqqq) {
                                $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                            });
                        });
                    })
                    ->orWhereNull('services_section')
                    ->orWhere('services_section','');*/
                });
                break;
            case 'Pending Agent Confirmation':
                return $query
                    ->where(function($query) {
                        $query
                            ->where('schedules_group_bookings', '<>', "")
                            ->where('schedules_group_bookings', '<>', "[]")
                            ->where('schedules_group_bookings', '<>', "[{}]")
                            ->where(function($query) {
                                $query->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"is_confirmed": 1}\', \'$\')');
                            });
                    })
                    ->where('is_agent_booking_confirm', '0')
                    ->where(function($query) {
                        $query->where('cancelled', 0);
                    })
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });
                        })
                        ->orWhereNull('services_section')
                        ->orWhere('services_section','');*/
                    })
                    ->whereDate('created_at', '>', '2023-01-01');
                break;
            case 'Pending Sufficient Commercial Docs':
                return $query
                    ->where('suppliers_group_bookings', '<>', '')
                    ->where('suppliers_group_bookings', '<>', '[]')
                    ->where('booking_confirmed', 1)
                    ->where('cancelled', 0)
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });
                        })
                        ->orWhereNull('services_section')
                        ->orWhere('services_section','');*/
                    })
                    ->where('has_pending_suff_docs', 1);
                break;
            //Pending So Received
            case 'Pending So Received':
                return $query
                    ->where('so_received', '0')
                    ->where('is_agent_booking_confirm', '1')
                    ->where(function($query) {
                        $query->where('cancelled', 0);
                    })
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });
                        })
                        ->orWhereNull('services_section')
                        ->orWhere('services_section','');*/
                    })
                    ->whereDate('created_at', '>', '2023-01-01');
                break;
            //Pending Containers Loaded
            case 'Pending Containers Loaded':
                return $query
                    ->where('is_containers_loaded', '0')
                    ->where(function($query) {
                        $query->where('cancelled', 0);
                    })
                    ->where(function($query) {
                        /*
                        $query->whereNotNull('services_section')
                          ->where('services_section','<>','')
                          ->where(function($q) {
                            $q->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                            ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1]);   
                        });*/
                    })
                    ->where(function($q) {
                        /*
                        $q->where(function($qq) {
                            $qq->whereNotNull('services_section')
                            ->where('services_section','<>','')
                            ->where(function ($qqq) {
                                $qqq->where(function($qqqq) {
                                    $qqqq->where('services_section',['trucking' => 1,'freight_forwarding' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'customs' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'duty_layout' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 1,'pier_pass' => 1])
                                    ->orWhereJsonContains('services_section',['trucking' => 0,'pier_pass' => 0,'duty_layout' => 0,'customs' => 0,'freight_forwarding' => 0]);
                                });
                            });
                        })
                        ->orWhereNull('services_section')
                        ->orWhere('services_section','');*/
                    })
                    ->whereDate('created_at', '>', '2023-01-01');
                break;
            default:
                return $query->where('shipment_status', $value);
          // code...
            break;
        }

        //  return $query->where('shipment_status', $value);
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
            'Pending Basic Information' => 'Pending Basic Information',
            'Pending Quote/Schedule' => 'Pending Quote/Schedule',
            'Pending Customer Approval' => 'Pending Customer Approval',
            'Pending Agent Confirmation' => 'Pending Agent Confirmation',
            'Pending Rate Confirmed' => 'Pending Rate Confirmed',
            'Pending Sufficient Commercial Docs' => 'Pending Sufficient Commercial Docs',
            'Pending Approval' => 'Pending Approval',
            'Pending So Received' => 'Pending So Received',
            'Pending Containers Loaded' => 'Pending Containers Loaded',
            'Approved' => 'Approved',
            'Pending SO/BC released' => 'Pending SO/BC released',
            'Pending AMS/ENS' => 'Pending AMS/ENS',
            'Pending Gate In' => 'Pending Gate In',
            'Pending ISF' => 'Pending ISF',
            'Pending MBL Released' => 'Pending MBL Released',
            'In Demurrage' => 'lfd_not_out',
            'Pending HBL Released' => 'Pending HBL Released',
            'Pending HBL Telex Release & Past ETA' => 'Pending HBL Telex Release & Past ETA',
            'In Transit - Pending AN/Customs/Freight Payment' => 'In Transit - Pending AN/Customs/Freight Payment',
            'Pending DO Confirmation' => 'Pending DO Confirmation',
            'Pending Delivery' => 'Pending Delivery',
            'Pending Freights/Customs Release' => 'Pending Freights/Customs Release',
            'Pending Billing' => 'Pending Billing',
            'Pending Refund' => 'Pending Refund',
            'Completed' => 'Completed',
            'Cancelled' => 'Cancelled'
        ];
    }
}

