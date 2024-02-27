<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentMilestone extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\ShipmentMilestone::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function optionsAlias(){
        return [
            'so_released' => 'SO/BC Released',
            'ams_done' => 'AMS/ENS Done',
            'arrival_notice' => 'Arrival Notice Sent',
            'arrival_notice_confirmed' => 'Arrival Notice Received',
            'freight_confirmed' => 'Freight Released Confirmed',
            'customs_released' => 'Customs Released Confirmed',
            'delivery_order_left' => 'DO Sent',
            'isf_done' => 'ISF Done',
            'mbl_released_confirmed' => 'MBL Released Confirmed',
            'hbl_released_confirmed' => 'HBL Released Confirmed'
        ];
    }

    public function fields(Request $request)
    {
        return [
            Text::make('Milestone',function(){
                $name = isset( $this->optionsAlias()[$this->name] ) ? $this->optionsAlias()[$this->name] : $this->name;

                return ucfirst(str_replace('_',' ',$name));
            })
            ->hideWhenCreating()
            ->hideWhenUpdating(),
            Select::make('Milestone', 'name')->options(function(){
                $arr = [
                    'booking_confirmed' => 'Booking Confirmed',
                    'so_released' => 'SO/BC Released',
                    'isf_done' => 'ISF Done',
                    'ams_done' => 'AMS/ENS Done',
                    'rate_confirmed' => 'Rate Confirmed',
                    'mbl_released_confirmed' => 'MBL Released Confirmed',
                    'arrival_notice' => 'Arrival Notice Sent',
                    'arrival_notice_confirmed' => 'Arrival Notice Received',
                    'hbl_released_confirmed' => 'HBL Released Confirmed',
                    'customs_sent' => 'Customs Sent',
                    'customs_processed' => 'Customs Processed',
                    'freight_released_processed' => 'Freight Released Processed',
                    'delivery_order_left' => 'DO Sent',
                    'DO_confirmed' => 'DO Confirmed',
                    'freight_confirmed' => 'Freight Released Confirmed',
                    'customs_released' => 'Customs Released Confirmed',
                    'released_at_terminal' => 'Released At Terminal',
                    'pending_refund' => 'Pending Refund',
                    'delivered' => 'Delivered',
                    'billed' => 'Billed',
                    'cancelled' => 'Cancelled'
                ];

                $arrkeys = array_keys($arr);

                foreach( $arrkeys as $ar ){
                    if( \DB::table('shipment_milestones')->where('name',$ar)->exists() ){
                        unset($arr[$ar]);
                    }
                }

                return $arr;
            })
            ->onlyOnForms()
            ->searchable()
            ->default($this->name)
            ->withMeta(['value' => $this->name])
            ->displayUsingLabels()
            ->required(),
            Textarea::make('Instructions', 'instructions')->required(),
            Textarea::make('General Information', 'general_information'),
        ];
    }

    public function fieldsForUpdate(){
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Milestone', 'name')
            ->default($this->name)
            ->readonly(),
            Textarea::make('Instructions', 'instructions')->required(),
            Textarea::make('General Information', 'general_information'),
        ];
    }

    public static function availableForNavigation(Request $request)
    {
       
        $checkRoles = $request->user()->roles;
        
        $debugbar = '\Debugbar';

        if (class_exists($debugbar)) {
            \Debugbar::info("ROLES", $checkRoles);
        }

        $role = 'Super Admin';
        $forNavigate = false;
        foreach ($checkRoles as $key => $checkRole) {
            if($checkRole->name === $role){
                $forNavigate = true;
                break;
            }
        }

        return $forNavigate;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
