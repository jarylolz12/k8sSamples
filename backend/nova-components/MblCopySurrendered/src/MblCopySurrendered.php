<?php

namespace Kenetashi\MblCopySurrendered;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class MblCopySurrendered extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'mbl-copy-surrendered';

    public function initFields($id,$tab) {

         return $this->withMeta([
            'base_url' => url('/'),
            'tabs' => $tab
        ]);
    }


    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {


        if ($request->has('mbl_copy_surrendered_remove')) {
            $check_value = $request->input('mbl_copy_surrendered_remove');

            if ( $check_value==1 && $model->mbl_copy_surrendered!=='' && $model->mbl_copy_surrendered!==null) {

                if (Storage::exists('public/shipment/mbl_copy_surrendered/'.$model->mbl_copy_surrendered)) {
                    Storage::delete('public/shipment/mbl_copy_surrendered/'.$model->mbl_copy_surrendered);
                    $model->mbl_copy_surrendered = '';
                }
            }
        }

        if ($request->has('mbl_copy_surrendered_file')) {
            if (!is_null($file = $request->file('mbl_copy_surrendered_file')) && $file->isValid()) {
                $hash_file = md5($request->input('mbl_copy_surrendered_name') . now());
                $final_display_name = $hash_file . '.' . $request->file('mbl_copy_surrendered_file')->guessExtension();
                $final_name = 'shipment/mbl_copy_surrendered/'.$final_display_name;
                Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy_surrendered_file'), $final_name);
                $model->mbl_copy_surrendered = $final_display_name;
            }
        }

        $model->mbl_released_confirmed = ($model->mbl_copy_surrendered=='' || $model->mbl_copy_surrendered==null) ? 0 : 1;

    }
}
