<?php

namespace App\Imports;

use App\Models\StatementMonthly;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StatementMonthlyImport implements WithCalculatedFormulas
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StatementMonthly([
            //
        ]);
    }

}
