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


        // HTML for stopwatch and completions
        $disp = new DisplayPages();
        $disp->lessonName = 'Fat Cat Sat';
        $disp->nTabs = 4;   // we are the fifth tab
        // printNice($disp->masteryControls('refresh.stopwatch.comment.mastery.completion'));


        // printNice($_SERVER['REQUEST_URI'], "request server URI");
        // global $USER;
        // printNice($USER);


        $this->wordSpinner();

        // assertTrue(false, 'why?')
        // alertMessage('this is an alert');
        // $this->viewComponents();   // tabs, accordians, etc
        // $this->clusterWords();
        // $this->moodleUSER();

        // $this->getAllStudents();
        // $this->editTutors();


        // $this->testStudentLog();



        // $this->wordArt();
        // $this->phonicTiles();

        // $this->testACL();
        // $this->showLessons();
        // soundInserter();
        // $this->navigation();


        $HTML = $GLOBALS['printNice'] ?? '';
        $GLOBALS['printNice'] = '';
        return $HTML;
    }

    function masteredLessons()
    {
    }


    function wordSpinner(){
        $temp = $GLOBALS['mobileDevice'];

        $v = new Views();

        $GLOBALS['mobileDevice'] = true;
        $HTML = $v->wordSpinner('b,c,d,f,g,h,j,k','a,e,i,o,u','b,c,d,f,g,h,j,k');
        printNice($HTML);

        $GLOBALS['mobileDevice'] = false;
        $HTML = $v->wordSpinner('b,c,d,f,g,h,j,k','a,e,i,o,u','b,c,d,f,g,h,j,k');
        printNice($HTML);

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


    function testStudentLog()
    {
        $studentID = 9999;   // test student

        // clear previous records for 999
        global $DB;
        $DB->delete_records_select('blendingtraininglog', "studentid = $studentID");

        // =============

        $log = new LogTable();

        $log->insertLog('9999', 'test', 'Instructions', 'mastered');
        $log->insertLog('9999', 'test', 'Bag Nag Tag', 'mastered');

        $lessons = new Lessons();
        $lesson = $lessons->getNextLesson(9999);
        assertTrue($lesson == 'Bat + Bag', "got '$lesson'");

        $lesson = 'Big Wig Pig';

        // $log->deleteStudent($studentID);
        $log->insertLog($studentID,  'test', $lesson, 'mastered', 0, 'my comment');

        $ret = $log->getLastMastered($studentID);
        printNice($ret);

        $ret = $log->getLessonTries($studentID, $lesson);
        printNice($ret);

        $ret = $log->getAllMastered($studentID);
        assert(count($ret)==3);
        printNice($ret);
    }


    function navigation()
    {
        $views = new Views();

        $studentID = 9999;   // test student
        $HTML = $views->blendingAccordian($studentID);
        printNice($HTML);
    }


    function showLessons()
    {
        $viewComponents = new ViewComponents;
        $ret = $viewComponents->lessonAccordian(99);

        $GLOBALS['printNice'] .= $ret;
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




