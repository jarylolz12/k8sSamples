<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\ShiflOfficeEmail;
class ShiflOffice extends Model
{
    //
    protected $fillable = ['name','country_id', 'managers_lists', 'address', 'phone_number'];

    protected $casts = [
        'emails' => 'array',
        'all_email_emails' => 'array',
        'booking_and_updated_emails' => 'array',
        'booking_confirmation_emails' => 'array',
        'agent_booking_confirmation_emails' => 'array',
        'departure_notice_emails' => 'array',
        'arrival_notice_emails' => 'array',
        'delivery_order_emails' => 'array',
        'booking_email_recipients' => 'array',
        'eta_alert_emails' => 'array',
        'eta_alert_trucker_emails' => 'array',
        'customer_entry_notice_emails' => 'array',
        'carrier_eta_discrepancy_emails' => 'array',
        'discharged_discrepancy_emails' => 'array',
        'ata_discrepancy_emails' => 'array',
        'manual_tracking_report_emails' => 'array',
        'container_count_mismatch_emails' => 'array'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shifl_offices';


    /*
    public function country()
    {
        return $this->belongsTo(Country::class);
    }*/

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'shifl_offices_countries', 'shifl_office_id', 'country_id');
    }

    public function managers() {

        //return $this->hasMany(AccountManager::class);
        return $this->belongsToMany(AccountManager::class, 'shifl_offices_managers', 'shifl_office_id', 'manager_id');
    }

    public function shiflEmails() {
        return $this->hasMany(ShiflOfficeEmail::class);
    }
    

    public static function saveOffice($getItem) {


        if (isset($getItem->managers_lists)) {

            $managers_lists = json_decode($getItem->managers_lists);

            if (is_array($managers_lists) && count($managers_lists) > 0) {
                $checkOfficesManagers = \DB::table('shifl_offices_managers')
                                   ->whereIn('manager_id', $managers_lists)
                                   ->get();

                if (isset($checkOfficesManagers[0])) {

                    //updates now the record
                    /*
                    \DB::table('shifl_offices_managers')
                        ->whereIn('manager_id', $managers_lists)
                        ->update([
                            'shifl_office_id' => $getItem->id,
                            'updated_at' => Carbon::now()
                        ]);

                    \DB::table('shifl_offices_managers')
                        ->whereNotIn('manager_id', $managers_lists)
                        ->delete();*/

                } else {

                    $inserts = [];
                    foreach ($managers_lists as $key => $value) {
                        array_push($inserts, 
                            [
                                'manager_id' => $value,
                                'shifl_office_id' => $getItem->id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]
                        );
                    }
                    
                    \DB::table('shifl_offices_managers')
                       ->insert($inserts);

                    $customers = \App\Customer::all();

                    if (count ($customers) > 0) {
                        foreach ($customers as $key => $customer) {
                            \App\Events\UpdateCustomerEvent::dispatch($customer);
                        }
                    }

                    
                }
            }

                
        }

        

    }

    public static function boot() {

        parent::boot();

        static::created(function (ShiflOffice $item) {

            if (isset($item->id) && $item->id!=='') {
                static::saveOffice($item);

                ShiflOfficeEmail::insert([[
                    'office_id' => $item->id,
                    'type' => 'from'
                ],[
                    'office_id' => $item->id,
                    'type' => 'to'
                ]]);
            }

        });

        static::saving(function (ShiflOffice $getItem) {

            if ( isset($getItem->office_emails)) {
                if ( isset($getItem->id) ) {
                    $office_emails = json_decode($getItem->office_emails);
                    $offices = ShiflOfficeEmail::where('office_id', $getItem->office_id)->get();


                    $fields = ['booking_and_updated_emails', 'booking_confirmation_emails', 'agent_booking_updated_emails', 'agent_booking_confirmation_emails', 'departure_notice_emails', 'arrival_notice_emails', 'customer_entry_notice_emails', 'delivery_order_emails', 'eta_alert_emails', 'eta_alert_trucker_emails', 'carrier_eta_discrepancy_emails', 'discharged_discrepancy_emails', 'ata_discrepancy_emails', 'booking_and_updated_emails', 'booking_confirmation_emails', 'agent_booking_updated_emails', 'agent_booking_confirmation_emails', 'departure_notice_emails', 'arrival_notice_emails', 'customer_entry_notice_emails', 'delivery_order_emails', 'eta_alert_emails', 'eta_alert_trucker_emails', 'carrier_eta_discrepancy_emails', 'discharged_discrepancy_emails', 'ata_discrepancy_emails', 'manual_tracking_report_emails'];

                    if ( count($offices) > 0 ) {
                        foreach( $offices as $office ) {

                            $types = ['to', 'from', 'all'];

                            foreach ( $types as $type ) {

                                if ( $office->type == $type) {
                                    if ( is_array($office_emails)) {
                                        foreach ($office_emails as $office_email ) {

                                            if ( $office_email->type == $type) {

                                                foreach ($fields as $field ) {
                                                    if ( $office_email->field === $field ) {
                                                        $office->{$field} = $office_email->value;
                                                    }
                                                }
                                                $office->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                unset($getItem->office_emails);
                unset($getItem->office_id);
            }

        });


        static::updated(function (ShiflOffice $item) {
            if (isset($item->shifl_office_id) && $item->shifl_office_id!=='') {
                static::saveOffice($item);
            }

        });

        static::saved(function (ShiflOffice $item) {

            if (isset($item->shifl_office_id) && $item->shifl_office_id!=='') {
                static::saveOffice($item);
            }

        });

    }

}
