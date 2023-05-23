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




function controller(): string
{
    $HTML = '';

    require_once 'source/viewcomponents.php';
    require_once 'source/views.php';
    require_once("source/wordart.php");
    require_once('source/models.php');

    $vc = new ViewComponents();
    $HTML .= $vc->loadLibraries();


    // comment this out for production      //////
    require_once('source/test.php');        //////
    $test = new Test();                     //////
    $HTML .=  $test->preFlightTest();       //////
    // comment this out for production      //////






    // require_once 'source/test.php';
    // $HTML .= test();

    return $HTML;
}
