<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiflOfficeEmail extends Model
{

	protected $table = 'office_emails';
	protected $fillable = ['type', 'office_id'];

	protected $casts = [
		'all_email_emails' => 'array',
		'booking_and_updated_emails' => 'array',
		'booking_confirmation_emails' => 'array',
		'agent_booking_updated_emails' => 'array',
		'agent_booking_confirmation_emails' => 'array',
		'departure_notice_emails' => 'array',
		'arrival_notice_emails' => 'array',
		'delivery_order_emails' => 'array',
		'eta_alert_emails' => 'array',
		'eta_alert_trucker_emails' => 'array',
		'customer_entry_notice_emails' => 'array',
		'carrier_eta_discrepancy_emails' => 'array',
		'discharged_discrepancy_emails' => 'array',
		'ata_discrepancy_emails' => 'array',
		'manual_tracking_report_emails' => 'array',
		'container_count_mismatch_emails' => 'array'
    ];

	public function office(){
		return $this->belongsTo(ShiflOffice::class);
    }

}