<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use App\Rules\ValidateExistingItemNumber;

class Item extends Resource
{

    public static $displayInNavigation = true;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Item';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'item_num';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'item_num', 'customer_id', 'description', 'classification_code', 'duty_rate'
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
            //ID::make()->sortable(),
            BelongsTo::make('Customer'),

            Text::make('Item Number', 'item_num')
                ->sortable()
                ->hideWhenUpdating()
                ->help("**Leave this field blank if you want to Auto Generate Item Number.**")
                ->rules('numeric','nullable',new ValidateExistingItemNumber),

            Textarea::make('Description', 'description'),

            Text::make('Classification', 'classification_code')
                ->sortable(),

            Number::make('Duty Rate', 'duty_rate'),

            BelongsToMany::make('Suppliers')->searchable(),
            
            BelongsToMany::make('Purchase Order')
            ->fields(function(){
                return [
                    Text::make('quantity')
                    ->displayUsing(function(){
                        return isset($this->pivot) ? $this->pivot->quantity : '-';
                    }),
                ];
            }),
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
