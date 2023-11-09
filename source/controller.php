<?php

namespace Blending;


assert_options(ASSERT_EXCEPTION, true);  // set false for production
$GLOBALS['debugMode'] = true;           // are we testing?  set false for producion

if (!isset($GLOBALS['isTesting']))
    $GLOBALS['isTesting'] = false;          // were we started with xDebug?  set false for producion

$GLOBALS['multiCourse'] = true;        // just BLENDING or multiple courses?


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


global $clusterWords;
$clusterWords = [];


$GLOBALS['allCourses'] = ['blending', 'phonics', 'decodable', 'spelling'];     // used for sanity checks?
// there should be matching files eg:  ./courses/blending.php
// TODO just interrogate the directory to find the courses available




require_once('utilities.php');

require_once('viewcomponents.php');
require_once('views.php');
require_once("wordart.php");
require_once("matrix.php");

require_once('models.php');
require_once('mforms.php');
require_once 'acl.php';

// require_once('blendingtable.php');
require_once('phonictiles.php');
require_once('lessons.php');



global $weWereAlreadyHereP;
$weWereAlreadyHere = false;

class Controller
{
    function controller(string $p, string $q, string $r): string
    {

        $HTML = '';
        $GLOBALS['printNice'] = '';
        $GLOBALS['alertString'] = '';

        global $weWereAlreadyHere;
        if ($weWereAlreadyHere) {
            return '';  // second time
        }
        $weWereAlreadyHere = true;

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

        // these two polyfills are for debug statements, so I don't have to take them out of the production code
        if (!function_exists("Blending\assertTrue")) {
            function assertTrue($condition, $message = '')
            {
                return '';
            }
        }
        if (!function_exists("Blending\printNice")) {
            function printNice($condition, $message = '')
            {
                return '';
            }
        }



        $views = new Views();
        $HTML .= $views->loadLibraries();


        // sometimes user times out, logs back in, loses session.
        if (!isset($_SESSION['currentStudent'])) {
            $_SESSION['currentStudent'] = $_SESSION['currentStudent'] ?? 0;
            $_SESSION['currentCourse'] = $_SESSION['currentCourse'] ?? '';
            $_SESSION['currentLesson'] = $_SESSION['currentLesson'] ?? '';
            $_SESSION['decodelevel'] = $defaultDecodableLevel;   // default
            $p = '';
        }


        printNice($p);
        switch ($p) {
            case '':
            case 'showStudentList':
                $HTML .= $this->showStudentList();
                break;


            case 'renderLesson':             // show a specific lesson $q in current course
                $_SESSION['currentLesson'] = $q;
                if (!empty($r))
                    $_SESSION['currentCourse'] = $r;    // can put links across courses (not used yet)

                $lessons = new Lessons($_SESSION['currentCourse']);
                $_SESSION['decodelevel'] = $defaultDecodableLevel;;   // default
                $HTML .= $lessons->render($q);
                break;


            case 'refresh':     // refrest to a specific tab
                $lessons = new Lessons($_SESSION['currentCourse']);
                $HTML .= $lessons->render($q, intval($r));
                break;

            case 'decodelevel':
                $_SESSION['decodelevel'] = intval($q);
                $lessons = new Lessons($_SESSION['currentCourse']);
                $HTML .= $lessons->render($_SESSION['currentLesson'], intval($r));
                break;


            case 'selectCourse':

                if (!$GLOBALS['multiCourse']) {     // just show students
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

                    // printNice([
                    //     'in SelectCourse' => '',

                    //     'student' => $_SESSION['currentStudent'] ?? '',
                    //     'course' => $_SESSION['currentCourse'] ?? '',
                    //     'lesson' => $_SESSION['currentLesson'] ?? '',
                    // ]);

                    $lessons = new Lessons($_SESSION['currentCourse']);
                    $lessonName = $lessons->getNextLesson($_SESSION['currentStudent']);
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

                $form =[];
                $form['name'] = required_param('name',PARAM_TEXT);

                $form['tutor1email']= optional_param('tutor1email','',PARAM_TEXT);
                $form['tutor2email']= optional_param('tutor2email','',PARAM_TEXT);
                $form['tutor3email']= optional_param('tutor3email','',PARAM_TEXT);

                $studentTable->updateStudent(intval($q), $form);
                    $HTML .= $views->showStudentList();
                }
                break;

            case 'lessonTest':  // Mastered or Completed buttons
                printNice('in lessonTest');
                assert(isset($_SESSION['currentStudent']) and !empty($_SESSION['currentStudent']));
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
                $logTable->insertLog($studentID, 'test', $_SESSION['currentCourse'], $lesson, $result, $score, $remark);

                // now find the NEXT lesson (requires that this lesson be completed)

                $lessons = new Lessons($_SESSION['currentCourse']);
                $lessonName = $lessons->getNextLesson($studentID);

                $HTML .= $lessons->render($lessonName);
                break;



            case 'studentHistory':
                $studentID = $_SESSION['currentStudent'] = intval($q);

                $views = new Views();
                $HTML .= $views->showStudentHistory($studentID);
                break;


            case 'deleteStudent':
                $db = new StudentTable();
                $db->deleteStudent(intval($q));

                $db = new LogTable();
                $db->deleteStudent(intval($q));

                $HTML .= $this->showStudentList();
                break;


            case 'next':
                $lessons = new Lessons($_SESSION['currentCourse']);

                $currentLesson =  $_SESSION['currentLesson'];
                $nextLesson = $lessons->getNextKey($currentLesson, $_SESSION['currentCourse']);

                if ($nextLesson) {  // if we found another lesson record
                    $_SESSION['currentLesson'] = $nextLesson;
                } else {
                    alertMessage('This is the last lesson.');
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

        // printNice([
        //     'afterController'=>'',
        //     'student' => $_SESSION['currentStudent']??'',
        //     'course' => $_SESSION['currentCourse']??'',
        //     'lesson' => $_SESSION['currentLesson']??'',
        // ]);

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
        $HTML .= $views->appHeader();
        $HTML .= $views->showStudentList();
        $HTML .= $views->appFooter();  // licence info
        return $HTML;
    }
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

// minimal safety string, won't disrupt JS, HTML or SQL
function neutered(string $string)
{
    // for my purposes, just convert to a similar unicode character

    $string = str_replace('&', '﹠', $string);     // should be first if we intend to use unicode '&1234;' style

    $string = str_replace('`', '’', $string);      // backtick (JS template string)
    $string = str_replace("'", '’', $string);
    $string = str_replace('"', '“', $string);

    $string = str_replace('<', '﹤', $string);
    $string = str_replace('>', '﹥', $string);

    return ($string);
}
