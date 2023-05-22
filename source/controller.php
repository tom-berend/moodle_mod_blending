<?php

// polyfill for PHP8
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }
}
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) === 0;
    }
}




function controller():string
{
    $HTML = '';

    require_once 'source/viewcomponents.php';
    require_once 'source/views.php';

    // load any javascript we need
    // $v = new Views();
    // $HTML .= $v->loadLibraries();


    $vc = new ViewComponents();
    $HTML .= $vc->accordian(['t1','t2'],['content 1','content 2']);




    // require_once 'source/test.php';
    // $HTML .= test();

    return $HTML;
}
