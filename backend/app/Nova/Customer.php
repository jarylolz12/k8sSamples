<?php 
namespace App\Nova;

use Telex\CustomerTelex\CustomerTelex;
use Vic\CustomerDefaultRequirementsField\CustomerDefaultRequirementsField;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Kenetashi\AccountManagerSpecialSelect\AccountManagerSpecialSelect;
use Kenetashi\SaleAgentSelect\SaleAgentSelect;
use Laravel\Nova\Fields\HasMany;
use Illuminate\Support\Facades\Auth;
use Kenetashi\MultipleSelectField\MultipleSelectField;
use Fourstacks\NovaRepeatableFields\Repeater;
use Illuminate\Support\Facades\Validator;
use Tanvirofficials\CustomFileField\CustomFileField;
use Illuminate\Support\Facades\Storage;
use Kenetashi\AccountManagerOldField\AccountManagerOldField;
use Shifl\CustomerQuickbooksField\CustomerQuickbooksField;
use Kenetashi\CustomerAccountRepSort\CustomerAccountRepSort;
use Tanvirofficials\ConnectedTruckers\ConnectedTruckers;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Kenetashi\Announcement\Announcement;
use Kenetashi\CustomerBillingSection\CustomerBillingSection;
use App\Custom\Traits\ResourceTrait;
use Juliverdevshifl\CustomerTermField\CustomerTermField;
use Kenetashi\CustomsApprovalAddition\CustomsApprovalAddition;

class Customer extends Resource
{
    use TabsonEdit;
    use ResourceTrait;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Customer';

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

    private function requireRuleCallback($attribute, $value, $fail, $message) {
        if ( $value === '[]' || $value === null || $value === 'null' ) {
            return true;
            //return $fail($message);
        } else {
            return true;
        }
    }

    private function requireNonShiflRule($attribute, $value, $fail, $message) {
        if ( $value === '[]' || $value === null || $value === 'null' ) {
           // return $fail($message);
            return true;
        } else {
            $pass = false;
            $setValue = is_countable($value) ? $value : [];
            
            if ( count($setValue) > 0 ) {
                foreach( $setValue as $v ) {
                    $getDomain = explode('@',$v);
                    $getDomain = explode('.',$getDomain[1]);
                    $getDomain = $getDomain[0];
                    $getDomain = strtolower($getDomain);

                    if ( $getDomain !== 'shifl' )
                        $pass = true;
                }
                if ( !$pass ) {
                    return $fail('Please make sure to include non shifl email as well.');
                } else {
                    return true;
                }
            }
        }
    }

    private function requireOnlyShiflRule($attribute, $value, $fail, $message) {
        if ( $value === '[]' || $value === null || $value === 'null' ) {
            //return $fail($message);
            return true;
        } else {
            $pass = 0;
            $length = is_countable($value) ? count($value) : 0;
            $setValue = is_countable($value) ? $value : [];
            
            if ( count($setValue) > 0 ) {
                $valid_extensions = ['com', 'cn'];
                
                foreach( $setValue as $v ) {
                    $getDomain = explode('@',$v);
                    $getDomain = explode('.',$getDomain[1]);
                    $com_cn = $getDomain[1];
                    $getDomain = $getDomain[0];
                    $getDomain = strtolower($getDomain);

                    if ( $getDomain == 'shifl' && in_array($com_con, $valid_extensions))
                        $pass++;
                }

                if ( $pass === count($setValue) ) {
                    return true;
                } else {
                    return $fail('Please make sure to include shifl emails.');
                }
            }
        }
    }
    
    public function fields($request) {
        if ( $this->isIndex($request)) {
            return $this->defaultFields($request);
        } else {
            return [
                (new Tabs('Customer Details', [
                    'Header Information' => [
                        Text::make('Company Name', 'company_name')->sortable(),
                        Number::make('Number Of Shipments', function($value){
                            $counts = \App\Shipment::where('customer_id', $value->id)->where('booking_confirmed', 1)->where('is_tracking_shipment', 0)->count();
                            return $counts;
                        })->onlyOnDetail(),
                        Text::make('Address', 'address')->hideFromIndex(),
                        Text::make('Phone Number', 'phone'),
                        Text::make('EIN', 'ein')->rules('nullable', function ($attribute, $value, $fail) {
                            $validator = Validator::make(
                                ['ein' => $value],
                                [
                                    'ein' => ['regex:/\d{2}-\d{7}[\dA-Za-z]?[\dA-Za-z]?|\d{6}-\d{5}|\d{3}-\d{2}-\d{4}/'],
                                ]
                            );
                            if ($validator->fails()) {
                                $fail('The ' . $attribute . ' field is not in tax id format.');
                            }
                        })->help(
                            'Format: xx-xxxxxxx, xx-xxxxxxxx, xx-xxxxxxxx, xxxxxx-xxxxx, xxx-xx-xxxx'
                        )->hideFromIndex(),
                        Text::make('Import Names', function(){
                            $names = (new \App\ImportNames)->displayImportName($this->id);
                            if($names){
                                $names = implode(", ", $names);
                            }
                            return $names;
                        })->asHtml()->onlyOnDetail(),

                        Textarea::make('Requirements', 'requirements')->alwaysShow(),
                        CustomsApprovalAddition::make('Customs Approval', 'customs_approval')
                                   ->loadData([
                                        'id' => $this->id,
                                        'documents_from' => $this->documents_from,
                                        'approval_requirements' => $this->approval_requirements,
                                        'approved_hs_codes' => $this->approved_hs_codes
                                   ])
                                   ->hideWhenCreating()
                                    ->hideFromIndex(),
                        CustomerDefaultRequirementsField::make('Default Requirements', 'customer_default_requirements')->hideFromIndex(),

                        CustomerTelex::make('Customer Telex Field', 'telex_release')->hideFromIndex(),
                        CustomerBillingSection::make('Billing', 'customer_billing_section')->initFields([
                            'billing_notes' => $this->billing_notes
                        ])->hideFromIndex(),
                        Boolean::make('Confirmed Default Requirements', 'confirmed_default_requirements')->hideFromIndex(),

                        AccountManagerOldField::make('Account Manager main', 'managers')
                                            ->initFields()
                                            ->hideFromIndex()
                                            ->rules('required'),
                        AccountManagerSpecialSelect::make('Account Managers', 'offices_managers')
                            ->initFields($this)
                            ->hideFromIndex()
                            ->rules(new \App\Rules\OfficeManagerRule),
                        SaleAgentSelect::make('Sales Representatives', ',sale_persons')->initFields($this),
                        Repeater::make('Email Recipients', 'emails')->addField([
                            'name' => 'email',
                            'type' => 'email',
                            'placeholder' => 'Customer Email Recipient',
                        ])->addButtonText('Add Email Recipient')->hideFromIndex(),
                        MultipleSelectField::make('Booking email recipients', 'booking_email_recipients')
                                        ->loadOptions($this->emails, 'No Booking Email added yet.')
                                        ->hideFromIndex(),
                        // Number::make('Credit Term Freight', 'credit_term_freight')
                        // ->min(0)
                        // ->step(1)
                        // ->rules('numeric', 'min:0')
                        // ->default(0),
                        CustomerTermField::make('Credit Term Freight')
                        ->initFields([ 'name' => 'Term','value' => $this->qboTerm() ])
                        ->hideWhenCreating()
                        ->hideWhenUpdating(),
                        Number::make('Credit Term Duty', 'credit_term_duty')->min(0)->step(1)->rules('numeric', 'min:0')->default(0),
                        CustomFileField::make('LOI Document', 'loi')->store(function (Request $request, $model) {
                            $originalName = 'customers/'. md5(time() . $request->file('loi')->getClientOriginalName()) . '.' . $request->file('loi')->guessExtension();
                            Storage::disk('local')->putFileAs('/public', $request->file('loi'), $originalName);
                            return [
                                'loi' => $originalName
                            ];
                        })
                        ->hideFromIndex(),
                        CustomFileField::make('POA Document', 'poa')->store(function (Request $request, $model) {
                            $originalName = 'customers/'. md5(time() . $request->file('poa')->getClientOriginalName()) . '.' . $request->file('poa')->guessExtension();
                            Storage::disk('local')->putFileAs('/public', $request->file('poa'), $originalName);
                            return [
                                'poa' => $originalName
                            ];
                        })
                        ->hideFromIndex(),
                        CustomFileField::make('SS-4', 'ssfour_doc')->store(function (Request $request, $model) {
                            $final_display_name = '';
                            if ($request->has('ssfour_doc')) {
                                if (!is_null($file = $request->file('ssfour_doc')) && $file->isValid()) {
                                    $hash_file = md5($request->file('ssfour_doc')->getClientOriginalName() . now());
                                    $final_display_name = $hash_file . '.' . $request->file('ssfour_doc')->guessExtension();
                                    $final_name = 'shipment/customer_additions/'.$final_display_name;
                                    Storage::disk('local')->putFileAs('/public', $request->file('ssfour_doc'), $final_name);
                                }
                            }
                            return [
                                'ssfour_doc' => $final_display_name
                            ];
                        })
                        ->hideFromIndex(),
                        CustomFileField::make('W-9', 'wnine_doc')->store(function (Request $request, $model) {
                            $final_display_name = '';
                            if ($request->has('wnine_doc')) {
                                if (!is_null($file = $request->file('wnine_doc')) && $file->isValid()) {
                                    $hash_file = md5($request->file('wnine_doc')->getClientOriginalName() . now());
                                    $final_display_name = $hash_file . '.' . $request->file('wnine_doc')->guessExtension();
                                    $final_name = 'shipment/customer_additions/'.$final_display_name;
                                    Storage::disk('local')->putFileAs('/public', $request->file('wnine_doc'), $final_name);
                                }
                            }
                            return [
                                'wnine_doc' => $final_display_name
                            ];
                        })
                        ->hideFromIndex(),
                        CustomerQuickbooksField::make('Link to QuickBooks Customer', 'qbo_customer')
                        ->rules('nullable', function ($attribute, $value, $fail) {
                            $obj = json_decode($value);
                            // \Log::info(json_encode($obj));
                            if (isset($obj->createQBOCustomer)) {
                                // \Log::info(json_encode($obj));
                                /*if($obj->createQBOCustomer === false){
                                    return $fail('QuickBooks customer is required.');
                                }*/
                            }else {
                                $obj = json_decode($value);
                                // \Log::info($obj);
                                /*if(!isset($obj->customer->Id) && !isset($obj->realm_id)){
                                    return $fail('QuickBooks customer is required.');
                                }*/
                            }
                        }),

                        Text::make('Created By', 'created_by')
                            //->withMeta(['type' => 'hidden', 'value' => \auth()->user()->email])
                            ->hideWhenCreating()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),

                        Text::make('Last Updated By', 'last_updated_by')
                            ->hideWhenCreating()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),

                        Text::make('Last Updated', 'last_updated')
                            ->withMeta(['type' => 'hidden', 'value' => $this->updated_at ? $this->updated_at->format('Y-m-d') : 'Y-m-d'])
                            ->hideWhenCreating()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),
                        //ConnectedTruckers::make(),
                        BelongsToMany::make('Suppliers')->searchable(),
                        BelongsToMany::make('Buyer')->searchable(),
                        BelongsToMany::make('CustomerAdmins')->searchable(),
                    ],
                    'Recipients' => [
                        MultipleSelectField::make('All Emails','all_email_emails')
                            ->loadOptions($this->all_email_emails, 'No Email added yet.', 'customer', $this->id)
                            ->rules('required', function ($attribute, $value, $fail){
                                $this->requireRuleCallback($attribute, $value, $fail, 'The all emails field should contain at least 1 email.');
                            })
                            ->hideFromDetail()
                            ->hideFromIndex(),
                        MultipleSelectField::make('Booking and Updated Booking','booking_and_updated_emails')
                               ->loadOptions($this->booking_and_updated_emails, 'No Booking and Update Booking email added yet.','customer', $this->id)
                                ->rules('required', function($attribute, $value, $fail) {
                                    $this->requireNonShiflRule($attribute, $value, $fail, 'Booking and Updated Booking field is required.');
                                })
                               ->hideFromIndex(),
                        MultipleSelectField::make('Booking Confirmation','booking_confirmation_emails')
                               ->loadOptions($this->booking_confirmation_emails, 'No Booking Confirmation email added yet.','customer', $this->id)
                               ->rules('required', function($attribute, $value, $fail) {
                                $this->requireNonShiflRule($attribute, $value, $fail, 'Booking Confirmation field is required.');
                                })
                               ->hideFromIndex(),
                        MultipleSelectField::make('Agent and Updated Booking', 'agent_booking_updated_emails')
                               ->loadOptions($this->agent_booking_updated_emails, 'No Agent Booking and Updated Email added yet.','customer', $this->id)
                               ->rules('required', function ($attribute, $value, $fail) {
                                    $this->requireOnlyShiflRule($attribute, $value, $fail, 'Agent and Updated Booking field is required.');
                                })
                               ->hideFromIndex(),
                        MultipleSelectField::make('Agent Booking Confirmation','agent_booking_confirmation_emails')
                               ->loadOptions($this->agent_booking_confirmation_emails, 'No Agent Booking Confirmation email added yet.','customer', $this->id)
                               ->rules('required', function ($attribute, $value, $fail) {
                                    $this->requireOnlyShiflRule($attribute, $value, $fail, 'Agent Booking Confirmation field is required.');
                                })
                               ->hideFromIndex(),
                        MultipleSelectField::make('Departure Notice', 'departure_notice_emails')
                               ->loadOptions($this->departure_notice_emails, 'No Departure Notice email added yet.','customer', $this->id)
                               ->rules('required', function($attribute, $value, $fail) {
                                $this->requireNonShiflRule($attribute, $value, $fail, 'Departure Notice email is required.');
                                })
                               ->hideFromIndex(),
                        MultipleSelectField::make('Arrival Notice', 'arrival_notice_emails')
                               ->loadOptions($this->arrival_notice_emails, 'No Arrival Notice email added yet.','customer', $this->id)
                               ->rules('required', function($attribute, $value, $fail) {
                                $this->requireNonShiflRule($attribute, $value, $fail, 'Arrival Notice email is required.');
                                })
                               ->hideFromIndex(),
                        MultipleSelectField::make('Delivery Order', 'delivery_order_emails')
                               ->loadOptions($this->delivery_order_emails, 'No Delivery Order email added yet.','customer', $this->id)
                               ->hideFromIndex(),
                        MultipleSelectField::make('ETA Alert', 'eta_alert_emails')
                               ->loadOptions($this->eta_alert_emails, 'No ETA Alert email added yet.','customer', $this->id)
                               ->hideFromIndex(),
                        MultipleSelectField::make('ETA Alert for trucker', 'eta_alert_trucker_emails')
                               ->loadOptions($this->eta_alert_trucker_emails, 'No ETA Alert for trucker email added yet.','customer', $this->id)
                               ->hideFromIndex(),
                    ]],
                ))->withToolbar()
            ];
        } 
    }
    
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function defaultFields($request)
    {

        return [
            Text::make('Company Name', 'company_name')->sortable(),
            Text::make('Address', 'address')->hideFromIndex(),
            Text::make('Phone Number', 'phone'),
            Text::make('EIN', 'ein')->rules('nullable', function ($attribute, $value, $fail) {
                $validator = Validator::make(
                    ['ein' => $value],
                    [
                        'ein' => ['regex:/\d{2}-\d{7}[\dA-Za-z]?[\dA-Za-z]?|\d{6}-\d{5}|\d{3}-\d{2}-\d{4}/'],
                    ]
                );
                if ($validator->fails()) {
                    $fail('The ' . $attribute . ' field is not in tax id format.');
                }
            })->help(
                'Format: xx-xxxxxxx, xx-xxxxxxxx, xx-xxxxxxxx, xxxxxx-xxxxx, xxx-xx-xxxx'
            )->hideFromIndex(),

            // Text::make('Import Names', function(){
            //     $names = (new \App\ImportNames)->getImportNameByCustomerId($this->id)->pluck('import_name');
            //     return $names;
            // })->onlyOnDetail(),

            Text::make('Import Names', function(){
                $names = (new \App\ImportNames)->displayImportName($this->id);
                if($names){
                    $names = implode(", ", $names);
                }
                return $names;
            })->asHtml()->onlyOnDetail(),

            Textarea::make('Requirements', 'requirements')->alwaysShow(),
            CustomerDefaultRequirementsField::make('Default Requirements', 'customer_default_requirements')->hideFromIndex(),
            CustomerTelex::make('Customer Telex Field', 'telex_release')->hideFromIndex(),
            Boolean::make('Confirmed Default Requirements', 'confirmed_default_requirements')->hideFromIndex(),

            AccountManagerOldField::make('Account Manager main', 'managers')
                                  ->initFields()
                                  ->hideFromIndex()
                                  ->rules('required'),
            AccountManagerSpecialSelect::make('Account Managers', 'offices_managers')
                ->initFields($this)
                ->hideFromIndex()
                ->rules(new \App\Rules\OfficeManagerRule),
            //->hideWhenCreating()
            //->hideFromDetail()
            //->hideFromIndex(),
            SaleAgentSelect::make('Sales Representatives', ',sale_persons')->initFields($this),
            Repeater::make('Email Recipients', 'emails')->addField([
                'name' => 'email',
                'type' => 'email',
                'placeholder' => 'Customer Email Recipient',
            ])->addButtonText('Add Email Recipient')->hideFromIndex(),
            MultipleSelectField::make('Booking email recipients', 'booking_email_recipients')
                               ->loadOptions($this->emails, 'No Booking Email added yet.')
                               ->hideFromIndex(),
            // Number::make('Credit Term Freight', 'credit_term_freight')->min(0)->step(1)->rules('numeric', 'min:0')->default(0),
            CustomerTermField::make('Credit Term Freight')
            ->initFields([ 'name' => 'Term', 'value' => $this->qboTerm() ])
            ->hideWhenCreating()
            ->hideWhenUpdating(),
            Number::make('Credit Term Duty', 'credit_term_duty')->min(0)->step(1)->rules('numeric', 'min:0')->default(0),
            CustomFileField::make('LOI Document', 'loi')->store(function (Request $request, $model) {
                $originalName = 'customers/'. md5(time() . $request->file('loi')->getClientOriginalName()) . '.' . $request->file('loi')->guessExtension();
                // $originalName =  basename($request->file('mbl_copy')->getClientOriginalName(), '.'. $request->file('mbl_copy')->guessExtension()) . '_'.$this->id.'_mbl.' .$request->file('mbl_copy')->guessExtension();

                Storage::disk('local')->putFileAs('/public', $request->file('loi'), $originalName);
                return [
                    'loi' => $originalName
                ];
            })
            ->hideFromIndex(),
            CustomFileField::make('POA Document', 'poa')->store(function (Request $request, $model) {
                $originalName = 'customers/'. md5(time() . $request->file('poa')->getClientOriginalName()) . '.' . $request->file('poa')->guessExtension();
                // $originalName =  basename($request->file('mbl_copy')->getClientOriginalName(), '.'. $request->file('mbl_copy')->guessExtension()) . '_'.$this->id.'_mbl.' .$request->file('mbl_copy')->guessExtension();

                Storage::disk('local')->putFileAs('/public', $request->file('poa'), $originalName);
                return [
                    'poa' => $originalName
                ];
            })
            ->hideFromIndex(),
            //Boolean::make('Default Duty Layout', 'default_duty_layout')->hideFromIndex(),
            // BelongsTo::make('Supplier')
            //->rules('required')
            //->hideWhenCreating()
            //->hideFromDetail()
            //->hideFromIndex(),
            //BelongsToMany::make('AccountManagers')->searchable(),

            CustomerQuickbooksField::make('Link to QuickBooks Customer', 'qbo_customer')
            ->rules('nullable', function ($attribute, $value, $fail) {
                $obj = json_decode($value);
                // \Log::info(json_encode($obj));
                if (isset($obj->createQBOCustomer)) {
                    // \Log::info(json_encode($obj));
                    /*if($obj->createQBOCustomer === false){
                        return $fail('QuickBooks customer is required.');
                    }*/
                }else {
                    $obj = json_decode($value);
                    // \Log::info($obj);
                    /*if(!isset($obj->customer->Id) && !isset($obj->realm_id)){
                        return $fail('QuickBooks customer is required.');
                    }*/
                }
            }),

            Text::make('Created By', 'created_by')
                //->withMeta(['type' => 'hidden', 'value' => \auth()->user()->email])
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Text::make('Last Updated By', 'last_updated_by')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex(),

            Text::make('Last Updated', 'last_updated')
                ->withMeta(['type' => 'hidden', 'value' => $this->updated_at ? $this->updated_at->format('Y-m-d') : 'Y-m-d'])
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex(),

            ConnectedTruckers::make(),

            BelongsToMany::make('Suppliers')->searchable(),
            BelongsToMany::make('Buyer')->searchable(),
            BelongsToMany::make('CustomerAdmins')->searchable(),
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
            new CustomerAccountRepSort
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->has('roles')) {
            $checkRolesTable = \DB::table('roles')->where('name', 'Account Manager')->first();
            if (isset($checkRolesTable->id)) {
                $modelRoles = \DB::table('model_has_roles')->where('model_id', $request->user()->id)
                    ->where('role_id', $checkRolesTable->id)
                    ->first();
                if (isset($modelRoles->role_id)) {
                    /*
                    return $query->whereHas('accountManagers',function($q) use ($request){
                        $q->where('user_id',$request->user()->id);
                    });*/
                    /* return $query->where('managers', $request->user()->id); */
                    return $query;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }




    public static function relatableQuery(NovaRequest $request, $query)
    {

        //return parent::relatableQuery($request, $query);
    }


    /*
    public static function relatableQuery(NovaRequest $request, $query)
    {

        return $query->whereHas('roles',function($q) {
            $q->where('name','Account Manager');
        });
        //return parent::relatableQuery($request, $query);
    }*/


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
