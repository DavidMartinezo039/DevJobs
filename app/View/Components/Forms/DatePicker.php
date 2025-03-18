<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatePicker extends Component
{
    public $id;
    public $name;
    public $label;
    public $wireModel;

    public function __construct($id, $name, $label, $wireModel = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->wireModel = $wireModel;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.date-picker');
    }
}
