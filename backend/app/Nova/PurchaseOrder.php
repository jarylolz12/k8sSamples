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
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Ezea\PoItemField\PoItemField;

use App\Rules\ValidateExistingPONumber;

class PurchaseOrder extends Resource
{
    use HasConditionalContainer;

    public static $displayInNavigation = true;

    public static $model = 'App\PurchaseOrder';

    public function title()
    {
        
        return 'Po Number : ' .$this->po_num;
    }

    public function subtitle()
    {
        return 'Customer-( ' . $this->customer->company_name.' ) , Supplier-( '. $this->supplier->company_name . ' )';
    }

    public static $search = [
        'po_num',
    ];

    // public static function fill(NovaRequest $request, $model)
    // {
    //     return static::fillFields(
    //         $request, $model,
    //         (new static($model))->creationFieldsWithoutReadonly($request)->reject(function ($field) use ($request) {
    //             return in_array('ignoreOnSaving', $field->meta);
    //         })
    //     );
    // }

    public function fields(Request $request)
    {

        return [
            //ID::make()->sortable(),
            //BelongsTo::make('Customer','customer'),
            NovaBelongsToDepend::make('Customer')
                ->options(\App\Customer::all())
                ->rules('required'),

            Text::make('P.O. Number', 'po_num')
                ->sortable()
                ->hideWhenUpdating()
                ->rules('numeric','nullable',new ValidateExistingPONumber)
                ->help("**Leave this field blank if you want to Auto Generate P.O. Number.**"),

            //BelongsTo::make('Supplier','supplier'),

            // Boolean::make('Auto Generate P.O. Number','auto_generate')
            //     ->onlyOnForms()
            //     ->hideWhenUpdating()
            //     ->withMeta(['ignoreOnSaving']),

            // ConditionalContainer::make([
            //     Text::make('PO number', 'po_num')
            //     ->rules('numeric','nullable',new ValidateExistingPONumber)
            // ])->if('auto_generate === false'),

            NovaBelongsToDepend::make('Supplier')
            ->optionsResolve(function ($customer) {
                return $customer->suppliers;
            })
            ->dependsOn('Customer')
            ->rules('required'),

            BelongsToMany::make('Items')
            ->fields(function(){
                return [
                    Text::make('quantity')
                    ->displayUsing(function(){
                        return isset($this->pivot) ? $this->pivot->quantity : '-';
                    })
                ];
            })
            ->hideWhenCreating(),
            
            PoItemField::make('Items','po_items')
            ->hideFromIndex()
            ->hideFromDetail(),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}
