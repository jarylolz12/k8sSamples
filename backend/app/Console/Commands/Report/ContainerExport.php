<?php

namespace App\Console\Commands\Report;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;

class ContainerExport implements FromArray, WithHeadings, WithColumnWidths
{
    protected $containers;

    public function __construct(array $containers)
    {
        $this->containers = $containers;
    }

    public function headings(): array
    {
        return [
            "MBL#",
            "Container#",
            "LFD"
          ];
    }

    public function array(): array
    {
        return $this->containers;
    }

    public function columnWidths(): array
    {
        return [
          'A' => 24,
          'B' => 24,
          'C' => 24,

      ];
    }
}
