<?php

namespace Kenetashi\OfficeEmailGroup;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class OfficeEmailGroup extends Field
{

    private $id = 0;
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'office-email-group';

    public function loadData($id) {
        $this->id = $id;
        return $this->withMeta([
            'id' => $id
        ]);
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->has('office_emails')) {
            $model->office_emails = $request->office_emails;
            $model->office_id = $this->id;
        }
    }


}
