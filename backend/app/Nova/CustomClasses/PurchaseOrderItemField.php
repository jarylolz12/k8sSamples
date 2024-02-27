<?php

namespace App\Nova\CustomClasses;

use Laravel\Nova\Fields\Text;

class PurchaseOrderItemField{

    public function __invoke(){
        return [
            Text::make('quantity')
        ];
    }
}