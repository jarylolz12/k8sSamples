<?php
namespace App\Custom;

/**
 *
 */
 use App\Shipment;

class ProcessSchedulesGroupBookings
{
    private $schedules_group_bookings;

    public function __construct($schedules_group_bookings = '[]')
    {
        // code...
        $this->schedules_group_bookings = $schedules_group_bookings;
    }

    public function getSchedule($s = null)
    {
        $schedule = $this->getSelectedSchedule($s);
        if ($schedule) {
            //
            return $schedule;
        }
        return null;
    }

    private function getSelectedSchedule($s = null)
    {
        if ($s) {
            $schedules =  json_decode($s);
        } else {
            // code...
            $schedules =  !empty($this->schedules_group_bookings) ? json_decode($this->schedules_group_bookings) : [];
        }
        foreach ($schedules ?? [] as $schedule) {
            //
            if (isset($schedule) && isset($schedule->is_confirmed) && $schedule->is_confirmed) {
                return $schedule;
            }
        }
        return false;
    }

    // deprecated method
    // public static function updateSchedule($id, $eta, $etd = null)
    // {
    //     $shipment = Shipment::find($id);
    //     if ($shipment) {
    //         $schedules_group_bookings = json_decode($shipment->schedules_group_bookings);
    //         if (!empty($schedules_group_bookings)) {
    //             foreach ($schedules_group_bookings ?? [] as $schedule) {
    //                 // code...
    //                 if (isset($schedule) && isset($schedule->is_confirmed) && $schedule->is_confirmed) {
    //                     if ($eta) {
    //                         $schedule->eta = \Carbon\Carbon::parse($eta)->format("Y-m-d H:i:s");
    //                         $shipment->eta = $schedule->eta;
    //                     }
    //                     if ($etd) {
    //                         $schedule->etd = \Carbon\Carbon::parse($etd)->format("Y-m-d H:i:s");
    //                         $shipment->etd = $schedule->etd;
    //                     }
    //                 }
    //             }
    //             $shipment->schedules_group_bookings = json_encode($schedules_group_bookings);
    //         }
    //         $shipment->save();
    //     }
    // }
}
