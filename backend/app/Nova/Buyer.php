<?php

namespace App\Nova;

use Cyrus\CustomersField\CustomersField;
use Illuminate\Http\Request;
use Kenetashi\MultipleSelectField\MultipleSelectField;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Vishalmarakana\BuyerConnect\BuyerConnect;
use Laravel\Nova\Fields\Boolean;

class Buyer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Buyer';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'company_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'company_name',
        'address',
        'phone',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $customerId = '';
        if($request->get('viaResourceId')) $customerId = $request->get('viaResourceId');

        return [
            /* ID::make()->sortable(), */
            Text::make('Company Name', 'company_name')
                ->sortable(),
            Text::make('Address', 'address')->hideFromIndex(),
            Text::make('Phone Number', 'phone')->hideFromIndex(),
            CustomersField::make('Customers', 'custom_customers')->hideFromIndex()->onlyOnForms()->hideWhenUpdating()->rules('required', function ($attribute, $value, $fail) {
                if ($value === "null") {
                    return $fail('The customers must contain atleast 1.');
                }
            }),
            MultipleSelectField::make('Emails', 'emails')
                ->loadOptions($this->emails, 'No email added yet.')
                ->hideFromIndex(),
            BelongsToMany::make('Customers')->searchable(),
            BuyerConnect::make('')->initFields(
                ['buyerId' => $this->id, 'customerId' => $customerId, 'connectedCustomerId' => $this->connected_customer]
            )->onlyOnIndex(),
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
