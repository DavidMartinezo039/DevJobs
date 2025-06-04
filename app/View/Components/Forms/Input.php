<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Input extends Component
{
    public $id;
    public $name;
    public $label;
    public $wireModel;
    public $type;

    public function __construct($id, $name, $label, $wireModel = null, $type = 'text')
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->wireModel = $wireModel;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input');
    }
}
