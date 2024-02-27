<?php

namespace App\Observers;

use App\Customer;
use QuickBooksOnline\API\Facades\Customer as QBOCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomerCreated;

class CustomerObserver
{
    /**
     * Handle the customer "created" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        $trigger = 'CREATED';
        $this->createAndAttachQBOCustomer($customer, $trigger);
        $this->updatedCreatedBy($customer, 'created_by');
        $this->updatedCreatedBy($customer, 'last_updated_by');
        \Log::info($customer);
        Notification::send($customer, new CustomerCreated($customer));
    }

    /**
     * Handle the customer "updated" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        $trigger = 'UPDATED';
        $this->createAndAttachQBOCustomer($customer, $trigger);
        $this->updatedCreatedBy($customer, 'last_updated_by');
    }

    /**
     * Handle the customer "deleted" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }

    /**
     * Handle the customer "restored" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the customer "force deleted" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }

    private function createAndAttachQBOCustomer($customer, $trigger)
    {
        $qboCustomer = json_decode($customer->qbo_customer);
        if (isset($qboCustomer->createQBOCustomer)) {
            try {
                $createdObj = $this->createCustomerOnQuickBooks($customer);
                if (isset($createdObj->results->Id)) {
                    //\Log::info(json_encode($createdObj));
                    $customerData = (object)[
                        "customer"=> [
                            "Id"=> $createdObj->results->Id,
                            "DisplayName" => $createdObj->results->DisplayName,
                            "FullyQualifiedName" => $createdObj->results->FullyQualifiedName,
                            "Balance"=> $createdObj->results->Balance,
                            "PrimaryEmailAddr" => $createdObj->results->PrimaryEmailAddr,
                        ],
                        "company"=> $createdObj->company,
                        "realm_id"=> $createdObj->realm_id
                    ];
                    //\Log::info(json_encode($customerData));
                    $customer->qbo_customer = json_encode($customerData);
                    $customer->save();
                }
            } catch (\Throwable $e) {
                \Log::warning($e);
            }
        }
    }
    private function updatedCreatedBy($customer, $status)
    {
        Customer::where('id', $customer->id)->update([$status => \auth()->user()->email]);
    }
    private function createCustomerOnQuickBooks($customer)
    {
        try {
            $quickbooks = app('QuickBooks');
            $createData = json_decode($customer->qbo_customer);
            $qboRealmId = Auth::user()->quickbookstoken->realm_id;
            $companyInfo = $quickbooks->getDataService()->getCompanyInfo();
            $companyName = '';
            if ($createData->companyName !=='') {
                $companyName = $createData->companyName;
            }else {
                $companyName = $customer->company_name;
            }

            $collectedEmail = collect($customer->emails);
            $customerEmails = $collectedEmail->pluck('email')->implode(', ');
            $qboCustomerEmails = $createData->invoiceEmailAddress !== '' ? $createData->invoiceEmailAddress : $customerEmails;

            $customerObj = QBOCustomer::create([
                "BillAddr" => [
                    "Line1"=>  $customer->address,
                ],
                "FullyQualifiedName"=>  $companyName,
                "CompanyName"=>  $companyName,
                "DisplayName"=>  $companyName,
                "PrimaryPhone"=>  [
                    "FreeFormNumber"=>  $customer->phone
                ],
                "PrimaryEmailAddr"=>  [
                    "Address" => $qboCustomerEmails
                ]
            ]);
            $resultingCustomerObj = $quickbooks->getDataService()->Add($customerObj);
            $error = $quickbooks->getDataService()->getLastError();
            if ($error) {
                \Log::warning('Failed to create QBO Customer');
                \Log::warning($error->getResponseBody());
                return false;
            }else {
                return (object)[
                    "results"=> $resultingCustomerObj,
                    "customer"=> [
                        "Id"=> $resultingCustomerObj->Id,
                        "DisplayName"=> $resultingCustomerObj->DisplayName,
                        "FullyQualifiedName"=> $resultingCustomerObj->FullyQualifiedName,
                        "Balance"=> $resultingCustomerObj->Balance,
                        "PrimaryEmailAddr"=> $resultingCustomerObj->PrimaryEmailAddr,
                        "BillAddr"=> $resultingCustomerObj->BillAddr,
                    ],
                    "company" => $companyInfo->CompanyName,
                    "realm_id"=> $qboRealmId
                ];
            }
        }catch (\Throwable $th) {
            \Log::warning('Failed to create QBO Customer');
            \Log::warning($th);
            return false;
        }
    }
}
