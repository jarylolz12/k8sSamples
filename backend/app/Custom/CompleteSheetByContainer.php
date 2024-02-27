<?php

namespace App\Custom;

use Maatwebsite\Excel\Concerns\WithTitle;

class CompleteSheetByContainer extends SheetConfigByContainer implements WithTitle
{
    public function __construct($shipments, $emailReport = null)
    {
        $data = $shipments->filter->isShipmentComplete()->values();
        parent::__construct($data->filter->isReportWithinSixMonths()->values(), $emailReport);
    }

    public function title() : string
    {
        return "Completed";
    }
}