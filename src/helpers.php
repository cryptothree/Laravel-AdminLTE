<?php

use Laracasts\Flash\FlashNotifier;

if (! function_exists('toast')) {
    /**
     * A wrapper for the flash function.
     *
     * @param  string|null  $message
     * @param  string  $level
     * @return \Laracasts\Flash\FlashNotifier
     */
    function toast(?string $message = null, string $level = 'default'): FlashNotifier
    {
        return flash($message, $level);
    }
}
