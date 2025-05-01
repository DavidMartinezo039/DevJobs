<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileField extends Component
{
    public string $label;
    public string $value;
    public bool $useFormattedDate;

    public function __construct(?string $label = '', $value, $useFormattedDate = false)
    {
        $this->label = $label ?? '';
        $this->value = $value ?? '';
        $this->useFormattedDate = $useFormattedDate;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-field');
    }
}
