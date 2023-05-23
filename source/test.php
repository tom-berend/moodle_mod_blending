<?php

class Test
{
    function PreFlightTest(): string
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $HTML = '';

        // $HTML .= $this->viewComponents();
        // $HTML .= $this->clusterWords();
        // $HTML .= $this->moodleUSER();
        $HTML .= $this->getAllStudents();

        return $HTML;
    }

    function getAllStudents(){
        $HTML = '';

        // clear any old records
        global $DB;
        $DB->delete_records_select('blendingstudents',"name like 'NEW-STUDENT-%'");


        $s = new StudentTable();
        $all = $s->getAllStudents();
        $HTML .= printNice($all,'all students for THIS user before addition');

        $student = 'NEW-STUDENT-'.time();       // unique
        $id = $s->insertNewStudent($student);
        $all = $s->getAllStudents();
        $HTML .= printNice($all,'all students after addition');

        return $HTML;

    }

    function moodleUSER():string{
        global $USER;
        $HTML = printNice($USER);
        return $HTML;
    }


    function viewComponents():string
    {
        $HTML = '';

        // make sure Muli font is loaded
        $HTML .= "<p>The 'a' in the line below should be 'cat' style.</p>";
        $HTML .= "<p style='font-family:Muli, sans-serif;'>cat fat hat rat</p>";
        $HTML .= "<hr />";

        // test printNice
        $HTML .= printNice(['a', 'b'], 'message');
        $HTML .= "<hr />";

        $vc =  new ViewComponents();


        // neutered
        $a = "neutered() <h1 style='color:blue;'>  should look like html</h1>";
        $HTML .= $vc->neutered($a) . "<br>";
        $a = "should look like amper-amp-semi:   &amp;";
        $HTML .= $vc->neutered($a) . "<br>";
        $HTML .= "<hr />";

        // tabs
        $names = ['first', 'second', 'third'];
        $panes = ['first content', 'second content', 'third content<br>third content<br>third content<br>third content<br>third content<br>third content<br>'];
        $HTML .= $vc->tabs($names, $panes);
        $HTML .= "<hr />";


        // accordian (uses data from tabs)
        $HTML .= $vc->accordian($names, $panes);
        $HTML .= "<hr />";

        return $HTML;
    }

    function clusterWords():string
    {

        $HTML = '';

        require_once("source/blendingtable.php");
        $b =  new BlendingTable();
        $b->loadClusterWords();

        $HTML .= printNice($b->words, 'words');
        $HTML .= printNice($b->CVC, 'CVC');
        $count = count($b->clusterWords);
        $HTML .= printNice($b->clusterWords, "clusterWords ($count lessons)");

        return $HTML;
    }


    // require_once("coursebuilder/steps/blending/wordspinner.php");
    // $HTML .= wordSpinner('b,c,d,f,g,h','a,e,i,o,u','b,c,d,f,g,h,j,k');


    // $HTML .= wTest();


    // $vc = new ViewComponents();
    // $HTML .= $vc->accordian(['t1','t2'],['content 1','content 2']);



    // $v = new Views();
    // $tabNames = ['First Panel', 'Second Panel', 'Third Panel'];
    // $tabContents = ['First Panel Content', 'Second Panel Content', 'Third Panel Content'];
    // $HTML .= $v->tabs($tabNames,$tabContents);

    // $HTML .= $v->wordSpinner('b,c,d,f,g,h','a,e,i,o,u','b,c,d,f,g,h,j,k');


}




function printNice($elem, string $message = ''): string
{

    $HTML = '<br /><p>from ';
    $HTML .= backTrace();

    $span = $span2 = '';

    // if (!$GLOBALS['debugMode']) {
    $span = "<span style='color:blue;'>";
    $span2 = "</span>";

    // // if debug is off, write to error.log
    // if (is_string($elem)) {
    //     $msg = str_replace('<br />', "\n", $HTML);
    //     $msg = str_replace('<p>', " ", $msg);
    //     $msg = str_replace('</p>', "", $msg);
    //     // file_put_contents('./error.log', "\n" . date('Y-M-d TH:i:s') . " $elem $msg", FILE_APPEND);
    //     // return;
    // } // debugging isn't on
    // }


    if (is_object($elem)) {
        // just cast it to an array
        //  $HTML .= "<b>(OBJECT)</b> $span $message $span2" . printNiceHelper((array)$elem) . '</p>';
        $HTML .= "$span $message $span2" . printNiceR((array)$elem) . '</p>';
    } else {
        // print whatever we got
        $HTML .= "$span $message $span2" . printNiceR($elem) . '</p>';
    }
    return $HTML;
}

function printNiceR($elem)
{

    $HTML = printNiceHelper($elem);
    return ($HTML);
}

// helper function for printNice()
function printNiceHelper($elem, $max_level = 12, $print_nice_stack = array(), $HTML = '')
{
    // // show where we were called from
    // $backtrace = debug_backtrace(); // if no title, then show who called us
    // if ($backtrace[1]['function'] !== 'printNice' and $backtrace[1]['function'] !== 'printNiceHelper') {
    //     if (isset($backtrace[1]['class'])) {
    //         $HTML .= "<hr /><h1>class {$backtrace[1]['class']}, function {$backtrace[1]['function']}() (line:{$backtrace[1]['line']})</h1>";
    //     }
    // }

    // $MAX_LEVEL = 5;


    if (is_array($elem) || is_object($elem)) {
        // if (in_array($elem, $print_nice_stack, true)) {
        //     $HTML .= "<hr /><h1>class {$backtrace[1]['class']}, function {$backtrace[1]['function']}() (line:{$backtrace[1]['line']})</h1>";
        //     return ($HTML);
        // }
        if ($max_level < 1) {
            //print_r(debug_backtrace());
            //die;
            $HTML .= "<FONT COLOR=RED>MAX STACK LEVEL EXCEEDED</FONT>";
            return ($HTML);
        }

        $print_nice_stack[] = &$elem;
        $max_level--;

        $HTML .= "<table border=1 cellspacing=0 cellpadding=3 width=100%>";
        if (is_array($elem)) {
            $HTML .= '<tr><td><b>ARRAY</b></td></tr>';
        } elseif (is_object($elem)) {
            $HTML .= '<tr><td><b>OBJECT</b></td></tr>';
        } else {
            $HTML .= '<tr><td colspan=2 style="background-color:#333333;"><strong>';
            $HTML .= '<font color=white>OBJECT Type: ' . get_class($elem) . '</font></strong></td></tr>';
        }
        $color = 0;
        foreach ((array)$elem as $k => $v) {
            if ($max_level % 2) {
                $rgb = ($color++ % 2) ? "#888888" : "#BBBBBB";
            } else {
                $rgb = ($color++ % 2) ? "#8888BB" : "#BBBBFF";
            }
            $HTML .= '<tr><td valign="top" style="width:40px;background-color:' . $rgb . ';">';
            $HTML .= '<strong>' . $k . "</strong></td><td>";
            $HTML .= printNiceHelper($v, $max_level, $print_nice_stack);

            $HTML .= "</td></tr>";
        }

        $HTML .= "</table>";
        return ($HTML);
    }
    if ($elem === null) {
        $HTML .= "<font color=green>NULL</font>";
    } elseif ($elem === 0) {
        $HTML .= "0";
    } elseif ($elem === true) {
        $HTML .= "<font color=green>TRUE</font>";
    } elseif ($elem === false) {
        $HTML .= "<font color=green>FALSE</font>";
    } elseif ($elem === "") {
        $HTML .= "<font color=green>EMPTY STRING</font>";
    } elseif (is_integer($elem)) {
        $HTML .= "<font color=blue>$elem</font>";
    } elseif (is_double($elem)) {
        $HTML .= "<font color=blue>" . round($elem, 3) . "</font>";
    } elseif (is_string($elem)) {
        $HTML .= $elem;
    } else {
        printNice(getType($elem), 'dealing with this in printNice()');
        $HTML .= $elem;
    }
    return ($HTML);
}


function ISOdate()
{
    return date('Y-m-d');
}

function backTrace(): string
{
    $debug = debug_backtrace();
    $HTML = '';
    for ($i = 1; $i < 7; $i++) {
        if (isset($debug[$i]['file'])) {
            $file = explode('/', $debug[$i]['file']);
            $f = $file[count($file) - 1];
            $line = $debug[$i]['line'];
            $HTML .= "$f($line) ";
        }
    }
    return $HTML;
}
