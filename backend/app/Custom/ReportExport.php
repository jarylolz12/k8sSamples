<?php
namespace App\Custom;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    private $shipments;
    private $reportBy;
    private $emailReport;

    public function __construct($shipments, $reportBy, $emailReport = null)
    {
        $this->shipments = $shipments;
        $this->reportBy = $reportBy;
        $this->emailReport = $emailReport;
    }

    public function sheets(): array
    {
        return [
            "InCompleted" => $this->reportBy !== 'BYCONTAINER' ? new InCompletedSheet($this->shipments, $this->reportBy, $this->emailReport) :
                 new InCompleteSheetByContainer($this->shipments, $this->emailReport),
            "Completed" => $this->reportBy !== 'BYCONTAINER' ? new CompletedSheet($this->shipments, $this->reportBy, $this->emailReport) :
                 new CompleteSheetByContainer($this->shipments, $this->emailReport)
        ];
    }
}
