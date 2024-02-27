<?php

namespace Tanvirofficials\OurAutomatedTrackingTab;

use Laravel\Nova\Fields\Field;
use App\Terminal49Shipment;

class OurAutomatedTrackingTab extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'our-automated-tracking-tab';

    public function initFields($fields = null)
    {
        $scac = $this->getScac($fields['mbl_num']);
        if(isset($fields['mbl_num']) && !empty($fields['mbl_num'])){
            $terminal_fortynine = Terminal49Shipment::find(trim($fields['mbl_num']));
            $attribute = (isset($terminal_fortynine) && isset($terminal_fortynine->attributes)) ? json_decode($terminal_fortynine->attributes) : [];
            $scac = $attribute->shipping_line_scac ?? $scac;
        }
        $fields['scac'] = $scac;
       
        return $this->withMeta(['defaultFields' =>  $fields]);
    }

    private function getScac($mbl)
    {
        $replace_default_scac = [
          "MEDU" => "MSCU"
        ];
        $scac = substr($mbl, 0, 4);

        if (array_key_exists($scac, $replace_default_scac)) {
            return $replace_default_scac[$scac];
        }

        return $scac;
    }
    
}
