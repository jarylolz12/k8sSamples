<?php

namespace Tanvirofficials\CustomFileField;

use Closure;
use Illuminate\Support\Facades\Storage;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Field;

class CustomFileField extends Field
{
    public $component = 'custom-file-field';

    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'center';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Determin if the file is able to be downloaded.
     *
     * @var bool
     */
    public $downloadsAreEnabled = true;


    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
        ) {
        if (is_null($file = $request->file($requestAttribute)) || ! $file->isValid()) {
            return;
        }
        
        $result = call_user_func(
            $this->storageCallback,
            $request,
            $model,
            $attribute,
            $requestAttribute
        );


        if (isset($result['mbl_released_confirmed'])) {
            $model->mbl_released_confirmed = $result['mbl_released_confirmed'];
        }

        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $result[$attribute];
        }
    }

    public function store(callable $storageCallback)
    {
        $this->storageCallback = $storageCallback;
        return $this;
    }
}
