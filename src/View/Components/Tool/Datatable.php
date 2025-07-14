<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Tool;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Datatable extends Component
{
    /**
     * Unique HTML id attribute for the table so that the associated
     * JavaScript initialization can focus on it.
     */
    public string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('adminlte::components.tool.datatable');
    }
}
