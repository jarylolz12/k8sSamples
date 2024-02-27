<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Fourstacks\NovaRepeatableFields\Repeater;
use Laravel\Nova\Fields\Select;
class Trucker extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Trucker';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public function subtitle()
    {
        return $this->email;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name','email'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make()->sortable(),
            Text::make('Name')->sortable()->rules('required'),

            Text::make('Email Address', 'email')->rules('required', 'email'),
            Select::make('Trucking Company Key', 'trucking_company_key')
            ->options($this->resolveTruckersOptions())
            ->displayUsingLabels(),        
            Repeater::make('Email Recipients', 'email_recipient')->addField([
                'name' => 'email' ,
                'type' => 'email',
                'placeholder' => 'Trucker Email Recipient',
            ])->addButtonText('Add Email Recipient')->hideFromIndex(),
        ];
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

    public function resolveTruckersOptions(){
       return \DB::connection('mysql-trucking')->table('truckers')
         ->where('trucking_company_key','<>','')->pluck('trucking_company_key', 'trucking_company_key');
    }
}
