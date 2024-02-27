<?php

namespace App\Imports;

use App\Models\Statements;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StatementsImport implements WithCalculatedFormulas
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Statements([
            //
        ]);
    }

}
