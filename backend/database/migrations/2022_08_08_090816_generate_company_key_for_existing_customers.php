<?php

use App\Helpers\Helper;
use Illuminate\Database\Migrations\Migration;
use App\Customer;

class GenerateCompanyKeyForExistingCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customers = Customer::all();
        $customers->each(function($customer)
        {
            $companyKey = Helper::generateKey($customer->company_name);
            $customer->company_key = $companyKey;
            $customer->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $customers = Customer::all();
        $customers->each(function($customer)
        {
            $customer->company_key = NULL;
            $customer->save();
        });
    }
}
