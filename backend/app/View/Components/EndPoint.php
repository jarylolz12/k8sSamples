<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EndPoint extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $parentEndpoint;

    public function __construct($parentEndpoint)
    {
        $this->parentEndpoint = "Get All" . $parentEndpoint;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */

    public function endpoints($parentEndpoint){
        return "get all". $parentEndpoint;
    }

    public function render()
    {
        return view('components.end-point');
    }
}
