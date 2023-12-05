<?php

namespace Blending;



// reading man with glasses:  public domain    https://commons.wikimedia.org/wiki/File:Nlyl_reading_man_with_glasses.svg


assert_options(ASSERT_EXCEPTION, true);  // set false for production
$GLOBALS['debugMode'] = true;           // are we testing?  set false for producion

if (!isset($GLOBALS['isDebugging']))
    $GLOBALS['isDebugging'] = false;          // were we started with xDebug?  set false for producion

$GLOBALS['multiCourse'] = false;        // just BLENDING or multiple courses?


// release history - REMEMBER TO RENAME BLENDING.JS !!!
$GLOBALS['VER_Version'] = 1;
$GLOBALS['VER_Revision'] = 0;
$GLOBALS['VER_Patch'] = 0;

// 1.0.0   2023/Dec/1   Initial release. Only BLENDING



// polyfills for PHP8
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




// require_once('utilities.php');

require_once('viewcomponents.php');
require_once('views.php');

require_once("wordart.php");
require_once("matrix.php");

require_once('models.php');
require_once('mforms.php');

require_once('lessonabstract.php');
require_once('phonictiles.php');
require_once('lessons.php');

// require_once 'acl.php';


global $weWereAlreadyHereP;
$weWereAlreadyHere = false;

class Controller
{
    function controller(string $p, string $q, string $r): string
    {

        $HTML = '';


        /// load JS and font components
        $JSFilename = "blending.{$GLOBALS['VER_Version']}.{$GLOBALS['VER_Revision']}.{$GLOBALS['VER_Patch']}.js";
        $HTML .= "<script type='text/javascript' src='source/$JSFilename'></script>";
        $HTML .= "<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>";




        global $defaultDecodableLevel;
        $defaultDecodableLevel = 2;

        // bootstrap says it is 'mobile first', but that is layout, not button size or spacing.
        // the result is a crappy view on both mobile and web
        // we can slightly change the HTML to make it better

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $GLOBALS['mobileDevice'] = str_contains($agent, 'mobile') or str_contains($agent, 'android') or str_contains($agent, 'iphone');

        if ($GLOBALS['mobileDevice'])   // always production mode for mobile!!
            $GLOBALS['debugMode'] = false;



        if ($GLOBALS['debugMode']) { // only permitted in debug mode
            require_once('source/test.php');        //////
            $test = new Test();                     //////
            $HTML .= $test->preFlightTest();                 //////

        }


        $views = new Views();

        // sometimes user times out, logs back in, loses session.
        if (!isset($_SESSION['currentStudent'])) {
            $_SESSION['currentStudent'] = $_SESSION['currentStudent'] ?? 0;
            $_SESSION['currentCourse'] = $_SESSION['currentCourse'] ?? '';
            $_SESSION['currentLesson'] = $_SESSION['currentLesson'] ?? '';
            $_SESSION['decodelevel'] = $defaultDecodableLevel;   // default
            $p = '';
        }

        switch ($p) {
            case '':
            case 'showStudentList':
                $HTML .= $this->showStudentList();
                break;

            case 'introduction':
                $lessons = new Lessons('introduction');
                $_SESSION['currentCourse'] = 'introduction';
                $_SESSION['decodelevel'] = $defaultDecodableLevel;;   // default
                $lessonName = $lessons->getFirstLesson();
                $_SESSION['currentLesson'] = $lessonName;
                $_SESSION['currentStudent'] = 0;        // not a real student
                $HTML .= $lessons->render($lessonName);
                break;


            case 'renderLesson':             // show a specific lesson $q in current course
                $_SESSION['currentLesson'] = $q;
                if (!empty($r))
                    $_SESSION['currentCourse'] = $r;    // can put links across courses (not used yet)

                $lessons = new Lessons($_SESSION['currentCourse']);
                $_SESSION['decodelevel'] = $defaultDecodableLevel;;   // default

                $HTML .= $lessons->render($q);
                break;


            case 'refresh':     // refrest to a specific tab, don't reset decodelevel
                $lessons = new Lessons($_SESSION['currentCourse']);
                $HTML .= $lessons->render($q, intval($r));   // currentLesson, nTab
                break;

            case 'decodelevel':

                $_SESSION['decodelevel'] = intval($q);
                $lessons = new Lessons($_SESSION['currentCourse']);
                $HTML .= $lessons->render($_SESSION['currentLesson'], intval($r));
                break;


            case 'selectCourse':

                if (!$GLOBALS['multiCourse']) {     // just show students
                    $_SESSION['currentCourse'] = 'blending';
                    $HTML .= $this->showStudentList();
                    break;
                }


                if (empty($q)) {
                    $_SESSION['currentCourse'] = '';
                    $_SESSION['currentLesson'] = '';
                    $_SESSION['decodelevel'] = $defaultDecodableLevel;   // default

                    $HTML .= displayAvailableCourses();  // not part of the Lessons class

                } else {
                    // user has selected course
                    assert(in_array($q, $GLOBALS['allCourses']), 'sanity check - unexpected courses?');

                    $_SESSION['currentCourse'] = $q;

                    $lessons = new Lessons($_SESSION['currentCourse']);
                    $lessonName = $lessons->getNextLesson($_SESSION['currentStudent']);
                    if (empty($lessonName)) {
                        $lessonName = $lessons->getFirstLesson();
                    }
                    $_SESSION['currentLesson'] = $lessonName;

                    $logTable = new LogTable();
                    $logTable->insertLog($_SESSION['currentStudent'], 'Start', $_SESSION['currentCourse'], $_SESSION['currentLesson']);

                    $HTML .= $lessons->render($lessonName);
                }
                break;


            case 'selectStudent':
                $_SESSION['currentStudent'] = intval($q);

                $_SESSION['currentCourse'] = '';
                $_SESSION['currentLesson'] = '';
                $_SESSION['decodelevel'] = $defaultDecodableLevel;   // default

                if ($GLOBALS['multiCourse']) {
                    $HTML .= displayAvailableCourses();  // not part of the Lessons class

                } else {
                    // bypass select-course logic and start lessons
                    $_SESSION['currentCourse'] = 'blending';
                    $lessons = new Lessons($_SESSION['currentCourse']);
                    $lessonName = $lessons->getNextLesson($_SESSION['currentStudent']);
                    if (empty($lessonName)) {
                        $lessonName = $lessons->getFirstLesson();
                    }
                    $_SESSION['currentLesson'] = $lessonName;

                    $logTable = new LogTable();
                    $logTable->insertLog($_SESSION['currentStudent'], 'Start', $_SESSION['currentCourse'], $_SESSION['currentLesson']);

                    $HTML .= $lessons->render($lessonName);
                }
                break;



            case 'showAddStudentForm':
                $_SESSION['currentStudent'] = 0;

                $HTML .= $views->addStudent();
                break;


            case 'showEditTutorsForm':

                $studentID = intval($q);    // which one was clicked?
                $_SESSION['currentStudent'] = $studentID;  // keep track

                $vc = new Views();
                $HTML .= $vc->editTutors($studentID);
                break;


            case 'processEditStudentForm':   // both add and edit student record

                $form = [];
                $form['name'] = required_param('name', PARAM_TEXT);

                $form['tutor1email'] = optional_param('tutor1email', '', PARAM_TEXT);
                $form['tutor2email'] = optional_param('tutor2email', '', PARAM_TEXT);
                $form['tutor3email'] = optional_param('tutor3email', '', PARAM_TEXT);



                $studentTable = new StudentTable();
                // might be an add
                if ($r == 'add') {
                    $studentID = $_SESSION['currentStudent'] = $studentTable->insertNewStudent($form);
                    $logTable = new LogTable();
                    $logTable->insertLog($studentID, 'Added Student', $_SESSION['currentCourse']);

                    $_SESSION['currentStudent'] = $studentID;   // was just added
                    $_SESSION['currentCourse'] = '';
                    $_SESSION['currentLesson'] = '';
                    $_SESSION['decodelevel'] = $defaultDecodableLevel;   // default

                    if ($GLOBALS['multiCourse']) {
                        $HTML .= displayAvailableCourses();  // not part of the Lessons class

                    } else {
                        // bypass select-course logic and start lessons
                        $_SESSION['currentCourse'] = 'blending';
                        $lessons = new Lessons($_SESSION['currentCourse']);

                        $lessonName = $lessons->getNextLesson($_SESSION['currentStudent']);
                        $_SESSION['currentLesson'] = $lessonName;

                        $logTable = new LogTable();
                        $logTable->insertLog($_SESSION['currentStudent'], 'Start', $_SESSION['currentCourse'], $_SESSION['currentLesson']);

                        $HTML .= $lessons->render($lessonName);
                    }
                } else {

                    $studentTable->updateStudent(intval($q), $form);
                    $HTML .= $views->showStudentList();
                }
                break;

            case 'lessonTest':  // Mastered or Completed buttons
                assert(isset($_SESSION['currentStudent']) and !empty($_SESSION['currentStudent']));
                $studentID = $_SESSION['currentStudent'];

                // retrieve and save the test result

                $lesson = required_param('lesson', PARAM_TEXT);
                $score = optional_param('score', '0', PARAM_TEXT);
                $remark = optional_param('remark', '', PARAM_TEXT);

                $possibleSubmits = ['mastered', 'inprogress', 'completed'];  // all possible submit buttons

                $result = 'Unknown';
                foreach ($possibleSubmits as $submit) {
                    if ((optional_param($submit, 'NoValue', PARAM_TEXT)) !== 'NoValue') {  // only one submit button will have a real value
                        $result = \get_string($submit, 'mod_blending');
                    }
                }

                $logTable = new LogTable();
                $logTable->insertLog($studentID, 'test', $_SESSION['currentCourse'], $lesson, $result, $score, $remark);

                // now find the NEXT lesson (requires that this lesson be completed)

                assertTrue(!empty($_SESSION['currentCourse'])); {   // happens if they sleep
                    if (empty($_SESSION['currentCourse'])) {
                        $HTML .= $this->showStudentList();
                        break;
                    }
                }

                $lessons = new Lessons($_SESSION['currentCourse']);
                $lessonName = $lessons->getNextLesson($studentID);   // goes to database, but we already know...

                if (empty($lessonName)) {
                    if ($_SESSION['currentCourse'] !== 'introduction')  // no congrats for finishing the introduction
                        $HTML .= MForms::alert(\get_string("finished", 'mod_blending'));

                    $logTable = new LogTable();
                    $logTable->insertLog($_SESSION['currentStudent'], 'FINISHED !!', $_SESSION['currentCourse'], $_SESSION['currentLesson']);
                    $HTML .= $this->showStudentList();
                    break;
                }

                $HTML .= $lessons->render($lessonName);  // always tab 1
                break;



            case 'studentHistory':
                $studentID = $_SESSION['currentStudent'] = intval($q);

                $views = new Views();
                $HTML .= $views->showStudentHistory($studentID);
                break;


            case 'deleteStudent':
                $table = new StudentTable();
                $table->deleteStudent(intval($q));

                $table = new LogTable();
                $table->deleteStudent(intval($q));

                $HTML .= $this->showStudentList();
                break;


            case 'next':
                $lessons = new Lessons($_SESSION['currentCourse']);

                $currentLesson =  $_SESSION['currentLesson'];
                $nextLesson = $lessons->getNextKey($currentLesson, $_SESSION['currentCourse']);

                if ($nextLesson) {  // if we found another lesson record
                    $_SESSION['currentLesson'] = $nextLesson;
                } else {
                    if ($_SESSION['currentCourse'] !== 'introduction')  // no congrats for finishing the introduction
                        MForms::alert(\get_string('finished', 'mod_blending'));
                }

                $logTable = new LogTable();
                $logTable->insertLog($_SESSION['currentStudent'], 'Next', $_SESSION['currentCourse'], $_SESSION['currentLesson']);

                $HTML .= $lessons->render($_SESSION['currentLesson']);
                break;


            case 'navigation':
                assert(isset($_SESSION['currentStudent']) and !empty($_SESSION['currentStudent']));
                assert(isset($_SESSION['currentCourse']) and !empty($_SESSION['currentCourse']));
                $debug = $q == 'debug';   // explode every line for review
                $HTML .= $views->navbar(['navigation']);
                $HTML .= $views->lessonAccordian($_SESSION['currentStudent'], $_SESSION['currentCourse'], $debug);
                break;


                // generate a new dictionary

            case 'generateDictionary':

                require_once('festival.php');
                $f = new festival();
                $f->generateDictionary(-1);
                $lessons = new Lessons($_SESSION['currentCourse']);
                $currentLesson =  $_SESSION['currentLesson'];
                $HTML .= $lessons->render($_SESSION['currentLesson']);

                // $HTML .= $views->appHeader();
                // $HTML .= $views->showStudentList();
                // $HTML .= $views->appFooter();  // licence info

                break;

            case 'about':
                $HTML .= $views->about();
                break;

            default:
                assertTrue(false, "Did not expect to get here with action '$p'");
                $HTML .= $this->showStudentList();
        }

        if ($GLOBALS['debugMode']) { // only show in debug mode, ahead of normal output

            $HTML = ($GLOBALS['alertString'] ?? '') . $HTML;
            $HTML = ($GLOBALS['printNice'] ?? '') . $HTML;

        }

        return $HTML;
    }



    ///////////////////////  utilities for the controller
    function showStudentList(): string
    {
        global $defaultDecodableLevel;

        $views = new Views();
        $HTML = '';

        $_SESSION['currentCourse'] = '';
        $_SESSION['currentLesson'] = '';
        $_SESSION['decodelevel'] = $defaultDecodableLevel;   // default
        $HTML .= $views->fullScreenSuggestion();        // only shows once
        $HTML .= $views->appHeader();
        $HTML .= $views->showStudentList();
        $HTML .= $views->appFooter();  // licence info
        return $HTML;
    }
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
    $HTML .= '<br>';
    return $HTML;
}

function assertTrue($condition, $message = 'No Message Provided')
{
    $HTML = '';
    if (!$condition) {
        $HTML .= "<span style='background-color:red;color:white;'>Assertion Error: " . htmlentities($message) . "</span>&nbsp;";
        $HTML .= backTrace();
        $HTML .= MForms::alert($message);
        echo $HTML;
    }
}

function printNice($var, $message = '')
{
    if ($GLOBALS['debugMode']) {      // only if debugging, so don't worry about leftover messages
        $backTrace = backTrace();
        $message = htmlentities($message);
        echo "<pre><span style='background-color:yellow;'><b>$message</b>  $backTrace</span><br>";
        echo htmlentities(print_r($var, true)) . "</pre>";
    }
}
