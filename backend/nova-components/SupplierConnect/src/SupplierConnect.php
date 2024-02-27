<?php

namespace Imbhavin95\SupplierConnect;

use App\Buyer;
use App\Customer;
use Laravel\Nova\Fields\Field;

class SupplierConnect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'supplier-connect';

    public function initFields($data)
    {

        if (!empty($data['connectedCustomerId'])) {
            $customer = Customer::where('id', $data['connectedCustomerId'])->first();
            $customerName = $customer->company_name;
        } else {
            $customerName = '';
        }

        if (!empty($data['customerId'])) {
            $buyerConnection = Buyer::where('connected_customer', $data['customerId'])->first();
            $buyerId = $buyerConnection ? $buyerConnection->id : 0;
        } else {
            $buyerId = 0;
        }

        $customers = Customer::where('id', '!=', $data['customerId'])->orderBy('company_name', 'ASC')->get();
        $customersData = collect($customers)->map(function ($customer) {
            $customersArray['value'] = $customer['id'];
            $customersArray['text'] = $customer['company_name'];

            return $customersArray;
        });

        return $this->withMeta([
            'supplierId' => $data['supplierId'],
            'customerId' => $data['customerId'],
            'customers' => $customersData,
            'customerName' => $customerName,
            'connectedCustomerId' => $data['connectedCustomerId'],
            'buyerId' => $buyerId
        ]);
    }
}
