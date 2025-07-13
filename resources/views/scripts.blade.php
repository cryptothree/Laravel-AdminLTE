<script>
    // ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content'),
        },
        timeout: 0,
    });

    // default options for BootstrapSwitch
    if (typeof $.fn.bootstrapSwitch === 'function') {
        $.fn.bootstrapSwitch.defaults.onText = 'Yes';
        $.fn.bootstrapSwitch.defaults.offText = 'No';
        $.fn.bootstrapSwitch.defaults.onColor = 'success';
        $.fn.bootstrapSwitch.defaults.offColor = 'danger';
    }

    $(function () {
        // append asterisk to required fields
        $('input[required], select[required], textarea[required]').each(function () {
            $(this).closest('.form-group').find('label').append('<span class="text-danger">*</span>');
        });

        // handle form submission
        $(document).on('submit', 'form', function () {
            // add hidden input for checkboxes
            $(this).find('input[type=checkbox]').each(function () {
                const $checkbox = $(this);
                $checkbox.prop('checked') ? $checkbox.val(1) : $checkbox.clone().prop('type', 'hidden').val(0).insertBefore($checkbox);
            });

            // add loading spinner
            const $submitBtn = $(this).find('button:not(.no-spinner)[type=submit]');
            if ($submitBtn) {
                $submitBtn.prop('disabled', true).width($submitBtn.width()).html('<i class="fas fa-spinner fa-pulse"></i>');
            }
        });
    });
</script>
