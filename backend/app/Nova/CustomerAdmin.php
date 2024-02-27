<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Fourstacks\NovaRepeatableFields\Repeater;
use Laravel\Nova\Fields\HasMany;
use Kenetashi\CustomerAdminCustomers\CustomerAdminCustomers;

class CustomerAdmin extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\CustomerAdmin';


    public static function label()
    {
        return 'Customer Admin';
    }


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];


    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'Customer Admin');
        });
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            /* Gravatar::make(), */
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

                // Repeater::make('Report Recipients', 'report_recipients')->addField([
                //     'name' => 'report_recipients' ,
                //     'type' => 'email',
                //     'placeholder' => 'Customer Report Recipients',
                // ])->addButtonText('Add Reports Recipient')->hideFromIndex(),

                Repeater::make('Shipment Report Recipients', 'report_recipients', function() use($request){ 
                    if(!$this->isCreate($request)){
                        $name = stripslashes(json_encode($this->report_recipients, true));
                        $name = str_replace('"{', '{', $name);
                        $this->report_recipients = str_replace('}"', '}', $name);
                        return $this->report_recipients;
                    }
                })->addField([
                    'name' => 'report_recipients' ,
                    'type' => 'email',
                    'placeholder' => 'Shipment Report Recipients', 
                ])->addButtonText('Add Reports Recipient')->hideFromIndex(),
            
            Repeater::make('ACH Report Recipients', 'ach_report_recipients')->addField([
                'name' => 'ach_report_recipients',
                'type' => 'email',
                'placeholder' => 'ACH Report Recipients',
            ])->addButtonText('Add ACH Reports Recipient')->hideFromIndex(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            //MorphToMany::make('Roles', 'roles', \Vyuldashev\NovaPermission\Role::class),
            //MorphToMany::make('Roles', 'permissions', \Vyuldashev\NovaPermission\Permission::class),

            CustomerAdminCustomers::make('Customers', 'customersApi')->onlyOnDetail(),
            //BelongsToMany::make('Customers', 'customersApi'),

            // HasOne::make('Email Report Schedule','EmailReportSchedule'),
            HasMany::make('Email Report Schedule','EmailReportSchedule'),
            Boolean::make('For Testing', 'for_testing')->sortable(),

        ];
    }

    public function isCreate($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\NovaRequest &&
                $request->editMode === 'create';
    }

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
        return [
            (new Actions\DownloadReport)
                ->onlyOnDetail()
                ->confirmText('Are you sure? ')
                ->confirmButtonText('Download')
                ->cancelButtonText("Cancel"),
            (new Actions\SendReport)
                ->onlyOnDetail()
                ->confirmText('Are you sure? ')
                ->confirmButtonText('Send')
                ->cancelButtonText("Cancel"),
        ];
    }
}
