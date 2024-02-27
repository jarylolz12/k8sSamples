<?php

namespace Vishalmarakana\ShipmentNotes;

use Laravel\Nova\Fields\Field;

class ShipmentNotes extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-notes';
    public $showOnCreation = false;
    public $showOnIndex = false;

    public function initFields($params) {
        return $this->withMeta([
            'shipment_id' => $params['id']
        ]);
    }

    /**
     * ShipmentNotesField constructor.
     *
     * @param $name
     * @param null $attribute
     * @param callable|null $resolveCallback
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'placeholder' => "Description",
            'addingNotesEnabled' => true,
            'fullWidth' => config('nova-notes-field.full_width_inputs', false),
        ]);
    }

    /**
     * Sets the placeholder value displayed on the field.
     *
     * @param string $placeholder
     * @return NotesField
     **/
    public function placeholder($placeholder)
    {
        return $this->withMeta(['placeholder' => $placeholder]);
    }

    /**
     * Show or hide the AddNote input.
     *
     * @param bool $addingNotesEnabled
     * @return NotesField
     */
    public function addingNotesEnabled($addingNotesEnabled = true)
    {
        return $this->withMeta(['addingNotesEnabled' => $addingNotesEnabled]);
    }

}
