<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class InputDate extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The default set of icons for the Tempus Dominus plugin configuration.
     *
     * @var array
     */
    protected $icons = [
        'time' => 'fa-solid fa-clock',
        'date' => 'fa-solid fa-calendar-days',
        'up' => 'fa-solid fa-arrow-up',
        'down' => 'fa-solid fa-arrow-down',
        'previous' => 'fa-solid fa-chevron-left',
        'next' => 'fa-solid fa-chevron-right',
        'today' => 'fa-solid fa-calendar-check',
        'clear' => 'fa-solid fa-trash',
        'close' => 'fa-solid fa-xmark',
    ];

    /**
     * The default set of buttons for the Tempus Dominus plugin configuration.
     *
     * @var array
     */
    protected $buttons = [
        'showClose' => true,
    ];

    /**
     * The Tempus Dominus plugin configuration parameters. Array with
     * 'key => value' pairs, where the key should be an existing configuration
     * property of the plugin.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'Tempus Dominus' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $enableOldSupport = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->enableOldSupport = isset($enableOldSupport);
        $this->config = is_array($config) ? $config : [];

        // Setup the default plugin icons option.

        $this->config['icons'] = $this->config['icons'] ?? $this->icons;

        // Setup the default plugin buttons option.

        $this->config['buttons'] = $this->config['buttons'] ?? $this->buttons;
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class.
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['form-control', 'datetimepicker'];

        if ($this->isInvalid()) {
            $classes[] = 'is-invalid';
        }

        return implode(' ', $classes);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-date');
    }
}
