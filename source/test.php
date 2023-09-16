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
            echo $GLOBALS['printNice'];
            $GLOBALS['printNice'] = '';
        }
        set_error_handler("myErrorHandler");


        ///////////////////////////////////////
        ///////////////////////////////////////
        ///////////////////////////////////////

        $HTML = '';


        ///////////////////////////////////////
        ///////////////////////////////////////
        ///////////////////////////////////////




        ////// clear data
        // global $DB;
        // $DB->delete_records('blendingstudents',[]);
        // $DB->delete_records('blendingtraininglog',[]);



        ///// art for fathatsat.png
        // $wordArt = new wordArtSimple();
        // $HTML .= MForms::rowOpen(2);
        // $HTML .= $wordArt->render('fat');
        // $HTML .= $wordArt->render('hat');
        // $HTML .= $wordArt->render('sat');
        // $HTML .= MForms::rowNextCol(2);
        // $HTML .= $wordArt->render('fit');
        // $HTML .= $wordArt->render('hit');
        // $HTML .= $wordArt->render('sit');
        // $HTML .= MForms::rowClose();
        // $HTML .= "<br><br>";
        // echo $HTML;



        // ///// art for phonics.png     /// turn off top and bottom in collectedHTML();
        // $wordArt = new wordArtSimple();
        // $view= new Views();
        // $HTML .= "<table>";
        // $HTML .= "<tr><td colspan=3 style='text-align:center;font-size:3.5rem;'>".$view->sound('oh')."</td></tr>";

        // foreach(['o_e'=>'note','oa'=>'boat','oe'=>'foe','o'=>'most','ow'=>'snow','ough'=>'dough','ou'=>'soul'] as $key=>$value){
        //     $HTML .= "<tr style='margin-top:0px;'>";
        //     $HTML .= "<td style='font-size:1.4rem;padding-top:15px;text-align:right;'>".$view->spelling($key)."</td>";
        //     $HTML .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        //     // $HTML .= "<td style='font-size:1.5rem;'>- $value</td>";
        //     $HTML .= "<td style='font-size:0.4rem;'>- ".$wordArt->render($value)."</td>";
        //     $HTML .= "</tr>";

        // }
        // $HTML .= "</table>";
        // $HTML .= "<br><br>";
        // echo $HTML;




        // global $USER;
        // printNice($USER,'USER');
        // printNice($GLOBALS['cm'],'cm');


        // HTML for stopwatch and completions
        $disp = new DisplayPages();
        $disp->lessonName = 'Fat Cat Sat';
        $disp->nTabs = 4;   // we are the fifth tab
        // printNice($disp->masteryControls('refresh.stopwatch.comment.mastery.completion'));


        // printNice($_SERVER['REQUEST_URI'], "request server URI");
        // global $USER;
        // printNice($USER);


        // $this->wordSpinner();

        // assertTrue(false, 'why?')
        // alertMessage('this is an alert');
        // $this->viewComponents();   // tabs, accordians, etc
        // $this->clusterWords();
        // $this->moodleUSER();

        // $this->getAllStudents();
        // $this->editTutors();

        // $this->appHeader();
        // $this->testStudentLog();

        // $this->wordArtDecodableTest();
        $this->wordArtTest();

        // $this->phonicTiles();

        // $this->testACL();
        // $this->showLessons();
        // soundInserter();
        // $this->navigation();



        //  $un = unserialize('a:8:{i:0;s:15:"t.r.eh.m/b.eh.l";i:1;s:37:"[t;t].[r;r].[e;eh].[mb;m]/[-le;eh+l])";i:2;s:2:"10";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";i:6;s:0:"";i:7;s:7:"tremble";}');
        //   printNice($un,'unserialize TREMBLE  (tremble" nil (((t r eh m) 1) ((b ax l) 0)))');
        // $this->testConnectorStrategy();

        // ("trembling" nil (((t r eh m) 1) ((b ax) 0) ((l ih ng) 0)))


        $HTML = $GLOBALS['printNice'] ?? '';
        $GLOBALS['printNice'] = '';
        return $HTML;
    }

    function masteredLessons()
    {
    }



    function appHeader()
    {
        $v = new Views();
        printNice($v->appHeader());
    }

    // test ////////////////////////
    // the only versions that work are wordArtFull(),  wordArtSimple(), and  wordArtNone()

    // function wordArtDecodableTest(){

    //     // $a = '[c;k].[a;ah].[t;t]';
    //     // $a = 'cat';
    //     $wa = new wordArtDecodable();


    //     $punctTests = [
    //         ['Stop!','Stop'],
    //     ];
    //     foreach ($punctTests as $test){
    //         assertTrue($wa->stripPunctuation($test[0]) == $test[1],"convert failure at {$test[0]}, got '{$wa->stripPunctuation($test[0])}'");
    //     }





    // }



    // this is now an 'extended phonestring.  for example:
    // "Stop!" becomes [";*].[S;s].[t;t].[o;aw].[p;p].[!";*]
    // can't  becomes  [c;k].[a;ah].[n;n].['t;*]    // note: root is 'can'
    // thought becomes [th;th].[ough;aw].[t;t]
    // trouble becomes  [t;t].[r;r].[ou;uh].[b;b].[-le;eh+l])

    function wordArtDecodableTest()
    {
        $HTML = '';



        $testWords = [
            'cat',
            'Stop!',
            '[";*].[S;s].[t;t].[o;aw].[p;p].[!";*]',
            "can't",
            '1924',
            'mumble',
            'administratively',
        ];
        foreach ($testWords as $testWord) {
            $wordArt = new wordArtDecodable();  // do not send phonestring, send original word
            $HTML .= "<br>Decodable:  " . $wordArt->render($testWord);
            printNice($HTML);
            $HTML = '';
        }
    }


    function wordArtTest()
    {

        // $testwords = [
        //     "simple",
        //     "bake>ing",
        //     "re<con<struct>ion>s",
        //     "pre<fix",
        //     "cook>ing",
        // ];
        // foreach ($testwords as $word) {
        //     $wordart = new wordArtFull();
        //     $parsed = $wordart->parseMorphology($word);
        //     printNice($parsed,htmlentities($word));
        // }




        $HTML = '';
        $testWords = [
            // 'cat',
            'un<re<con<struct>ed',
            'ride',
            'ride>ing',
            'un<ride>able',
            'brave>er>y',
            'brave',
            'think',
            'xcomputer',
            'xblending',
            'xadoring',
            'blending',
            'fired',
            'tremble',
            'mumble',
            // 'administratively',

        ];


        // $testArray = array(
        //     'scrap',
        //     'wholesome',
        //     'overstatement',
        //     'enterprise',
        //     'alphabetical',
        //     'straightening',
        //     'bride',
        //     'association',
        //     'plaid',
        //     'abbreviation',
        //     'ambassadorial',
        //     'boot',
        //     'foot',
        //     'strengths',
        // );

        // $testArray = array(
        //     'stairway',
        //     'phonics',
        // );


        require_once("source/dictionary.php");
        global $spellingDictionary;
        printNice(count($spellingDictionary), 'count(spellingDictionary)');


        $HTML .= "<table class='table table-borderless' >";
        foreach ($testWords as $test) {

            $HTML .= "<tr><td colspan=3><h3>$test</h3></td></tr>";

            $HTML .= "<tr>";
            for ($i = 0; $i < 6; $i++) {
                switch ($i) {
                    case 0:
                        $wordArt = new wordArtNone();
                        $HTML .= "<td>None:  " . $wordArt->render($test) . "</td>";
                        break;
                    case 1:
                        $wordArt = new wordArtSimple();
                        $HTML .= "<td>Simple:  " . $wordArt->render($test) . "</td>";
                        break;
                    case 2:
                        $wordArt = new wordArtFull();
                        $HTML .= "<td>Full:  " . $wordArt->render($test) . "</td>";
                        $HTML .= "</tr><tr>";  // new line
                        break;
                    case 4:
                        $wordArt = new wordArtDecodable();  // do not send phonestring, send original word
                        $HTML .= "<td>Decodable:  " . $wordArt->render($test) . "</td>";
                        break;
                    case 5:
                        $wordArt = new wordArtAffixed();
                        $HTML .= "<td>Affixed:  " . $wordArt->render($test) . "</td>";
                        break;
                        // case 5:
                        // $wordArt = new wordArtMinimal();
                        // $HTML .= "<br>Minimal:  " . $wordArt->render($test);
                        // break;
                }
            }
            $HTML .= "</tr>";
        }
        $HTML .= "</table>";

        printNice($HTML);
    }







    function wordSpinner()
    {
        $temp = $GLOBALS['mobileDevice'];

        $v = new Views();

        $GLOBALS['mobileDevice'] = true;
        $HTML = $v->wordSpinner('b,c,d,f,g,h,j,k', 'a,e,i,o,u', 'b,c,d,f,g,h,j,k');
        printNice($HTML);

        $GLOBALS['mobileDevice'] = false;
        $HTML = $v->wordSpinner('b,c,d,f,g,h,j,k', 'a,e,i,o,u', 'b,c,d,f,g,h,j,k');
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

        $log->insertLog('9999', 'test', 'ASSISTED', 'Instructions', 'mastered');
        $log->insertLog('9999', 'test', 'BLENDING', 'Bag Nag Tag', 'mastered');

        $lessons = new Lessons('blending');
        $lesson = $lessons->getNextLesson(9999);
        assertTrue($lesson == 'Bat + Bag', "got '$lesson'");

        $course = 'BLENDING';
        $lesson = 'Big Wig Pig';

        // $log->deleteStudent($studentID);
        $log->insertLog($studentID,  'test', $course, $lesson, 'mastered', 0, 'my comment');

        $ret = $log->getLastMastered($studentID);
        printNice($ret);

        $ret = $log->getLessonTries($studentID, $lesson);
        printNice($ret);

        $ret = $log->getAllMastered($studentID);
        assert(count($ret) == 3);
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
        $ret = $viewComponents->lessonAccordian(99, 'blending');

        $GLOBALS['printNice'] .= $ret;
    }


    function phonicTiles()
    {
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



    function testConnectorStrategy()
    {

        $mc = new matrixAffix(MM_POSTFIX);

        $base = 'bake';
        $affix = 'ing';

        $strategy = $mc->connectorStrategy($base, $affix);
        $name = $mc->connectorStrategyName($strategy);

        printNice("connectorStrategy($base,$affix) returns '$name'");
        printNice($mc->connectDisplay($base, $affix, $strategy), 'connectDisplay');
        printNice($mc->connectPlus($base, $affix, $strategy), 'connectPlus');
        printNice($mc->connectText($base, $affix, $strategy), 'connectText');



        $testSuite = array(


            array('prefer', 'ing', 'preferring'),     // multi-syllable where SECOND is stressed
            array('prefer', 'ed', 'preferred'),
            array('prefer', 'ence', 'preference'),    // stress moved from preFER to PREference

            array('crumb', 'y', 'crummy'),      // mb->mm
            array('dumb', 'y', 'dummy'),

            array('notice', 'able', 'noticeable'),           // able after ce ending
            array('replace', 'able', 'replaceable'),         // able after ce ending

            array('be', 'ing', 'being'),                    // final sylabic e  + initial not-e
            array('see', 'ing', 'seeing'),
            array('agree', 'able', 'agreeable'),
            array('forsee', 'able', 'forseeable'),
            array('agree', 'ed', 'agreed'),

            array('canoe', 'ing', 'canoeing'),               // final oe,ye is like final ee
            array('canoe', 'ed', 'canoed'),
            array('toe', 'ing', 'toeing'),
            array('toe', 'ed', 'toed'),
            array('eye', 'ing', 'eyeing'),
            array('eye', 'ed', 'eyed'),

            array('due', 'ly', 'duly'),                     // three 'ly' exceptions
            array('whole', 'ly', 'wholly'),
            array('true', 'ly', 'truly'),



            array('ease', 'y', 'easy'),               // final e + y

            array('cancel', 'ing', 'cancelling'),     // final L on a multi-syllable word
            array('open', 'ing', 'opening'),

            array('box', 'ing', 'boxing'),
            array('pry', 'ing', 'prying'),
            array('pry', 'ed', 'pried'),

            array('pony', 'es', 'ponies'),

            array('pack', 'ing', 'packing'),
            array('care', 'ing', 'caring'),

            array('pet', 'ing', 'petting'),       // monosyllable rule
            array('carpet', 'ing', 'carpeting'),
            array('pen', 'ed', 'penned'),
            array('happen', 'ed', 'happened'),

            array('focus', 'ing', 'focusing'),
            array('focus', 'ed', 'focussed'),

            array('crumb', 'y', 'crummy'),

            array('star', 'ing', 'starring'),
            array('die', 'ing', 'dying'),
            array('try', 'ing', 'trying'),
            array('fly', 'es', 'flies'),
            array('sew', 'ing', 'sewing'),
            array('wax', 'ing', 'waxing'),
            array('play', 'ing', 'playing'),
            array('pay', 'ment', 'payment'),
            array('army', 'es', 'armies'),
            array('quilt', 'ing', 'quilting'),
            array('bridge', 'ed', 'bridged'),
            array('calm', 'ed', 'calmed'),

            // som silent-e tests from http://www.resourceroom.net/readspell/silentedrop.asp
            array('home', 'less', 'homeless'),
            array('shape', 'ing', 'shaping'),
            array('shape', 'ed', 'shaped'),
            array('become', 'ing', 'becoming'),
            array('waste', 'ing', 'wasting'),
            array('fame', 'ous', 'famous'),
            array('hope', 'ful', 'hopeful'),

            array('duce', 'ate', 'ducate'),               // e+duce+ate+ion
            array('cate', 'ion', 'cation'),               // educate+ion

            array('place', 'ate', 'placate'),
            array('plate', 'ing', 'plating'),
            array('toe', 'ing', 'toeing'),
            array('toe', 'ed', 'toed'),
            array('eye', 'ed', 'eyed'),
            array('eye', 'ing', 'eyeing'),


            // this one fails....
            array('grime', 'y', 'grimy'),
            array('fun', 'y', 'funny'),

            //http//www.ryerson.ca/learningsuccess/resources/appendixg.pdf
            array('true', 'ly', 'truly'),
            //Keep the final silent e when it is preceded by a soft g or soft c:
            array('change', 'able', 'changeable'),
            array('courage', 'ous', 'courageous'),
            array('enforce', 'able', 'enforceable'),
            array('peace', 'able', 'peaceable'),
            array('produce', 'ing', 'producing'),

            // <panicking> <picnicking> <politicking> <rollicking>
            array('music', 'al', 'musical'),
            array('panic', 'ing', 'panicking'),
            array('picnic', 'ing', 'picnicking'),
            array('picnic', 'er', 'picnicker'),
            array('politic', 'ing', 'politicking'),
            array('traffic', 'ing', 'trafficking'),
            array('traffic', 'ed', 'trafficked'),

            array('fizz', 'y', 'fizzy'),    // words that end in z
            array('fizz', 'ing', 'fizzing'),
            array('blitz', 'ing', 'blitzing'),

            array('humble', 'ly', 'humbly'),
            array('gentle', 'ly', 'gently'),

            array('general', 'ly', 'generally'),

            array('hap', 'y', 'happy'),
            array('happy', 'ness', 'happiness'),

            array('barge', 'ed', 'barged'),

            // test the 'double-l'
            array('legal', 'ize', 'legalize'),   // -al endings don't double (except for crystal)
            array('final', 'ize', 'finalize'),
            array('propel', 'ed', 'propelled'),

            // exceptions for -ish, ite
            array('mon', 'ish', 'monish'),    //admonish
            array('mon', 'ite', 'monite'),    //premonition
            array('fin', 'ite', 'finite'),
            array('min', 'ion', 'minion'),
            array('trin', 'ity', 'trinity'),
            array('nat', 'ive', 'native'),
            array('nat', 'ure', 'nature'),
            array('affix', 'ed', 'affixed'),

            array('tie', 'ing', 'tying'),   // ie -> y

        );

        $mc = new matrix_common();
        $HTML = '<table><tr><th>connectPlus</th>
                        <th>connectDisplay</th>
                        <th>connectText</th>
                        <th>connectLogic</th>
                        </tr>';
        foreach ($testSuite as $test) {
            $strategy = $mc->connectorStrategy($test[0], $test[1]);

            $base = $test[0];
            $affix = $test[1];

            $HTML .= "<tr><td>";
            $HTML .= $mc->connectPlus($base, $affix, $strategy);
            $HTML .= "</td><td>";
            $HTML .= $mc->connectDisplay($base, $affix, $strategy);
            $HTML .= "</td><td>";
            $HTML .= $mc->connectText($base, $affix, $strategy);
            $HTML .= "</td><td>";
            $HTML .= $mc->connectLogic($base, $affix, $strategy);
            $HTML .= "</td></tr>";

            $result = $mc->connectText($test[0], $test[1], $strategy);

            assertTrue($result == $test[2], "'{$test[0]}' + '{$test[1]}' -> '$result' (expected '{$test[2]}') <br>{$mc->debug}<br><br>");
        }
        $HTML .= "</table>";
        echo $HTML;
    }
}
