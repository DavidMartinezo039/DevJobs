<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileFieldBucle extends Component
{
    public string $label;
    public array $values;
    public array $items;
    public array $dataPivot;

    /**
     * Create a new component instance.
     */
    public function __construct($label, $values, array $items = [], array $dataPivot = [])
    {
        $this->label = $label;
        $this->values = is_array($values) ? $values : json_decode($values, true) ?? [];
        $this->items = $items;
        $this->dataPivot = $dataPivot;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-field-bucle');
    }
}
