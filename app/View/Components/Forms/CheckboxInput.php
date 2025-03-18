<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckboxInput extends Component
{
    public string $id;
    public string $name;
    public bool $checked;

    /**
     * Create a new component instance.
     */
    public function __construct(string $id, string $name, bool $checked = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->checked = $checked;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.checkbox-input');
    }
}
