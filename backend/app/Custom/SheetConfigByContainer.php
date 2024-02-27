<?php

namespace App\Custom;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetConfigByContainer implements FromView, WithColumnWidths, WithEvents, WithStyles
{
   private $shipments;
   private $emailReport;
   private $bCustomize = false;

   public function __construct($shipments = null, $emailReport = null)
   {
       $this->shipments = $shipments;
       $this->emailReport = $emailReport;
   }

   public function view() : View
  {     
       $temp = 'excel.report-by-container';
       $columns = [];
       if($this->emailReport){
            $columns = json_decode($this->emailReport->report_columns, true) ?? [];
            if(count($columns) > 0){
                $this->reportType = $this->emailReport->report_type;
                $this->bCustomize = true;
                $temp = 'excel.cuztomize-by-container';
            }
        }
       
       return view($temp,[
            'shipments' => $this->shipments,
            'columns' => $columns
       ]);
   }

   public function columnWidths() : array
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

            return [

                'A' => 12, //Shifl Ref#
                'B' => 22, //PO#
                'C' => 45, //Shipper
                'D' => 23, //Factory Cargo Ready Date
                'E' => 18, //Supplier Cartons
                'F' => 15, // Booked Date
                'G' => 15, //POL
                'H' => 20, //POD
                'I' => 25, //Delivery Loc (WRHS)
                'J' => 25, //Consignee
                'K' => 15, //Type
                'L' => 15, //Mode
                'M' => 15, //Carrier
                'N' => 24, //MBL#
                'O' => 28, //Vessel Name
                'P' => 18, //Voyage #
                'Q' => 15, //Total Cartons
                'R' => 18, //Container# 
                'S' => 16, //Container Sizes
                'T' => 22, //Container Weight (kg)
                'U' => 20, //Container Cartons
                'V' => 20, //Container Volume (cbm)
                'W' => 17, //Container Seal# 
                'X' => 14, //Freight Rate 
                'Y' => 13, //Status
                'Z' => 15, //HBL #
                'AA' => 15, //Telex Release
                'AB' => 15, //Current ETD
                'AC' => 15, //Current ETA
                // 'T' => 15, //Original ETD
                'AD' => 15, //Original ETA
                'AE' => 18, //Empty out to FTY
                'AF' => 15, //Gated In POL
                'AG' => 20, //Arrival Notice Sent
                'AH' => 15, //DO Sent
                'AI' => 16, //Freight Release 
                'AJ' => 17, //Customs Release
                'AK' => 13, //Discharge
                'AL' => 20, //Available for Pickup
                'AM' => 13, //Latest LFD
                // 'AM' => 13, //Original LFD
                'AN' => 20, //Container Picked up
                'AO' => 23, //Empty container returned
                'AP' => 20, //Dmrg Rate Per Day
                'AQ' => 18, //Demurrage Days
                'AR' => 19, //Demurrage Total
                'AS' => 20, //Per diem Rate Day
                'AT' => 18, //Per diem days
                'AU' => 19, //Total Per diem
                'AV' => 15, //Pier Pass 
                'AW' => 18, //Customs Entry Cost
                'AX' => 18, //Other Charges
                'AY' => 20, //Other services Total
                'AZ' => 18, //Tracking Status
                'BA' => 15, //Booking#
                // 'E' => 15, //Forwarder  
                
                //new columns from trucking shipment
                'BB' => 30, //Location At
                'BC' => 30, //Deliver Too 
                'BD' => 15, //Pre Gate Fees 
                'BE' => 25, //Pickup Scheduled/Port Appointment 
                'BF' => 18, //Pickup Date 
                'BG' => 18, //Prepull 
                'BH' => 18, //Port Wait Time 
                'BI' => 18, //Scheduled Delivery
                'BJ' => 13, //Trucking Mode
                'BK' => 18, //Delivered
                'BL' => 18, //Container Empty
                'BM' => 18, //Wait Time
                'BN' => 18, //Empty Pickup Date
                'BO' => 18, //Return Empty
                'BP' => 18, //Per Diem Date
                'BQ' => 18, //Chassis Days
                'BR' => 18, //Storage Days
                //end new columns from trucking shipment      

            ];
        }   
   }

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
                //Highlight Pending on Telex Release Column 
                for ($row = 2; $row <= $highestRow; $row++) {
                    $text_release = $event->sheet->getCellByColumnAndRow(27, $row)->getValue();
                    if(strpos($text_release, 'Pending') !== false){
                        $event->sheet->getCellByColumnAndRow(27, $row)->getStyle()->applyFromArray($styleArray);
                    }
                } 

                // //PO#
                // $event->sheet->getStyle('B2:B'.$highestRow)
                // ->getNumberFormat()
                // ->setFormatCode(
                //     '0'
                // );

                // //Booking#
                // $event->sheet->getStyle('BA2:BA'.$highestRow)
                //     ->getNumberFormat()
                //     ->setFormatCode(
                //         '0'
                //     );

                    

            
          }
      );
    }

    public function columnNames() : array
   {
        return [

            '1' => 'Shifl Ref#',
            '2' => 'PO#', 
            '3' => 'Shipper', 
            '4' => 'Factory Cargo Ready Date',
            '5' => 'Supplier Cartons', 
            '6' => 'Booked Date', 
            '7' => 'POL', 
            '8' => 'POD', 
            '9' => 'Delivery Loc (WRHS)', 
            '10' => 'Consignee', 
            '11' => 'Type', 
            '12' => 'Mode', 
            '13' => 'Carrier', 
            '14' => 'Vessel Name', 
            '15' => 'Voyage#', 
            '16' => 'Total Cartons', 
            '17' => 'Container#', 
            '18' => 'Container Sizes', 
            '19' => 'Container Weight (kg)', 
            '20' => 'Container Cartons', 
            '21' => 'Container Volume (cbm)', 
            '22' => 'Container Seal#', 
            '23' => 'Freight Rate', 
            '24' => 'Status', 
            '25' => 'HBL#', 
            '26' => 'Telex Release', 
            '27' => 'Current ETD', 
            '28' => 'Current ETA', 
             // 'T' => 'Original ETD', //
            '29' => 'Original ETA', 
            '30' => 'Empty out to FTY', 
            '31' => 'Gated In POL', 
            '32' => 'Arrival Notice Sent', 
            '33' => 'DO Sent', 
            '34' => 'Freight Release', 
            '35' => 'Customs Release', 
            '36' => 'Discharge', 
            '37' => 'Available for Pickup', //
            '38' => 'Latest LFD', 
            // 'AM' => 13, //Original LFD
            '39' => 'Container Picked Up', 
            '40' => 'Empty Container Returned',
            '41' => 'Dmrg Rate Per Day', 
            '42' => 'Demurrage Days', 
            '43' => 'Demurrage Total', 
            '44' => 'Per Diem Rate Day', 
            '45' => 'Per Diem Days', 
            '46' => 'Total Per Diem', 
            '47' => 'Pier Pass', 
            '48' => 'Customs Entry Cost', 
            '49' => 'Other Charges', 
            '50' => 'Other Services Total', 
            '51' => 'Tracking Status', 
            '52' => 'Booking#', 
            // 'E' => 15, //Forwarder  
            //new columns from trucking shipment
            '53' => 'Location At', 
            '54' => 'Deliver To', 
            '55' => 'Pre Gate Fees', 
            '56' => 'Pickup Scheduled/Port Appointment',
            '57' => 'Pickup Date', 
            '58' => 'Prepull', 
            '59' => 'Port Wait Time', 
            '60' => 'Scheduled Delivery',
            '61' => 'Trucking Mode',
            '62' => 'Delivered',
            '63' => 'Container Empty',
            '64' => 'Wait Time',
            '65' => 'Empty Pickup Date',
            '66' => 'Return Empty',
            '67' => 'Per Diem Date',
            '68' => 'Chassis Days',
            '69' => 'Storage Days',
            //end new columns from trucking shipment             

        ];
   }

   public function columnSize() : array
   {
        return [
            'Shifl Ref#' => 12,
            'BPO#' => 22, 
            'Shipper' => 45, 
            'Factory Cargo Ready Date' => 23, 
            'Supplier Cartons' => 18, 
            'Booked Date' => 15, 
            'POL' => 15, 
            'POD' => 20, 
            'Delivery Loc (WRHS)' => 25, 
            'Consignee' => 25, 
            'Type' => 15, 
            'Mode' => 15, 
            'Carrier' => 15, 
            'MBL#' => 24, 
            'Vessel Name' => 28, 
            'Voyage #' => 18, 
            'Total Cartons' => 15, 
            'Container#' => 18,  
            'Container Sizes' => 16, 
            'Container Weight (kg)' => 22, 
            'Container Cartons' => 20, 
            'Container Volume (cbm)' => 20, 
            'Container Seal# ' => 17, 
            'Freight Rate ' => 14, 
            'Status' => 13, 
            'HBL #' => 15, 
            'Telex Release' => 15, 
            'Current ETD' => 15, 
            'Current ETA' => 15, 
            // 'Original ETD' => 15, 
            'Original ETA' => 15, 
            'Empty out to FTY' => 18, 
            'Gated In POL' => 15, 
            'Arrival Notice Sent' => 20, 
            'DO Sent' => 15, 
            'Freight Release ' => 16, 
            'Customs Release' => 17, 
            'Discharge' => 13, 
            'Available for Pickup' => 20,
            'Latest LFD' => 13, 
            // 'Original LFD' => 13, 
            'Original LFD' => 20, 
            'Empty container returned' => 23, 
            'Dmrg Rate Per Day' => 20, 
            'Dmrg Rate Per Day' => 18, 
            'Demurrage Total' => 19, 
            'Per diem Rate Day' => 20, 
            'Per diem days' => 18, 
            'Total Per diem' => 19, 
            'Pier Pass ' => 15, 
            'Customs Entry Cost' => 18, 
            'Other Charges' => 18, 
            'Other services Total' => 20, 
            'Tracking Status' => 18, 
            'Booking#' => 15, 
            // 'Forwarder' => 15,   
             //new columns from trucking shipment
             'Location At' => 30,
             'Deliver To' => 30, 
             'Pre Gate Fees' => 15, 
             'Pickup Scheduled/Port Appointment' => 25, 
             'Pickup Date' => 18, 
             'Prepull' => 18, 
             'Port Wait Time' => 18, 
             'Scheduled Delivery' => 18,
             'Trucking Mode' => 13,
             'Delivered' => 18,
             'Container Empty' => 18,
             'Wait Time' => 18,
             'Empty Pickup Date' => 18,
             'Return Empty' => 18,
             'Per Diem Date' => 18,
             'Chassis Days' => 18,
             'Storage Days' => 18,
             //end new columns from trucking shipment                   
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