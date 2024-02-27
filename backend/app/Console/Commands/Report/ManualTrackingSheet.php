<?php

namespace App\Console\Commands\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ManualTrackingSheet implements FromView, WithColumnWidths, WithTitle
{
    private $shipments;
    private $title;

    public function __construct($shipments,$title = "Untracked")
    {
        $this->shipments = $shipments;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('excel.manual_tracking_report', [
            'shipments' => $this->shipments,
            'tab' => $this->title
        ]);
    }


    public function columnWidths(): array
    {
        // new column
        return ($this->title == 'All') ?
              [
                  'A' => 11, // Shifl Ref#
                  'B' => 15,
                  'C' => 11,
                  'D' => 25, // Expected to be addressed
                  'E' => 22, // Last Addressed/Tracked
                  'F' => 25, // Last Addressed/Tracked By
                  'G' => 30, // Next Expected to be Addressed  - 30
                  'H' => 25, // Last Updated At
                  'I' => 25, // Last Updated By
                  'J' => 30, // Status
                  'K' => 65, // Comments
              ]
              :
              [
                  'A' => 11, // Shifl Ref#
                  'B' => 15,
                  'C' => 25, // Expected to be addressed
                  'D' => 22, // Last Addressed/Tracked
                  'E' => 25, // Last Addressed/Tracked By
                  'F' => 30, // Next Expected to be Addressed  - 30
                  'G' => 25, // Last Updated At
                  'H' => 25, // Last Updated By
                  'I' => 30, // Status
                  'J' => 65, // Comments
              ];
    }

    /**
    * @return string
    */
    public function title(): string
    {
        return $this->title;
    }

}
