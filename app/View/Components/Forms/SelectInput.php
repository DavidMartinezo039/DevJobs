<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectInput extends Component
{
    public $id;
    public $name;
    public $options;
    public $selectedValue;

    public function __construct($id, $name, $options, $selectedValue = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->options = $options;
        $this->selectedValue = $selectedValue;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.select-input');
    }
}
