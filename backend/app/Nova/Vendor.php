<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Kenetashi\VendorPaymentAccounts\VendorPaymentAccounts;
use Kenetashi\LinkToBrexAccount\LinkToBrexAccount;
use Kenetashi\SelectJsonValueField\SelectJsonValueField;

use Juliverdevshifl\SelectCountry\SelectCountry;
use Juliverdevshifl\SelectCity\SelectCity;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use App\Rules\CheckVendorNameBrex;
use App\Rules\CheckVendorNameQuickbook;
use App\Rules\CheckVendorQuickbook;
use App\Rules\CheckVendorBrex;
use Illuminate\Support\Facades\Auth;

class Vendor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Vendor';

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
        'email',
        'phone',
        'realm_id'

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
            /* ID::make()->sortable(), */
            /*
            Text::make('Suffix', 'suffix')
                ->sortable(),*/
            /*
            Text::make('Title', 'title')
                ->creationRules('required', 'string', 'max:16')
                ->sortable(),

            Text::make('Given Name', 'given_name')
                ->sortable(),
            Text::make('Family Name', 'family_name')
                ->sortable(),*/

            Text::make('Company Name', 'company_name')
                ->updateRules('required')
                ->creationRules('required',new CheckVendorNameQuickbook)
                ->sortable(),
            /*
            Text::make('Tax Identifier', 'tax_identifier')
                ->sortable(),
            Text::make('Account #', 'account_number')
                ->sortable(),*/
            SelectCountry::make('Country','country')->initFields([ 'selected' => $this->country ]),
            SelectCity::make('City','city')->initFields([ 'selected' => $this->city, 'api_url' => config('app.url').'/nova-vendor/select-city/get/' ]),
            // SelectJsonValueField::make('Country', 'country')->jsonField('country')->hideFromIndex(),
            // SelectJsonValueField::make('City', 'city')->jsonField('city')->hideFromIndex(),
            Text::make('Line 1', 'line1')
                ->hideFromIndex()
                ->sortable(),
            Text::make('Line 2', 'line2')
                ->hideFromIndex()
                ->sortable(),
            Text::make('Line 3', 'line3')
                ->hideFromIndex()
                ->sortable(),
            Text::make('Postal Code', 'postal_code')
                ->hideFromIndex()
                ->sortable(),
            Text::make('Country Subdivision Code', 'country_subdivision_code')
                // ->creationRules('min:2','max:2')
                ->hideFromIndex()
                ->sortable(),
            /*
            Text::make('Print on check name', 'print_on_check_name')
                ->sortable(),*/
            Text::make('Email Address', 'email')
                // ->creationRules('required', 'unique:vendors', new CheckVendorQuickbook)
            // ->creationRules('required','unique:vendors', new CheckVendorQuickbook)
                ->sortable(),
            Text::make('Phone Number', 'phone')
                ->sortable(),
            Text::make('Quickbooks Company', function () {
                $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
                if ( $currentUserRealmId==$this->realm_id ) {
                    $checkCompany = \App\QuickbooksCompany::where('company_realm_id', $currentUserRealmId)->first();
                    $companyName = (isset($checkCompany->company_name)) ? $checkCompany->company_name : '';
                    return $companyName;
                } else {
                    return '<span style="color: red;">Not connected</span>';
                }
            })->asHtml(),
            LinkToBrexAccount::make('Link to Brex Vendor','brex_id')->initFields()->hideFromIndex()
            //VendorPaymentAccounts::make('Payment Accounts', 'payment_accounts')->hideFromIndex()
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
        return [new Filters\VendorOwn];
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