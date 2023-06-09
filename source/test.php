<?php



class Test
{
    function PreFlightTest()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once('source/htmltester.php');

        function myErrorHandler($errno, $errstr, $errfile, $errline)
        {
            echo "<b style='background-color:red;color:white;'>FATAL ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            echo $GLOBALS['printNice'];
            $GLOBALS['printNice'] = '';

        }
        set_error_handler("myErrorHandler");

        ////// clear data
        // global $DB;
        // $DB->delete_records('blendingstudents',[]);
        // $DB->delete_records('blendingtraininglog',[]);




        // global $USER;
        // printNice($USER,'USER');
        // printNice($GLOBALS['cm'],'cm');


        // ///// this recreates the spelling dictionary
        // require_once("source/festival.php");
        // $f = new festival();
        // $f->generateDictionary();





        // printNice($_SERVER['REQUEST_URI'], "request server URI");
        // global $USER;
        // printNice($USER);



        // $this->getNextKey();

        // assertTrue(false, 'why?')
        // alertMessage('this is an alert');
        // $this->viewComponents();   // tabs, accordians, etc
        // $this->clusterWords();
        // $this->moodleUSER();
        // $this->getAllStudents();
        // $this->editTutors();
        // $this->wordArt();
        // $this->phonicTiles();

        // $this->testACL();
        // $this->showLessons();
        // soundInserter();
        // $this->accordian();

        // $this->writeLog();

        $HTML = $GLOBALS['printNice'] ?? '';
        $GLOBALS['printNice'] = '';
        return $HTML;
    }

    function soundInserter()
    {
        $viewComponents = new ViewComponents;
    }

    function testACL()
    {
        $a = [
            // ask about, i am,  returns
            ['admin', 'admin', true],
            ['admin', 'author', false],
            ['admin', 'teacher', false],

            ['author', 'admin', true],
            ['author', 'author', true],
            ['author', 'teacher', false],

            ['teacher', 'admin', true],
            ['teacher', 'author', true],
            ['teacher', 'teacher', true],
            ['teacher', 'tutor', false],

            ['tutor', 'author', true],
            ['tutor', 'teacher', true],
            ['tutor', 'tutor', true],
            ['tutor', 'student', false],

        ];

        foreach ($a as $test) {
            $acl = new BlendingACL();
            printNice($test);
            assertTrue($acl->ACL_Eval($test[0], $test[1]) == $test[2], "if I am {$test[1]} then I should have rights to {$test[0]}");
        }
    }


    function getNextKey()
    {
        $logTable = new LogTable();
        $logTable->insertLog('99','test','Bag Nag Tag','Mastered');

        $lessons = new Lessons();
        $lesson = $lessons->getNextLesson(99);
        assertTrue($lesson == 'Bat + Bag',"got '$lesson'");
    }


    function writeLog()
    {
        $studentID = 9999;   // test student
        $lesson = 'Big Wig Pig';

        $log = new LogTable();
        // $log->deleteStudent($studentID);
        $log->insertLog($studentID, 'tutor@me.com', $lesson, 'test', 'mastered', 8);

        $ret = $log->getLastMastered($studentID);
        printNice($ret);

        $ret = $log->getLessonTries($studentID, $lesson);
        printNice($ret);

        $ret = $log->getAllMastered($studentID);
        printNice($ret);
    }


    function accordian()
    {
        $views = new Views();

        $studentID = 9999;   // test student
        $HTML = $views->blendingAccordian($studentID);
        printNice($HTML);
    }


    function showLessons()
    {
        $bTable = new BlendingTable();
        $lessons = new Lessons();
        $views = new Views();

        $HTML = '';

        $i = 0;
        foreach ($bTable->clusterWords as $lessonName => $lessonData) {

            if ($i > 10) continue;
            $HTML = $lessons->render($lessonName, $lessonData);

            if (strlen($HTML) > 10) $i += 1;

            $GLOBALS['printNice'] .= $HTML;
        }
    }


    function phonicTiles()
    {
    }


    function wordArt()
    {
        // $art = new WordArt();
        $GLOBALS['printNice'] .= wTest();
    }

    function getAllStudents()
    {
        $HTML = '';

        // clear any old records
        global $DB;
        $DB->delete_records_select('blendingstudents', "name like 'NEW-STUDENT-%'");


        $s = new StudentTable();
        $all = $s->getAllStudents();
        printNice($all, 'all students for THIS user before addition');

        $student = ['NEW-STUDENT-'];       // unique
        $id = $s->insertNewStudent($student);
        $all = $s->getAllStudents();
        printNice($all, 'all students after addition');

        return;
    }

    function editTutors()
    {
        $HTML = '';

        require_once('source/models.php');

        $s = new StudentTable();
        $all = $s->getAllStudents();
        if (empty($all)) {
            printNice('no students, cannot test editTutors()');
            return;
        }
        printNice($all, 'student we are about to edit');

        // ok, our test student will be the first one
        $student = reset($all);     // the first one
        $_SESSION['currentStudent'] = $student->id;

        // finally, here's our test

        $vc = new Views();
        $HTML .= MForms::rowOpen(4);
        $HTML .= $vc->editTutors($_SESSION['currentStudent']);
        $HTML .= MForms::rowClose();
        echo $HTML;
    }

    function viewComponents()
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
        $HTML .= neutered($a) . "<br>";
        $a = "should look like amper-amp-semi:   &amp;";
        $HTML .= neutered($a) . "<br>";
        $HTML .= "<hr />";

        // tabs
        $tabs = [
            'first' => 'first content',
            'second' => 'second content',
            'third' => 'third content<br>third content<br>third content<br>third content<br>third content<br>third content<br>'
        ];
        $HTML .= $vc->tabs($tabs);
        $HTML .= "<hr />";


        // accordian (uses data from tabs)
        $HTML .= $vc->accordian($tabs);
        $HTML .= "<hr />";

        $GLOBALS['printNice'] .= $HTML;
    }

    function clusterWords(): string
    {

        $HTML = '';

        require_once("source/blendingtable.php");
        $b =  new BlendingTable();
        $b->loadClusterWords();

        printNice($b->words, 'words');
        printNice($b->CVC, 'CVC');
        $count = count($b->clusterWords);
        printNice($b->clusterWords, "clusterWords ($count lessons)");

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




function printNice($elem, string $message = 'no msg'): string
{

    $HTML = '';
    $span = $span2 = '';

    $span = "<span style='background-color:blue;color:white'>";
    $span2 = "</span>";

    if (is_object($elem)) {
        // just cast it to an array
        //  $HTML .= "<b>(OBJECT)</b> $span $message $span2" . printNiceHelper((array)$elem) . '</p>';
        $HTML .= "$span $message $span2 &nbsp; " . backtrace() . printNiceR((array)$elem) . '</p>';
    } else {
        // print whatever we got
        $HTML .= "$span $message $span2 &nbsp; " . backtrace() .  printNiceR($elem) . '</p>';
    }

    if (!isset($GLOBALS['printNice'])) {
        $GLOBALS['printNice'] = ''; // initialize
    }
    $GLOBALS['printNice'] .= $HTML;

    // // if debug is off, write to error.log
    // if (is_string($elem)) {
    //     $msg = str_replace('<br />', "\n", $HTML);
    //     $msg = str_replace('<p>', " ", $msg);
    //     $msg = str_replace('</p>', "", $msg);
    //     // file_put_contents('./error.log', "\n" . date('Y-M-d TH:i:s') . " $elem $msg", FILE_APPEND);
    //     // return;
    // } // debugging isn't on
    // }

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
    $HTML .= '<br>';
    return $HTML;
}

function assertTrue($condition, $message = '', $data = '')
{
    $HTML = '';
    if (!$condition) {
        $HTML .= "<span style='background-color:red;color:white;'>Assertion Error: $message</span>&nbsp;";
        $HTML .= backTrace();
        echo $HTML;
        echo printNiceR($data);
        echo $GLOBALS['printNice'];
        die;
    }
}
