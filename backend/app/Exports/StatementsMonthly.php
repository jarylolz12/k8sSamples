<?php

namespace App\Exports;

use App\Statements;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatementsMonthly implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($monthyear, $customer_id)
    {
        $this->monthyear = $monthyear;
        $this->customer_id = $customer_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Statements::join('shipments', 'shifl_ref','=','reference_no')
                        ->join('customers', 'customers.id','=','shipments.customer_id')
                        ->where('shipments.customer_id', $this->customer_id)
                        ->where(\DB::raw("CONCAT_WS(' ',MONTHNAME(statement_date),YEAR(statement_date))"), $this->monthyear)
                        ->select('company_name','statement_no','statement_date','entry_no','reference_no','amount')
                        ->get();
    }

    public function headings(): array
    {
        return ['statement_no','statement_date','customer_name','entry_no','reference_no','total_amount'];
    }
}
