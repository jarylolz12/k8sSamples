<?php
namespace App\Custom;

use Maatwebsite\Excel\Concerns\WithTitle;

class InCompletedSheet extends SheetConfig implements WithTitle
{
    public function __construct($shipments, $reportType, $emailReport = null)
    {
        parent::__construct($shipments->filter->isShipmentActive()->values(), $reportType, $emailReport);
    }

    /**
    * @return string
    */
    public function title(): string
    {
        return "Active";
    }
}
