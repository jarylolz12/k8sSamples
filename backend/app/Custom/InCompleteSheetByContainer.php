<?php

namespace App\Custom;

use Maatwebsite\Excel\Concerns\WithTitle;

class InCompleteSheetByContainer extends SheetConfigByContainer implements WithTitle
{
    public function __construct($shipments, $emailReport = null)
    {
        parent::__construct($shipments->filter->isShipmentActive()->values(), $emailReport);
    }

    public function title() : string
    {
        return "Active";
    }
}