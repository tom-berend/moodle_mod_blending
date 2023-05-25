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



    require_once 'source/viewcomponents.php';
    require_once 'source/views.php';
    require_once("source/wordart.php");
    require_once('source/models.php');
    require_once('source/mforms.php');





function controller(): string
{
    $HTML = '';


    // comment this out for production      //////
    require_once('source/test.php');        //////
    $test = new Test();                     //////
    $HTML .= $test->preFlightTest();                 //////
    // comment this out for production      //////


    // these two polyfills are for debug statements, so I don't have to take them out of the production code
    if (!function_exists("assertTrue")) {
        function assertTrue($condition, $message)
        {
            return '';
        }
    }
    if (!function_exists("printNice")) {
        function printNice($condition, $message)
        {
            return '';
        }
    }



    $views = new Views();
    $HTML .= $views->loadLibraries();


    $p = $_REQUEST['p'] ?? '';
    $q = $_REQUEST['q'] ?? '';
    $r = $_REQUEST['r'] ?? '';

    printNice($_REQUEST, 'request');
    assert(isset($p));


    switch ($p) {
        case '':
            $HTML .= $views->showStudentList();
            break;

        case 'processEditTutorForm':   // user hit submit on profile form
            $studentTable = new StudentTable();
            $studentTable->updateStudent(intval($q), $_REQUEST);
            break;

        default:
            assertTrue(false, "Did not expect to get here with action '$p'");
    }

    echo $GLOBALS['printNice'];



    // should never be called without a current student, but maybe session expired
    if (!isset($_SESSION['currentStudent']) or empty($_SESSION['currentStudent'])) {
    }





    // require_once 'source/test.php';
    // $HTML .= test();

    return ($GLOBALS['alertString'] ?? '') . $HTML;
}

// most view functions return HTML.  this adds to a message box at the top of the page
function alertMessage($message, $alertType = "danger") // primary, secondary, success, danger, warning, info
{
    if (!isset($GLOBALS['alertString']))
        $GLOBALS['alertString'] = '';

    $GLOBALS['alertString'] .=
        "<div style='border: 2px solid black;' class='alert alert-$alertType' role='alert'>
                    <b>$message</b>
                </div>";
}


    // minimal safety string, won't disrupt HTML or SQL
    function neutered(string $string, bool $forJS = false)
    {

        $string = str_replace('&', '&amp;', $string);  // MUST BE FIRST (or will catch subsequent ones we insert)

        $string = str_replace('`', '&#96;', $string);  // backtick (JS template string)

        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);


        // $string = str_replace('$', '&#36;', $string);

        $string = str_replace('+', '&plus;', $string);
        $string = str_replace('=', '&equals;', $string);

        // JS engine converts HTML back to danger, need to escape twice
        //  https://stackoverflow.com/questions/26245955/encoded-quot-treated-as-a-real-double-quote-in-javascript-onclick-event-why
        if ($forJS) {
            $string = str_replace("'", '&#39;', $string);
            $string = str_replace('"', '&#34;', $string);
        } else {
            $string = str_replace("'", '&#39;', $string);
            $string = str_replace('"', '&#34;', $string);
        }

        // echo "neutered ", $oldString, ' ',$string;

        return ($string);
    }
