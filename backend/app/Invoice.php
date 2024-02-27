<?php

namespace App;

use App\Card;
use App\InvoiceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class Invoice extends Model
{
    protected $appends = ['opening_balance','total_paid_amount','is_duty_invoice','total_duty_amount','mbl_num','is_sent'];

    protected $fillable = [
        'qbo_customer_id',
        'qbo_customer_name',
        'invoice_num',
        'qbo_bill_to_email',
        'qbo_bill_to_address',
        'term',
        'invoice_date',
        'due_date',
        'shipment_id',
        'qbo_id',
        'qbo_term_id',
        'qbo_term_name',
        'qbo_term_days',
        'qbo_customer_memo',
        'qbo_country',
        'qbo_tax_detail',
        'allow_credit_card_payment',
        'allow_online_ach_payment',
        'total_tax',
        'total_amount',
        'is_email_sent',
        'email_sent_count',
        'email_sent_at',
        'shifl_office_from_id',
        'shifl_office_to_id',
        'qbo_company',
        'home_total_amount',
        'balance',
        'home_balance',
        'currency_ref',
        'home_currency_ref',
        'exchange_rate',
        'exchange_rate_info',
        'global_tax_calculation',
        'sync_token',
        'payment_method',
        'paid_on',
        'is_processed'
    ];

    protected $casts = ['invoice_date' => 'date', 'due_date' => 'date'];
    
    public static $invoice_service_date = '2022-12-06';

    /* public function customer()
    {
        return $this->belongsTo('App\Customer');
    } */

    public static function boot()
    {
        parent::boot();
        self::deleting(function($invoice) {
             $invoice->invoiceServices()->each(function($invoice) {
                $invoice->delete();
             });
        });
    }

    public function shipment()
    {
        return $this->belongsTo('App\Shipment');
    }

    public function paymentMethod()
    {
        $paymentMethod = $this->payment_method;
        if ($this->payment_method_processor === 'ACH') {
            $paymentMethod = 'ACH';
        } elseif ($this->payment_method_processor === 'CC') {
            $card = Card::where('id', $this->payment_method)->first();
            if ($card)
                $paymentMethod = $card->number_masked;
        }
        $string = $paymentMethod;
        $replaceLength = ((int)strlen($string) - (int)4);
        $paymentMethod = str_replace(substr($string, 0, $replaceLength), "************", $string);
        return $paymentMethod;
    }

    public function invoiceServices()
    {
        return $this->hasMany(InvoiceService::class);
    }

    public function getIsEmailSentAttribute($value)
    {
        return $value == 1 ? 'Yes' : 'No';
    }

    public function getIsSentAttribute(){
        return $this->sent_status;
    }

    public function getInvoiceDateAttribute($value)
    {
        return date_format(date_create($value),'Y-m-d');
    }

    public function getCreatedAtAttribute($value)
    {
        return date_format(date_create($value),'Y-m-d');
    }

    //new feature for shipment report
    public function getTotalInvoiceServiceByShipmentId($id, $service, $qbo)
    {
        $invoices = \DB::table('invoice_services')
                        ->join('invoices', 'invoices.id', '=', 'invoice_services.invoice_id')
                        ->where('invoices.shipment_id', $id)
                        ->where('qbo_customer_id', $qbo->customer->Id)
                        ->where('qbo_customer_name', $qbo->customer->DisplayName)
                        ->whereIn('invoice_services.qbo_service_name', $service)
                        ->where('invoice_services.created_at', '>=', $this::$invoice_service_date)
                        ->where('invoices.is_email_sent', 1)
                        ->select('invoice_services.quantity','invoice_services.rate')
                        ->get();

        $totalServices = 0;
        if(count($invoices) > 0){
            foreach($invoices as $invoice){
                $totalServices += $invoice->quantity * $invoice->rate;
            }
        }
        return $totalServices > 0 ? $totalServices : '';
    }

    public function getInvoiceServiceRateAndDays($shipmentId, $qbo, $service)
    {

        $invoices = \DB::table('invoice_services')
                ->join('invoices', 'invoices.id', '=', 'invoice_services.invoice_id')
                ->where('invoices.shipment_id', $shipmentId)
                ->where('qbo_customer_id', $qbo->customer->Id)
                ->where('qbo_customer_name', $qbo->customer->DisplayName)
                ->where('invoice_services.qbo_service_name', $service)
                ->where('invoice_services.created_at', '>=', $this::$invoice_service_date)
                ->where('invoices.is_email_sent', 1)
                ->select('invoice_services.qbo_service_name','invoice_services.quantity','invoice_services.rate')
                ->get();

        return $invoices;
    }

    public function getOtherServiceTotal($shipmentId, $qbo, $services, $schedule = null)
    {
        if($schedule->sell_rates ?? false){
            foreach($schedule->sell_rates as $sell_rate){
                if($sell_rate->service_id == 2){
                    array_push($services , 'Customs handling');
                }
            }
        }

        $invoices = \DB::table('invoice_services')
                        ->join('invoices', 'invoices.id', '=', 'invoice_services.invoice_id')
                        ->where('invoices.shipment_id', $shipmentId)
                        ->where('qbo_customer_id', $qbo->customer->Id)
                        ->where('qbo_customer_name', $qbo->customer->DisplayName)
                        ->whereNotIn('invoice_services.qbo_service_name', $services)
                        ->where('invoice_services.created_at', '>=', $this::$invoice_service_date)
                        ->where('invoices.is_email_sent', 1)
                        ->select('invoice_services.quantity', 'invoice_services.rate', 'invoice_services.qbo_service_name')
                        ->get();

        $data = [];
        if(count($invoices) > 0){
            foreach($invoices as $invoice){
                $charge = $invoice->quantity * $invoice->rate;
                if(isset($data[$invoice->qbo_service_name])){
                    $data[$invoice->qbo_service_name] += $charge;
                }else{
                    $data[$invoice->qbo_service_name] = $charge;
                }
            }
        }
        return $data;
    }

    public function getTotalCostByShipmentIdAndQboCustomer($shipmentId, $qbo)
    {
        $invoices = \DB::table('invoice_services')
                        ->join('invoices', 'invoices.id', '=', 'invoice_services.invoice_id')
                        ->where('invoices.shipment_id', $shipmentId)
                        ->where('qbo_customer_id', $qbo->customer->Id)
                        ->where('qbo_customer_name', $qbo->customer->DisplayName)
                        ->where('invoice_services.created_at', '>=', $this::$invoice_service_date)
                        ->where('invoices.is_email_sent', 1)
                        ->select('invoice_services.quantity','invoice_services.rate')
                        ->get();
        $totalDemurrage = 0;
        if(count($invoices) > 0){
            foreach($invoices as $invoice){
                $totalDemurrage += $invoice->quantity * $invoice->rate;
            }
        }

        return $totalDemurrage > 0 ? number_format((float)$totalDemurrage, 2) : '';
    }

    public function getOpeningBalanceAttribute(){
        $cc = empty($this->currency_ref) ? 'USD' : $this->currency_ref;
        $precision = config('money.' . $cc. '.precision');

        return round($this->total_amount - $this->total_paid_amount, $precision);
    }

    public function getTotalPaidAmountAttribute(){
        return empty($this->balance) ? 0 : ( $this->total_amount - $this->balance );
    }

    public function getIsDutyInvoiceAttribute()
    {
        $dutyServiceCount = InvoiceService::where('invoice_id', $this->id)
            ->where(function ($query) {
                $query->where('qbo_service_name', 'LIKE', '%Duty%')
                    ->orWhere('qbo_service_name', 'LIKE', '%duty%');
            })
            ->count();
        return (strpos(strtolower($this->invoice_num), "duty")) || $dutyServiceCount > 0;
    }

    public function getTotalDutyAmountAttribute(){
        $duities = InvoiceService::where('invoice_id', $this->id)
            ->where(function ($query) {
                $query->where('qbo_service_name', 'LIKE', '%Duty%')
                    ->orWhere('qbo_service_name', 'LIKE', '%duty%');
            })
            ->get();
        $totalDutyAmount = 0;
        foreach ($duities as $duty){
            $totalDutyAmount = $totalDutyAmount + ($duty->quantity * $duty->rate);
        }
        return $totalDutyAmount;
    }

    public function getMblNumAttribute(){
        if( empty($this->shipment_id) ) return '';

        $shipment = \App\Shipment::find($this->shipment_id);

        return !empty($shipment) ? $shipment->mbl_num : '';
    }

}
