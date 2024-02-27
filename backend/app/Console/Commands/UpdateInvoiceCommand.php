<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invoice;
use App\User;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;

class UpdateInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:invoices-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Invoices status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function sendErrorLogs($invoice, $error) {
        $subject = sprintf('Invoice processing error invoice id %d', $invoice->id);
        $content = 'Error in processing invoice: ' . $error->getMessage();
        $attachments = [];
        $markdown = 'mails.basic';

        //if something unexpected error then send email
        Mail::to('kenneth@shifl.com')->send(new BasicMail($subject, $content, $attachments, $markdown));
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $realm_id = 123146157027444;
        $invoices = Invoice::where('qbo_company_realmid', $realm_id)
                                ->where('is_processed', 0)
                                ->orderBy('id','desc')
                                ->paginate(100);

        if ( count ($invoices) > 0 ) {
            foreach ($invoices as $invoice ) {
                $this->processInvoice($invoice);
            }
        }

    }

    private function processInvoice($invoice) {

        $get_user = User::where('email', 'shabsie@shifl.com')->first();

        try {

            $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.data_service.client_id'),config('quickbooks.data_service.client_secret'));
            $accessTokenObj = $oauth2LoginHelper->
                                refreshAccessTokenWithRefreshToken($get_user->quickbookstoken->refresh_token);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            $refreshTokenValue = $accessTokenObj->getRefreshToken();

            \DB::table('quickbooks_tokens')->where('user_id', $get_user->id)
                                        ->update([
                                            'access_token' => $accessTokenValue,
                                            'refresh_token' => $refreshTokenValue
                                        ]);

            $data_service = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => config('quickbooks.data_service.client_id'),
                'ClientSecret' => config('quickbooks.data_service.client_secret'),
                'accessTokenKey' => $accessTokenValue,
                'refreshTokenKey' => $refreshTokenValue,
                'QBORealmID' => $get_user->quickbookstoken->realm_id,
                'baseUrl' => 'Production'
            ]);

            $invoice_obj = [];
            $realm_id = 123146157027444;

            if (isset($invoice->id) && intval($invoice->qbo_company_realmid) === $realm_id ) {
                $invoice_obj = $data_service->FindById("Invoice", $invoice->qbo_id);
                if ( isset($invoice_obj->DocNumber)) {
                    $getInvoice = $invoice_obj;
                    $paidOn = null;
                    if ( isset($getInvoice->LinkedTxn)) {
                        try {

                            if  (is_countable($getInvoice->LinkedTxn) && count($getInvoice->LinkedTxn) > 0) {
                                $checkLink = $getInvoice->LinkedTxn[0];
                                if ( $checkLink->TxnType === 'Payment') {
                                    $checkPayment = $data_service->FindById("Payment", $checkLink->TxnId);
                                    if ( isset($checkPayment->MetaData)) {
                                        $paidOn = $checkPayment->MetaData->LastUpdatedTime;
                                    }

                                    if ( $paidOn !== null ) {
                                        $invoice->paid_on = $paidOn;
                                    }
                                }

                            } else {
                                if ( $getInvoice->LinkedTxn->TxnType === 'Payment') {
                                    $checkPayment = $data_service->FindById("Payment", $getInvoice->LinkedTxn->TxnId);
                                    if ( isset($checkPayment->MetaData)) {
                                        $paidOn = $checkPayment->MetaData->LastUpdatedTime;
                                    }

                                    if ( $paidOn !== null ) {
                                        $invoice->paid_on = $paidOn;
                                    }
                                }    
                            }

                            $invoice->is_processed = 1;
                            $invoice->balance = $getInvoice->Balance;
                            $invoice->home_balance = $getInvoice->HomeBalance;
                            $invoice->total_amount = $getInvoice->TotalAmt;
                            $invoice->save();

                        } catch(Exception $e) {
                            $this->sendErrorLogs($invoice, $e);
                        }
                        
                    }

                }
            }
        }catch( Exception $e ) {
            $this->sendErrorLogs($invoice, $e);
        }
        
    }
}