@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Switch --}}
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass(), 'value' => 'true']) }}>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {

        let usrCfg = @json($config);
        $('#{{ $id }}').bootstrapSwitch(usrCfg);

        // Workaround to ensure correct state setup on initialization.

        $('#{{ $id }}').bootstrapSwitch('state', usrCfg.state ?? false);

        // Add support to auto select the previous submitted value in case of
        // validation errors.

        @if($errors->any() && $enableOldSupport)
            let oldState = @json((bool)$getOldValue($errorKey));
            $('#{{ $id }}').bootstrapSwitch('state', oldState);
        @endif
    })

</script>
@endpush

{{-- Setup the height/font of the plugin when using sm/lg sizes --}}
{{-- NOTE: this may change with newer plugin versions --}}

@once
@push('css')
<style type="text/css">

    {{-- MD (default) size setup --}}
    .input-group .bootstrap-switch-handle-on,
    .input-group .bootstrap-switch-handle-off,
    .input-group .bootstrap-switch-label,
    .bootstrap-switch .bootstrap-switch-handle-off,
    .bootstrap-switch .bootstrap-switch-handle-on,
    .bootstrap-switch .bootstrap-switch-label {
        font-size: .875rem;
        padding: .375rem .5rem;
    }

    {{-- LG size setup --}}
    .input-group-lg .bootstrap-switch-handle-on,
    .input-group-lg .bootstrap-switch-handle-off,
    .input-group-lg .bootstrap-switch-label,
    .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-handle-off,
    .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-handle-on,
    .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-label {
        font-size: 1.125rem;
        padding: .425rem .5rem;
    }

    {{-- SM size setup --}}
    .input-group-sm .bootstrap-switch-handle-on,
    .input-group-sm .bootstrap-switch-handle-off,
    .input-group-sm .bootstrap-switch-label,
    .bootstrap-switch.bootstrap-switch-small .bootstrap-switch-handle-off,
    .bootstrap-switch.bootstrap-switch-small .bootstrap-switch-handle-on,
    .bootstrap-switch.bootstrap-switch-small .bootstrap-switch-label {
        font-size: .825rem;
        padding: .2rem .4rem;
    }

    {{-- Mini size setup --}}
    .bootstrap-switch.bootstrap-switch-mini .bootstrap-switch-handle-off,
    .bootstrap-switch.bootstrap-switch-mini .bootstrap-switch-handle-on,
    .bootstrap-switch.bootstrap-switch-mini .bootstrap-switch-label {
        font-size: .65rem;
    }

    {{-- Custom invalid style setup --}}

    .adminlte-invalid-iswgroup > .bootstrap-switch-wrapper,
    .adminlte-invalid-iswgroup > .input-group-prepend > *,
    .adminlte-invalid-iswgroup > .input-group-append > * {
        box-shadow: 0 .25rem 0.5rem rgba(255, 0, 0, .25);
    }

</style>
@endpush
@endonce
