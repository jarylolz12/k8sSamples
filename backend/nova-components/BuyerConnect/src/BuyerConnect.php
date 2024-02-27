<?php

namespace Vishalmarakana\BuyerConnect;

use App\Supplier;
use App\Customer;
use Laravel\Nova\Fields\Field;

class BuyerConnect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'buyer-connect';

    public function initFields($data)
    {

        if (!empty($data['connectedCustomerId'])) {
            $customer = Customer::where('id', $data['connectedCustomerId'])->first();
            $customerName = $customer->company_name;
        } else {
            $customerName = '';
        }

        $customers = Customer::where('id', '!=', $data['customerId'])->orderBy('company_name', 'ASC')->get();
        $customersData = collect($customers)->map(function ($customer) {
            $customersArray['value'] = $customer['id'];
            $customersArray['text'] = $customer['company_name'];

            return $customersArray;
        });
        if (!empty($data['customerId'])) {
            $supplierConnection = Supplier::where('connected_customer', $data['customerId'])->first();
            $supplierId = $supplierConnection ? $supplierConnection->id : 0;
        } else {
            $supplierId = 0;
        }

        return $this->withMeta([
            'buyerId' => $data['buyerId'],
            'customerId' => $data['customerId'],
            'customers' => $customersData,
            'customerName' => $customerName,
            'connectedCustomerId' => $data['connectedCustomerId'],
            'supplierId' => $supplierId
        ]);
    }
}
