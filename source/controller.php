<?php

$GLOBALS['debugMode'] = true;     // set false for producion


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

// utility function for printable time
function printableTime(int $t): string
{
    return date("D F j Y g:ia", $t);
}

require_once('utilities.php');

require_once('source/viewcomponents.php');
require_once('source/views.php');
require_once("source/wordart.php");
require_once('source/models.php');
require_once('source/mforms.php');
require_once 'source/acl.php';

require_once('source/blendingtable.php');
require_once('source/phonictiles.php');
require_once('source/lessons.php');




global $weWereAlreadyHere;
$weWereAlreadyHere = false;

function controller(): string
{
    $HTML = '';
    $GLOBALS['printNice'] = '';
    $GLOBALS['alertString'] = '';

    global $weWereAlreadyHere;
    if ($weWereAlreadyHere) {
        return '';  // second time
    }
    $weWereAlreadyHere = true;


    // bootstrap says it is 'mobile first', but that is layout, not button size or spacing.
    // the result is a crappy view on both mobile and web
    // we can slightly change the HTML to make it better

    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $GLOBALS['mobileDevice'] = str_contains($agent, 'mobile') or str_contains($agent, 'android');

    if ($GLOBALS['mobileDevice'])   // always production mode for mobile!!
        $GLOBALS['debugMode'] = false;

    printNice('$GLOBALS[\'mobileDevice\']', $GLOBALS['mobileDevice'] ? 'mobile web browser' : 'desktop web browser');





    if ($GLOBALS['debugMode']) { // only permitted in debug mode
        require_once('source/test.php');        //////
        $test = new Test();                     //////
        $HTML .= $test->preFlightTest();                 //////

    }

    // these two polyfills are for debug statements, so I don't have to take them out of the production code
    if (!function_exists("assertTrue")) {
        function assertTrue($condition, $message = '')
        {
            return '';
        }
    }
    if (!function_exists("printNice")) {
        function printNice($condition, $message = '')
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


    // $GLOBALS['mobileDevice'] = true;
    // $HTML .= $views->wordSpinner('b,c,d,f,g,h,j,k','a,e,i,o,u','b,c,d,f,g,h,j,k');


    switch ($p) {
        case '':
        case 'showStudentList':
            $HTML .= $views->showStudentList();
            break;


        case 'blendingLesson':             // show a specific lesson $q

            $lessons = new Lessons();
            $_SESSION['currentLesson'] = $q;
            $HTML .= $lessons->render($q);
            break;


        case 'refresh':     // refrest to a specific tab
            $lessons = new Lessons();
            $HTML .= $lessons->render($q, intval($r));
            break;


        case 'selectStudent':
            $studentID = $_SESSION['currentStudent'] = intval($q);

            $lessons = new Lessons();
            $lessonName = $lessons->getNextLesson($studentID);
            $_SESSION['currentLesson'] = $lessonName;

            $logTable = new LogTable();
            $logTable->insertLog($studentID, 'Start', $lessonName);

            $HTML .= $lessons->render($lessonName);

            break;


        case 'showAddStudentForm':
            $_SESSION['currentStudent'] = 0;

            $HTML .= MForms::rowOpen(6);
            $HTML .= $views->addStudent();
            $HTML .= MForms::rowClose();
            break;


        case 'showEditTutorsForm':

            $studentID = intval($q);    // which one was clicked?
            $_SESSION['currentStudent'] = $studentID;  // keep track

            $HTML .= MForms::rowOpen(6);
            $vc = new Views();
            $HTML .= MForms::rowOpen(4);
            $HTML .= $vc->editTutors($studentID);
            $HTML .= MForms::rowClose();
            $HTML .= MForms::rowClose();
            break;


        case 'processEditStudentForm':   // both add and edit student record
            $studentTable = new StudentTable();
            // might be an add
            if ($r == 'add') {
                $studentID = $_SESSION['currentStudent'] = $studentTable->insertNewStudent($_REQUEST);
                $logTable = new LogTable();
                $logTable->insertLog($studentID, 'Added Student');

                $lessons = new Lessons();
                $lessonName = $lessons->getNextLesson($studentID);
                $HTML .= $lessons->render($lessonName);
            } else {
                $studentTable->updateStudent(intval($q), $_REQUEST);
                $HTML .= $views->showStudentList();
            }

            break;

        case 'lessonTest':  // Mastered or Completed buttons
            printNice('in lessonTest');
            assertTrue(isset($_SESSION['currentStudent']) and !empty($_SESSION['currentStudent']));
            $studentID = $_SESSION['currentStudent'];

            // first, write out a log record
            $logTable = new LogTable();

            $result = 'Unknown';
            if (isset($_REQUEST['mastered'])) {  // which submit button?
                $result = 'mastered';   // usually 'mastered' or 'completed'
            } elseif (isset($_REQUEST['InProgress'])) {
                $result = 'inprogress';
            } else {
                // other values?
            }


            $lesson = $_REQUEST['lesson'];
            $score = $_REQUEST['score'];
            $remark = $_REQUEST['remark'];
            $logTable->insertLog($studentID, 'test', $lesson, $result, $score, $remark);

            // now find the NEXT lesson (requires that this lesson be completed)

            $lessons = new Lessons();
            $lessonName = $lessons->getNextLesson($studentID);

            $HTML .= $lessons->render($lessonName);
            break;



        case 'studentHistory':
            $studentID = $_SESSION['currentStudent'] = intval($_REQUEST['q']);

            $views = new Views();
            $HTML .= $views->showStudentHistory($studentID);
            break;



        case 'next':
            $lessons = new Lessons();
            $blendingTable = new BlendingTable();

            $currentLesson =  $_SESSION['currentLesson'];
            $nextLesson = $blendingTable->getNextKey($currentLesson);

            if ($nextLesson) {  // if we found another lesson record
                $lessons = new Lessons();
                $_SESSION['currentLesson'] = $nextLesson;
            } else {
                alertMessage('This is the last lesson.');
            }

            $logTable = new LogTable();
            $logTable->insertLog($_SESSION['currentStudent'], 'Next', $_SESSION['currentLesson']);

            $HTML .= $lessons->render($_SESSION['currentLesson']);
            printNice($nextLesson, "next lesson");

            break;


        case 'navigation':
            $viewComponents = new ViewComponents;
            $HTML = $viewComponents->lessonAccordian(99);
            break;


        default:
            assertTrue(false, "Did not expect to get here with action '$p'");
    }

    if ($GLOBALS['debugMode']) { // only show in debug mode, ahead of normal output
        $HTML = ($GLOBALS['alertString'] ?? '') . $HTML;
        $HTML = ($GLOBALS['printNice'] ?? '') . $HTML;
    }

    return $HTML;
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
