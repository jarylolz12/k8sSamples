<?php
namespace App\Custom;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class SheetConfig implements FromView, WithColumnWidths, WithEvents, WithStyles 
{
    private $shipments;
    private $reportType;
    private $emailReport;
    private $bCustomize = false;

    public function __construct($shipments = null, $reportType, $emailReport = null)
    {
        $this->shipments = $shipments;
        $this->reportType = $reportType;
        $this->emailReport = $emailReport;
    }

    public function view(): View
    {   
        $temp = 'excel.report';
        $columns = [];
        if($this->emailReport){
            $columns = json_decode($this->emailReport->report_columns, true) ?? [];
            if(count($columns) > 0){
                $this->reportType = $this->emailReport->report_type;
                $this->bCustomize = true;
            }
        }

        if($this->bCustomize){
            $temp = 'excel.report-customize';
        }else{
            if($this->reportType == 'BYREFERENCEADV'){
                $temp = 'excel.report-advanced';
            }
        }
        
        return view($temp, [
            'shipments' => $this->shipments,
            'columns' => $columns
        ]);
    }


    public function columnWidths(): array
    {
        
      if($this->bCustomize){
            $columns = json_decode($this->emailReport->report_columns, true) ?? [];
            $col = [];
            $sizes = $this->columnSize();
            foreach($columns as $key => $val){
                $k = $key+1;
                $col[Coordinate::stringFromColumnIndex($k)] = $sizes[$val] ?? 15;
            }
            return $col;
      }else{
        if($this->reportType == 'BYREFERENCEADV'){
            return [
                'A' => 12, //Shifl Ref#
                'B' => 24, //MBL#
                'C' => 25, //Consignee
                'D' => 18, //Status
                'E' => 15, //Booked Date
                'F' => 22, //PO#
                'G' => 35, //Shipper
                'H' => 18, //Supplier Cartons
                'I' => 15, //HBL#
                'J' => 15, //Telex Release
                'K' => 15, //Type
                'L' => 15, //Mode
                'M' => 15, //Carrier
                'N' => 28, //Vessel Name
                'O' => 18, //Voyage #
                'P' => 15, //Total Cartons
                'Q' => 19, //No. of Containers
                'R' => 18, //Container #
                'S' => 18, //Container Seal# 
                'T' => 16, //Container Size
                'U' => 22, //Container Weight (kg)
                'V' => 18, //Container Cartons
                'W' => 18, //Container Volume
                'X' => 13, //Freight Cost
                'Y' => 13, //Current ETD//ETD(latest)
                'Z' => 13, //Current ETA //ETA(latest)
                // 'AA' => 16, //Original ETD
                'AA' => 14, //Original ETA
                'AB' => 22, //POL
                'AC' => 15, //POD
                'AD' => 19, //Cargo Ready Date
                'AE' => 14, //Empty Out
                'AF' => 14, //Gated In //Full In
                'AG' => 30, //Terminal
                'AH' => 22, //Shifl AN Sent
                'AI' => 22, //Shifl DO Sent
                'AJ' => 25, //Delivery Loc (WRHS)
                'AK' => 16, //Freight Release
                'AL' => 18, //Customs Release
                'AM' => 12, //Discharge
                'AN' => 13, //LFD(latest)
                // 'AO' => 16, //Original LFD
                'AO' => 20, //Available for Pickup
                'AP' => 16, //Full Out/Full Gate Out
                'AQ' => 14, //Empty In //Empty Gated In
                'AR' => 18, //Demurrage Days
                'AS' => 20, //Dmrg Rate Per Day
                'AT' => 17, //Demurrage Total
                'AU' => 17, //Per Diem Total
                'AV' => 18, //Pier Pass Total
                'AW' => 13, //Other Totals
                'AX' => 15, //Customs Total
                'AY' => 30, //Other Services Total
                'AZ' => 18, //Tracking Status
                'BA' => 15, //Booking#
                // 'D' => 25, //Forwarder
                            
            ];
        }else{
            return [
                'A' => 12, //Shifl Ref#
                'B' => 24, //MBL#
                'C' => 25, //PO#
                'D' => 25, //Consignee
                'E' => 60, //Shipper
                'F' => 15, //Telex Release
                'G' => 20, // Booked Date
                'H' => 13, //ETD
                'I' => 13, //ETA
                'J' => 15, //POL
                'K' => 15, //POD
                'L' => 30, //Terminal
                'M' => 22, //Number of Containers
                'N' => 18, //Container# 
                'O' => 16, //Container Sizes
                'P' => 16, //Discharge Date
                'Q' => 16, //Freight Release 
                'R' => 17, //Customs Release
                'S' => 27, //Customs Submitted/Release Date
                'T' => 13, //LFD
                'U' => 18, //Status
                'V' => 16, //Full Gated Out
                'W' => 16, //Empty Gated In
                'X' => 18, //Tracking Status
    
            ];
         }
      }

    
    }

    /**
   * @return array
   */
    public function registerEvents(): array
    {
        return array(
          AfterSheet::class => function (AfterSheet $event) {
              $event->sheet->getDelegate()->setAutoFilter('A1:'.$event->sheet->getDelegate()->getHighestColumn().'1');

                $styleArray = [
                'font' => [
                    'color' => ['rgb' => 'FF0000'],
                ]];

                $highestRow = $event->sheet->getHighestRow();
                
                $telexCol = ($this->reportType == 'BYREFERENCEADV') ? '10' : '6';

                for ($row = 2; $row <= $highestRow; $row++) {
                    $text_release = $event->sheet->getCellByColumnAndRow($telexCol, $row)->getValue();
                    if(strpos($text_release, 'Pending') !== false){
                        $event->sheet->getCellByColumnAndRow($telexCol, $row)->getStyle()->applyFromArray($styleArray);
                    }
                } 

                /*NOTE: this will change for the customize report where columns may change according 
                to the user choice
                 */
                // if($this->reportType == 'BYREFERENCEADV'){  

                //     //PO#
                //     $event->sheet->getStyle('F2:F'.$highestRow)
                //         ->getNumberFormat()
                //         ->setFormatCode(
                //             '0'
                //         );

                //     //Booking#
                //     $event->sheet->getStyle('BA2:BA'.$highestRow)
                //         ->getNumberFormat()
                //         ->setFormatCode(
                //             '0'
                //         );
                        
                //     //Pier Pass Total    
                //     $event->sheet->getStyle('AV2:AV'.$highestRow)
                //     ->getNumberFormat()
                //     ->setFormatCode(
                //         '0.00'
                //     );

                //     //Demurrage Total
                //     $event->sheet->getStyle('AT2:AT'.$highestRow)
                //         ->getNumberFormat()
                //         ->setFormatCode(
                //             '0.00'
                //         );
                
                // // Dmrg Rate Per Day
                // $event->sheet->getStyle('AS2:AS'.$highestRow)
                //         ->getNumberFormat()
                //         ->setFormatCode(
                //             '0.00'
                //         );   
                    
                //     //Demurrage Days
                //     $event->sheet->getStyle('AR2:AR'.$highestRow)
                //         ->getNumberFormat()
                //         ->setFormatCode(
                //             '0'
                //     );   

                //     // Per Diem Total
                //     $event->sheet->getStyle('AU2:AU'.$highestRow)
                //         ->getNumberFormat()
                //         ->setFormatCode(
                //             '0.00'
                //         );    
                // }    
            
          }
      );
    }

    public function columnNames(): array
    {
        

      if($this->reportType == 'BYREFERENCEADV'){
        return [
            '1' => 'Shifl Ref#',
            '2' => 'MBL#',
            '3' => 'Consignee',
            '4' => 'Status',
            '5' => 'Booked Date',
            '6' => 'PO#',
            '7' => 'Shipper',
            '8' => 'Supplier Cartons',
            '9' => 'HBL#',
            '10' => 'Telex Release',
            '11' => 'Type',
            '12' => 'Mode',
            '13' => 'Carrier',
            '14' => 'Vessel Name',
            '15' => 'Voyage #',
            '16' => 'Total Cartons',
            '17' => 'No. of Containers',
            '18' => 'Container#',
            '19' => 'Container Seal#',
            '20' => 'Container Size',
            '21' => 'Container Weight (kg)',
            '22' => 'Container Cartons',
            '23' => 'Container Volume',
            '24' => 'Freight Cost',
            '25' => 'Current ETD',
            '26' => 'Current ETA',
            // 'AA' => 'Original ETD',
            '27' => 'Original ETA',
            '28' => 'POL',
            '29' => 'POD',
            '30' => 'Cargo Ready Date',
            '31' => 'Empty Out',
            '32' => 'Gated In',
            '33' => 'Terminal',
            '34' => 'Shifl AN Sent',
            '35' => 'Shifl DO Sent',
            '36' => 'Delivery Loc (WRHS)',
            '37' => 'Freight Release',
            '38' => 'Customs Release',
            '39' => 'Discharge',
            '40' => 'LFD(latest)',
            // 'AO' => 'Original LFD',
            '41' => 'Available for Pickup',
            '42' => 'Full Out',
            '43' => 'Empty In',
            '44' => 'Demurrage Days',
            '45' => 'Dmrg Rate Per Day',
            '46' => 'Demurrage Total',
            '47' => 'Per Diem Total',
            '48' => 'Pier Pass Total',
            '49' => 'Other Totals',
            '50' => 'Customs Total',
            '51' => 'Other Services Total',
            '52' => 'Tracking Status',
            '53' => 'Booking#',
            // 'D' => 'Forwarder'
                        
        ];
    }else{
        return [
            '1' => 'Shifl Ref#',
            '2' => 'MBL#',
            '3' => 'PO#',
            '4' => 'Consignee',
            '5' => 'Shipper',
            '6' => 'Telex Release',
            '7' => 'Booked Date',
            '8' => 'ETD',
            '9' => 'ETA',
            '10' => 'POL',
            '11' => 'POD',
            '12' => 'Terminal',
            '13' => 'Number of Containers',
            '14' => 'Container#',
            '15' => 'Container Size',
            '16' => 'Discharge Date',
            '17' => 'Freight Release', 
            '18' => 'Customs Release',
            '19' => 'Customs Submitted/Release Date',
            '20' => 'LFD',
            '21' => 'Status',
            '22' => 'Full Gated Out',
            '23' => 'Empty Gated In',
            '24' => 'Tracking Status',

        ];
     }
    }

    public function columnSize(): array
    {
        return [
            'Shifl Ref#' => 12,
            'MBL#' => 24,
            'Consignee' => 25,
            'Status' => 18,
            'Booked Date' => 15,
            'PO#' => 15,
            'Shipper' => 35,
            'Supplier Cartons' => 18,
            'HBL#' => 15,
            'Telex Release' => 15,
            'Type' => 15,
            'Mode' => 15,
            'Carrier' => 15,
            'Vessel Name' => 28,
            'Voyage #' => 18,
            'Total Cartons' => 15,
            'No. of Containers' => 22,
            'Container#' => 18,
            'Container Seal#' => 18,
            'Container Size' => 16,
            'Container Weight (kg)' => 22,
            'Container Cartons' => 18,
            'Container Volume' => 18,
            'Freight Cost' => 13,
            'Current ETD' => 13,
            'Current ETA' => 13,
            // 'Original ETD' => 16,
            'Original ETA' => 14,
            'POL' => 22,
            'POD' => 15,
            'Cargo Ready Date' => 19,
            'Empty Out' => 14,
            'Gated In' => 14,
            'Terminal' => 30,
            'Shifl AN Sent' => 22,
            'Shifl DO Sent' => 22,
            'Delivery Loc (WRHS)' => 25,
            'Freight Release' => 16,
            'Customs Release' => 18,
            'Discharge' => 12,
            'LFD(latest)' => 13,
            // 'Original LFD' => 16,
            'Available for Pickup' => 20,
            'Full Out' => 16,
            'Empty In' => 14,
            'Demurrage Days' => 18,
            'Dmrg Rate Per Day' => 20,
            'Demurrage Total' => 17,
            'Per Diem Total' => 17,
            'Pier Pass Total' => 18,
            'Other Totals' => 13,
            'Customs Total' => 15,
            'Other Services Total' => 30,
            'Tracking Status' => 18,
            'Booking#' => 15,
            // 'Forwarder' => 25,
            'ETD' => 13,
            'ETA' => 13,
            'Number of Containers' => 22,
            'Customs Submitted/Release Date' => 27,
            'Discharge Date' => 16,
            'LFD' => 13, //
            'Full Gated Out' => 16,
            'Empty Gated In' => 16,
            
                        
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
             // Style the first row as bold text.
             1    => ['font' => ['bold' => true]],
        ];
    }

}
