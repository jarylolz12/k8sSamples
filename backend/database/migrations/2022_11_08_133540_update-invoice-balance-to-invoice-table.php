<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Invoice;

class UpdateInvoiceBalanceToInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $invoices = Invoice::where('balance', 0)->whereIn('id', ['16613', '17358', '17402', '17409', '15805', '16295', '16668', '17807', '18127'])->get();
        foreach ($invoices as $key => $value) {
            Invoice::where('id', $value->id)->update(['balance' => $value->home_balance, 'paid_on' => null]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
