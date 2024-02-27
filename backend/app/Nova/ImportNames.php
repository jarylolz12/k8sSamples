<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Image;
use Carbon\Carbon;
use Noor\CountryStateCityField\CountryStateCityField;

class ImportNames extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ImportNames';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'import_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'import_name'
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
            // ID::make(__('ID'), 'id')->sortable(),
            Image::make('Image', 'image')
            ->disk('local')
            ->path('public/import-names/images')
            ->storeAs(function (Request $request) {
                return Carbon::now()->timestamp . '_' . $request->customer_id . '_' . $request->image->getClientOriginalName();
            }),
            Text::make('Import Name', 'import_name')
                ->sortable()
                ->rules('required', 'max:254'),
            Text::make('EIN', 'ein')->rules('nullable', function($attribute, $value, $fail){
                $validator = Validator::make(
                    ['ein' => $value],
                    [
                        'ein' => ['regex:/\d{2}-\d{7}[\dA-Za-z]?[\dA-Za-z]?|\d{6}-\d{5}|\d{3}-\d{2}-\d{4}/'],
                    ]
                );
                if($validator->fails()){
                    $fail('The ' . $attribute . ' field is not in tax id format.');
                }
            })->help(
                'Format: xx-xxxxxxx, xx-xxxxxxxx, xx-xxxxxxxx, xxxxxx-xxxxx, xxx-xx-xxxx'
            )->hideFromIndex(),

            Text::make('Email', 'email')->rules('required','email'),

            BelongsTo::make('Customer', 'customer')
                ->required()
                ->searchable(),  
             
            Text::make('Address', 'address')->hideFromindex(),
            Text::make('Phone Number', 'phone_number')->hideFromIndex(),
            Boolean::make('Activated', 'status')->default(true),
            CountryStateCityField::make('CountryStateCity', 'country_state_city')->initFields([
                "country" => $this->country,
                "state" => $this->state,
                "city" => $this->city
            ])->onlyOnForms(),
            Text::make('Country', 'country')->hideWhenCreating()->hideWhenUpdating(),
            Text::make('State', 'state')->hideWhenCreating()->hideWhenUpdating(),
            Text::make('City', 'city')->hideWhenCreating()->hideWhenUpdating(),
            Text::make('Zip Code', 'zipcode')->hideFromIndex(),
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
}