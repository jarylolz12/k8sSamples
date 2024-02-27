<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\asHtml;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Terminal extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Terminal';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public function title()
    {
        return $this->name;
    }

    public function subtitle()
    {
        return 'Firms Code : ' . $this->firms_code;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','name','firms_code', 'website', 'phone_number'
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
            Text::make('Terminal Name', 'name')->rules('required')->sortable(),


            Text::make('Website', 'website')->rules('required')->hideFromIndex()->hideFromDetail(),


            Text::make('Website', function () {
                $website = $this->website;
                return "<div class='text-left text-left'>
                        <span>
                            <span>
                                <a href='{$website}' class='no-underline dim text-primary font-bold'>
                                    {$website}
                                </a>
                            </span>
                        </span>
                    </div>";
            })->asHtml()->rules('required')->hideFromIndex()->sortable(),


            Text::make('Phone Number', 'phone_number')->rules('required')->sortable(),


            Text::make('Firms Code')->rules('required','unique:terminals,firms_code,'.$this->id.',id')->sortable(),
            // Place::make('Address'),
            Text::make('Address')
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
