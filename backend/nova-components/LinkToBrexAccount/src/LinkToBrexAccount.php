<?php

namespace Kenetashi\LinkToBrexAccount;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Auth;

class LinkToBrexAccount extends Field
{

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'link-to-brex-account';

    public function initFields() {

        return $this->withMeta([
            'base_url' => url('/')
        ]);

    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        $currentUserRealmId = Auth::user()->quickbookstoken->realm_id;
        $model->brex_id = $request->input('brex_id');
        $model->realm_id = $currentUserRealmId;
    }

}
