<?php

namespace App\Nova;

use Fourstacks\NovaRepeatableFields\Repeater;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;

class Agent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Coloaders::class;

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
        'id','name','email','number','address','website','notes_box'
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
            ID::make(__('ID'), 'id')->sortable()
                ->hideFromIndex(),
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email', 'email')
                ->rules('required' ,'email', 'nullable', 'max:254')
                ->creationRules('unique:coloaders,email')
                ->updateRules('unique:coloaders,email,{{resourceId}}'),

            Repeater::make('Booking Email Recipients', 'emails')->addField([
                'name' => 'email',
                'type' => 'email',
                'placeholder' => 'Email Recipient' ,
            ])->addButtonText('Add Email Recipient')->hideFromIndex(),

            Text::make('Number', 'number')
                ->rules('max:254'),

            Text::make('Address', 'address')
                ->rules('max:254'),

            Text::make('Website', 'website')
                ->rules('max:254'),

            Textarea::make('Notes box', 'notes_box')->alwaysShow(),
            Boolean::make('For Testing', 'for_testing')->sortable(),

            BelongsTo::make('Country'),
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

    public static function perPageOptions()
    {
        return [50, 100, 150];
    }
}
