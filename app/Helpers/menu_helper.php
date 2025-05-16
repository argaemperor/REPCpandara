<?php

if (!function_exists('is_active')) {
    /**
     * Cek apakah URI saat ini cocok dengan salah satu dari nilai yang diberikan
     * @param string|array $routes - Bisa string tunggal atau array
     * @param string $class - Class CSS yang dikembalikan, default 'active'
     * @return string
     */
    function is_active($routes, $class = 'active')
    {
        $current_uri = uri_string(); // di CI3 dan CI4 bisa pakai ini
        if (is_array($routes)) {
            return in_array($current_uri, $routes) ? $class : '';
        }
        return $current_uri === $routes ? $class : '';
    }
}
