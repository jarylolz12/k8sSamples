<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Kenetashi\OfficeManagersField\OfficeManagersField;
use Kenetashi\MultipleSelectField\MultipleSelectField;
use Kenetashi\OfficeEmailGroup\OfficeEmailGroup;



use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use App\Custom\Traits\ResourceTrait;


class ShiflOffice extends Resource
{

    use TabsOnEdit;
    use ResourceTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\ShiflOffice::class;

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
        'id',
        'name'
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
            return true;
            //return $fail($message);
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
           return true;
           // return $fail($message);
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



    /**
     * force sync the eta and etd with t49
     *
     * @param  void
     * @return bool
     */

    private function syncT49()
    {
        $shipment_t49 = Terminal49Shipment::find($this->shipment->mbl_num);
        if (empty($shipment_t49)) {
            return false;
        }
        $attributes = json_decode($shipment_t49->attributes);

        $eta = $attributes->pod_eta_at;

        if (empty($eta)) {
            return false;
        }

        $eta = Carbon::parse($eta);

        $schedule = $this->processSchedulesGroupBookings->getSchedule();

        $app_eta = Carbon::parse($schedule->eta);

        if ($eta->format("Y-m-d") == $app_eta->format("Y-m-d")) {
            return false;
        }
        // old eta
        $old_eta = "";
        $origianl_schedule = $this->processSchedulesGroupBookings->getSchedule($this->shipment->getOriginal("schedules_group_bookings"));

        if (!is_null($origianl_schedule)) {
            if (!empty($origianl_schedule->eta)) {
                $old_eta = Carbon::parse($origianl_schedule->eta);
            }
        }
        //

        $schedules_group_bookings = json_decode($this->shipment->schedules_group_bookings);
        foreach ($schedules_group_bookings as $schedules_group_booking) {
            // code...
            if ($schedules_group_booking->is_confirmed) {
                $schedules_group_booking->eta = $eta->format("Y-m-d 00:00:00");
                break;
            }
        }
        $this->shipment->schedules_group_bookings = json_encode($schedules_group_bookings);
        $this->shipment->eta = $eta->format("Y-m-d");

        if (!empty($old_eta)) {
            if ($old_eta->format("Y-m-d") != $eta->format("Y-m-d")) {
                //
                self::send($this->shipment, $this->processSchedulesGroupBookings->getSchedule($this->shipment->schedules_group_bookings), $old_eta, $this->getAis($this->shipment));
            }
        }
        return true;
    }

    public function fields($request) {

        
        if ( $this->isIndex($request) || $this->isCreate($request)) {
            return $this->defaultFields($request);
        } else {
            /*
            $recipients = [
                MultipleSelectField::make('All Emails','all_email_emails')
                    ->loadOptions($this->all_email_emails, 'No Email added yet.', 'office', $this->id)
                    ->rules('required', function ($attribute, $value, $fail){
                        $this->requireRuleCallback($attribute, $value, $fail, 'The all emails field should contain at least 1 email.');
                    })
                    ->hideFromDetail()
                    ->hideFromIndex(),
                MultipleSelectField::make('Booking & Updates','booking_and_updated_emails')
                       ->loadOptions($this->booking_and_updated_emails, 'No Booking and Update Booking email added yet.','office', $this->id)
                        ->rules('required', function($attribute, $value, $fail) {
                            $this->requireNonShiflRule($attribute, $value, $fail, 'Booking and Updated Booking field is required.');
                        })
                       ->hideFromIndex(),
                MultipleSelectField::make('Booking Confirmed','booking_confirmation_emails')
                       ->loadOptions($this->booking_confirmation_emails, 'No Booking Confirmation email added yet.','office', $this->id)
                       ->rules('required', function($attribute, $value, $fail) {
                        $this->requireNonShiflRule($attribute, $value, $fail, 'Booking Confirmation field is required.');
                        })
                       ->hideFromIndex(),
                MultipleSelectField::make('Agent Booking & Updates', 'agent_booking_updated_emails')
                       ->loadOptions($this->agent_booking_updated_emails, 'No Agent Booking and Updated Email added yet.','office', $this->id)
                       ->rules('required', function ($attribute, $value, $fail) {
                            $this->requireOnlyShiflRule($attribute, $value, $fail, 'Agent and Updated Booking field is required.');
                        })
                       ->hideFromIndex(),
                MultipleSelectField::make('Agent Booking Confirmed ','agent_booking_confirmation_emails')
                       ->loadOptions($this->agent_booking_confirmation_emails, 'No Agent Booking Confirmation email added yet.','office', $this->id)
                       ->rules('required', function ($attribute, $value, $fail) {
                            $this->requireOnlyShiflRule($attribute, $value, $fail, 'Agent Booking Confirmation field is required.');
                        })
                       ->hideFromIndex(),
                MultipleSelectField::make('Departure Notice', 'departure_notice_emails')
                       ->loadOptions($this->departure_notice_emails, 'No Departure Notice email added yet.','office', $this->id)
                       ->rules('required', function($attribute, $value, $fail) {
                        $this->requireNonShiflRule($attribute, $value, $fail, 'Departure Notice email is required.');
                        })
                       ->hideFromIndex(),
                MultipleSelectField::make('Arrival Notice', 'arrival_notice_emails')
                       ->loadOptions($this->arrival_notice_emails, 'No Arrival Notice email added yet.','office', $this->id)
                       ->rules('required', function($attribute, $value, $fail) {
                        $this->requireNonShiflRule($attribute, $value, $fail, 'Arrival Notice email is required.');
                        })
                       ->hideFromIndex(),
                MultipleSelectField::make('Delivery Order', 'delivery_order_emails')
                       ->loadOptions($this->delivery_order_emails, 'No Delivery Order email added yet.','office', $this->id)
                       ->hideFromIndex(),
                MultipleSelectField::make('ETA Alert', 'eta_alert_emails')
                       ->loadOptions($this->eta_alert_emails, 'No ETA Alert email added yet.','office', $this->id)
                       ->hideFromIndex(),
                MultipleSelectField::make('ETA Alert for trucker', 'eta_alert_trucker_emails')
                       ->loadOptions($this->eta_alert_trucker_emails, 'No ETA Alert for trucker email added yet.','office', $this->id)
                       ->hideFromIndex(),
            ];

            if ( $this->name === 'Shifl USA' ) {

                $us_recipients = [
                    MultipleSelectField::make('Manual Tracking Report (Maintenance)', 'manual_tracking_report_emails')
                    ->loadOptions($this->manual_tracking_report_emails, 'No manual tracking report(maintenance) email added yet.','office', $this->id)
                    ->hideFromIndex(),
                    MultipleSelectField::make('ATA Discrepancy ', 'ata_discrepancy_emails')
                       ->loadOptions($this->ata_discrepancy_emails, 'No ata discrepancy email added yet.','office', $this->id)
                       ->hideFromIndex(),
                    MultipleSelectField::make('Carrier ETA Discrepancy', 'carrier_eta_discrepancy_emails')
                       ->loadOptions($this->carrier_eta_discrepancy_emails, 'No Carrier ETA Discrepancy email added yet.','office', $this->id)
                       ->hideFromIndex(),

                    MultipleSelectField::make('Discharged Discrepancy ', 'discharged_discrepancy_emails')
                    ->loadOptions($this->eta_alert_trucker_emails, 'No discharged discrepancy email added yet.','office', $this->id)
                           ->hideFromIndex(),
                    MultipleSelectField::make('Container Count Mismatch', 'container_count_mismatch_emails')
                           ->loadOptions($this->carrier_eta_discrepancy_emails, 'No container count mismatch email added yet.','office', $this->id)
                        ->hideFromIndex()
                ];
                $recipients = array_merge($recipients, $us_recipients);
            }*/

            $recipients = [
                OfficeEmailGroup::make('Office Section','office_emails_section')
                                ->loadData($this->id)
                                ->hideWhenCreating()
                                ->hideFromIndex()
            ];

            return [
                (new Tabs('Office Details', [
                    'Header Information' => [
                        ID::make(__('ID'), 'id')->sortable(),
                        Text::make('Name', 'name')->rules('required'),
                        Text::make('Address', 'address')->rules('required'),
                        Text::make('Phone Number', 'phone_number')->rules('required'),
                        BelongsToMany::make('Country', 'countries')->searchable(),
                        BelongsToMany::make('Account Managers', 'managers'),
                        OfficeManagersField::make('Managers', 'managers_lists')
                                            ->initFields($this)
                                            ->hideFromIndex()
                                            ->hideWhenUpdating()
                                            ->hideFromDetail()
                    ],
                    'Recipients' => $recipients
                ]
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
    public function defaultFields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Name', 'name')->rules('required'),
            Text::make('Address', 'address')->rules('required'),
            Text::make('Phone Number', 'phone_number')->rules('required'),
            BelongsToMany::make('Country', 'countries')->searchable(),
            BelongsToMany::make('Account Managers', 'managers'),
            OfficeManagersField::make('Managers', 'managers_lists')
                                ->initFields($this)
                                ->hideFromIndex()
                                ->hideWhenUpdating()
                                ->hideFromDetail()
                                /*
                                ->rules('required', function ($attribute, $value, $fail) {
                                    $getValue = json_decode($value);
                                    if (count($getValue) == 0) {
                                        $fail('Please make sure to assign an account manager to this office.');
                                    }
                                    
                                })*/
            //BelongsToMany::make('Account Manager', 'managers')->searchable()
            //BelongsTo::make('Country', 'country')->searchable()
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
