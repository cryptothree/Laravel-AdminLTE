@isset($href)
    <a href="{{ $href }}" target="{{ $target }}"
        {{ $attributes->class(['px-2' => isset($label)])->merge(['class' => 'btn btn-xs btn-'.$theme.' mr-2']) }}>
        @isset($icon)
            <i class="{{ $icon }} fa-fw"></i>
        @endisset

        @isset($label)
            <span class="ml-1">{{ $label }}</span>
        @endisset
    </a>
@else
    <form action="{{ $action }}" method="{{ $method }}"
        {{ $attributes->merge(['class' => 'd-inline-block mr-2']) }}>
        @csrf
        <x-adminlte-button :label="$label"
                           type="submit"
                           :theme="$theme"
                           class="btn-xs {{ isset($label) ? 'px-2' : '' }}"
                           icon="{{ isset($icon) ? $icon.' fa-fw' : '' }}"
                           :data-confirmation="$confirmation" />
    </form>
@endisset
