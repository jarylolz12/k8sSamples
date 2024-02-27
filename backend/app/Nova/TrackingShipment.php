<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Kenetashi\ShipmentCustomerDocuments\ShipmentCustomerDocuments;
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
use Vishalmarakana\ShipmentNotes\ShipmentNotes;
use Whitecube\NovaFlexibleContent\Flexible;
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
use Tanvirofficials\ManualTrackingTab\ManualTrackingTab;

class TrackingShipment extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Shipment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return 'Shifl Ref : ' . $this->shifl_ref;
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'shifl_ref',
        'mbl_num',

    ];
    public static $with = ['customer'];

    public static function label() {
        return 'External Shipments';
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
      $carriers = \App\Carrier::all();

      return ($this->cancelled) ?
          [
              (new Tabs('External Shipment Details | Other Tabs are hidden because Shipment is in cancelled milestone', [
                  'Header Information' => [
                    Text::make('Shifl Reference', 'shifl_ref')->sortable()->hideWhenCreating(),

                    BelongsTo::make('customer', 'customer')->searchable()->required()->onlyOnForms()->hideWhenUpdating(),

                    //Text::make('PO #', 'po_num')->hideWhenUpdating()->onlyOnForms(),

                    Hidden::make('is_tracking_shipment')->default(1)->onlyOnForms()->hideWhenUpdating(),

                    Select::make('Shifl Office From', 'shifl_office_origin_from_id')
                          ->rules('required')
                          ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                          ->hideFromDetail()
                          ->hideFromIndex()
                          ->hideWhenCreating()
                          ->searchable(),
                    Select::make('Shifl Office To', 'shifl_office_origin_to_id')
                          ->rules('required')
                          ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                          ->hideFromDetail()
                          ->hideFromIndex()
                          ->hideWhenCreating()
                          ->searchable(),


                    BelongsTo::make('Customer', 'customer')->hideWhenCreating()->hideWhenUpdating()->searchable(),

                    Text::make('MBL#d', 'mbl_num')
                        ->sortable()->rules('required')->hideWhenUpdating()->hideFromDetail(),

                    //Text::make('PO #', 'po_num')->onlyOnIndex(),


                    CustomerField::make('Customer', 'custom_customer')->initFields($this->customer)->onlyOnForms()->hideFromDetail()->hideFromIndex()->hideWhenCreating()->required(),

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


                    Textarea::make('Notes', 'custom_notes')->hideWhenCreating(),
                    ShipmentServicesSectionField::make('Services', 'services_section')->hideFromIndex()->hideWhenCreating(),


                    ShipmentMilestoneComponent::make('Booking Confirmed', 'booking_confirmed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'booking_confirmed'),
                        ]
                    )->hideFromIndex()->readonly()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('SO/BC Released', 'so_released')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'so_released'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('ISF Done', 'isf_done')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'isf_done'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('AMS Done', 'ams_done')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'ams_done'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Rate Confirmed', 'rate_confirmed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'rate_confirmed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('MBL Released Confirmed', 'mbl_released_confirmed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'mbl_released_confirmed'),
                        ]
                    )->onlyOnDetail()->hideWhenCreating(),

                    MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                        ->initFields($this->id,'header')->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Arrival Notice Sent', 'arrival_notice')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'arrival_notice'),
                        ]
                    )->onlyOnDetail()->hideWhenCreating(),

                    Text::make('Arrival Notice Sent At', 'an_sent_at')->onlyOnDetail()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Arrival Notice Received', 'arrival_notice_confirmed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'arrival_notice_confirmed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    Boolean::make('Customs Sent', function() {
                        return ($this->entry_netchb_submitted==1) ? 1 : 0;
                    })->onlyOnDetail()->hideWhenCreating(),
                    Text::make('Customs Sent At', function() {
                        if ( $this->entry_netchb_submitted==1 ) {
                            return $this->entry_netchb_date;
                        } else {
                            return '';
                        }
                    })->onlyOnDetail()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Customs Processed', 'customs_processed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'customs_processed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Freight Released Processed', 'freight_released_processed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'freight_released_processed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    Boolean::make('DO Sent', 'delivery_order_left')->onlyOnDetail()->hideWhenCreating(),
                    Text::make('DO Sent At', 'do_sent_at')->onlyOnDetail()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('DO Confirmed', 'DO_confirmed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'DO_confirmed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Freight Released Confirmed', 'freight_confirmed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'freight_confirmed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Customs Released Confirmed', 'customs_released')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'customs_released'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Released At Terminal', 'released_at_terminal')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'released_at_terminal'),
                        ]
                    )->readonly()->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Pending Refund', 'pending_refund')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'pending_refund'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Delivered', 'delivered')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'delivered'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Billed', 'billed')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'billed'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),

                    ShipmentMilestoneComponent::make('Cancelled', 'cancelled')->initFields(
                        [
                            'milestone' => ShipmentMilestoneModel::firstWhere('name', 'cancelled'),
                        ]
                    )->hideFromIndex()->hideWhenCreating(),




                    Button::make('CREATE SHIPMENT')->link(url('/administrator/resources/shipments/new?viaResource=&viaResourceId=&viaRelationship='), '_self')->onlyOnDetail(),


                    InfoSaveButton::make('SAVE')->onlyOnForms()->hideWhenCreating(),

                    Boolean::make('Cancelled' , 'cancelled')->onlyOnIndex(),
                  ],

              ]))->withToolbar(),
              CopyClone::make()
                  ->withMeta([
                      'resource' => 'shipments', // resource url
                      'model' => 'App\Shipment', // model path
                      'id' => $this->id, // id of record
                      'except' => ['shifl_ref', 'mbl_copy', 'debit_note'], // an array of fields to not replicate (nullable).
                      'override' => ['cloned_from' => $this->id], // an array of fields and values which will be set on the modal after Cloning(nullable).
                      'button_text' => 'Clone Shipment', // By deafult its copy\clone  icon.
                      'confirm_button_text' => 'Clone Shipment',   // by default 'confirm_button_text' => 'Copy\Clone'
                  ]),
          ]
          :
          [
              (new Tabs('External Shipment Details', [
                  'Header Information' => [
                      Text::make('Shifl Reference', 'shifl_ref')->sortable()->hideWhenCreating(),

                      BelongsTo::make('customer', 'customer')->searchable()->required()->onlyOnForms()->hideWhenUpdating(),

                      //Text::make('PO #', 'po_num')->hideWhenUpdating()->onlyOnForms(),

                      Hidden::make('is_tracking_shipment')->default(1)->onlyOnForms()->hideWhenUpdating(),

                      Select::make('Shifl Office From', 'shifl_office_origin_from_id')
                            ->rules('required')
                            ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                            ->hideFromDetail()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->searchable(),
                      Select::make('Shifl Office To', 'shifl_office_origin_to_id')
                            ->rules('required')
                            ->options(\App\ShiflOffice::all()->pluck('name', 'id'))
                            ->hideFromDetail()
                            ->hideFromIndex()
                            ->hideWhenCreating()
                            ->searchable(),


                      BelongsTo::make('Customer', 'customer')->hideWhenCreating()->hideWhenUpdating()->searchable(),

                      Text::make('MBL#', 'mbl_num')
                          ->sortable()->rules('required')->hideWhenUpdating()->hideFromDetail(),

                      CustomerField::make('Customer', 'custom_customer')->initFields($this->customer)->onlyOnForms()->hideFromDetail()->hideFromIndex()->hideWhenCreating()->required(),

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


                      Textarea::make('Notes', 'custom_notes')->hideWhenCreating(),
                      ShipmentServicesSectionField::make('Services', 'services_section')->hideFromIndex()->hideWhenCreating(),


                      ShipmentMilestoneComponent::make('Booking Confirmed', 'booking_confirmed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'booking_confirmed'),
                          ]
                      )->hideFromIndex()->readonly()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('SO/BC Released', 'so_released')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'so_released'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('ISF Done', 'isf_done')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'isf_done'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('AMS Done', 'ams_done')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'ams_done'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Rate Confirmed', 'rate_confirmed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'rate_confirmed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('MBL Released Confirmed', 'mbl_released_confirmed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'mbl_released_confirmed'),
                          ]
                      )->onlyOnDetail()->hideWhenCreating(),

                      MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                          ->initFields($this->id,'header')->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Arrival Notice Sent', 'arrival_notice')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'arrival_notice'),
                          ]
                      )->onlyOnDetail()->hideWhenCreating(),

                      Text::make('Arrival Notice Sent At', 'an_sent_at')->onlyOnDetail()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Arrival Notice Received', 'arrival_notice_confirmed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'arrival_notice_confirmed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      Boolean::make('Customs Sent', function() {
                          return ($this->entry_netchb_submitted==1) ? 1 : 0;
                      })->onlyOnDetail()->hideWhenCreating(),
                      Text::make('Customs Sent At', function() {
                          if ( $this->entry_netchb_submitted==1 ) {
                              return $this->entry_netchb_date;
                          } else {
                              return '';
                          }
                      })->onlyOnDetail()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Customs Processed', 'customs_processed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'customs_processed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Freight Released Processed', 'freight_released_processed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'freight_released_processed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      Boolean::make('DO Sent', 'delivery_order_left')->onlyOnDetail()->hideWhenCreating(),
                      Text::make('DO Sent At', 'do_sent_at')->onlyOnDetail()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('DO Confirmed', 'DO_confirmed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'DO_confirmed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Freight Released Confirmed', 'freight_confirmed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'freight_confirmed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Customs Released Confirmed', 'customs_released')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'customs_released'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Released At Terminal', 'released_at_terminal')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'released_at_terminal'),
                          ]
                      )->readonly()->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Pending Refund', 'pending_refund')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'pending_refund'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Delivered', 'delivered')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'delivered'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Billed', 'billed')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'billed'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),

                      ShipmentMilestoneComponent::make('Cancelled', 'cancelled')->initFields(
                          [
                              'milestone' => ShipmentMilestoneModel::firstWhere('name', 'cancelled'),
                          ]
                      )->hideFromIndex()->hideWhenCreating(),




                      Button::make('CREATE SHIPMENT')->link(url('/administrator/resources/shipments/new?viaResource=&viaResourceId=&viaRelationship='), '_self')->onlyOnDetail(),


                      InfoSaveButton::make('SAVE')->onlyOnForms()->hideWhenCreating(),



                  ],
                  'Booking' => [
                      Hidden::make('Multi Purchae Orders', 'multi_purchase_orders')->hideWhenCreating(),
                      Hidden::make('Personal Token', 'personal_token')->hideWhenCreating(),

                      BookingsTabSaveGroup::make('BookingsTabSave')
                          ->initFields($this->id,$this->shifl_ref)
                          ->hideFromIndex(),
                      ShipmentContainerLimitedGroup::make('Containers Section', 'containers_group_bookings')
                          ->hidefromIndex()
                          ->hideWhenUpdating()
                          ->hideWhenCreating()
                          ->initFields($this->id),


                      Textarea::make('Memo', 'memo_customer')->onlyOnDetail(),
                      CustomButton::make('Booking Email Buttons')->initFields($this->id)->onlyOnDetail(),
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
                          ->initFields($this->id)
                          ->hideFromIndex()
                          ->hideWhenCreating(),

                      Text::make('MBL#', 'mbl_num')
                              ->sortable()->rules('required')->onlyOnDetail(),
                      //Text::make('PO #', 'po_num')->hideWhenUpdating()->hideWhenCreating(),
                      Boolean::make('Cancelled' , 'cancelled')->onlyOnIndex(),

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


                      MblCopySurrendered::make('MBL Copy Surrendered/Released','mbl_copy_surrendered')
                          ->initFields($this->id,'bl')->hideFromIndex()->hideWhenUpdating()->hideWhenCreating()->hideFromDetail(),

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

                      Date::make('Estimated Time of Arrival', 'eta')
                          ->onlyOnDetail()->format('MM/DD/YYYY'),
                      Date::make('Carrier Arrival Notice Eta', 'carrier_arrival_notice_eta')
                          ->onlyOnDetail()->format('MM/DD/YYYY'),
                      Button::make('Send Arrival Notice')->event('App\Events\SendArrivalNoticeEvent')->confirm("Are You sure ?")->visible(!$this->arrival_notice)->onlyOnDetail(),
                      Button::make('Resend Arrival Notice')->event('App\Events\SendArrivalNoticeEvent')->confirm("Are You sure ?")->visible($this->arrival_notice)->onlyOnDetail()->style('link-success'),

                  ],
                  'Customs' => [
                      EntryNetchbField::make('NETCHB ENTRY FIELD')->onlyOnDetail()->initFields($this->id)
                  ],
                  'Delivery Order' => [
                      ShipmentDeliverySaveDeliveryOrder::make('Delivery Order Group button')
                          ->initiate($this->id)
                          ->hideFromIndex()
                          ->hideFromDetail()
                          ->hideWhenCreating(),



                      Select::make('Type', 'type')
                          ->options([
                              'LCL' => 'LCL',
                              'FCL' => 'FCL',
                              'Air' => 'Air'
                          ])
                          ->displayUsingLabels()
                          ->onlyOnDetail(),

                      Button::make('Send Delivery Order')->event('App\Events\SendDeliveryOrderEvent')->confirm("Are You sure ?")->visible(!$this->delivery_order_left)->onlyOnDetail(),
                      Button::make('Resend Delivery Order')->event('App\Events\SendDeliveryOrderEvent')->confirm("Are You sure ?")->visible($this->delivery_order_left)->onlyOnDetail()->style('link-success'),
                  ],
                  'Charges' => [
                      ChargesTab::make('Charges')->hideFromIndex()->readonly()->hideWhenCreating(),
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
                          return auth()->user()->hasRole('Super Admin');
                      }),
                  ],
              ]))->withToolbar(),
              CopyClone::make()
                  ->withMeta([
                      'resource' => 'shipments', // resource url
                      'model' => 'App\Shipment', // model path
                      'id' => $this->id, // id of record
                      'except' => ['shifl_ref', 'mbl_copy', 'debit_note'], // an array of fields to not replicate (nullable).
                      'override' => ['cloned_from' => $this->id], // an array of fields and values which will be set on the modal after Cloning(nullable).
                      'button_text' => 'Clone Shipment', // By deafult its copy\clone  icon.
                      'confirm_button_text' => 'Clone Shipment',   // by default 'confirm_button_text' => 'Copy\Clone'
                  ]),


          ];
    }


    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where("is_tracking_shipment", 1);
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
        return [
            (new Actions\ConvertToInternal)
                ->onlyOnDetail()
                ->confirmText('Are you sure you want to convert this shipment to an Internal Shipment?')
                ->confirmButtonText('Ok')
                ->cancelButtonText("Cancel"),
        ];
    }
}
