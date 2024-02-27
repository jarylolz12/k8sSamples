<?php

namespace Kenetashi\CustomsApprovalAddition;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
class CustomsApprovalAddition extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'customs-approval-addition';

    public function loadData($data) {

        $documents_from = [
            ['label' => 'Customer','id' => 1],
            ['label' => 'Factory/Supplier','id' => 2],
            ['label' => 'Our Overseas Team/Office From', 'id' => 3]
        ];

        $approval_requirements = [
            ['label' => 'Approve if matches already approved rates', 'id' => 1],
            ['label' => 'Approve with Customer Every Time', 'id' => 2]
        ];

        return $this->withMeta([
            'data' => $data,
            'documents_from_options' => $documents_from,
            'approval_requirements_options' => $approval_requirements
        ]);
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {


        $fields = ['documents_from', 'approval_requirements', 'approved_hs_codes'];

        foreach ($fields as $field ) {
            if ( $request->has($field) ) {
                $model->{$field} = $request->{$field};
            }    
        }
    }
}
