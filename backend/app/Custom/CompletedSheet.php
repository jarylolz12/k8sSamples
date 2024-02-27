<?php
namespace App\Custom;

use Maatwebsite\Excel\Concerns\WithTitle;

class CompletedSheet extends SheetConfig implements WithTitle
{
    public function __construct($shipments, $reportType, $emailReport = null)
    {
        $data = $shipments->filter->isShipmentComplete()->values();
        parent::__construct($data->filter->isReportWithinSixMonths()->values(), $reportType, $emailReport);
    }

    /**
    * @return string
    */
    public function title(): string
    {
        return "Completed";
    }
}
