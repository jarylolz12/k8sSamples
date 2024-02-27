<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Imbhavin95\SoTabContent\SoTabContent;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\File;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Vishalmarakana\BookingSentStatusMilestone\BookingSentStatusMilestone;
use Vishalmarakana\CancelShipmentCheckbox\CancelShipmentCheckbox;
use Vishalmarakana\ShipmentContainersLoadedCheckbox\ShipmentContainersLoadedCheckbox;
use Vishalmarakana\ShipmentNotes\ShipmentNotes;
use Vishalmarakana\ShipmentSoReceivedCheckbox\ShipmentSoReceivedCheckbox;
use Vishalmarakana\SufficientCommercialDocs\SufficientCommercialDocs;
use Whitecube\NovaFlexibleContent\Flexible;
use Kenetashi\ShipmentCustomerDocuments\ShipmentCustomerDocuments;
use Kenetashi\ShipmentSupplierGroup\ShipmentSupplierGroup;
use Kenetashi\ShipmentScheduleGroup\ShipmentScheduleGroup;
use Kenetashi\ShipmentScheduleMultipleGroup\ShipmentScheduleMultipleGroup;
use Kenetashi\ShipmentSupplierLimitedGroup\ShipmentSupplierLimitedGroup;
use Kenetashi\ShipmentContainerGroup\ShipmentContainerGroup;
use Kenetashi\ShipmentContainerLimitedGroup\ShipmentContainerLimitedGroup;
use Kenetashi\ShipmentDeliverySaveDeliveryOrder\ShipmentDeliverySaveDeliveryOrder;
use Kenetashi\ShipmentArrivalNoticeSave\ShipmentArrivalNoticeSave;
use Kenetashi\ShipmentDepartureNoticeSave\ShipmentDepartureNoticeSave;
use Kenetashi\BookingsTabSaveGroup\BookingsTabSaveGroup;
use Kenetashi\CustomButton\CustomButton;
use Kenetashi\MblCopySurrendered\MblCopySurrendered;
use Kenetashi\ShipmentServicesSectionField\ShipmentServicesSectionField;
use NovaButton\Button;

use Thond1st\InfoSaveButton\InfoSaveButton;
use Thond1st\ChargesTab\ChargesTab;

use Illuminate\Support\Facades\Storage;
use App\Rules\EstimatedRule;
use App\Rules\FindDuplicateSupplier;
use App\ShipmentMilestone as ShipmentMilestoneModel;
use App\Document;
use Auth;
use Cyrus\CustomerField\CustomerField;
use Cyrus\CustomResourceToolBtn\CustomResourceToolBtn;
use Cyrus\EntryNetchbField\EntryNetchbField;
use Cyrus\ShipmentMilestoneComponent\ShipmentMilestoneComponent;
use Kenetashi\CustomResourceButton\CustomResourceButton;
use Laravel\Nova\Fields\Hidden;
use Sloveniangooner\SearchableSelect\SearchableSelect;

use Tanvirofficials\CopyClone\CopyClone;
use Tanvirofficials\CustomFileField\CustomFileField;
use Tanvirofficials\CruxContainersDetail\CruxContainersDetail;
use Tanvirofficials\Terminal49ShipmentDetailsTab\Terminal49ShipmentDetailsTab as StatusTab;
use Tanvirofficials\OurAutomatedTrackingTab\OurAutomatedTrackingTab;
use Gale\CustomerImportName\CustomerImportName;
use Tanvirofficials\ManualTrackingTab\ManualTrackingTab;
use Sixlive\TextCopy\TextCopy;
use Juliverdevshifl\ShipmentRefLink\ShipmentRefLink;

use PosLifestyle\DateRangeFilter\DateRangeFilter;
use PosLifestyle\DateRangeFilter\Enums\Config;
//ini_set('memory_limit', '512M');
//ini_set('max_execution_time', '300');

class Shipment extends Resource
{
    use TabsOnEdit;

    public static $searchRelations = [
        'customer' => ['company_name'],
        'shipmentSuppliers' => ['po_num'],
        'suppliers' => ['company_name', 'address', 'phone'],
        'containers' => ['container_num', 'type', 'seal_num', 'cartons', 'cbm', 'kg', 'chargeable_weight', 'size'],
        'invoice' => ['invoice_num'],
        'bill' => ['qbo_bill_num']
    ];


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Shipment';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return 'Shifl Ref : ' . $this->shifl_ref;
    }
    public function subtitle()
    {
        return $this->shipment_status;
    }

    public static $with = ['customer'];

    public static $search = [
        'id',
        'shifl_ref',
        /* 'etd',
        'eta', */
        'shipment_status',
        'mbl_num',
        'custom_notes',
        'origin_country'
    ];

    /**
     * The column that should be the default sort.
     *
     * @var array
     */
    public static $orderBy = ['eta' => 'desc'];


    public function isIndex($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\ResourceIndexRequest;
    }
    public function isDetail($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\ResourceDetailRequest;
    }
    public function isCreate($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\NovaRequest &&
            $request->editMode === 'create';
    }
    public function isUpdate($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\NovaRequest &&
            $request->editMode === 'update';
    }

    public function fields($request) {


        if ( $this->isIndex($request)) {
            return [
                ShipmentRefLink::make('Shifl Reference','shifl_ref')
                ->initFields([ 'id' => $this->id, 'shifl_ref' => $this->shifl_ref ])
                ->sortable()
                ->hideWhenCreating(),
                Date::make('ETD', 'etd')
                    ->onlyOnIndex()->format('MM/DD/YYYY')->sortable(),
                Date::make('ETA', 'eta')
                    ->onlyOnIndex()->format('MM/DD/YYYY')->sortable(),
                Select::make('Shifl Office From', 'shifl_office_origin_from_id')
                        ->rules('required')
                        ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->searchable(),
                Select::make('Shifl Office To', 'shifl_office_origin_to_id')
                        ->rules('required')
                        ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->searchable(),
                Text::make('Shifl Office From', function () {
                    return (isset($this->officeFrom) && isset($this->officeFrom->name)) ? $this->officeFrom->name : '—';
                })->hideWhenCreating()
                ->hideWhenUpdating(),
                Text::make('Shifl Office To', function () {
                    return (isset($this->officeTo) && isset($this->officeTo->name)) ? $this->officeTo->name : '—';
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),
                BelongsTo::make('Customer', 'customer')->hideWhenCreating()->hideWhenUpdating()->searchable(),
                Boolean::make('BC', 'booking_confirmed')->onlyOnIndex(),
                Boolean::make('RC', 'rate_confirmed')->onlyOnIndex(),
                Boolean::make('ANR', 'arrival_notice_confirmed')->onlyOnIndex(),
                Boolean::make('FRP', 'freight_released_processed')->onlyOnIndex(),
                Boolean::make('CP', 'customs_processed')->onlyOnIndex(),
                Boolean::make('DC', 'DO_confirmed')->onlyOnIndex(),
                Boolean::make('FRC', 'freight_confirmed')->onlyOnIndex(),
                Boolean::make('CR', 'customs_released')->onlyOnIndex(),
                Boolean::make('PR', 'pending_refund')->onlyOnIndex(),
                Boolean::make('D', 'delivered')->onlyOnIndex(),
                Boolean::make('B', 'billed')->onlyOnIndex(),
                Boolean::make('C', 'cancelled')->onlyOnIndex(),
                Text::make('MBL Number', 'mbl_num')
                    ->onlyOnIndex()->sortable(),
                SoTabContent::make('SO FIELD')->initFields($this->id)
            ];
        } else {
            return $this->defaultFields($request);
        }
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function defaultFields(Request $request)
    {
        $carriers = \App\Carrier::all();

        return ($this->cancelled) ?
            [
                (new Tabs('Shipment Details | Other Tabs are hidden because Shipment is in cancelled milestone', [
                    'Header Information' => [
                        Text::make('Shifl Reference', 'shifl_ref')->sortable()->hideWhenCreating(),

                        Date::make('ETD', 'etd')
                            ->onlyOnIndex()->format('MM/DD/YYYY')->sortable(),

                        Date::make('ETA', 'eta')
                            ->onlyOnIndex()->format('MM/DD/YYYY')->sortable(),

                        /*  Date::make('ETD', 'etd')
                    ->hideFromDetail()->hideWhenCreating()->format('MM/DD/YYYY')->sortable(),

                    Date::make('ETA', 'eta')
                    ->hideFromDetail()->hideWhenCreating()->format('MM/DD/YYYY')->sortable(),
 */

                       Select::make('Shifl Office From', 'shifl_office_origin_from_id')
                             ->rules('required')
                             ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                             ->hideFromDetail()
                             ->hideFromIndex()
                             ->searchable(),
                       Select::make('Shifl Office To', 'shifl_office_origin_to_id')
                             ->rules('required')
                             ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                             ->hideFromDetail()
                             ->hideFromIndex()
                             ->searchable(),
                       Text::make('Shifl Office From', function () {
                           return (isset($this->officeFrom) && isset($this->officeFrom->name)) ? $this->officeFrom->name : '—';
                       })
                       ->hideWhenCreating()
                       ->hideWhenUpdating(),
                       Text::make('Shifl Office To', function () {
                           return (isset($this->officeTo) && isset($this->officeTo->name)) ? $this->officeTo->name : '—';
                       })
                       ->hideWhenCreating()
                       ->hideWhenUpdating(),




                        // SearchableSelect::make('Customer', 'customer_id')->resource(\App\Nova\CustomDropdowns\CustomerDropdown::class)->hideFromIndex()->hideFromDetail()->hideWhenCreating()->rules('required'),
                        BelongsTo::make('customer', 'customer')->hideWhenCreating()->hideWhenUpdating()->searchable(),
                        // CustomerField::make('Customer', 'custom_customer')->initFields($this->customer)->onlyOnForms()->hideFromDetail()->hideFromIndex()->required(),

                        $this->checkImportNameStatus(),

                        Hidden::make('Import', 'import_name_id'),
                        CustomerImportName::make('Customer', 'custom_customer')
                            ->initFields(['customer' => $this->customer, 'import_name' => $this->importNames])
                            ->onlyOnForms()
                            ->hideFromDetail()
                            ->hideFromIndex()
                            ->required(),

                        /* BelongsTo::make('Origin Country', 'originCountry')
                    ->hideFromIndex()->searchable(), */

                        /*  BelongsTo::make('Country', 'originCountry', 'App\Country')
                        ->rules('required')
                    ->searchable(), */

                        Select::make('Origin Country')
                            ->rules('required')
                            ->options(\App\Country::all()->pluck('name', 'id'))
                            ->hideFromIndex()
                            ->hideFromDetail()
                            ->searchable(),

                        /* BelongsTo::make('Customer', 'customer_id')
                    ->searchable(), */

                        Textarea::make('Customer Requirements', function () {
                            if (isset($this->customer)) {
                                return $this->customer->requirements;
                            }
                        })->alwaysShow()->hideWhenCreating(),

                        Number::make('Credit Term Freight', function () {
                            if (isset($this->customer)) {
                                return $this->customer->credit_term_freight;
                            }
                        })->hideWhenCreating()->hideFromIndex(),

                        Number::make('Credit Term Duty', function () {
                            if (isset($this->customer)) {
                                return $this->customer->credit_term_duty;
                            }
                        })->hideFromIndex()->hideWhenCreating(),

                        /* NovaDependencyContainer::make([
                        Text::make('Requirements', 'requirements')
                    ])->dependsOn('customer.id', 0), */


                        /* BelongsTo::make('customer', 'customer')->hideWhenUpdating()->searchable(), */
                        Textarea::make('Notes', 'custom_notes'),

                        Boolean::make('Booking Confirmed', 'booking_confirmed')->hideFromIndex()->readonly(),
                        Boolean::make('SO Received', 'so_received')->hideFromIndex(),
                        Boolean::make('SO/BC Sent/Released to Shipper', 'so_released')->hideFromIndex(),
                        Boolean::make('ISF Done', 'isf_done')->hideFromIndex(),
                        Boolean::make('AMS Done', 'ams_done')->hideFromIndex(),
                        Boolean::make('Rate Confirmed', 'rate_confirmed')->hideFromIndex(),
                        Boolean::make('Gate In/Full In', 'gate_full_in')->hideFromIndex()->readonly(),
                        Boolean::make('Arrival Notice Sent', 'arrival_notice')->onlyOnDetail(),
                        Text::make('Arrival Notice Sent At', 'an_sent_at')->onlyOnDetail(),
                        Boolean::make('Arrival Notice Received', 'arrival_notice_confirmed')->hideFromIndex(),
                        Boolean::make('Freight Released Processed', 'freight_released_processed')->hideFromIndex(),
                        Boolean::make('Customs Processed', 'customs_processed')->hideFromIndex(),
                        Boolean::make('DO Sent', 'delivery_order_left')->onlyOnDetail(),
                        Text::make('DO Sent At', 'do_sent_at')->onlyOnDetail(),
                        Boolean::make('DO Confirmed', 'DO_confirmed')->hideFromIndex(),
                        Boolean::make('Freight Released Confirmed', 'freight_confirmed')->hideFromIndex(),
                        Boolean::make('Customs Released Confirmed', 'customs_released')->hideFromIndex(),
                        Boolean::make('Released At Termnial', 'released_at_terminal')->readonly()->hideFromIndex(),
                        Boolean::make('Pending Refund', 'pending_refund')->hideFromIndex(),
                        Boolean::make('Delivered', 'delivered')->hideFromIndex(),
                        Boolean::make('Billed', 'billed')->hideFromIndex(),
                        Boolean::make('Cancelled', 'cancelled')->hideFromIndex(),

                        /*Boolean::make('Cancelled', 'cancelled')
                    ->onlyOnIndex()
                    ->resolveUsing(function () {
                        $words = explode(" ", 'Cancelled');
                        $acronym = "";

                        if (is_array($words)) {
                            foreach ($words as $w) {
                                $acronym .= $w[0];
                            }
                        } else {
                            $acronym = $words[0];
                        }
                        return $acronym;
                        //return substr($title, 0, 35) . '...';
                    }),*/

                        /* Boolean::make('Booking Co..', 'booking_confirmed')->onlyOnIndex(),
                    Boolean::make('Arrival No..', 'arrival_notice_confirmed')->onlyOnIndex(),
                    Boolean::make('Freight Re..', 'freight_released_processed')->onlyOnIndex(),
                    Boolean::make('Customs Pr..', 'customs_processed')->onlyOnIndex(),
                    Boolean::make('DO Confirm..', 'DO_confirmed')->onlyOnIndex(),
                    Boolean::make('Freight Re..', 'freight_confirmed')->onlyOnIndex(),
                    Boolean::make('Customs Re..', 'customs_released')->onlyOnIndex(),
                    Boolean::make('Pending Re..', 'pending_refund')->onlyOnIndex(),
                    Boolean::make('Delivered', 'delivered')->onlyOnIndex(),
                    Boolean::make('Billed', 'billed')->onlyOnIndex(),
                    Boolean::make('Cancelled', 'cancelled')->onlyOnIndex(), */

                        Boolean::make('BC', 'booking_confirmed')->onlyOnIndex(),
                        Boolean::make('RC', 'rate_confirmed')->onlyOnIndex(),
                        Boolean::make('ANR', 'arrival_notice_confirmed')->onlyOnIndex(),
                        Boolean::make('FRP', 'freight_released_processed')->onlyOnIndex(),
                        Boolean::make('CP', 'customs_processed')->onlyOnIndex(),
                        Boolean::make('DC', 'DO_confirmed')->onlyOnIndex(),
                        Boolean::make('FRC', 'freight_confirmed')->onlyOnIndex(),
                        Boolean::make('CR', 'customs_released')->onlyOnIndex(),
                        Boolean::make('PR', 'pending_refund')->onlyOnIndex(),
                        Boolean::make('D', 'delivered')->onlyOnIndex(),
                        Boolean::make('B', 'billed')->onlyOnIndex(),
                        Boolean::make('C', 'cancelled')->onlyOnIndex(),
                        Text::make('MBL Number', 'mbl_num')
                            ->onlyOnIndex()->sortable(),

                        Button::make('CREATE SHIPMENT')->link(url('/administrator/resources/shipments/new?viaResource=&viaResourceId=&viaRelationship='), '_self')->onlyOnDetail(),

                        /* InfoSaveButton::make('Save')
                    ->hideFromIndex()
                    ->hideWhenCreating()
                    ->hideFromDetail(), */
                        InfoSaveButton::make('SAVE')->initFields(['customer' => $this->customer])->onlyOnForms()->hideWhenCreating(),

                        // Button::make('Send Pre alert Mail')->event('App\Events\SendLeaveShipmentNotificationMailEvent')->onlyOnDetail()->confirm("Are You sure ?")->visible(!$this->shipment_left),
                        // Button::make('Resend Pre alert Mail')->event('App\Events\SendLeaveShipmentNotificationMailEvent')->onlyOnDetail()->confirm("Are You sure ?")->visible($this->shipment_left)->style('link-success'),

                    ],

                ]))->withToolbar(),
                CopyClone::make()
                    ->withMeta([
                        'resource' => 'shipments', // resource url
                        'model' => 'App\Shipment', // model path
                        'id' => $this->id, // id of record
                        'except' => ['shifl_ref', 'mbl_copy', 'debit_note', 'delivery_order_left', 'do_sent_at', 'arrival_notice', 'an_sent_at', 'entry_netchb_submitted', 'entry_netchb_date', 'is_agent_booking_confirm', 'agent_booking_sent', 'booking_confirmed'], // an array of fields to not replicate (nullable).
                        'override' => ['cloned_from' => $this->id], // an array of fields and values which will be set on the modal after Cloning(nullable).
                        'button_text' => 'Clone Shipment', // By deafult its copy\clone  icon.
                        'confirm_button_text' => 'Clone Shipment',   // by default 'confirm_button_text' => 'Copy\Clone',
                        'cancle_button_text' => 'Cancel'
                    ]),
            ]
            :
            [
                (new Tabs('Shipment Details', [
                    'Header Information' => [
                        Text::make('Shifl Reference', 'shifl_ref')->sortable()->hideWhenCreating(),

                        Date::make('ETD', 'etd')
                            ->onlyOnIndex()->format('MM/DD/YYYY')->sortable(),

                        Date::make('ETA', 'eta')
                            ->onlyOnIndex()->format('MM/DD/YYYY')->sortable(),

                        /*  Date::make('ETD', 'etd')
                    ->hideFromDetail()->hideWhenCreating()->format('MM/DD/YYYY')->sortable(),

                    Date::make('ETA', 'eta')
                    ->hideFromDetail()->hideWhenCreating()->format('MM/DD/YYYY')->sortable(),
 */
                        // SearchableSelect::make('Customer', 'customer_id')->resource(\App\Nova\CustomDropdowns\CustomerDropdown::class)->hideFromIndex()->hideFromDetail()->hideWhenCreating()->rules('required'),

                        /* BelongsTo::make('Origin Country', 'OriginCountry
                    ')
                    ->hideFromIndex()->searchable(), */
                    /*
                        Select::make('Origin Country')
                            //->rules('required')
                            ->options(\App\Country::all()->pluck('name', 'id'))
                            ->hideFromIndex()
                            ->hideFromDetail()
                            ->searchable(),*/
                        Select::make('Shifl Office From', 'shifl_office_origin_from_id')
                              ->rules('required')
                              ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                              ->hideFromDetail()
                              ->hideFromIndex()
                              ->searchable(),
                        Select::make('Shifl Office To', 'shifl_office_origin_to_id')
                              ->rules('required')
                              ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                              ->hideFromDetail()
                              ->hideFromIndex()
                              ->searchable(),
                        Text::make('Shifl Office From', function () {
                            return (isset($this->officeFrom) && isset($this->officeFrom->name)) ? $this->officeFrom->name : '—';
                        })
                        ->hideWhenCreating()
                        ->hideWhenUpdating(),
                        Text::make('Shifl Office To', function () {
                            return (isset($this->officeTo) && isset($this->officeTo->name)) ? $this->officeTo->name : '—';
                        })
                        ->hideWhenCreating()
                        ->hideWhenUpdating(),
                        /* BelongsTo::make('Customer', 'customer_id')
                    ->searchable(), */
                        BelongsTo::make('Customer', 'customer')->hideWhenCreating()->hideWhenUpdating()->searchable(),

                        $this->checkImportNameStatus(),

                        // CustomerField::make('Customer', 'custom_customer')->initFields($this->customer)->onlyOnForms()->hideFromDetail()->hideFromIndex()->required(),

                        Hidden::make('Import', 'import_name_id'),
                        CustomerImportName::make('Customer', 'custom_customer')
                            ->initFields(['customer' => $this->customer, 'import_name' => $this->importNames])
                            ->onlyOnForms()
                            ->hideFromDetail()
                            ->hideFromIndex()
                            ->required(),

                        Textarea::make('Customer Requirements', function () {
                            if (isset($this->customer)) {
                                return $this->customer->requirements;
                            }
                        })->alwaysShow()->hideWhenCreating(),

                        Number::make('Credit Term Freight', function () {
                            if (isset($this->customer)) {
                                return $this->customer->credit_term_freight;
                            }
                        })->hideWhenCreating()->hideFromIndex(),

                        Number::make('Credit Term Duty', function () {
                            if (isset($this->customer)) {
                                return $this->customer->credit_term_duty;
                            }
                        })->hideFromIndex()->hideWhenCreating(),

                        /* NovaDependencyContainer::make([
                        Text::make('Requirements', 'requirements')
                    ])->dependsOn('customer.id', 0), */
                        Textarea::make('Notes', 'custom_notes'),
                        ShipmentServicesSectionField::make('Services', 'services_section')->hideFromIndex(),
/*
                        Boolean::make('Booking Confirmed', 'booking_confirmed')->hideFromIndex()->readonly(),
                        Boolean::make('SO/BC Released', 'so_released')->hideFromIndex(),
                        Boolean::make('ISF Done', 'isf_done')->hideFromIndex(),
                        Boolean::make('AMS Done', 'ams_done')->hideFromIndex(),
                        Boolean::make('Rate Confirmed', 'rate_confirmed')->hideFromIndex(),
                        Boolean::make('MBL Released Confirmed',function() {
                            return $this->mbl_copy_surrendered!=='' && $this->mbl_copy_surrendered!==null ? 1 : 0;
                        })->onlyOnDetail(),
                        MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                            ->initFields($this->id,'header')->hideFromIndex(),
                        Boolean::make('Arrival Notice Sent', 'arrival_notice')->onlyOnDetail(),
*/
                        // Boolean::make('Booking Confirmed', 'booking_confirmed')->hideFromIndex()->readonly(),
                        // Boolean::make('SO/BC Released', 'so_released')->hideFromIndex(),
                        // Boolean::make('ISF Done', 'isf_done')->hideFromIndex(),
                        // Boolean::make('AMS Done', 'ams_done')->hideFromIndex(),
                        // Boolean::make('Rate Confirmed', 'rate_confirmed')->hideFromIndex(),
                        // Boolean::make('MBL Released Confirmed','mbl_released_confirmed')->onlyOnDetail(),
                        // MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                        //     ->initFields($this->id)->hideFromIndex(),
                        // Boolean::make('Arrival Notice Sent', 'arrival_notice')->onlyOnDetail(),
                        // Text::make('Arrival Notice Sent At', 'an_sent_at')->onlyOnDetail(),
                        // Boolean::make('Arrival Notice Received', 'arrival_notice_confirmed')->hideFromIndex(),
                        // Boolean::make('Customs Sent', function() {
                        //     return ($this->entry_netchb_submitted==1) ? 1 : 0;
                        // })->onlyOnDetail(),
                        // Text::make('Customs Sent At', function() {
                        //     if ( $this->entry_netchb_submitted==1 ) {
                        //         return $this->entry_netchb_date;
                        //     } else {
                        //         return '';
                        //     }
                        // })->onlyOnDetail(),
                        // Boolean::make('Customs Processed', 'customs_processed')->hideFromIndex(),
                        // Boolean::make('Freight Released Processed', 'freight_released_processed')->hideFromIndex(),
                        // Boolean::make('DO Sent', 'delivery_order_left')->onlyOnDetail(),
                        // Text::make('DO Sent At', 'do_sent_at')->onlyOnDetail(),
                        // Boolean::make('DO Confirmed', 'DO_confirmed')->hideFromIndex(),
                        // Boolean::make('Freight Released Confirmed', 'freight_confirmed')->hideFromIndex(),
                        // Boolean::make('Customs Released Confirmed', 'customs_released')->hideFromIndex(),
                        // Boolean::make('Released At Terminal', 'released_at_terminal')->readonly()->hideFromIndex(),
                        // Boolean::make('Pending Refund', 'pending_refund')->hideFromIndex(),
                        // Boolean::make('Delivered', 'delivered')->hideFromIndex(),
                        // Boolean::make('Billed', 'billed')->hideFromIndex(),
                        // Boolean::make('Cancelled', 'cancelled')->hideFromIndex(),

                        BookingSentStatusMilestone::make('Booking Sent to Customer', '')
                            ->onlyOnDetail()
                            ->initFields($this->id),
                        ShipmentMilestoneComponent::make('Booking Confirmed', 'booking_confirmed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','booking_confirmed'),
                            ]
                        )->hideFromIndex()->readonly(),
                        ShipmentMilestoneComponent::make('SO Received', 'so_received')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','so_received'),
                            ]
                        )->hideFromIndex()->hideWhenUpdating(),
                        ShipmentSoReceivedCheckbox::make('SO Received', 'so_received')->initFields($this->so_received, $this->is_agent_booking_confirm, $this->id)->hideFromIndex()->hideWhenCreating()->hideFromDetail(),
                        ShipmentMilestoneComponent::make('SO/BC Sent/Released to Shipper', 'so_released')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','so_released'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('ISF Done', 'isf_done')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','isf_done'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('AMS/ENS Done', 'ams_done')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','ams_done'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Rate Confirmed', 'rate_confirmed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','rate_confirmed'),
                            ]
                        )->hideFromIndex(),
                        ShipmentMilestoneComponent::make('Gate In/Full In', 'gate_full_in')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','gate_full_in'),
                            ]
                        )->hideFromIndex()->readonly(),

                        ShipmentMilestoneComponent::make('Containers Loaded', 'is_containers_loaded')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','is_containers_loaded'),
                            ]
                        )->hideFromIndex()->hideWhenUpdating()->hideFromDetail(),

                        ShipmentContainersLoadedCheckbox::make('Containers Loaded', 'is_containers_loaded')->initFields($this->mbl_num, $this->so_received, $this->is_agent_booking_confirm, $this->id)
                            ->hideFromIndex()
                            ->hideWhenCreating(),

                        ShipmentMilestoneComponent::make('MBL Released Confirmed', 'mbl_released_confirmed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','mbl_released_confirmed'),
                            ]
                        )->onlyOnDetail(),
                        /*
                        MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                            ->initFields($this->id,'header')->hideFromIndex(),
                        */
                        ShipmentMilestoneComponent::make('Arrival Notice Sent', 'arrival_notice')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','arrival_notice'),
                            ]
                        )->onlyOnDetail(),
                        // Text::make('Arrival Notice Sent At', 'an_sent_at')->onlyOnDetail(),
                        ShipmentMilestoneComponent::make('Arrival Notice Sent At', function(){
                            return is_null($this->an_sent_at) ? '-' : date_format(date_create($this->an_sent_at),'M d, Y');
                        })->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','an_sent_at'),
                                'disable_icon' => true,
                                'disable_instructions' => true,
                                'disable_information' => true
                            ]
                        )->onlyOnDetail(),
                        ShipmentMilestoneComponent::make('Arrival Notice Received', 'arrival_notice_confirmed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','arrival_notice_confirmed'),
                            ]
                        )->hideFromIndex(),
                        // Boolean::make('HBL Released Confirmed', function() {

                        //     $total_suppliers = $this->shipmentSuppliers;
                        //     if (count($total_suppliers)>0) {
                        //         $completed = 0;
                        //         foreach ($total_suppliers as $supplier) {
                        //             if ( isset($supplier->bl_status) && $supplier->bl_status === 'Telex Released' ) {
                        //                 $completed++;
                        //             }
                        //         }
                        //         if ($completed === count($total_suppliers)) {
                        //             return 1;
                        //         }
                        //     } else {
                        //         return 0;
                        //     }
                        // })->onlyOnDetail(),
                        ShipmentMilestoneComponent::make('HBL Released Confirmed', 'hbl_released_confirmed')->default(function(){

                            $suppliers_group = $this->suppliers_group_bookings;
                            try {
                                $suppliers_group = (isset($this->suppliers_group_bookings)) ? $this->suppliers_group_bookings : '[]';

                            }catch(Exception $e) {
                                    $suppliers_group = '[]';
                            }

                            $suppliers_group = json_decode($suppliers_group) ?? [];

                            //$total_suppliers = $this->shipmentSuppliers;
                            $total_suppliers = $suppliers_group;

                            if (count($total_suppliers)>0) {
                                $completed = 0;
                                foreach ($total_suppliers as $supplier) {
                                    if ( isset($supplier->bl_status) && ($supplier->bl_status === 'Telex Released' || $supplier->bl_status === 'Original Received')) {
                                        $completed++;
                                    }
                                }
                                if ($completed === count($total_suppliers)) {
                                    return 1;
                                } else {
                                    return 0;
                                }
                            } else {
                                return 0;
                            }
                        })->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','hbl_released_confirmed'),
                            ]
                        )->onlyOnDetail(),
                        // Boolean::make('Customs Sent', function() {
                        //     return ($this->entry_netchb_submitted==1) ? 1 : 0;
                        // })->onlyOnDetail(),
                        SufficientCommercialDocs::make('Sufficient Commercial Docs')
                            ->hideWhenUpdating()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->initFields($this->id),
                        ShipmentMilestoneComponent::make('Customs Sent', 'entry_netchb_submitted')
                        ->initFields( [ 'milestone' => ShipmentMilestoneModel::firstWhere('name','customs_sent'), ] )
                        ->onlyOnDetail(),
                        // Text::make('Customs Sent At', function() {
                        //     if ( $this->entry_netchb_submitted==1 ) {
                        //         return $this->entry_netchb_date;
                        //     } else {
                        //         return '';
                        //     }
                        // })->onlyOnDetail(),
                        ShipmentMilestoneComponent::make('Customs Sent At', function(){
                            if (isset($this)) {
                                return $this->entry_netchb_submitted == 1 ? date_format(date_create($this->entry_netchb_date),'M d, Y') : '';
                            }else{
                                return '';
                            }
                        })->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','customs_sent_at'),
                                'disable_icon' => true,
                                'disable_instructions' => true,
                                'disable_information' => true
                            ]
                        )->onlyOnDetail(),

                        ShipmentMilestoneComponent::make('Customs Processed', 'customs_processed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','customs_processed'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Freight Released Processed', 'freight_released_processed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','freight_released_processed'),
                            ]
                        )->hideFromIndex(),

                        // Boolean::make('DO Sent', 'delivery_order_left')->onlyOnDetail(),

                        ShipmentMilestoneComponent::make('DO Sent', 'delivery_order_left')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','delivery_order_left'),
                            ]
                        )->onlyOnDetail(),

                        // Text::make('DO Sent At', 'do_sent_at')->onlyOnDetail(),

                        ShipmentMilestoneComponent::make('DO Sent At', 'do_sent_at')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','do_sent_at'),
                                'disable_icon' => true,
                                'disable_instructions' => true,
                                'disable_information' => true
                            ]
                        )->onlyOnDetail(),

                        ShipmentMilestoneComponent::make('DO Confirmed', 'DO_confirmed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','DO_confirmed'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Freight Released Confirmed', 'freight_confirmed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','freight_confirmed'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Customs Released Confirmed', 'customs_released')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','customs_released'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Released At Terminal', 'released_at_terminal')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','released_at_terminal'),
                            ]
                        )->readonly()->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Pending Refund', 'pending_refund')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','pending_refund'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Delivered', 'delivered')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','delivered'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Billed', 'billed')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','billed'),
                            ]
                        )->hideFromIndex(),

                        ShipmentMilestoneComponent::make('Cancelled', 'cancelled')->initFields(
                            [
                                'milestone' => ShipmentMilestoneModel::firstWhere('name','cancelled'),
                            ]
                        )->hideFromIndex()->hideWhenUpdating(),

                        CancelShipmentCheckbox::make('Cancelled', 'cancelled')->initFields($this->mbl_num)->hideFromIndex()->hideWhenCreating()->hideFromDetail(),

                        /*Boolean::make('Cancelled', 'cancelled')
                    ->onlyOnIndex()
                    ->resolveUsing(function () {
                        $words = explode(" ", 'Cancelled');
                        $acronym = "";

                        if (is_array($words)) {
                            foreach ($words as $w) {
                                $acronym .= $w[0];
                            }
                        } else {
                            $acronym = $words[0];
                        }
                        return $acronym;
                        //return substr($title, 0, 35) . '...';
                    }),*/

                        /* Boolean::make('Booking Co..', 'booking_confirmed')->onlyOnIndex(),
                    Boolean::make('Arrival No..', 'arrival_notice_confirmed')->onlyOnIndex(),
                    Boolean::make('Freight Re..', 'freight_released_processed')->onlyOnIndex(),
                    Boolean::make('Customs Pr..', 'customs_processed')->onlyOnIndex(),
                    Boolean::make('DO Confirm..', 'DO_confirmed')->onlyOnIndex(),
                    Boolean::make('Freight Re..', 'freight_confirmed')->onlyOnIndex(),
                    Boolean::make('Customs Re..', 'customs_released')->onlyOnIndex(),
                    Boolean::make('Pending Re..', 'pending_refund')->onlyOnIndex(),
                    Boolean::make('Delivered', 'delivered')->onlyOnIndex(),
                    Boolean::make('Billed', 'billed')->onlyOnIndex(),
                    Boolean::make('Cancelled', 'cancelled')->onlyOnIndex(), */

                        Boolean::make('BC', 'booking_confirmed')->onlyOnIndex(),
                        Boolean::make('RC', 'rate_confirmed')->onlyOnIndex(),
                        Boolean::make('ANR', 'arrival_notice_confirmed')->onlyOnIndex(),
                        Boolean::make('FRP', 'freight_released_processed')->onlyOnIndex(),
                        Boolean::make('CP', 'customs_processed')->onlyOnIndex(),
                        Boolean::make('DC', 'DO_confirmed')->onlyOnIndex(),
                        Boolean::make('FRC', 'freight_confirmed')->onlyOnIndex(),
                        Boolean::make('CR', 'customs_released')->onlyOnIndex(),
                        Boolean::make('PR', 'pending_refund')->onlyOnIndex(),
                        Boolean::make('D', 'delivered')->onlyOnIndex(),
                        Boolean::make('B', 'billed')->onlyOnIndex(),
                        Boolean::make('C', 'cancelled')->onlyOnIndex(),

                        Button::make('CREATE SHIPMENT')->link(url('/administrator/resources/shipments/new?viaResource=&viaResourceId=&viaRelationship='), '_self')->onlyOnDetail(),

                        /* InfoSaveButton::make('Save')
                    ->hideFromIndex()
                    ->hideWhenCreating()
                    ->hideFromDetail(), */
                        InfoSaveButton::make('SAVE')->initFields(['customer' => $this->customer])->onlyOnForms()->hideWhenCreating(),

                        // Button::make('Send Pre alert Mail')->event('App\Events\SendLeaveShipmentNotificationMailEvent')->onlyOnDetail()->confirm("Are You sure ?")->visible(!$this->shipment_left),
                        // Button::make('Resend Pre alert Mail')->event('App\Events\SendLeaveShipmentNotificationMailEvent')->onlyOnDetail()->confirm("Are You sure ?")->visible($this->shipment_left)->style('link-success'),

                    ],
                    'Booking' => [
                        Hidden::make('Multi Purchae Orders', 'multi_purchase_orders'),
                        Hidden::make('Personal Token', 'personal_token'),
                        BookingsTabSaveGroup::make('BookingsTabSave')
                            ->initFields($this->id,$this->shifl_ref)
                            ->hideFromIndex(),
                        ShipmentSupplierGroup::make('Suppliers Section', 'suppliers_group_bookings')
                            ->hideWhenUpdating()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->initFields($this->id),

                        //->hideWhenCreating()
                        //->hideFromDetail(),
                        /*
                    ShipmentScheduleMultipleGroup::make('Schedules Section', 'schedules_group_bookings')
                    ->rules(new EstimatedRule)
                    ->hideFromIndex()
                    ->hideWhenUpdating()
                    ->initFields($this->id),
                    ShipmentSupplierLimitedGroup::make('Suppliers Section', 'suppliers_group_bookings')
                                                ->hideFromIndex()
                                                ->hideWhenUpdating()
                                                ->initFields($this->id),

                    ShipmentContainerLimitedGroup::make('Containers Section', 'containers_group_bookings')->hidefromIndex()
                        ->hideWhenUpdating()
                        ->initFields($this->id),*/
                        //Textarea::make('Memo', 'memo_customer')->hideFromIndex(),
                        //Boolean::make('Booking Confirmed', 'booking_confirmed')->hideFromIndex()->hideWhenUpdating()
                        Textarea::make('Memo', 'memo_customer')->onlyOnDetail(),
                        CustomButton::make('Booking Email Buttons')->initFields($this->id)->onlyOnDetail(),
                        //Button::make('Send Updated Booking Email')->event('App\Events\SendUpdatedBookingEmailEvent')->onlyOnDetail()->confirm("Are you sure you want to send this updated booking?"),
                        //Button::make('Send Booking Email')->event('App\Events\SendBookingEmailEvent')->onlyOnDetail()->confirm("Are you sure you want to send this booking?"),
                    ],

                    //Originally Departure Notice renamed to BL's
                    "BL's" => [
                        Boolean::make('Booking Confirmed', 'booking_confirmed')->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex()
                            ->nullable()->readonly(),

                        ShipmentDepartureNoticeSave::make('DepartureSave')
                            ->storeDebitNote(function (Request $request, $model) {
                                $originalName = 'shipment/debit_note/' .  basename($request->file('debit_note')->getClientOriginalName(), '.' . $request->file('debit_note')->guessExtension()) . '_' . $this->id . '_debit.' . $request->file('debit_note')->guessExtension();
                                Storage::disk('local')->putFileAs('/public', $request->file('debit_note'), $originalName);
                                return [
                                    'debit_note' => $originalName
                                ];
                            })
                            ->storeMblCopy(function (Request $request, $model) {
                                $originalName = 'shipment/mbl_copy/' . basename($request->file('mbl_copy')->getClientOriginalName(), '.' . $request->file('mbl_copy')->guessExtension()) . '_' . $this->id . '_mbl.' . $request->file('mbl_copy')->guessExtension();
                                Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy'), $originalName);
                                return [
                                    'mbl_copy' => $originalName
                                ];
                            })
                            ->initFields($this->id,$this->etalogs,$this->terminal_fortynineMany)
                            ->hideFromIndex(),
                        //->hideWhenCreating(),

                        /*
                    Date::make('Estimated Time of Departure','etd')->format('MM/DD/YYYY')->sortable()
                    ->hideFromIndex()
                    ->nullable(),
                    Date::make('Estimated Time of Arrival','eta')->format('MM/DD/YYYY')->sortable()
                    ->hideFromIndex()
                    ->nullable(),*/

                        ShipmentScheduleGroup::make('Schedules Section', 'schedules_group')
                            ->rules(new EstimatedRule)
                            ->hideWhenUpdating()
                            ->hideFromDetail()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        BelongsTo::make('Carrier', 'carrier')
                            ->hideWhenUpdating()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->hideFromDetail()
                            ->nullable(),
                        Text::make('Vessel', 'vessel')
                            ->nullable()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),
                        Text::make('Voyage Number', 'voyage_number')
                            ->nullable()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),
                        Text::make('Booking Number', 'booking_number')
                            ->nullable()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),
                        Select::make('Type', 'type')->options([
                            'LCL' => 'LCL',
                            'FCL' => 'FCL',
                            'Air' => 'Air'
                        ])
                            ->displayUsingLabels()
                            ->hideWhenUpdating()
                            ->hideFromIndex(),

                        Text::make('MBL Number', 'mbl_num')
                            ->hideWhenUpdating()->sortable(),
                        Text::make('MBL Shipper', 'mbl_shipper')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Shipper Address', 'mbl_shipper_address')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Shipper Phone Number', 'mbl_shipper_phone_number')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Consignee', 'mbl_consignee')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Consignee Address', 'mbl_consignee_address')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Consignee Phone Number', 'mbl_consignee_phone_number')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Notify', 'mbl_notify')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Notify Address', 'mbl_notify_address')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Text::make('MBL Notify Phone Number', 'mbl_notify_phone_number')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Textarea::make('MBL Description', 'mbl_description')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),
                        Textarea::make('MBL Marks', 'mbl_marks')
                            ->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),

                        CustomFileField::make('MBL Copy', 'mbl_copy')->store(function (Request $request, $model) {
                            $originalName = 'shipment/mbl_copy/' . basename($request->file('mbl_copy')->getClientOriginalName(), '.' . $request->file('mbl_copy')->guessExtension()) . '_' . $this->id . '_mbl.' . $request->file('mbl_copy')->guessExtension();
                            // $originalName =  basename($request->file('mbl_copy')->getClientOriginalName(), '.'. $request->file('mbl_copy')->guessExtension()) . '_'.$this->id.'_mbl.' .$request->file('mbl_copy')->guessExtension();

                            Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy'), $originalName);
                            return [
                                'mbl_copy' => $originalName
                            ];
                        })
                            ->hideWhenUpdating()
                            ->hideFromIndex(),
                        CustomFileField::make('MBL Copy Surrendered/Released', 'mbl_copy_surrendered')->store(function (Request $request, $model) {

                            $final_display_name = '';
                            if ($request->has('mbl_copy_surrendered')) {
                                if (!is_null($file = $request->file('mbl_copy_surrendered')) && $file->isValid()) {
                                    $hash_file = md5($request->file('mbl_copy_surrendered')->getClientOriginalName() . now());
                                    $final_display_name = $hash_file . '.' . $request->file('mbl_copy_surrendered')->guessExtension();
                                    $final_name = 'shipment/mbl_copy_surrendered/'.$final_display_name;
                                    Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy_surrendered'), $final_name);
                                }
                            }
                            return [
                                'mbl_copy_surrendered' => $final_display_name,
                                'mbl_released_confirmed' => ($final_display_name=='' || $final_display_name==null) ? 0 : 1
                            ];
                        })
                            ->hideFromIndex()->hideWhenUpdating(),
                        MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                            ->initFields($this->id,'bl')->hideFromIndex()->hideWhenUpdating()->hideWhenCreating()->hideFromDetail(),
                        CustomFileField::make('Debit Note', 'debit_note')->store(function (Request $request, $model) {
                            // $originalName =  basename($request->file('debit_note')->getClientOriginalName(), '.'. $request->file('debit_note')->guessExtension()) . '_'.$this->id.'_debit.' .$request->file('debit_note')->guessExtension();
                            $originalName = 'shipment/debit_note/' .  basename($request->file('debit_note')->getClientOriginalName(), '.' . $request->file('debit_note')->guessExtension()) . '_' . $this->id . '_debit.' . $request->file('debit_note')->guessExtension();
                            // $originalName = 'shipment/debit_note/'. $request->file('debit_note')->getClientOriginalName();
                            Storage::disk('local')->putFileAs('/public', $request->file('debit_note'), $originalName);
                            return [
                                'debit_note' => $originalName
                            ];
                        })
                            ->hideWhenUpdating()
                            ->hideFromIndex(),
                        ShipmentSupplierGroup::make('Suppliers Section', 'suppliers_group')
                            ->hideWhenUpdating()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->initFields($this->id),
                        //->rules(new FindDuplicateSupplier),

                        ShipmentContainerGroup::make('Container Section', 'containers_group')
                            ->hideWhenUpdating()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->initFields($this->id),
                        /*
                    Flexible::make('Suppliers','suppliers_group')
                            ->addLayout('Suppliers','supplierslayout',[
                                Text::make('PO #', 'po_num'),
                              //  Text::make('Volume', 'volume'),
                                Text::make('Name','company_name'),
                                Text::make('Address','address'),
                                Text::make('Phone','phone'),
                                Text::make('Weight','total_weight'),
                                Text::make('Total Cartons','total_cartons'),
                                Text::make('AMS #','ams_num'),
                                Select::make('BL Status','bl_status')->options([
                                        'Pending' => 'Pending',
                                        'Telex Released' => 'Telex Released',
                                        'Original Received' => 'Original Received'
                                    ])->displayUsingLabels()


                            ])*/


                        /*
                    ->addLayout('Schedules', 'schedulelayout', [
                         Date::make('Estimated Time of Departure','etd')->format('MM/DD/YYYY')->sortable()
                        ->nullable()
                        ,
                        Date::make('Estimated Time of Arrival','eta')->format('MM/DD/YYYY')->sortable()->nullable()
                    ])
                    /*
                    ->addLayout('Carriers','carrierlayout', [
                        //BelongsTo::make('Carrier','carrier_id')
                        Select::
                    ])

                    /*
                    Flexible::make('Schedules','custom_notes')
                            ->addLayout('Simple content section', 'wysiwyg', [
                                Text::make('Wew')
                            ])*/
                        // Button::make('Send')->event('App\Events\SendLeaveShipmentNotificationMailEvent', $this)->detail('App\Nova\Shipment', \App\Shipment::orderBy('id', 'desc')->first()->id + 1)->confirm('Are You Sure..?'),
                        Button::make('Send Departure Notice')->event('App\Events\SendLeaveShipmentNotificationMailEvent')->onlyOnDetail()->confirm("Are You sure ?")->visible(!$this->shipment_left),
                        Button::make('Resend Departure Notice')->event('App\Events\SendLeaveShipmentNotificationMailEvent')->onlyOnDetail()->confirm("Are You sure ?")->visible($this->shipment_left)->style('link-success'),
                        // Button::make('CREATE SHIPMENT')->link(url('/administrator/resources/shipments/new?viaResource=&viaResourceId=&viaRelationship='), '_self')->onlyOnDetail(),

                    ],
                    'Arrival Notice' => [

                        ShipmentArrivalNoticeSave::make('Arrival Notice Group button')
                            ->initiate($this->id)
                            ->hideFromIndex()
                            ->hideFromDetail()
                            ->hideWhenCreating(),
                        // Text::make('Dummy field')->default('Dummy Value')->onlyOnDetail(),
//                        BelongsTo::make('Terminal')->searchable()
//                            ->hideWhenUpdating()
//                            ->hideFromIndex()
//                            ->nullable(),


                        BelongsTo::make('Terminal', 'terminal', 'App\Nova\Terminal')->displayUsing(function ($terminal) {
                            return $terminal->name. ' - '. $terminal->firms_code .' - '.     $terminal->address;
                        })->searchable()
                            ->hideWhenUpdating()
                            ->hideFromIndex()
                            ->nullable(),


                        TextCopy::make('Copy Firms Code')
                            ->copyValue(function(){
                                $terminal =\App\Terminal::select('id', 'name', 'firms_code')->where("id", '=', $this->terminal_id)->limit(1)->get()->toArray();
                                $terminal1 = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($terminal), ENT_NOQUOTES));
                                $term = json_decode($terminal1, true);
                                if(isset($term['id'])){
                                    return  $term['firms_code'];
                                }else{
                                    return "NULL";
                                }
                            })->hideWhenUpdating()
                            ->hideWhenCreating()
                            ->hideFromIndex(),


                        Date::make('Estimated Time of Arrival', 'eta')
                            ->onlyOnDetail()->format('MM/DD/YYYY'),

                        Date::make('Carrier Arrival Notice Eta', 'carrier_arrival_notice_eta')
                            ->onlyOnDetail()->format('MM/DD/YYYY'),

                        ShipmentArrivalNoticeSave::make('Carrier Arrival Notice Firms & Customs Broker')
                            ->initiate($this->id)
                            ->onlyOnDetail(),

                        Button::make('Send Arrival Notice')->event('App\Events\SendArrivalNoticeEvent')->confirm("Are You sure ?")->visible(!$this->arrival_notice&&isset($this->customs_broker_id))->onlyOnDetail(),
                        Button::make('Resend Arrival Notice')->event('App\Events\SendArrivalNoticeEvent')->confirm("Are You sure ?")->visible($this->arrival_notice&&isset($this->customs_broker_id))->onlyOnDetail()->style('link-success'),

                    ],
                    'Customs' => [
                        EntryNetchbField::make('NETCHB ENTRY FIELD')->onlyOnDetail()->initFields($this->id)
                    ],
                    'SO' => [
                        SoTabContent::make('SO FIELD')->initFields($this->id)
                    ],
                    'Delivery Order' => [
                        ShipmentDeliverySaveDeliveryOrder::make('Delivery Order Group button')
                            ->initiate($this->id)
                            ->hideFromIndex()
                            ->hideFromDetail()
                            ->hideWhenCreating(),
                        BelongsTo::make('Trucker')->searchable()
                            ->hideFromIndex()
                            ->hideWhenUpdating()
                            ->nullable(),


                        Textarea::make('Delivery Address and Contact', 'trucker_custom_note')->hideFromIndex()->hideWhenUpdating(),

                        Select::make('Type', 'type')
                            ->options([
                                'LCL' => 'LCL',
                                'FCL' => 'FCL',
                                'Air' => 'Air'
                            ])
                            ->displayUsingLabels()
                            ->onlyOnDetail(),
                        /*
                        Boolean::make("Copy Customer", "copy_customer")->hideFromIndex()->hideWhenCreating(),*/
                        Button::make('Send Delivery Order')->event('App\Events\SendDeliveryOrderEvent')->confirm("Are You sure ?")->visible(!$this->delivery_order_left)->onlyOnDetail(),
                        Button::make('Resend Delivery Order')->event('App\Events\SendDeliveryOrderEvent')->confirm("Are You sure ?")->visible($this->delivery_order_left)->onlyOnDetail()->style('link-success'),
                    ],
                    'Charges' => [
                        ChargesTab::make('Charges')->hideFromIndex()->readonly(),
                    ],
                    "Customer Documents" => [
                        ShipmentCustomerDocuments::make('Customer Documents')->initFields([
                            'documents' => $this->customerDocuments,
                            'id' => $this->id,
                            'shifl_ref' => $this->shifl_ref,
                            'suppliers' => $this->suppliers_group_bookings
                        ])->canSee(function ($request) {
                            return $request->user()->hasRole('Super Admin');
                        })->onlyOnDetail(),
                        ShipmentNotes::make("Notes")
                            ->initFields([
                                'id' => $this->id
                            ])
                            ->addingNotesEnabled(true)
                            ->canSee(function ($request) {
                                return $request->user()->hasRole('Super Admin');
                            })
                            ->hideFromIndex()
                            ->hideWhenCreating(),
                    ],
                    "T49 Tracking" => [
                        StatusTab::make('Status', 'mbl_num')->isAdmin(auth()->user()->hasRole('Super Admin'))->onlyOnDetail(),
                    ],
                    "Manual Tracking Tab" => [
                        ManualTrackingTab::make('Manual Tracking Tab', 'mbl_num')->onlyOnDetail()->canSee(function(){
                            return auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Sales Representative');
                        }),
                    ],
                    "Our Automated Tracking" => [
                        OurAutomatedTrackingTab::make('Our Automated Tracking','mbl_num')->onlyOnDetail()->canSee(function(){
                            return auth()->user()->hasRole('Super Admin');
                        })->initFields([
                            "id" => $this->id,
                            "mbl_num" => $this->mbl_num,
                            "loginEmail" => \Auth::user()->email ?? ''
                        ])
                    ],
                ]))->withToolbar(),
                CopyClone::make()
                    ->withMeta([
                        'resource' => 'shipments', // resource url
                        'model' => 'App\Shipment', // model path
                        'id' => $this->id, // id of record
                        'except' => ['shifl_ref', 'mbl_copy', 'debit_note', 'delivery_order_left', 'do_sent_at', 'arrival_notice', 'an_sent_at', 'entry_netchb_submitted', 'entry_netchb_date', 'is_agent_booking_confirm', 'agent_booking_sent', 'booking_confirmed'], // an array of fields to not replicate (nullable).
                        'override' => ['cloned_from' => $this->id], // an array of fields and values which will be set on the modal after Cloning(nullable).
                        'button_text' => 'Clone Shipment', // By deafult its copy\clone  icon.
                        'confirm_button_text' => 'Clone Shipment',   // by default 'confirm_button_text' => 'Copy\Clone',
                        'cancle_button_text' => 'Cancel'
                    ]),


            ];
    }


    public static function indexQuery(NovaRequest $request, $query)
    {
        // don't show tracking shipments
        $query->where("is_tracking_shipment", false);
        $query->where(function ($qq){
            $qq->where('is_draft', '!=', 1);
            $qq->orWhereNull('is_draft');
        });
        //
        if (method_exists($request, 'filters')) {
            $getFilters = $request->filters();

            $shipmentStatusFilter = null;
            $shipmentOwnFilter = null;

            if (count($getFilters) > 0) {
                foreach ($getFilters as $key => $filter) {
                    $className = get_class($filter->filter);
                    if ($className == 'App\Nova\Filters\ShipmentStatus') {
                        $shipmentStatusFilter = $filter->value;
                    } elseif ($className == 'App\Nova\Filters\ShipmentOwn') {
                        $shipmentOwnFilter = $filter->value;
                    }
                }
            }


            if ($request->user()->has('roles')) {
                $checkRoles = $request->user()->roles;
                $roles = ['Super Admin', 'Sales Representative', 'Account Manager'];
                $assigned_role = null;
                foreach ($checkRoles as $key => $checkRole) {
                    foreach ($roles as $key_second => $role) {
                        if ($checkRole->name == $role) {
                            $assigned_role = $role;
                        }
                    }
                }
                $value = $shipmentOwnFilter;
                if ($assigned_role == 'Super Admin') {
                    if ($value == 'me') {
                        if ($shipmentStatusFilter == null) {
                            return $query->where('shipment_status', '<>', 'Cancelled');
                        }/* else {
                            if($shipmentStatusFilter!='Cancelled') {
                                return $query->where('shipment_status','<>','Cancelled');
                            }
                        } */
                    } elseif ($value == 'unassigned') {
                        if ($shipmentStatusFilter == null) {
                            return $query->where('customer_id', null)
                                ->where('shipment_status', '<>', 'Cancelled');
                        } else {
                            /*  if($shipmentStatusFilter!='Cancelled') {
                                 return $query->where('shipment_status','<>','Cancelled')
                                              ->where('customer_id',null);
                             }else { */
                            return $query->where('customer_id', null);
                            //}
                        }
                    } elseif ($value == 'all') {
                        if ($shipmentStatusFilter == null) {
                            return $query->where('shipment_status', '<>', 'Cancelled');
                        }/* else {
                            if($shipmentStatusFilter!='Cancelled') {
                                return $query->where('shipment_status','<>','Cancelled');
                            }

                        } */
                    }
                } elseif ($assigned_role == 'Account Manager') {
                    $value = $shipmentOwnFilter;

                    //s
                    if ($value=='me') {

                        //$offices_managers = json_decode($request->user()->customer->offices_managers);

                        if ($shipmentStatusFilter==null) {
                            return $query->whereHas('customer', function ($q) use ($request) {
                                $office = $request->user()->office;
                                if (isset($office->shifl_office_id)) {
                                    $q->where(function ($qq) use ($office) {
                                        $qq->where('shifl_office_origin_from_id', $office->shifl_office_id);
                                        $qq->orWhere('shifl_office_origin_to_id', $office->shifl_office_id);
                                    });

                                }
                                //$q->orWhere('managers', $request->user()->id);
                                //$q->whereJsonContains('offices_managers', [['manager_id' => intval($request->user()->id)]]);

                            })

                            //->where('shifl_office_origin_from_id', )

                            ->where(function ($q) {
                                $q->where('shipment_status', '<>', 'Completed');
                            });
                        } else {
                            if ($shipmentStatusFilter!='Completed') {
                                return $query->whereHas('customer', function ($q) use ($request) {
                                    $office = $request->user()->office;
                                    if (isset($office->shifl_office_id)) {
                                        $q->where(function ($qq) use ($office) {
                                            $qq->where('shifl_office_origin_from_id', $office->shifl_office_id);
                                            $qq->orWhere('shifl_office_origin_to_id', $office->shifl_office_id);
                                        });
                                        //$q->where('shifl_office_origin_from_id', $office->shifl_office_id);
                                       // $q->orWhere('shifl_office_origin_to_id', $office->shifl_office_id);
                                    }
                                    //$q->orWhere('managers', $request->user()->id);
                                    //$q->whereJsonContains('offices_managers', [['manager_id' => intval($request->user()->id)]]);
                                })
                                ->where(function ($q) {
                                    $q->where('shipment_status', '<>', 'Completed');
                                });
                            } else {
                                return $query->whereHas('customer', function ($q) use ($request) {
                                    //$q->orWhere('managers', $request->user()->id);
                                    $q->whereJsonContains('offices_managers', [['manager_id' => intval($request->user()->id)]]);
                                });
                            }
                        }
                    } elseif ($value=='all') {
                        if ($shipmentStatusFilter==null) {
                           // return $query->where('shipment_status', '<>', 'Completed');

                            $office = $request->user()->office;
                            if (isset($office->shifl_office_id)) {

                                $query->where(function ($q) use ($office) {
                                    $q->where('shifl_office_origin_from_id', $office->shifl_office_id);
                                    $q->orWhere('shifl_office_origin_to_id', $office->shifl_office_id);
                                });

                            }
                            return $query->where('shipment_status', '<>', 'Completed');
                        } else {
                            if ($shipmentStatusFilter!='Completed') {
                                $office = $request->user()->office;
                                if (isset($office->shifl_office_id)) {

                                    $query->where(function ($q) use ($office) {
                                        $q->where('shifl_office_origin_from_id', $office->shifl_office_id);
                                        $q->orWhere('shifl_office_origin_to_id', $office->shifl_office_id);
                                    });
                                    //$query->where('shifl_office_origin_from_id', $office->shifl_office_id);
                                    //$query->orWhere('shifl_office_origin_to_id', $office->shifl_office_id);
                                }
                                return $query->where('shipment_status', '<>', 'Completed');
                            }
                        }
                    }
                    //e
                } elseif ($assigned_role == 'Sales Representative') {
                    if ($value == 'me') {
                        if ($shipmentStatusFilter == null) {
                            return $query->whereHas('customer', function ($q) use ($request) {
                                $q->where('sale_persons', $request->user()->id);
                            })
                                ->where('shipment_status', '<>', 'Cancelled');
                        } else {
                            /* if($shipmentStatusFilter!='Cancelled') {
                                return $query->whereHas('customer',function($q) use ($request){
                                        $q->where('sale_persons',$request->user()->id);
                                    })
                                    ->where('shipment_status','<>','Cancelled');
                            }else { */
                            return $query->whereHas('customer', function ($q) use ($request) {
                                $q->where('sale_persons', $request->user()->id);
                            });
                            //}
                        }
                    } elseif ($value == 'unassigned') {
                        if ($shipmentStatusFilter == null) {
                            return $query->where('customer_id', null)
                                ->where('shipment_status', '<>', 'Cancelled');
                        } else {
                            /*  if($shipmentStatusFilter!='Cancelled') {
                                 return $query->where('shipment_status','<>','Cancelled')
                                              ->where('customer_id',null);
                             }else { */
                            return $query->where('customer_id', null);
                            //}
                        }
                    } elseif ($value == 'all') {
                        if ($shipmentStatusFilter == null) {
                            return $query->where('shipment_status', '<>', 'Cancelled');
                        }/* else {
                            if($shipmentStatusFilter!='Cancelled') {
                                return $query->where('shipment_status','<>','Cancelled');
                            }
                        } */
                    }
                }
            }
        } else {
            return $query;
        }
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
        $super_admin_account_mananger = [
            new Filters\ShipmentOwn,
            new Filters\ShipmentStatus,
            new Filters\ShipmentOriginCountry,
            new Filters\ShipmentOfficeFrom,
            new Filters\ShipmentOfficeTo,
            new Filters\InternalExternal,
            new Filters\Customer,
            new Filters\MBL,
            new Filters\Missing,
            new DateRangeFilter('ETA', 'eta', [
                Config::ALLOW_INPUT => false,
                Config::DATE_FORMAT => 'Y-m-d',
                Config::DEFAULT_DATE => [],
                Config::DISABLED => false,
                Config::ENABLE_TIME => false,
                Config::ENABLE_SECONDS => false,
                Config::FIRST_DAY_OF_WEEK => 0,
                Config::LOCALE => 'default',
                Config::MAX_DATE => '',
                Config::MIN_DATE => '2019-01-01',
                Config::PLACEHOLDER => __('Choose date range'),
                Config::SHORTHAND_CURRENT_MONTH => false,
                Config::SHOW_MONTHS => 1,
                Config::TIME24HR => false,
                Config::WEEK_NUMBERS => false,
            ]),
            new DateRangeFilter('ETD', 'etd', [
                Config::ALLOW_INPUT => false,
                Config::DATE_FORMAT => 'Y-m-d',
                Config::DEFAULT_DATE => [],
                Config::DISABLED => false,
                Config::ENABLE_TIME => false,
                Config::ENABLE_SECONDS => false,
                Config::FIRST_DAY_OF_WEEK => 0,
                Config::LOCALE => 'default',
                Config::MAX_DATE => '',
                Config::MIN_DATE => '2019-01-01',
                Config::PLACEHOLDER => __('Choose date range'),
                Config::SHORTHAND_CURRENT_MONTH => false,
                Config::SHOW_MONTHS => 1,
                Config::TIME24HR => false,
                Config::WEEK_NUMBERS => false,
            ]),
            new DateRangeFilter('Created at', 'created_at', [
                Config::ALLOW_INPUT => false,
                Config::DATE_FORMAT => 'Y-m-d',
                Config::DEFAULT_DATE => [],
                Config::DISABLED => false,
                Config::ENABLE_TIME => false,
                Config::ENABLE_SECONDS => false,
                Config::FIRST_DAY_OF_WEEK => 0,
                Config::LOCALE => 'default',
                Config::MAX_DATE => '',
                Config::MIN_DATE => '',
                Config::PLACEHOLDER => __('Choose date range'),
                Config::SHORTHAND_CURRENT_MONTH => false,
                Config::SHOW_MONTHS => 1,
                Config::TIME24HR => false,
                Config::WEEK_NUMBERS => false,
            ]),
            new DateRangeFilter('Confirmed', 'booking_confirmed_at', [
                Config::ALLOW_INPUT => false,
                Config::DATE_FORMAT => 'Y-m-d',
                Config::DEFAULT_DATE => [],
                Config::DISABLED => false,
                Config::ENABLE_TIME => false,
                Config::ENABLE_SECONDS => false,
                Config::FIRST_DAY_OF_WEEK => 0,
                Config::LOCALE => 'default',
                Config::MAX_DATE => '',
                Config::MIN_DATE => '2019-01-01',
                Config::PLACEHOLDER => __('Choose date range'),
                Config::SHORTHAND_CURRENT_MONTH => false,
                Config::SHOW_MONTHS => 1,
                Config::TIME24HR => false,
                Config::WEEK_NUMBERS => false,
            ]),
        ];
        if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Account Manager')){
            $am = false;
            if(auth()->user()->hasRole('Account Manager')){
                $account_managers = array('carla@shifl.com', 'frady@shifl.com', 'tehilla@shifl.com', 'shia@shifl.com');
                foreach ($account_managers as $account_manager){
                    if(strtolower(auth()->user()->email) == $account_manager){
                        $am = true;
                    }
                }
                if($am){
                    array_splice($super_admin_account_mananger, 5, 0, array(new Filters\AccountManager) );
                }
            }else{
                array_splice($super_admin_account_mananger, 5, 0, array(new Filters\AccountManager) );
            }
        }

        return $super_admin_account_mananger;
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
            (new Actions\ConvertToExternal)
                ->onlyOnDetail()
                ->confirmText('Are you sure you want to convert this shipment to an External Shipment?')
                ->confirmButtonText('Ok')
                ->cancelButtonText("Cancel"),
            (new Actions\ShipmentCancelMblRemoved())
                ->onlyOnDetail()
                ->confirmText('Are you sure you want to cancel this shipment?')
                ->confirmButtonText('Ok')
                ->cancelButtonText("Cancel"),
        ];
    }

    /**
     * Get the perPageOptions for the resource.
     *
     * @return array
     */

    public static function perPageOptions()
    {
        //return [100,50,25];
        return [50, 25, 100];
    }

    public function checkImportNameStatus()
    {
        $import = $this->importNamesfiltered()->get();
        if(count($import) > 0){
            return $this->merge([
                BelongsTo::make('Import Names', 'importNamesfiltered', 'App\Nova\ImportNames')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->searchable()
            ]);
        }else{
            return $this->merge([]);
        }
    }
}
