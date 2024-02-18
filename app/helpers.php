<?php


if (!function_exists('removeVideoNamePrefixes')) {
    function removeVideoNamePrefixes(string $originalName): string
    {
        $name = explode('/', $originalName);
        return collect($name)->last();
    }
}
