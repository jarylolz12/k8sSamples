<?php

namespace App\Nova;

use App\Nova\Filters\CustomsBrokerCustomer;
use Illuminate\Http\Request;
use Kenetashi\MultipleSelectField\MultipleSelectField;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Sloveniangooner\SearchableSelect\SearchableSelect;

class CustomsBroker extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\CustomsBroker::class;

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
        'id','name','emails'
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
            ID::make(__('ID'), 'id')->hideFromIndex()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            MultipleSelectField::make('Emails', 'emails')
                ->loadOptions($this->emails, 'No email added yet.')
                ->hideFromIndex(),
            
            Text::make('Emails', function(){
                $emails = json_decode($this->emails) ?? [];
                return implode(", ", $emails);
            })->onlyOnIndex(),

            SearchableSelect::make('Customer', 'customer_id')->resource(\App\Nova\CustomDropdowns\CustomerDropdown::class)
                ->rules('required')
                ->hideWhenUpdating()
                ->displayUsingLabels(),
                
            // Select::make('Customer', 'customer_id')
            //     ->rules('required')
            //     ->options(\App\Customer::all()->pluck('company_name', 'id'))
            //     ->hideWhenUpdating()
            //     ->searchable()->displayUsingLabels()
            Boolean::make('For Testing', 'for_testing')->sortable(),
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
        return [
            new CustomsBrokerCustomer()
        ];
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
    public static function perPageOptions()
    {
        return [50, 100, 150];
    }
}
