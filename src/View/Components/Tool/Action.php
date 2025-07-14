<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Tool;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Action extends Component
{
    /**
     * The anchor hyperlink (href attribute). If defined, the component will
     * render an <a> tag, otherwise it will fall back to a small form with a
     * submitted button.
     *
     * @var string|null
     */
    public ?string $href;

    /**
     * The target attribute for the anchor tag.
     *
     * @var string
     */
    public string $target;

    /**
     * The form action attribute (used when $href is not set).
     *
     * @var string|null
     */
    public ?string $action;

    /**
     * The HTTP method for the form submission (default POST).
     *
     * @var string
     */
    public string $method;

    /**
     * The visible label for the action.
     *
     * @var string|null
     */
    public ?string $label;

    /**
     * The AdminLTE theme for the button/anchor (primary, info, ...).
     *
     * @var string
     */
    public string $theme;

    /**
     * A Font Awesome icon class to display.
     *
     * @var string|null
     */
    public ?string $icon;

    /**
     * The confirmation message to show before performing the action. When
     * defined, a native browser confirmation dialog will be displayed and
     * the action will be canceled if the user rejects it.
     *
     * @var string|null
     */
    public ?string $confirmation;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $href
     * @param  string|null  $target
     * @param  string|null  $action
     * @param  string|null  $method
     * @param  string|null  $label
     * @param  string|null  $theme
     * @param  string|null  $icon
     * @param  string|null  $confirmation
     */
    public function __construct(
        ?string $href = null,
        ?string $target = null,
        ?string $action = null,
        string $method = 'POST',
        ?string $label = null,
        string $theme = 'default',
        ?string $icon = null,
        ?string $confirmation = null
    ) {
        $this->href = $href;
        $this->target = $target ?? '_self';
        $this->action = $action;
        $this->method = $method ?? 'POST';
        $this->label = UtilsHelper::applyHtmlEntityDecoder($label);
        $this->theme = $theme ?? 'default';
        $this->icon = $icon;
        $this->confirmation = UtilsHelper::applyHtmlEntityDecoder($confirmation);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('adminlte::components.tool.action');
    }
}
