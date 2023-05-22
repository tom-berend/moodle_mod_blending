<?php
// Interface that all scripts must support

function debugDisplayPages()
{
    return (false);
}

interface Chapters
{
    public function FTR();
    public function PEX();
    public function V01();
    public function V02();
    public function V03();
    public function PMX();
}

// trainingProgram invokes a chapter in the
// script which creates a bunch of rule-objects from scripts, each
// registering itself back into an array in trainingProgram.

class lesson_prototype
{
    var $script;
    var $chapter;
    var $lessonName;
    var $lessonNameFormatted;
    var $lessonKey; // url encoded unique name from $script.$lessonName
    var $aPrerequisites;
    var $group;

    var $showTiles = false;
    var $redTiles = ''; // rest are blue

    var $pages = array();

    // the rule_prototype returns a rule, but also registers that rule into a number of data structures.

    function __construct($lessonName = "????", $aPrerequisites = false)
    {

        //        $this->script         = $script;       // initialize the parameters
        //        $rule_instance->chapter        = $chapter;
        $this->lessonName = $lessonName;
        $this->aPrerequisites = $aPrerequisites;
    }

    function addPageToLesson($displayType, $layout, $style, $tabname, $dataparm, $data, $note = '')
    {
        // just stores the parameters for the page, doesn't generate the page object yet
        $this->pages[] = array($displayType, $layout, $style, $tabname, $dataparm, $data, $note);
    }

    function renderLesson($lessonName = '', $tabName = '')
    { // starting tab, if specified

        assertTRUE(count($this->pages) > 0, "No pages for lesson '{$this->lessonName}'"); // need at least one page

        $document = document::singleton();

        // if we are the phonics lesson, then add the phonics tiles
        if ($this->showTiles) {
            require_once 'models/phonicTiles.php';
            $phonicTiles = new phonicTiles();
            $document->writePhonicTiles($phonicTiles->render());
        }

        $document->setInitialTab($tabName);

        printNice('page parameters',$this->pages);
        foreach ($this->pages as $pageParameters) {
            $pageClass = $pageParameters[0]; // first parameter is the object class
            // styleParm      // tabName         // dataParm           // data
            $page = new $pageClass();
            $page->lesson = $this; // so the page can access the lesson's properties

            // type of page is controlled by the class that is invoked
            $page->layout = $pageParameters[1];
            $page->style = $pageParameters[2];
            $page->tabName = $pageParameters[3];
            $page->dataParm = $pageParameters[4];
            $page->data = $pageParameters[5];
            if (isset($pageParameters[6])) {
                $page->note = $pageParameters[6];
            }

            //   echo "have a page", $page->tabName, '<br />';
            $page->render($this->lessonName);

            $document->setTitleBar('header', 'title', $this->lessonName, '');
        }
    }

}

interface BasicDisplayFunctions
{ // Interface that all display classes must support

    public function setupLayout($layout);
    public function render($lessonName);
}

class defaultDisplay extends viewpages
{

#       +---------------------------+
    #       |           HEADER          |
    #       +------------------+--------+
    #       |                  |        |
    #       |                  |        |
    #       |     ABOVE        | ASIDE  |
    #       |                  |        |
    #       +------------------+        |
    #       |                  |        |
    #       |     BELOW        |        |
    #       |                  |        |
    #       +------------------+--------+
    #       |           FOOTER          |
    #       +---------------------------+
    #

// new style parameters
    var $lesson;
    var $style;
    var $layout;
    var $tabName;
    var $dataParm;
    var $data;
    var $note;

// old style
    var $aMethods = array();

    // these two are used for the 'aside' on the right
    var $controls = 'note.refresh'; // default

    var $suggestion = '';

    var $header = '';
    var $above = '';
    var $below = '';
    var $aside = '';
    var $footer = '';

    var $showPageName = false;
    var $defaultDifficulty = 2;

    // feel free to subclass these functions for any page...

    function header()
    {return ('');}
    function above()
    {return ('');}
    function below()
    {return ('');}
    function aside()
    {$r = $this->refreshNotes();
        $m = $this->masteryControls();
        //echo "'$r', '$m'";die;
        $HTML = '';
        if (!empty($r) or !empty($m)) {
            $HTML = "<table> $r $m </table>";
        }

        return ($HTML);
    }
    function footer()
    {return ($this->footer);}

    function styleDefinitions()
    {
        $HTML = PHP_EOL . "<style>   <!--  /* Dead Simple Grid (c) 2012 Vladimir Agafonkin */   -->
		.container { max-width: 90em;  background-color:white;}

		/* you only need width to set up columns; all columns are 100%-width by default, so we start
		   from a one-column mobile layout and gradually improve it according to available screen space */

		.header,.above,.below,.aside,.footer
                     { width: 100%; display:block; padding:0px;}
		.inactive {  width:0%; display:none;  padding:0px;}
                        .phone  {width:100%; display:block; padding:0px;}
                        .laptop {width:  0%; display:none; padding:0px;}

                /* for wordspinner */
                .ui-btn-inner { font-size: 14px;
                                padding: 2px 10px 2px 10px;
                                min-width: .30em;
                                }
                /* for phonics tiles */
                sound {font-size:12px;}



		@media only screen and (min-width: 500px) {
        		.header,.footer{ width: 100%; }
                        .above,.below { width: 66%;}
                        .aside { width: 33%;}
                        .phone  {width: 0%;  display:none; padding:0px;}
                        .laptop {width:100%; display:block; padding:0px;}

                /* for wordspinner */
                .ui-btn,.ui-btn-inner,.ui-btn-hidden { font-size: 12px;
                                padding: 1px 5px 1px 5px;
                                min-width: .20em;
                                margin:0px;
                                padding:0px;
                                border:0px;
                                min-width:0px;
                                }
                /* for phonics tiles */
                sound {font-size:14px;}


		@media only screen and (min-width: 700px) {
        		.header,.footer{ width: 100%; }
                        .above,.below { width: 66%;}
                        .aside { width: 33%;}
                        .phone  {width: 0%;  display:none; padding:0px;}
                        .laptop {width:100%; display:block; padding:0px;}

                /* for phonics tiles */
                sound {font-size:22px;}
		}


     		@media only screen and (min-width: 620px) {
                        .ui-btn-inner { font-size: 16px;
                                        padding: 4px 15px 4px 15px;
                                        min-width: .60em;}
                }
		@media only screen and (min-width: 700px) {
                        .ui-btn-inner { font-size: 18px;
                                        padding: 6px 20px 6px 20px;
                                        min-width: .75em;}
                }

                </style>";
        return ($HTML);
    }

    function setupLayout($layout)
    {
        trace(__CLASS__, __METHOD__, __FILE__, $layout);
        $this->layout = $layout;
    }

    // it would be nice to rewrite this in CSS-driven HTML5
    function render($lessonName)
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        // logic for now is that

        // only call them once, and call them all early, in case of side effects.
        $above = $this->above(); // and call above() FIRST because it tends to
        // do things like move the controls to the header
        $below = $this->below();
        $header = $this->header();
        $footer = $this->footer();
        $aside = $this->aside();

        if (false) { //empty($header) and empty($footer) and empty($above) and empty($aside)){
            // nothing in table.   might be a startup page.
        } else {

            if ($this->showPageName) {
                $above .= "<br />$lessonName";
            }

            $border = '';
            if (debugDisplayPages()) {
                $border = 'style="border-style:solid;border-width:2px;"';
            }

            $HTML = '';

            if (!empty($header)) {
                $HTML .= PHP_EOL . '<div class="row">';
                $HTML .= "<div class='col header' $border>";
                $HTML .= "<h2>$header</h2>";
                $HTML .= PHP_EOL . '</div>';
                $HTML .= '</div>';
            }
            if (!empty($aside)) {
                $HTML .= PHP_EOL . '<div class="row">';
                $HTML .= "<div class='col above' $border>";
                $HTML .= $above;
                $HTML .= $below;
                $HTML .= PHP_EOL . '</div>';
                $HTML .= "<div class='col aside'>";
                $HTML .= $aside;
                $HTML .= PHP_EOL . '</div>';
                $HTML .= '</div>';
            } else { // no aside, take the full page if we need to
                $HTML .= PHP_EOL . '<div class="row">';
                $HTML .= "<div class='col header' $border>";
                $HTML .= $above;
                $HTML .= $below;
                $HTML .= PHP_EOL . '</div>';
                $HTML .= '</div>';
            }

            if (!empty($footer)) {
                $HTML .= PHP_EOL . '<div class="row">';
                $HTML .= '<div class="col header" >';
                $HTML .= $footer;
                $HTML .= PHP_EOL . '</div>';
                $HTML .= '</div>';
            }

            if ($GLOBALS['debugON']) {
                $HTMLTester = new HTMLTester();
                $HTMLTester->validate($above);
                $HTMLTester->validate($below);
                $HTMLTester->validate($aside);
                $HTMLTester->validate($footer);
            }

            $document = document::singleton();
            $document->writeTab($this->tabName, $HTML);

        }
    }

    function generate($aString, $n = 10)
    { // given a string, generate 10 (or n) words in random order

        if (!is_string($aString)) {
            assertTRUE(false, "Expecting a comma-string, got " . serialize($aString) . " in $this->lessonName");
            return (array_fill(0, $n, '?'));
        }

        $aString = str_replace(' ', '', $aString); // lose spaces
        $aString = str_replace("\n", '', $aString); // lose CRs
        $aString = str_replace("\r", '', $aString); // lose LFs

        $wordArray = explode(',', $aString);

        // may have some empty elements, remove them...
        while (($key = array_search('', $wordArray)) !== false) {
            unset($wordArray[$key]);
        }

        // handle the exception cases first...
        if (empty($wordArray)) {
            assertTRUE(false, "Received an empty array in Generate() after exploding '$aString'");
            $resultArray = array_fill(0, $n, '?');

        } elseif (count($wordArray) == 1) { // special case, legal but should never happen
            assertTRUE(false, "Received a single-element array in Generate() after exploding '$aString'");
            $resultArray = array_fill(0, 10, current($wordArray));

        } else { // ok, this is the general case, at least two elements...

            // we want a particular type of sort:  if the input is
            //  a,b,c  then we want  a,c,b,  b,c,a,  a,b,c,   etc,
            // and never the more random possiblity  a,a,a...

            shuffle($wordArray); // weird function, sorts in place
            $tempArray = $wordArray; // a copy...
            while (count($tempArray) < $n) {
                shuffle($wordArray); // only suffles the stuff we are adding

                // still the possibility of a,b,c  c,b,a  (two c's in a row)
                // in that case, shuffle again (and we'll accept the result)
                if ($tempArray[count($tempArray) - 1] == $wordArray[0]) {
                    shuffle($wordArray);
                }

                $tempArray = array_merge($tempArray, $wordArray);
            }
            // now $tempArray is guaranteed to be 10 or longer
            // select the first 10 elements
            $resultArray = array_slice($tempArray, 0, $n);

        }
        return ($resultArray);
    }

    function generate9($dataParm, $layout, $data)
    { // split data into an array

        // first we make up the dataset.  each ROW must look like word or word/word or word/word/word

        if (empty($layout)) { // default layout
            $layout = '.1col';
        }

        assertTRUE(strpos('.1col.2col.3col.4col.5col', $layout) !== false, "layout is '$layout', must be '1col','2col','3col','4col',' or '5col'");

        $displayColumns = strval(substr($layout, 0, 1)); // 1col, etc

        $result = array();

        switch ($dataParm) {

            case 'reverse':

                $d = new nextWordDispenser($data);
                assertTRUE($d->count() == 1, "Only one data column allowed for reverse");

                for ($i = 0; $i < 9; $i++) {
                    $result[] = implode('/', array_reverse(explode('/', $d->pull())));
                }
                break;

            case 'normal': // strangely, normal and scramble are the same
            case 'scramble':

                // every display column gets its own dispenser (because we want the
                // words DOWN to have that too-random feel, not be merely random.)

                $d = array();
                for ($j = 0; $j < $displayColumns; $j++) { // array from 1 to n
                    $d[$j] = new nextWordDispenser($data);
                }

                $userWords = array();
                $nth = 0;
                for ($i = 0; $i < 9; $i++) {
                    $row = '';
                    for ($j = 0; $j < $displayColumns; $j++) {
                        if (!empty($row)) {
                            $row .= '/';
                        }

                        // but we run into a problem that we reuse word.
                        // if we have already seen this word, then try again (up to 3 times)
                        $candidate = $d[$j]->pull();
                        if (array_search($candidate, $userWords) !== false) {
                            $candidate = $d[$j]->pull();
                            if (array_search($candidate, $userWords) !== false) {
                                $candidate = $d[$j]->pull();
                                if (array_search($candidate, $userWords) !== false) {
                                    $candidate = $d[$j]->pull();
                                }
                            }
                        }
                        $userWords[] = $candidate;
                        $row .= $candidate;
                    }
                    $result[] = $row;
                    $row = '';
                }
                break;

            default:
                assertTRUE(false, "dataParm is '$dataParm', must be 'normal', 'reverse', 'noSort', or scramble'");
        }

        return ($result);
    }

    function wordartlist()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $HTML .= '<div id="wordArtList">';

        $data9 = $this->generate9($this->dataParm, $this->layout, $this->data); // split data into an array

        // only use the 'wordlist' class for no styling, otherwise use the wordard
        if ($this->style == 'none') {
            $HTML .= '<table class="wordlist">';
        } else {
            $HTML .= '<table>';
        }

        $n = 8; // usually we have 9 elements (0 to 8)
        // if ($this->style == 'full' or $this->style == 'simple') {
        //     $n -= 2;
        // }
        // two less if we use wordart
        for ($i = 0; $i <= $n; $i++) {

            if (strpos($data9[$i], '</') !== false) {
                $triple = $data9[$i];
            }
            //  ignore <style>thing</style>
            else {
                $triple = explode('/', $data9[$i]);
            }
            //  turn make/made/mate into an array

            $HTML .= '<tr>';
            foreach ($triple as $word) {
                if ($this->style == 'full') {
                    $HTML .= "<td style=\"width:400px\">" . $this->wordArt->render($word) . "</td>";
                } elseif ($this->style == 'simple') {
                    $HTML .= "<td style=\"width:320px\">" . $this->wordArt->render($word) . "</td>";
                } else {
                    $HTML .= "<td style=\"width:250px\" class=\"processed\">" . $this->wordArt->render($word) . "</td>";
                }
            }

            $HTML .= '</tr>';
        }
        $HTML .= '</table>';

        $HTML .= '</div>';
        return ($HTML);
    }

    // code for a stopwatch plus learning curve
    function stopwatchHTML()
    {

        $HTML = '';

        $HTML .= PHP_EOL . '<form name="stopwatchForm" method="POST" id="stopwatchForm" data-ajax="false">';
        $HTML .= PHP_EOL . '<table ><tr>';
        $HTML .= PHP_EOL . '<td class="display" style="text-align:center;padding:5px;">
                                    <input type="text" name="sec" value="000" size="3" style="font-size:300%" />';
        $HTML .= PHP_EOL . '</td>';

        $HTML .= PHP_EOL . '<td>';
        // the default for a button is type="submit", so don't forget the type="button"
        $HTML .= PHP_EOL . '<table><tr>';
        $HTML .= PHP_EOL . '<td><button type="button" style="background-color:green;" onClick="StopWatch.start()">Start</button></td>';
        $HTML .= PHP_EOL . '</tr><tr>';
        $HTML .= PHP_EOL . '<td><button type="button" style="background-color:red;" onClick="StopWatch.stop()">Stop</button></td>';
        $HTML .= PHP_EOL . '</tr><tr>';
        $HTML .= PHP_EOL . '<td><button type="button" onClick="StopWatch.reset()">Reset</button></td>';
        $HTML .= PHP_EOL . '</tr></table>';

        $HTML .= PHP_EOL . '</td>';
        $HTML .= '</tr><tr>';
        $HTML .= PHP_EOL . '<td colspan=2 style="padding-bottom:15px;">
                                        <button type="button" style="background-color:blue;" onClick="StopWatch.save()">Save</button>
                                    </td>';
        $HTML .= '</tr></table>';

        $HTML .= '<input type="hidden" name="action" value="firstpage.RRstopwatch" />';
        $HTML .= '<input type="hidden" name="lessonKey" value="' . $this->lesson->lessonKey . '" />';
        $HTML .= '<input type="hidden" name="tabName" value="' . $this->tabName . '" />';
        $HTML .= '</form>';
        return ($HTML);
    }

    // code for a stopwatch plus learning curve
    function learningCurveHTML()
    {
        $HTML = '';

        $ts = new student(); // pick up current session

        $cargo = $ts->cargo;
        printNice('LC', "The cargo we are going to graph");
// printNice('LC',$cargo);

        $currentLessonName = $cargo['currentLesson'];

        // we need to get our data
        $data = array(); // default is empty array
        if (isset($cargo['currentLessons'][$currentLessonName])) {
            $currentLesson = $cargo['currentLessons'][$currentLessonName];

            // it is possible that this lesson has already been mastered
            if (isset($cargo['masteredLessons'][$currentLessonName])) {
                if (isset($cargo['masteredLessons'][$currentLessonName]['learningCurve'])) {
                    $data = $cargo['masteredLessons'][$currentLessonName]['learningCurve'];
                }
            }

            // it is possible that this lesson is in the current lessons
            //     then use currentlesson data only
            if (isset($cargo['currentLessons'][$currentLessonName])) {
                if (isset($currentLesson['learningCurve'])) {
                    $data = $currentLesson['learningCurve'];
                }
            }

            printNice('LC', "The lesson we are going to graph: $currentLessonName");
            printNice('LC', $currentLesson);

            // if this is the first time in lesson, we might not have a 'learningCurve'
            if (isset($currentLesson['learningCurve'])) {
                $data = $currentLesson['learningCurve'];
            }

        }
        // one way or another we have set $data

//                $data = array(241,165,139,127,120);

        printNice('LC', "The data we are going to graph");
        printNice('LC', $data);

        if (!empty($data)) {
            $learningCurve = new learningCurve();
            $imgURL = $learningCurve->learningCurveChart($data);

            $HTML .= '<table><td>';
            $HTML .= '<img alt="Line chart" src="' . $imgURL . '" style="border: 1px solid gray;" />';
            $HTML .= '</td></table>';
        }
        return ($HTML);
    }

    function refreshHTML()
    {
        $HTML = '';
        if (strpos($this->controls, 'refresh') !== false) {
            $HTML .= '<tr><td>';
            $systemStuff = new systemStuff();
            $HTML .= $systemStuff->buildIconSubmit('view-refresh-3', 48, 'actions', 'Refresh', 'firstpage', 'RefreshPage', $this->tabName, $this->lesson->lessonKey, '', 'TM_stopClock();');
            $HTML .= '<br />Refresh<br /><br /><br />';
            $HTML .= '</td></tr>';
        }
        return ($HTML);
    }

    function noteHTML()
    {
        $HTML = '';
        if (strpos($this->controls, 'note') !== false) {
            $HTML .= '<tr><td align="left">';
            $HTML .= '<span style="font-size:150%">';
            $HTML .= "<blockquote>$this->note</blockquote>"; // use the Joomla format
            $HTML .= '</span></td></tr>';
        }
        return ($HTML);
    }

    function refreshNotes()
    {
        return ($this->refreshHTML() . $this->noteHTML());
    }

    function completionHTML()
    {
        return ($this->masteryOrCompletion(false));
    }

    function completedHTML()
    {
        return ($this->masteryOrCompletion(false, false));
    }

    function masteryHTML()
    {
        return ($this->masteryOrCompletion(true));
    }

    // this guy avoids cut-and-paste for two prev functions
    function masteryOrCompletion($includeTimer, $includeAdvancing = 'true')
    {
        $HTML = '';
//$HTML .=" masteryOrCompletion(";
        //$HTML .= $includeTimer?'true':'false';
        //$HTML .= $includeAdvancing?'true':'false';

        $systemStuff = new systemStuff();

        $loginForm = new mobileForms();
        $loginForm->addForm("mstryfrm", "mstryfrm", $systemStuff->PHONICS_URL(), "POST");
        //$loginForm->addTextFieldToForm("", "", "hidden", "action", "", "firstpage.mastery");

        if ($includeTimer) {
            $loginForm->addTextFieldToForm("Timer", "", "text", "timer", "timer", "0");
        }

        $loginForm->addTextAreaToForm("", "Comment", "Comment", "Comment");

        // same URL in all cases, use the $action to capture the value
        $URL = '';

        if ($includeAdvancing) {
            $action = "TM_buttonSubmit('Advancing')";
            $loginForm->addSubmitButton("Advancing", YELLOW, $action);
            $action = "TM_buttonSubmit('Mastered')";
            $loginForm->addSubmitButton("Mastered", BLUE, $action);
        } else {
            $action = "TM_buttonSubmit('Mastered')";
            $loginForm->addSubmitButton("Completed", BLUE, $action);

        }

        $loginForm->addTextFieldToForm("", "", "hidden", "P1", "P1", ""); // P1 is the mastery level (eg: Completed)
        $loginForm->addTextFieldToForm("", "", "hidden", "testwords", "", "");
        $loginForm->addTextFieldToForm("", "", "hidden", "errors", "", "");
        $loginForm->addTextFieldToForm("", "", "hidden", "lessonKey", "", $this->lesson->lessonKey);
        $loginForm->addTextFieldToForm("", "", "hidden", "action", "", "firstpage.TimedSubmit");
        $loginForm->addTextFieldToForm("", "", "hidden", "transaction", "", 'T' . uniqid());

        $HTML .= $loginForm->render();
        return ($HTML);
    }

    // masteryControls uses $this->controls, but the whole thing can be overwritten
    function masteryControls()
    { // eg:  'refresh.timer.comment'
        $HTML = '';

        // empty but there
        if (strpos($this->controls, 'empty') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= ' ';
            $HTML .= '</td></tr>';
        }

        // stopwatch
        if (strpos($this->controls, 'stopwatch') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= $this->stopwatchHTML();
            $HTML .= '</td></tr>';
        }

        // timer element (combines timer and completion)
        if (strpos($this->controls, 'timer') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= $this->masteryHTML();
            $HTML .= '</td></tr>';
        }

        // mastery element
        if (strpos($this->controls, 'mastery') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= $this->masteryHTML();
            $HTML .= '</td></tr>';
        }

        // completion element
        if (strpos($this->controls, 'completion') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= $this->completionHTML();
            $HTML .= '</td></tr>';
        }

        // completion-only element
        if (strpos($this->controls, 'completed') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= $this->completedHTML();
            $HTML .= '</td></tr>';
        }

        // learning curve graph
        if (strpos($this->controls, 'LCgraph') !== false) {
            $HTML .= '<tr><td>';
            $HTML .= $this->learningCurveHTML();
            $HTML .= '</td></tr>';
        }

        return ($HTML);

    }

    function debugParms($class, $override = false)
    {

        $HTML = '';
        if (debugDisplayPages() or $override) { // DEBUG
            $HTML .=
                "script:   {$this->lesson->script} <br />
                     class:    $class  <br />
                     layout:   $this->layout <br />
                     style:    $this->style <br />
                     tabName:  $this->tabName  <br />
                     dataParm: $this->dataParm  <br />
                     data:     ";
            foreach ($this->data as $k => $s) {
                $HTML .= $k . ' => ' . substr($s, 0, 50) . '...    ';
            }

            $HTML .= " <br />
                     note:     $this->note <br />";
            $HTML .= "<b>{$this->lesson->lessonName}</b>";
            $HTML .= '<br />' . $this->dataParm;
            //$HTML .= serialize($this->lesson);
        }

        return ($HTML);

    }
}

class nextWordDispenser extends UnitTestCase
{

    public $wordArrays;
    public $depleteArrays;
    public $indexes = array(); // array of indexes into $wordArrays

    public $random = true; // default, randomize

    public function __construct($wordStrings)
    { // $wordStrings is either a wordString or
        // an array of wordStrings.  A wordString is
        // a comma-delimited set of words
        $this->load($wordStrings);
    }

    public function testFunction()
    {
        $this->load('a,b,c');
        printNice('nextWordDispenser', $this);
        $pull = '';
        for ($i = 0; $i < 50; $i++) {
            $pull .= $this->pull();
        }

        printNice('nextWordDispenser', $pull);
        assertTRUE($this->count() == 1);

        $this->load(array('d,e,f,g', 'h,i'));
        printNice('nextWordDispenser', $this);
        $pull = '';
        for ($i = 0; $i < 50; $i++) {
            $pull .= $this->pull();
        }

        printNice('nextWordDispenser', $pull);
        assertTRUE($this->count() == 2);

        return (true);
    }

    public function count()
    {
        return (count($this->wordArrays)); // simply the number of arrays
    }

    public function load($wordStrings)
    {
        switch (gettype($wordStrings)) {
            case 'string':

                $wordStrings = str_replace(' ', '', $wordStrings); // lose spaces
                $wordStrings = str_replace("\n", '', $wordStrings); // lose CRs
                $wordStrings = str_replace("\r", '', $wordStrings); // lose LFs

                $this->wordArrays = array(explode(',', $wordStrings)); //one-element array
                break;

            case 'array':
                $this->wordArrays = array();
                foreach ($wordStrings as $words) {
                    $words = str_replace(' ', '', $words); // lose spaces
                    $words = str_replace("\n", '', $words); // lose CRs
                    $words = str_replace("\r", '', $words); // lose LFs

                    $this->wordArrays[] = explode(',', $words);
                }
                break;

            default:
                assertTRUE(false, "Didn't expect type " . gettype($wordStrings));
        }
        // ok, $wordArrays is set up with one or more arrays of words

        $this->depleteArrays = $this->wordArrays; // copy them
    }

    public function pull()
    {
        // first we check if there are any indexes left, refill if necessary
        if (count($this->indexes) == 0) {
            $this->indexes = array_keys($this->wordArrays);
        }

        assertTRUE(count($this->indexes) > 0);

        if ($this->random) {
            $index = array_rand($this->indexes, 1);
        }
        // pick an index
        else {
            reset($this->indexes);
            $first_key = key($this->indexes);
        }

//////////////////////////////////////
        // would like some logic here to prevent runs
        //  eg: if 2  indexes then prevent 1.1.1)
        //      if 3+ indexes then prevent 1.1

        // next we check that the array hasn't been depleted
        if (count($this->depleteArrays[$index]) == 0) {
            $this->depleteArrays[$index] = $this->wordArrays[$index];
        }

        assertTRUE(count($this->depleteArrays[$index]) > 0);

//        printNice('nextWordDispenser',$this->indexes);
        //        printNice('nextWordDispenser',"index is $index from count ".count($this->indexes));

        if ($this->random) {
            $target = array_rand($this->depleteArrays[$index], 1);
        }
        // pick an target word
        else {
            reset($this->depleteArrays[$index]);
            $target = key($this->depleteArrays[$index]);
        }

//        printNice('nextWordDispenser',"target was {$target},will pull {$target}[{$index}] {$this->depleteArrays[$index][$target]}");

        $word = $this->depleteArrays[$index][$target];
        unset($this->indexes[$index]);
        unset($this->depleteArrays[$index][$target]);

//        printNice('nextWordDispenser',$this->depleteArrays);
        return ($word);
    }
}

class startupPage extends defaultDisplay implements BasicDisplayFunctions
{

    public function __construct($tabName = "", $data = "")
    {
        $this->controls = ''; // override the default controls
        $this->data = $data;
        $this->tabName = $tabName;
    }

    public function above()
    {
        $HTML = '';
        foreach ($this->data as $name => $linkArray) {
            $HTML .= "<b>$name</b>";
            $HTML .= "<ul>";
            foreach ($linkArray as $text => $url) {
                $HTML .= "<li><a href=\"$url\">$text</a></li>";
            }
            $HTML .= "</ul>";
        }
        return ($HTML);
    }

}

class formPage extends defaultDisplay implements BasicDisplayFunctions
{

    public function __construct($tabName = '', $data = '')
    {
        $this->controls = ''; // override the default controls
        $this->data = $data;
        $this->tabName = $tabName;
    }

    public function above()
    {
        $this->controls = ''; // override the default controls
        return ($this->data->render());
    }
}

// difference between InstructionPage / Page2 / Page3 is just the controls

class instructionPage extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        //$HTML .= "<b>{$this->lesson->lessonName}</b>";
        $HTML .= $this->dataParm;

        $this->controls = ''; // override the default controls

        return ("<div style=\"max-width:650px;font-size:150%;line-height:150%;\">$HTML</div>");
    }

}

class instructionPage2 extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        //$HTML .= "<h1>{$this->lesson->lessonName}</h1>";
        $HTML .= $this->dataParm;
        $this->controls = 'completed'; // override the default controls

        return ("<div style=\"max-width:600px;font-size:150%;line-height:150%;float:top\">$HTML</div>");
    }

}

class instructionPage3 extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $HTML .= $this->dataParm;
        $this->controls = 'note.completed';
        $HTML .= $this->masteryControls();
        $this->controls = '';

        return ("<div style=\"max-width:600px;font-size:150%;line-height:150%;float:top\">$HTML</div>");
    }
}

class instructionPage4 extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {
        $this->controls = 'note.timer.comments';

        if ($this->style == 'completed') // simple completion box
        {
            $this->controls = 'completed';
        }

        $HTML = $this->debugParms(__CLASS__); // start with debug info
        $HTML .= $this->dataParm;

        return ("<div style=\"max-width:600px;font-size:150%;line-height:150%;float:top\">$HTML</div>");
    }

}

class pronounce extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $this->controls = '';
        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $systemStuff = new systemStuff();
        $style = "border:3px solid black;";

        $HTML = "<br><span style='font-size:20px;'>
                We are starting a new vowel - <sound>{$this->dataParm}</sound>.
                Practice pronouncing it.<br>  Make shapes with
                your mouth, exaggerate it, play
                with it, find other words with it.</span><br><br><br>";

        $HTML .= $systemStuff->buildImageURL('b-' . $this->dataParm . '.jpg', $style);

        return ($HTML);
    }

}

class contrast extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $this->controls = '';
        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $aTemp = explode(',', $this->dataParm);
        assert(count($aTemp) == 2);
        $first = $aTemp[0];
        $second = $aTemp[1];

        $systemStuff = new systemStuff();
        $style = "border:3px solid black;width:250px;margin:10px";

        $HTML = "<br><span style='font-size:20px;'>
                Contrast the pronunciation of <sound>$first</sound> and <sound>$second</sound>.<br>
                Feel the difference in your mouth.  Practice contrasting them.</span><br><br><br>";

        $HTML .= $systemStuff->buildImageURL('b-' . $first . '.jpg', $style);
        $HTML .= $systemStuff->buildImageURL('b-' . $second . '.jpg', $style);

        return ($HTML);
    }
}

class blankPage extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        // sometimes we don't want the lesson name as our standard title..

        $HTML = $this->data[0];
        $this->controls = ''; // override the default controls

        return ("<div style=\"max-width:600px;font-size:150%;line-height:150%;float:top\">$HTML</div>");
    }

}

class blankPageCompletion extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        // a blank page with a completion button

        $HTML = $this->data[0];
        $this->controls = 'note.completion'; // override the default controls

        return ("<div style=\"max-width:600px;font-size:150%;line-height:150%;float:top\">$HTML</div>");
    }

}

class marqueePage extends defaultDisplay implements BasicDisplayFunctions
{
    // javascript page that scrolls sideways

    public function above()
    {

        $document = document::singleton();
        $HTML = '';

        $systemStuff = new systemStuff();

        $HTML .= $systemStuff->showIcon('media-seek-backward-3', 32, 'actions', 'slower', 'marAdjustSpeed(-1)');
        $HTML .= $systemStuff->showIcon('media-playback-pause-3', 32, 'actions', 'pause', "marRequestStop()");
        $HTML .= $systemStuff->showIcon('media-seek-forward-3', 32, 'actions', 'faster', 'marAdjustSpeed(1)');
        //$HTML .=  '<label id="marSpeed">0</label>';

        $HTML .= "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";

        // this is the canvas for the marquee
        $HTML .= '<div style="position: absolute;
                  left: 5px;
                  top: 300px;
                  width: 950px;
                  height: 200px;">
               <canvas id="marquee" width="930" height="50">
                         Your browser does not support HTML5.</canvas>
               </div>';

        $javaElements = '';
        $aElements = explode(',', $this->data[0]);
        foreach ($aElements as $je) {
            if (!empty($javaElements)) {
                $javaElements .= ',';
            }

            $javaElements .= "'$je'";
        }
        $HTML .= "<script type=\"text/javascript\">
                   Marquee([$javaElements]);       // pass a well-formed javascript array
              </script>";

        $this->controls = 'note'; // override the default controls

        return ($HTML);
    }

}

class typingPage extends defaultDisplay implements BasicDisplayFunctions
{

    public function __construct($a = '', $b = '', $c = '')
    {
        $this->controls = 'note.completion'; // override the default controls
        parent::__construct($a, $b, $c); // of course, script can override again
    }

    public function above()
    {
        return ("<div class=\"myform\"><textarea name=\"typing\" rows=\"18\" cols=\"60\"></textarea></div>");
    }
}

class wordListTimed extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {
        $systemStuff = new systemStuff();

        $this->controls = 'refresh.note.timer.comments'; // override the default controls

        $data9 = $this->generate9($this->dataParm, $this->layout, $this->data);

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        switch ($this->style) {
            case 'full':
                $this->wordArt = new wordArtFull();
                break;
            case 'simple':
                $this->wordArt = new wordArtSimple();
                break;
            case 'none':
                $this->wordArt = new wordArtNone();
                break;
            default:
                assertTRUE(false, "wordArt style is '{$this->style}', must be 'full', 'simple', or 'none'");
        }

        $border = '';
        if (debugDisplayPages()) {
            $border = 'border: 2px solid green;';
        }

        $HTML .= '<div id="wordArtList">';
        $HTML .= "<table class=\"wordlist\" id=\"WLtable\">";

        $altImg = $systemStuff->PHONICS_URL() . 'png/32x32/others/button-red.png';

        for ($i = 0; $i < 9; $i++) {
            // each text has id 'TM0' to 'TM9' in the first column
            $HTML .= "<tr><td id=\"TM$i\">{$data9[$i]}</td>";

//            // the error controls the SECOND column are clickable icons
            //            $HTML .= "\n<td style=\"width:100; text-align:right;\" onClick=\"TM_markError('$i','$altImg');\">".
            //                        $this->systemStuff->showIcon('circle_grey',32,'others','Mark Error','',"TMx$i").
            //                     "</td>";

// green dot:    others/button-green.png
            // red dot:      others/button-red.png
            // yellow dot:   others/button-yellow.png
            // grey dot:     others/circle_grey.png

            // the third colum has the start and stop controls
            switch ($i) {
                // start icon
                case 0:$HTML .= "\n<td width=100 align=right onClick=\"TM_startClock();\">" .
                    $this->systemStuff->showIcon('button-green', 32, 'others', 'Start Timer') . "</td>";
                    break;

                // a different icon...
                // $this->systemStuff->showIcon('accessories-clock',32,'apps','Start Timer')."</td>";

                // stop icon
                case 8:$HTML .= "\n<td width=100 align=right onClick=\"TM_stopClock();\">" .
                    $this->systemStuff->showIcon('process-stop', 32, 'actions', 'Stop Timer') . "</td>";
                    break;

                default:$HTML .= "<td></td>";
            }
            $HTML .= "</tr>";
        }
        $HTML .= '</table>';
        $HTML .= '</div>';
        return ($HTML);
    }
}

class wordList extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $this->controls = 'refresh.note'; // override the default controls

        switch ($this->style) {
            case 'full':
                $this->wordArt = new wordArtDecodable();
                break;
            case 'simple':
                $this->wordArt = new wordArtSimple();
                break;
            case 'none':
                $this->wordArt = new wordArtNone();
                break;
            default:
                assertTRUE(false, "wordArt style is '{$this->style}', must be 'full', 'simple', or 'none'");
                $this->wordArt = new wordArtNone();
        }

        $HTML = $this->wordartlist();
        return ($HTML);

    }
}

// exactly the same controls as wordList(), but adds mastery
class wordListComplete extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $this->controls = 'completion.refresh.note'; // override the default controls

        switch ($this->style) {
            case 'full':
                $this->wordArt = new wordArtDecodable();
                break;
            case 'simple':
                $this->wordArt = new wordArtSimple();
                break;
            case 'none':
                $this->wordArt = new wordArtNone();
                break;
            default:
                assertTRUE(false, "wordArt style is '{$this->style}', must be 'full', 'simple', or 'none'");
                $this->wordArt = new wordArtNone();
        }

        $HTML = $this->wordartlist();
        return ($HTML);

    }
}

class wordListComplete2 extends wordListComplete implements BasicDisplayFunctions
{

    public function above()
    {
        $HTML = parent::above();
        $this->controls = 'refresh,completed.note'; // just a 'completed' button instead of mastery
        return ($HTML);
    }
}

class morphoWordList extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $HTML .= '<div id="wordArtList">';

        $data9 = $this->generate9($this->dataParm, $this->layout, $this->data); // split data into an array

        $HTML .= '<table>';
        for ($i = 0; $i < 8; $i++) {

            $wordArtFull = new wordArtFull();
            $wordArtNone = new wordArtNone();

            $double = explode('/', $data9[$i]); //  turn make/made/mate into an array
            $HTML .= '<tr>';
            $HTML .= "<td width=200px>" . $wordArtNone->render($double[0]) . "</td>"; // complete
            $HTML .= "<td width=200px>" . $wordArtNone->render($double[1]) . "</td>"; // completion
            $HTML .= "<td width=200px>" . $wordArtFull->render($double[1]) . "</td>"; // completion (WordArt)
            $HTML .= '</tr>';

        }
        $HTML .= '</table>';

        $HTML .= '</div>';

        return ($HTML);

    }
}

class wordListArt_1 extends defaultDisplay implements BasicDisplayFunctions
{

    public $textStyle = '';

    // each element is a simple word; look up in dictionary and draw

    public function above()
    {

        $this->wordArt = new wordArtFull();
        return ($this->wordListArt_render());
    }

    public function wordListArt_render()
    {
        $HTML = '<div id="wordArtList">';

        $data10 = $this->generate($this->data, 10); // split data into an array

        $HTML .= '<table>';
        for ($i = 0; $i < 8; $i++) {

            $triple = explode('/', $data10[$i]); //  turn make/made/mate into an array
            $HTML .= '<tr>';
            foreach ($triple as $word) {
                $HTML .= "<td width=200px>" . $this->wordArt->render($word) . "</td>";
            }

            $HTML .= '</tr>';

        }
        $HTML .= '</table>';

        $HTML .= '</div>';

        return ($HTML);
    }
}

class wordListArt_2 extends wordListArt_1 implements BasicDisplayFunctions
{

    public function above()
    {
        $this->wordArt = new wordArtSimple();
        return ($this->wordListArt_render());
    }
}

class wordListArt_3 extends wordListArt_1 implements BasicDisplayFunctions
{

    public function above()
    {
        $this->wordArt = new wordArtNone();
        return ($this->wordListArt_render());
    }
}

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

class wordListArt_3x_1 extends defaultDisplay implements BasicDisplayFunctions
{

    public $textStyle = '';

    // each element is a simple word; look up in dictionary and draw

    public function above()
    {

        $this->wordArt = new wordArtFull();
        return ($this->wordListArt_render());
    }

    public function wordListArt_render()
    {
        $HTML = '<div id="wordArtList">';

        $data10 = $this->generate($this->data, 10); // split data into an array

        $HTML .= '<table>';
        for ($i = 0; $i < 8; $i++) {

            $triple = explode('/', $data10[$i]); //  turn make/made/mate into an array
            $HTML .= '<tr>';
            foreach ($triple as $word) {
                $HTML .= "<td width=200px>" . $this->wordArt->render($word) . "</td>";
            }

            $HTML .= '</tr>';
        }
        $HTML .= '</table>';

        $HTML .= '</div>';

        return ($HTML);
    }
}

class wordListArt_3x_2 extends wordListArt_3x_1 implements BasicDisplayFunctions
{

    public function above()
    {
        $this->wordArt = new wordArtSimple();
        return ($this->wordListArt_render());
    }
}

class wordListArt_3x_3 extends wordListArt_3x_1 implements BasicDisplayFunctions
{

    public function above()
    {
        $this->wordArt = new wordArtNone();
        return ($this->wordListArt_render());
    }
}

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

class wordCompareArt_1 extends defaultDisplay implements BasicDisplayFunctions
{

    public $textStyle = '';

    // each element is a simple word; look up in dictionary and draw

    public function above()
    {

        $this->wordArt = new wordArtFull();
        return ($this->wordCompareArt_render());
    }

    public function wordCompareArt_render()
    {
        $HTML = '<div id="wordArtList">';

        $data10 = $this->generate($this->data, 10); // split data into an array

        // each element looks like:  pat/pay   need to split into two columns

        $HTML .= '<table>';
        for ($i = 0; $i < 8; $i++) {

            $triple = explode('/', $data10[$i]); //  turn make/made/mate into an array
            $HTML .= '<tr>';
            foreach ($triple as $word) {
                $HTML .= "<td width=200px>" . $this->wordArt->render($word) . "</td>";
            }

            $HTML .= '</tr>';

        }
        $HTML .= '</table>';

        $HTML .= '</div>';

        return ($HTML);
    }
}

class wordCompareArt_2 extends wordCompareArt_1 implements BasicDisplayFunctions
{

    public function above()
    {
        $this->wordArt = new wordArtSimple();
        return ($this->wordCompareArt_render());
    }
}

class wordCompareArt_3 extends wordCompareArt_1 implements BasicDisplayFunctions
{

    public function above()
    {
        $this->wordArt = new wordArtNone();
        return ($this->wordCompareArt_render());
    }
}

class letterPage extends defaultDisplay implements BasicDisplayFunctions
{

    public function __construct($a = '', $b = '', $c = '')
    {
        $this->controls = 'note.comments'; // override the default controls
        parent::__construct($a, $b, $c); // of course, script can override again
    }

    public function above()
    {
        $HTML = '';

        $HTML .= "letterPage is not available yet";
        return ($HTML);
    }
}

class soundCard extends defaultDisplay implements BasicDisplayFunctions
{

    public function render($lessonName)
    {

        $HTML = '<style>
	.draggable { width: 90px; height: 80px; padding: 5px; float: left; margin: 0 10px 10px 0; font-size: .9em; }
	.ui-widget-header p, .ui-widget-content p { margin: 0; }
	#snaptarget { height: 140px; }
	</style>';

        // does this have to go in the head ??
        $HTML = '<script>
	$(function() {
		$( "#draggable4" ).draggable({ grid: [ 20,20 ] });
		$( "#draggable5" ).draggable({ grid: [ 80, 80 ] });
	});
	</script>';

//$HTML .= '<div class="dragable" style="left:20px;"></div>
        //<div class="dragable" style="left:100px;"></div>
        //<div class="dragable" style="left:180px;"></div>';

        $HTML .= '<div class="demo">

               <div id="draggable4" class="draggable ui-widget-content">
                       <p>I snap to a 20 x 20 grid</p>
               </div>

               <div id="draggable5" class="draggable ui-widget-content">
                       <p>I snap to a 80 x 80 grid</p>
               </div>

               </div>';

        $this->above = $HTML;
        $this->createTab();
    }
}

class uploadFilePage extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {
        // don't always have a TMP directory defined...
        //echo "here comes the initial upload directory:",ini_get('upload_tmp_dir'),'<br />';
        ini_set('upload_tmp_dir', './tmp');
        //echo "here comes the final upload directory:",ini_get('upload_tmp_dir'),'<br />';
        //echo "here comes the initial upload max filesize:",ini_get('upload_max_filesize'),'<br />';
        ini_set('upload_max_filesize', '128M');
        //echo "here comes the final upload max filesize:",ini_get('upload_max_filesize'),'<br />';

        $form = new userforms("testform");

//            $form->addField("hidden",   "action", 'saveUploadedFile');

        $form->addField("select", "stopped", "", "Stopped On Tab:", "", array("options" => array("0" => "Tab 0",
            "1" => "Tab 1",
            "2" => "Tab 2",
            "3" => "Tab 3",
            "4" => "Tab 4",
            "5" => "Tab 5",
            "6" => "Tab 6",
            "7" => "Tab 7",
            "8" => "Tab 8",
            "9" => "Tab 9",
        )));

        $form->addField("file", "uploadedfile", "", "File to Upload:");
        $form->addField('submit', 'saveUploadFile', "Upload File"); // default is blue

//         $form->addField('button',  $this->systemStuff->buildFormURL('firstpage','saveUploadedFile'),  "Upload File", '' );

        $this->controls = 'note'; // override the default controls

        $HTML = ($form->render());
        return ($HTML);
    }
}

class sightWordEliminator extends defaultDisplay implements BasicDisplayFunctions
{

    public $useTopWords = 4; // default is 100 words; values range from 0 to 5
    public $festivalObject;

    // these utilities build $aText, and array of arrays...
    public $aText = array(); //    array('old'=>'This', 'generic'=>'this', 'post' = ' ', 'new'=>'****',)
    // to unload, create a string of either old+post or new+post

    //http://www.duboislc.org/EducationWatch/First100Words.html
    //Taken From: The Reading Teachers Book of Lists, Third Edition; by Edward Bernard Fry, Ph.D, Jacqueline E. Kress, Ed.D & Dona Lee Fountoukidis, Ed.D.

    public $top100 = ",the,of,and,a,to,in,is,you,that,it,he,was,for,on,are,as,with,his,they,i,at,be,this,have,from,or,one,had,by,word,
             ,but,not,what,all,were,we,when,your,can,said,there,use,an,each,which,she,do,how,their,if,will,up,other,about,out,
             ,many,then,them,these,so,some,her,would,make,like,him,into,time,has,look,two,more,write,go,see,number,no,way,could,
             ,people,my,than,first,been,call,who,oil,its,now,find,long,down,day,did,get,come,made,,may,part'";
    // removed water

    public $top200 = ",over,new,sound,take,only,little,work,know,place,year,live,me,back,give,most,very,after,thing,our,just,
               ,name,good,sentence,man,think,say,great,where,help,through,much,before,line,right,too,mean,old,any,same,
               ,tell,boy,follow,came,want,show,also,around,form,three,small,set,put,end,does,another,well,large,must,
               ,big,even,such,because,turn,here,why,ask,went,men,read,need,land,different,home,us,move,try,kind,hand,
               ,picture,again,change,off,play,spell,air,away,animal,house,point,page,letter,mother,answer,found,study,
               ,still,learn,should,america,world,";

    public $top300 = ",high,every,near,add,food,between,own,below,country,plant,last,school,father,keep,
               ,tree,never,start,city,earth,eye,light,thought,head,under,story,saw,left,don't,few,while,along,might,close,
               ,something,seem,next,hard,open,example,begin,life,always,those,both,paper,together,got,group,often,run,important,
               ,until,children,side,feet,car,mile,night,walk,white,sea,began,grow,took,river,four,carry,state,once,book,,hear,stop,
               ,without,second,later,miss,idea,enough,eat,face,watch,far,indian,really,almost,let,above,girl,sometimes,mountain,
               ,cut,young,talk,soon,list,song,being,leave,family,it's,";

    public $top400 = ",body,music,color,stand,sun,questions,fish,area,mark,dog,horse,
               ,birds,problem,complete,room,knew,since,ever,piece,told,usually,didn't,friends,easy,heard,order,door,sure,become,top,
               ,ship,across,today,during,short,better,best,,however,low,hours,black,products,happened,whole,measure,remember,early,
               ,waves,reached,listen,wind,rock,space,covered,fast,several,hold,himself,toward,five,step,morning,passed,vowel,true,
               ,hundred,against,pattern,numeral,table,north,slowly,money,map,farm,pulled,draw,voice,seen,cold,cried,plan,notice,
               ,south,sing,war,ground,fall,king,town,I'll,unit,figure,certain,field,travel,wood,fire,upon,";

    public $top500 = ",done,English,road,halt,ten,fly,gave,box,finally,wait,correct,oh,quickly,person,became,shown,minutes,strong,verb,
               ,stars,front,feel,fact,inches,street,decided,contain,course,surface,produce,building,ocean,class,note,nothing,rest,
               ,carefully,scientists,inside,wheels,stay,green,known,island,week,less,machine,base,ago,stood,plane,system,behind,
               ,ran,round,boat,game,force,brought,understand,warm,common,bring,explain,dry,though,language,shape,deep,thousands,
               ,yes,clear,equation,yet,government,filled,heat,full,hot,check,object,am,rule,,among,noun,power,cannot,able,six,size,
               ,dark,ball,material,special,heavy,fine,pair,circle,include,built,";

    public $others = ",used,";

    public $topwords;

    public function __construct()
    {
        trace(__CLASS__, __METHOD__, __FILE__);
        $this->festivalObject = festival::singleton();
        parent::__construct();
    }

    public function above()
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        $profiler = profiler::singleton();
        $profiler->startTimer(__CLASS__);

        $this->preparePage($this->data[0]); // call the specific function

        $HTML = '';

        // the data for this is an array:  { method, string }
        if (!method_exists($this, $this->dataParm)) {
            // fatal error if method doesn't exist, catch it here
            assertTRUE(false, "No method '{$this->data[0]}' in class {__class__}");
            return ($HTML);
        }

        $f = $this->dataParm; // eg: originalText, hidesightwords, timertext
        $this->$f(); // this calls listWords() or whatever is specified

        $wordArt = new wordArtDecodable();

        if ($f == 'listWords' or $f == 'wordArt') {
            $this->controls = 'refresh'; // override controls

            $HTML .= '<div id="wordArtList">';

            $dataString = '';
            foreach ($this->aText as $a) {
                if (!empty($a['new'])) {
                    if (!empty($dataString)) {
                        $dataString .= ',';
                    }
                    // comma delimited
                    $dataString .= $a['new'];
                }
            }

            $data9 = $this->generate9('scramble', '1col', $dataString); // split data into an array
            $HTML .= '<table>';
            for ($i = 0; $i < 8; $i++) {
                //printNice('TOM99',$data9[$i]);
                if ($f == 'listWords') {
                    $HTML .= '<tr><td><span style="font-size:40px;">' . $data9[$i] . '</span></td></tr>';
                } else { // wordArt
                    printNice('TOM99', $data9[i]);
                    // $HTML .= '<tr><td style=\"width:400px\">'.$wordArt->render($data9[$i]).'</td></tr>';

                }
            }
            $HTML .= '</table>';

            $HTML .= '</div>';

        } else {
            foreach ($this->aText as $a) {
                $HTML .= $a['pre'] . $a['new'] . $a['post'];
            }

        }

        $profiler->stopTimer(__CLASS__);

        return ($HTML);
    }

    public function loadTopWords()
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        $difficulty = $this->defaultDifficulty;

        switch ($difficulty) {
            case 0:
                break;
            case 1:
                $this->topwords = $this->top100 . $this->others;
                break;
            case 2:
                $this->topwords = $this->top100 . $this->top200 . $this->others;
                break;
            case 3:
                $this->topwords = $this->top100 . $this->top200 . $this->top300 . $this->others;
                break;
            case 4:
                $this->topwords = $this->top100 . $this->top200 . $this->top300 . $this->top400 . $this->others;
                break;
            case 5:
                $this->topwords = $this->top100 . $this->top200 . $this->top300 . $this->top400 . $this->top500 . $this->others;
                break;
            default:
                assertTRUE(false, "Unexpected value '$this->useTopWords' encountered in SightWordEliminator");
        }
    }

    // take a page and load up $this->aText[] with words
    public function preparePage($text)
    {
        trace(__CLASS__, __METHOD__, __FILE__, left($text, 65));

        // we'll parse $text into $aText, leaving the 'new' field empty for now
        $tokCurrent = 0;
        $state = 'wordInitial';

        $old = '';
        $pre = '';
        $post = '';

        $this->loadTopWords(); // top 100 or top 500 ??  or nothing??
        //      $text = $this->data[0];
        while ($tokCurrent < strlen($text)) {
            $token = substr($text, $tokCurrent, 1);

            //$ord = ord($token);
            //echo "$tokCurrent, '$token' $ord state:$state   old:'$old' pre:'$pre' post:'$post' <br />";

            switch ($state) {

                case 'wordInitial':
                    if ($token == '<') {
                        $this->emit($old, $pre, $post);
                        $old = $pre = $post = '';
                        $pre .= $token; // HTML stuff goes into PRE
                        $state = 'HTML';
                        break;}
                    if (ctype_alnum($token)) { // starts a new word
                        $old .= $token;
                        $state = 'word';
                        break;}
                    // anything else goes into $pre - periods, spaces, etc.
                    $pre .= $token;
                    $state = 'post';
                    break;

                case 'word': // if we are here, we are collecting a word
                    if ($token == '<') {
                        $this->emit($old, $pre, $post);
                        $old = $pre = $post = '';
                        $pre .= $token; // HTML stuff goes into PRE
                        $state = 'HTML';
                        break;}
                    if ((ctype_alnum($token)
                        and ord($token) > 65
                        and ord($token) < 123)
                        or $token == "'") { // continues this word, stays in this state
                        $old .= $token; // i'd and he's is a single word
                        break;}
                    // anything else goes into $post - periods, spaces, etc.
                    $post .= $token;
                    $state = 'post';
                    break;

                case 'HTML':
                    if ($token == '>') { // scan until we find a >
                        $pre .= $token; // HTML stuff goes into PRE
                        $this->emit($old, $pre, $post);
                        $old = $pre = $post = '';
                        $state = 'wordInitial';
                        break;}
                    $pre .= $token; // HTML stuff goes into PRE
                    break;

                case 'post': // if we are here, then we've got a word, looking for post
                    if ($token == '<') {
                        $this->emit($old, $pre, $post);
                        $old = $pre = $post = '';
                        $pre .= $token; // HTML stuff goes into PRE
                        $state = 'HTML';
                        break;}
                    if (ctype_alnum($token)) { // a new word !!
                        $this->emit($old, $pre, $post);
                        $old = $pre = $post = '';
                        $old .= $token;
                        $state = 'word';
                        break;}
                    // anything else goes into $post - periods, spaces, etc.
                    $post .= $token;
                    break;
                default:
                    assertTRUE(false, "did not expect to get here");

            }
            $tokCurrent++;
        }
        // force the last write...
        $this->emit($old, $pre, $post);
        //printNice('pagePrep',$this->aText);
        // now process $aText to generate the generic values (always lowercase)
    }

    public function emit($old, $pre, $post)
    {
        //echo "EMIT   old:'<strong>$old</strong>' pre:'<strong>".htmlentities($pre)."</strong>' post:'<strong>$post</strong>' <br />";
        $this->aText[] = array('old' => $old, 'generic' => strtolower($old), 'pre' => $pre, 'post' => htmlspecialchars($post), 'new' => $old);

        //  // this piece of debugging helps find funny characters like the apostrophe (which needed to be changed to a single quote)
        //$p0 = str_replace(' ','',$post);
        //if(!empty($p0)){
        //   $p1=htmlentities($pre);$p2=htmlentities($post); $p3 = ord($p0); echo "old=$old, pre=$p1, post=$p2, ord=$p3 <br />";
        // }
    }

    // these are styles of using this class (set in param 2 of the constructor)

    public function isSightWord($a)
    { // $a is an array
        if (strpos($a['generic'], "'") === false// can't fool us with  fred's
             and strpos($this->topwords, ',' . $a['generic'] . ',') === false// if NOT in top 100
             and !ctype_digit(substr($a['generic'], 0, 1)) // if first digit is NOT numeric
             and strlen($a['generic']) > 3// more than 3 chaacters
             and strtoupper($a['generic']) !== $a['old']) { // not all UCASE (like 'ATAC')
            return (false);
        }
        return (true);
    }

    /////////////////////////////////////////////////////////////////////////
    // these are the callable versions

    public function originalText()
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        foreach ($this->aText as &$a) {
            $a['new'] = $a['old'];

            ////////////////////// take out the hoverhelp
            //  if($this->isSightWord($a))
            //      $a['new'] = $a['old'];
            //  else
            //      $a['new'] = $this->hoverHelp($a['old']);
        }
        $this->controls = 'empty'; // override the default controls
    }

    public function timerText()
    {
        trace(__CLASS__, __METHOD__, __FILE__);
        foreach ($this->aText as &$a) {
            $a['new'] = $a['old']; // no pop-up help
        }
        $this->controls = ',stopwatch,LCgraph'; // override the default controls
    }

    public function debug()
    {
        trace(__CLASS__, __METHOD__, __FILE__);
        foreach ($this->aText as &$a) {
            $a['new'] = "old:<strong>" . htmlentities($a['old']) . "</strong>" .
            " generic:<strong>" . htmlentities($a['generic']) . "</strong>";
        }
    }

    public function hideSightWords()
    { // look for  ,word,  since don't want partial words
        trace(__CLASS__, __METHOD__, __FILE__);
        foreach ($this->aText as &$a) {

            // hide sightwords and also all capitalized words
            if (!empty($a['old'])) { // don't bother with punction-only

                //////////////////////// no more hoverhelp
                if ($this->isSightWord($a) or ctype_upper(substr($a['old'], 0, 1))) {
                    $a['new'] = str_repeat('*', strlen($a['generic']));
                    $a['post'] = ' '; // wipes out most punctuation
                } else {
                    $a['new'] = $a['new'];
                }
            }
        }
        $this->controls = 'empty'; // override the default controls
    }

    public function highlightWords()
    { // look for  ,word,  since don't want partial words
        trace(__CLASS__, __METHOD__, __FILE__);
        foreach ($this->aText as &$a) {
            if (!$this->isSightWord($a)) { // if NOT in top 100
                $a['new'] = '<b>' . $a['old'] . '</b>';
            }
        }
        $this->controls = ''; // override the default controls
    }

    //   // include a few function words...
    //function slowdownReader(){    // look for  ,word,  since don't want partial words
    //   $prior = false;   // don't allow two words to be starred side-by-side
    //   foreach($this->aText as &$a){
    //      if(!$prior and $this->isSightWord($a)){   // if NOT in top 100
    //         $csub = str_repeat('*',strlen($a['old']));
    //         $a['new'] = "<span title=\"{$a['old']}\">$csub</span>";
    //         $prior = true;
    //      }else{
    //         $prior = false;
    //      }
    //   }
    //}

    public function listWords()
    { // look for  ,word,  since don't want partial words
        trace(__CLASS__, __METHOD__, __FILE__);

        $unique = array();

        //  $a will look like this...
        // a:5:{s:3:"old";s:4:"hawk";s:7:"generic";s:4:"hawk";s:3:"pre";s:0:"";s:4:"post";s:1:" ";s:3:"new";s:4:"hawk";}

        foreach ($this->aText as &$a) {
            if (array_search($a['generic'], $unique) === false// we haven't seen it before
                 and !$this->isSightWord($a)) { // if NOT in top 100
                //$a['new'] = $this->wordArt($a['generic']); // lowercase, usually first word in sentence
                $a['new'] = $a['generic']; // lowercase, usually first word in sentence
                $a['pre'] = '';
                $a['post'] = '';
                $unique[] = $a['generic'];
            } else {
                $a['pre'] = $a['post'] = $a['new'] = ''; // flush simple words
            }
        }
        $this->controls = ''; // override the default controls
        shuffle($this->aText);
        return ($unique);
    }

//    function wordArt(){

//       if (isset($this->data[3])){
    //          $style = $this->data[3];

//          if(!class_exists($style)){
    //             assertTRUE(false,"expecting a WordArt style, got '$style'");
    //          }else{
    //             trace(__CLASS__,__METHOD__,__FILE__,"$word, style = $style}");

//             $wordArt = new $style();
    //             $HTML = $wordArt->render($word);

//             if(!$wordArt->bigStyle())  // for the small styles, we need to add CR
    //                $HTML .= '<br /><br />';

//             return ($HTML);
    //          }
    //       }else{
    //          return ($word);
    //       }

//    }

    public function hoverHelp($word)
    {

        if ($this->festivalObject->wordIsValid($word)) {
            return ("<span class=\"tips\" rel=\"/phonics/{$GLOBALS['PVersion']}/ajax/wordartajax.php?word=$word\">$word</span>");
        }

        /////// debug - list any invalid words
        //printNice('~decode',$word);

        return ($word);
    }

}

class authentic_text
{

    public $nChapters = -1;

    public function title()
    {return ('Unknown TITLE');}
    public function author()
    {return ('Unknown AUTHOR');}

    public function getChapter($n)
    {

        // it's hard to make sure that every chapter is in place
        $reflect = new ReflectionClass('authentic_book');
        $a = $reflect->getMethods();

        foreach ($a as $chapter) {
            if (substr(($chapterName = $chapter->getName()), 0, 7) == 'chapter') { // don't want to call getName() again and again...
                $texts[$chapterName] = $chapterName;
                $nChapter = substr($chapterName, 7);
                $this->nChapters = max($this->nChapters, $nChapter); // find the highest chapter number
            }
        }
        // now have a list of chapters, let's make sure none is missing
        for ($i = 1; $i < $this->nChapters; $i++) {
            assertTRUE(isset($texts['chapter' . iif($i < 10, "0$i", $i)]), "chapter $i not set in '{$this->title()}'");
        }

        // finally, make sure that we have the chapter we actually want
        $chapter = 'chapter' . iif($n < 10, "0$n", $n);
        if (!isset($texts[$chapter])) {
            assertTRUE(false, "requested chapter $n is not available in '{$this->title()}");
            return ('requested text not found');
        }

        // TEMPORARY - show length of each chapter
        reset($texts);
        foreach ($texts as $text) {
            $cLength[$text] = strlen($this->$text());
        }
        printNice('Chapter chars', $cLength);

        // good to go...
        return ($this->$chapter());
    }

}

class factoryView extends UnitTestCase
{

}

class TrainingView extends factoryView
{

    public $rules;
    public $document;
    public $studentObject;
    public $soundObject;

    public $single_rule;

    public $cardwidth = 200;
    public $cardheight = 130;

    public $cardwidthSmall = 100;
    public $cardheightSmall = 50;

    public $firstpage = true;

    public $currentTab = -1; // ordinal of the first tab is ZERO

    public function __construct()
    { // runs after constructor

        // bring in the rules class                                                        // everyone here needs the rule class
        $cfile = "models/rules.php";
        assertTRUE(file_exists($cfile), "Can't find file $cfile");
        require_once $cfile;

        $this->rules = new rules();

        $this->document = singleton('document');

        $cfile = '..' . DECODE . "/models/student.php";
        assertTRUE(file_exists($cfile), "Can't find the standard database objects in $cfile"); //  make sure the controller directory exists
        require_once $cfile;

        $this->studentObject = singleton('student');

        // decide what kind of sound files to use...

        switch ($this->studentObject->PrefSoundfiles) {
            case 'MP3':$this->soundObject = new soundsMP3();
                break;
            case 'none':$this->soundObject = new soundsNone();
                break;

            default:$this->soundObject = new soundsMP3();
        }

        ;

    }

    ///////////////////////////////////////////////////
    // two different entry points - one student-driven, the other for psychologists
    ///////////////////////////////////////////////////

    public function ViewTraining()
    {
        $this->page = $this->studentObject->fetchCurrentTrainingPage(); // gets an ARRAY of tabs, uses the current firstpage
        $this->ViewSpecificTrainingPage();
    }

    public function ViewWarmup()
    {

        $this->page = $this->studentObject->fetchWarmupPage(); // gets an ARRAY of tabs, uses the current firstpage
        $this->ViewSpecificTrainingPage();
    }

    public function ViewTrainingParam($page, $tab = 0)
    {

        $page = str_replace("%27", "'", $page); // fix up some conversions caused by HTML and web servers
        $page = str_replace("%20", " ", $page);

        $single_rule = new single_rule($page);

        // collect all the rules on this page
        $result = array();
        $page = $single_rule->page;

        $rules_class = new rules();

//echo serialize($single_rule),"<br /><br />";

//         $single_rule  =  $rules_class->getNextSingleRule($single_rule->key /*,$trainingPathway*/ );
        //echo serialize($single_rule),"<br /><br />";
        //die;

        while ($single_rule->page == $page) {
            $result[] = $single_rule;
            // now overwrite single_rule with the next key
            $single_rule = $rules_class->getNextSingleRule($single_rule->key/*,$trainingPathway*/);
        }

        $this->page = $result; // uses the current firstpage
        $this->ViewSpecificTrainingPage($tab);
    }

    ///////////////////////////////////////////////////
    // both entry points end up here...
    ///////////////////////////////////////////////////

    public function ViewSpecificTrainingPage($tab = 0)
    {

        $this->document->setStartTab($tab); // almost always tab 0

        /////////////////////////////
        // start with the EVAL box
        /////////////////////////////

        $firstrule = true;
        foreach ($this->page as $singleRule) {

            $this->document->setTrainingPage($singleRule->tabtitle);
            $this->currentTab++; // ordinal of this tab

            switch ($singleRule->ruletype) { // show what type of page this is
                case "read": // reading page
                    $this->ViewReadPage($singleRule);
                    break;

                case "picture": // picture page
                    $this->ViewPicturePage($singleRule);
                    break;

                case "bingo": // bingo page
                    $this->ViewBingoPage($singleRule);
                    break;

                case "logo": // logo page
                    $this->ViewLogoPage($singleRule);
                    break;

                case "logoarray":
                    $this->ViewLogoArrayPage($singleRule);
                    break;

                case "logoarrayBAD":
                    $this->ViewLogoArrayBADPage($singleRule); // a very specific rule for the bad-vowel testing
                    break;

                case "wordlist": // wordlist page
                    $this->ViewWordList($singleRule);
                    break;

                case "survey":
                    $this->SurveyPage($singleRule);
                    break;

                case "dictationsound":
                    $this->ViewDictationSound($singleRule);
                    break;

                //case "dictation":             // we wrote 'DictationSound' for the self-test.   this one is in the same form as read
                //   $this->ViewDictation($singleRule);
                //   break;

                case "dictation2": // we wrote 'DictationSound' for the self-test.   this one is in the same form as read
                case "dictation3":
                case "dictation4":
                case "dictation5":
                    $this->ViewMultiDictation($singleRule);
                    break;

                case "sentences":
                    $this->ViewSentences($singleRule);
                    break;

                case "soundtest":
                    $this->ViewSoundTest($singleRule); // several sounds, the student has to pick the right one
                    break;

                default:
                    $this->document->write("UNKNOWN RULE TYPE: " . $singleRule->ruletype);
                    break;
            }
        }

        $this->soundObject->writeOut();

    }

    //////////////////////////////////////////////////////////////////////////////////////////
    //// a little stub (used to be a function, now it's a class
    //////////////////////////////////////////////////////////////////////////////////////////

    public function sound($rule, $word, $forceDuplicates = false)
    { // $rule is something like 'a0001', used to find the right directory
        return ($this->soundObject->singleSound($word, $forceDuplicates));
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    //// these methods are variations on the various forms of showing a training page
    //////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////
    //// standard columns of reading words
    ///////////////////////////////////////

    public function ViewReadPage($singleRule)
    {
        //trace(__CLASS__,__METHOD__,__FILE__,singleRule = $singleRule);

        $cellspacing = 15;

        //font-family: Century, "Century Gothic"

        // we get 10 strings with space separaters (eg: 'OG AG IG, ...',
        //                                              'OP AP IP, ...').
        // First thing to do is split them into individual sounds into a two dimensional array.  EXPLODE() separates them.

        // but we really want them as an array the OTHER way - as {OG  OP  ..
        //                                                        {AG  AP  ..
        // because when we create an HTML table, we want to build the VERTICAL cells first so the columns line up

        $wordArray = array();
        for ($j = 0; $j < count($singleRule->words); $j++) {

            $string = str_replace("  ", " ", $singleRule->words[$j]); // make sure only single spaces before EXPLODE
            $string = str_replace("  ", " ", $string);

            $wordlist = explode(" ", $string);
            for ($i = 0; $i < count($wordlist); $i++) {
                if (!isset($wordArray[$i])) {
                    $wordArray[$i] = array(array()); // make sure this element is an array of arrays
                }
                $wordArray[$j][$i] = $wordlist[$i];
            }
        }

        //TODO ("link to Joomla's font size");
        //$fontSize = 6;

        $style = '"font-size:30px; font-family:Muli, Comic Sans MS ,Cursive;"';

        $this->document->write("<table>"); // this table lets us put the clocks afterwards, usually not used in any way

        $this->document->write("<tr valign=top><td><table cellspacing=\"$cellspacing\">");

        for ($i = 0; $i < count($wordArray); $i++) { // for each column

            $this->document->write("<tr>");

            for ($j = 0; $j < count($wordArray[$i]); $j++) {

                // there are FOUR parts to making sound work, and all have to be in place.
                //     1) need to have a java class available called 'AudioPlay.class' on the server
                //     2) need a little <script> called EvalSound that triggers an applet.  only need one copy, we pass the name of the applet.
                //     3) need an <applet> with a unique ID for each sound, pointing at the correct .WAV file
                //     4) need to embed an onClick event on the trigger text or image.
                // the user triggers the onClick, which fires the javascript, which plays the applet, which uses the java class.  simple.

                //   http://www.phon.ucl.ac.uk/home/mark/audio/play.htm

                $thisword = $wordArray[$i][$j];

                //$sound = $this->sound($singleRule->key,strToLower($thisword));

                $this->document->write("<td nowrap align=\"center\" width=\"10%\">");

                //if (!empty($sound)){    // only write out the onClick() if the sound exists
                //   $this->document->write("<span style=$style; onClick=\"EvalSound('{$sound}')\">".strToLower($thisword)."</span>");
                //}else{
                //   $this->document->write("<span style=$style;>".strToLower($thisword)."</span>");
                //}

                $this->document->write("<span style=$style " . $this->soundObject->onClick(strToLower($thisword))
                    . ">" . strToLower($thisword) . "</span>\n");

////// i'd like to bring this back one day, but no one is using it now.
                //                if (($singleRule->teachtype)=='test' and $j==count($wordArray[$i])-1){       // this code only gets shown for TESTING pages, only LAST COLUMN
                //                    $this->document->write('</td><td>'.IconLink('delete32.png','Error',DECODE."/training/ProcessMastery/difficulty/$i",'tiny'));
                //                }

                $this->document->write("</td>");
            }
            $this->document->write("</tr>");

        }

        $this->document->write("</table>");

        $this->document->write("</td><td width=100% align=right>"); // move the clocks a bit to the right

        $formName = "submitMastery{$this->currentTab}";
        $this->document->write("<form name=\"$formName\"  onSubmit=\"return OnSubmitMastery('$formName');\" method=\"post\">");

        $this->document->write("<table cellspacing=\"$cellspacing\">");
        $this->document->write("<tr><td align=right>");

        if (($singleRule->teachtype) == 'warm-up') { // don't let a review be 'scrambled', because we can't rebuild that page
            // only for review
            $this->document->write('<span style="font-size:20px; background-color:yellow">These are Warm-ups !!</span>');
        } else {

            $this->document->write(IconLink('refresh.png', $alt = 'Scramble', $script = DECODE . "/training/navigationParam/{$singleRule->page}/{$this->currentTab}", 'medium'));
            $this->document->write('<br /><span style="font-size:11px;">Scramble</span>');
        }

        $this->document->write("</td></tr>");

        // if this is a test, we put a second table with clocks beside the first one
        if (($singleRule->teachtype) == 'test') { // this code only gets shown for TESTING pages

            // we put in a few spacers  of the same font as the letters.  the clocks take up 2 more.  that drops the time a bit below the training words.
            for ($i = 0; $i < 1; $i++) {
                $this->document->write("<tr><td><span style=$style>&nbsp;</span></td></tr>");
            }

            $this->document->write("<tr><td align=right>" . IconClick('clock1.jpg', $alt = 'Start Timer', $script = "clockstart('$formName')", 'big'));
            $this->document->write("</td></tr>");

            // we put in a spacer  of the same font as the letters.  the clocks take up 2 more.  that drops the time a bit below the training words.
            //              $this->document->write("<tr><td><span style=$style>&nbsp;</span></td></tr>");

            $this->document->write("<tr><td align=right>");
            $this->document->write(IconClick('clock2.jpg', $alt = 'Stop Timer', $script = "clockstop('$formName')", 'big'));
            $this->document->write("</td></tr>");

            $this->document->write("<tr><td align=right>");

            //$this->document->write("<FORM NAME=\"stopwatch\">\n");
            $this->document->write("Time:");
            $this->document->write("<INPUT TYPE=\"text\" Name=\"time\" id=\"time\" Size=\"3\"></input>\n");
            //$this->document->write("</FORM>\n");
            $this->document->write("</td></tr>");

            // bit of a hack, but let's update the studentTraining table here so we can write an error message later...
            // we know that this is the test page
            $auth = singleton('authentication');
            if ($auth->isValid()) {
                $identity = $auth->getIdentity(); // an array of UserID, UserRole, StudentID

                $studentTrainingTBL = singleton('studenttrainingTBL');
                $studentTrainingTBL->UpdateTrainingPage($identity['StudentID'],
                    $singleRule->page, // $key
                    serialize($singleRule->words), // $currentPage
                    serialize($singleRule->errorlinks), //$currentPage
                    time()); // $currentPageStamp
            }

        }

        //// we write out any buttons REGARDLESS of whether we are practice or test
        //$this->document->write("<tr><td>");
        //
        //foreach($singleRule->nextsteps as $nextstep){
        //   $this->document->write(ButtonLink($nextstep[0],$nextstep[1]));
        //}
        //$this->document->write("</td></tr>");

        // we write out any buttons REGARDLESS of whether we are practice or test
        $this->document->write("<tr><td>");

        if (is_array($singleRule->nextsteps) and count($singleRule->nextsteps) > 0) { // there are buttons

//            $this->document->write("<br /><form name=\"submitMastery{$this->currentTab}\"  onSubmit=\"return OnSubmitMastery('submitMastery{$this->currentTab}');\" method=\"post\">");

            // only show the comment box if the first option is 'Mastered'
            if ($singleRule->nextsteps[0][0] == 'Mastered') {
                $this->document->write("<br /><span style=\"font-size:10px\">Add a Training Comment to your Log:</span><br /><textarea name=\"comments\" COLS=30 ROWS=3></textarea>");
                $this->document->write("<br /><input type=\"checkbox\" name=\"sendCopy\" value=\"1\"></input><span style=\"font-size:10px\"> Copy to DECODE admininstrator?</span><br /><br /><br />");
            }

            // show each button
            foreach ($singleRule->nextsteps as $nextstep) {
                //$escaped_string = str_replace("'","\'",$nextstep[1]);    // may be like Contrast 'a' and 'o', need to escape the single quotes
                $this->document->write("<input type=\"submit\" name=\"{$nextstep[0]}\" onClick=\"document.pressed='{$nextstep[1]}'\" VALUE=\"{$nextstep[0]}\"></input>");
                $this->document->write("&nbsp;&nbsp;"); // just a small spacer
            }

            $this->document->write("<input type=\"hidden\" name=\"submitMastery{$this->currentTab}\" id=\"submitMastery{$this->currentTab}\" value=\"\"></input>");

        }
        $this->document->write("</td></tr>");
        $this->document->write("</table>"); // close 'last column' table

        $this->document->write("</form>");

        $this->document->write("</td></tr></table>"); // close big table

    }

    //////////////////////////////////////
    //// a picture page, optionally with sounds
    ///////////////////////////////////////

    public function ViewPicturePage($singleRule)
    {
        global $userID, $fontSize;

        $letter = strtolower($singleRule->content[0]); // this is the name of the image that we want to show

        $this->document->write("<br /><table cellpadding=\"10%\">");

        $this->document->write("<tr>");
        $this->document->write("<td align=\"center\">\n<img src=\"" . DECODE . "/images/$letter.jpg\" usemap=\"#imagemap\"></img></td>");
        $this->document->write("</tr>");

        $this->document->write("</table>");

        // useful if we need to test the vectors automaticall
        //  $this->document->write("<span onClick=\"vectordraw();\">Check graphics</span>");

        // now, for each item on the image, create a shape that will sound it out...

        // nasty trick here - the <AREA> tag requires an href.  so i've put a 'jump' to '#a' to fool it
        $this->document->write("<map name=\"imagemap\">\n");
        foreach ($singleRule->content as $area) { // circle array(x,y,radius,name)
            if (!is_array($area)) {
                continue;
            }
            // first element is the letter being sounded

            $thisword = $area[3];
            $sound = $this->sound($singleRule->key, strToLower($thisword), true);
            $this->document->write("<area shape=\"circle\" coords=\"$area[0],$area[1],$area[2]\" href=\"#a\" " . $this->soundObject->onClick($thisword) . "></area>\n");
        }
        $this->document->write("</map>\n");

////////////////////////////
        //// for graphics library
        ////
        ////       $this->document->write('
        ////
        ////                     <script type="text/javascript">
        ////
        ////                     //var cnv = document.getElementById("ja-content");
        ////                     //var jg_doc = new jsGraphics(cnv);
        ////
        ////                     var jg_doc = new jsGraphics();
        ////                     //var jg_doc = new jsGraphics("ja-content");
        ////
        ////                     function vectordraw(){
        ////                       //jg_doc.setColor("#00ff00"); // green
        ////
        ////                       offx = 330;                           // this is the 0,0 for our test script
        ////                       offy = 80;
        ////                       jg_doc.setColor("blue");
        ////                       jg_doc.drawEllipse(1+offx, 1+offy, 50, 50); // co-ordinates related to the document
        ////
        ////                          // so magic nubmers are 290,205 added to the offset we want
        ////                     ');
        ////
        ////                  foreach($singleRule->content as $area){     // circle array(x,y,radius,name)
        ////                         if(!is_array($area)) continue;   // first element is the letter being sounded
        ////                         $this->document->write("
        ////                             jg_doc.setColor(\"maroon\");
        ////                             jg_doc.drawEllipse({$area[0]}-{$area[2]}+offx, {$area[1]}-{$area[2]}+offy, {$area[2]}*2, {$area[2]}*2); // co-ordinates related to the document
        ////                         ");
        ////                   }
        ////
        ////                  $this->document->write('
        ////                           jg_doc.paint(); // draws into the document
        ////                     }
        ////                     vectordraw();
        ////                     </script>
        ////                  ');
        ////

    }

    //////////////////////////////////////
    //// a bingo page
    ///////////////////////////////////////

    public function ViewBingoPage($singleRule)
    {
        global $userID, $fontSize;

        sort($singleRule->words); // makes it easier to play bingo

        $this->document->write("<table cellpadding=\"10%\">");

        $this->document->write("<tr><td><input id=\"reload\" type=\"button\" onClick=\"javascript:document.location.reload(1);\"></td></tr>");
        for ($i = 1; $i <= $singleRule->matrix; $i++) {
            $line_array = explode(" ", strtolower($singleRule->words[$i - 1])); // make an array of each element (and convert to lower at the same time)
            $this->document->write("<tr>");
            for ($j = 1; $j <= $singleRule->matrix; $j++) {
                //$thisword = str_replace(" ","</font></td><td nowrap align=\"center\"><font size=\"$fontSize\" face=\"Century Gothic\">&nbsp;",$singleRule->words[$i]);
                $this->document->write("<td align=\"center\" id=\"$i:$j\" width=\"15%\" onClick=\"javascript:highlight(this.id);\"><font size=\"$fontSize\" face=\"Century Gothic\">{$line_array[$j - 1]}</font></td>");
            }
            $this->document->write("</tr>");
        }
        $this->document->write("</table>");

        $this->document->body_onload = "loadbingolist({$singleRule->matrix})"; // this is the function that launches the bingo game

        $this->document->bottomlist[] = array('Again', 'Incomplete');
        $this->document->bottomlist[] = array('Done', 'CompletedMastery');
    }

    //////////////////////////////////////
    //// logo - sort of like the front page
    ///////////////////////////////////////

    public function ViewLogoPage($singleRule)
    { // content array has two elements, a logo, and text to surround it.  looks like the front-page boxes.
        // if a third element, then that is a SOUND to play if the logo is clicked.

        // image in /decode/views/images,          stored in ->logo as array(image,height,width)
        //        // text                            stored in ->ruletext
        //     $icon = array('callout2.jpg',300,300);                //        //           // a function that returns an array of text
        //     self::addrule('',  "logo",   'Continue',     array($icon,    $text,    'lambdaFunction(x)' ));

        if ($this->firstpage) {
            $title = str_replace('_', ' ', $singleRule->page); // sometimes we use underscores instead of spaces

            $this->document->write("<h4>$title</h4><br /><br />"); // title on the first page if it is a logo page
            $this->firstpage = false;
        }

        $this->document->write("<table><tr><td>"); // a one-column table to keep the page components organized

        if (!empty($singleRule->logo)) { // is there a logo?

            $width = $singleRule->logo[2] + 16;

            if (is_array($singleRule->ruletext)) { // play a sound when we hit the logo
                $sound = $this->sound($singleRule->key, $singleRule->ruletext[1]); // grab the sound
                $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px; width: {$width}px\">");
                $this->document->write("<img src=\"" . DECODE . "/views/images/{$singleRule->logo[0]}\" width=\"{$singleRule->logo[1]}\"
                                                height=\"{$singleRule->logo[2]}\" border=\"0\" alt=\"Click to play...\" onClick=\"EvalSound('{$sound}')\" ></img>");
                $this->document->write("</div><br />");
                $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px;\">" .
                    $singleRule->ruletext[0] . "</div>");
            } else {
                $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px; width: {$width}px\">");
                $this->document->write("  <img src=\"" . DECODE . "/views/images/{$singleRule->logo[0]}\" width=\"{$singleRule->logo[1]}\" height=\"{$singleRule->logo[2]}\" border=\"0\" alt=\"{$singleRule->logo}\"></img>");
                $this->document->write("</div>");
                $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px;\">" .
                    $singleRule->ruletext . "</div>");

            }
        } else {
            $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px;\">" .
                $singleRule->ruletext . "</div>");

        }

        if (!empty($singleRule->lambda)) { // a lambda function that returns an array of text - allows for deferred execution
            $lines = call_user_func($singleRule->lambda, $singleRule->lambdaParam);

            $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px;\"><table>");
            foreach ($lines as $line) {
                $this->document->write("<tr><td>$line</td></tr>");
            }
            $this->document->write("</table></div>");
        }

        $this->document->write("</td></tr><tr><td align=\"left\">"); // a one-column table to keep the page components organized

        foreach ($singleRule->nextsteps as $nextstep) {
            $this->document->write(ButtonLink($nextstep[0], $nextstep[1]));
        }
        $this->document->write("</td></tr></table>"); // a one-column table to keep the page components organized
    }

    //////////////////////////////////////
    //// logoarray -
    ///////////////////////////////////////

    public function ViewLogoArrayPage($singleRule)
    {

        // image in /decode/views/images,          stored in ->logo as array(image,height,width)
        //     // text                            stored in ->ruletext
        //  self::addrule('t001a',  "logodiv",   'Hearing',     array('a',  ''));

        $this->document->write("<table><tr><td>"); // outer table has only 1 column

        // sound icon and counter here...

        $this->document->write("<table cellspacing=\"30\"><tr><td valign=top>"); // inner table
        if ($singleRule->teachtype == 'test' or $singleRule->teachtype == 'write') {
            //$soundCount = $this->soundObject->soundCount();    // total number of sounds
            //$this->document->write(IconClick('sound48.png',$alt='Play',$script="playCurrentSound($soundCount,".count($singleRule->content).')',$style='medium'));    // param to playCurrentSound is max # of sounds
            $this->document->write($this->soundObject->multiSoundIcon('sound48.png', $singleRule->content, $alt = 'Play', $style = 'medium'));
        }
        $this->document->write("</td><td>{$singleRule->ruletext}</td></tr></table>"); // close inner table

        $this->document->write("</td></tr>"); // outer table has only 1 column
        $this->document->write("<tr><td>");

        $counter = 0; // break the inner table every three cards
        $this->document->write("<table border=0><tr>"); // new inner table

        foreach ($singleRule->content as $word) {

            if ($counter++ == 3) {
                $this->document->write("</tr><tr>"); // start a new row
                $counter = 0; // and reset the counter
            }

            $sound = $this->sound($singleRule->key, strToLower($word), true); // force duplicates !!

            // the cards PLAY the sound in practice, and accept the answer in test

            if ($singleRule->teachtype == 'test') {
                // the test part is always 'cards' - we don't show images
                $this->test_letter_card($word, $sound);

            } elseif ($singleRule->teachtype == 'Sound Test') {
                // practice image cards
                $image = DECODE . "/images/small-" . $word . ".jpg";
                $this->practice_image_card($image, $word);

            } elseif ($singleRule->teachtype == 'practice') { // show ONLY the letter in practice mode
                // practice letter cards
                $this->practice_letter_card($word, $word);

            } elseif ($singleRule->teachtype == 'image') { // show a picture card in practice mode
                // practice letter cards
                $image = DECODE . "/images/small-" . $word . ".jpg";
                $this->practice_image_card($image, $word);

            } else { //blind
                $this->sound_only($word, $sound);
            }

        }
        $this->document->write("</tr></table>\n"); // close the inner table

        $this->document->write("</td></tr><tr><td align=\"left\">"); // the outer table again

        if ($singleRule->teachtype == 'test') {
            $this->document->write('<table>');
            $this->document->write('<td><img id="cwrong" src="/decode/views/images/help128.png" width="64" height="64"></img></td>');

            foreach ($singleRule->nextsteps as $nextstep) {
                $this->document->write('<td>' . ButtonLink($nextstep[0], $nextstep[1]) . '</td>');
            }
            $this->document->write('</table>');

        }

        $this->document->write("</td></tr></table>\n"); // close the outer table

    }

    //////////////////////////////////////
    //// logoarrayBAD -
    ///////////////////////////////////////

    public function ViewLogoArrayBADPage($singleRule)
    { // this is a specific rule for the BAD vowels page

        $this->document->write("<table><tr><td>"); // outer table has only 1 column

        // sound icon and counter here...

        $this->document->write("<table cellspacing=\"30\"><tr><td valign=top>"); // inner table
        if ($singleRule->teachtype == 'test' or $singleRule->teachtype == 'write') {
            //$soundCount = $this->soundObject->soundCount();    // total number of sounds
            //$this->document->write(IconClick('sound48.png',$alt='Play',$script="playCurrentSound($soundCount,".count($singleRule->content).')',$style='medium'));    // param to playCurrentSound is max # of sounds
            $this->document->write($this->soundObject->dictateSoundIcon('sound48.png', $singleRule->content, $alt = 'Play', $style = 'medium'));

        }
        $this->document->write("</td><td>{$singleRule->ruletext}</td></tr></table>"); // close inner table

        $this->document->write("</td></tr>"); // outer table has only 1 column
        $this->document->write("<tr><td>");

        $counter = 0; // break the inner table every three cards
        $this->document->write("<table border=0><tr>"); // new inner table

        foreach ($singleRule->content as $iconpair) {

            if ($iconpair[1] != '?') {
                if ($counter++ == 3) {
                    $this->document->write("</tr><tr>"); // start a new row
                    $counter = 0; // and reset the counter
                }
            }

            $sound = $this->sound($singleRule->key, strToLower($iconpair[0]), true); // force duplicates !!

            // the cards PLAY the sound in practice, and accept the answer in test

            //if ($singleRule->teachtype=='test'){
            // the test part is always 'cards' - we don't show images
            if ($iconpair[1] != '?') {
                $letters = $iconpair[0];
                $this->test_letter_card($letters, $sound);
            }

        }
        $this->test_letter_card('???', '???'); // we don't have a sound called '???', they are usually 'Audio7' or similar.

        $this->document->write("</tr></table>\n"); // close the inner table

        $this->document->write("</td></tr><tr><td align=\"left\">"); // the outer table again

        if ($singleRule->teachtype == 'test') {
            $this->document->write('<table>');
            $this->document->write('<td><img id="cwrong" src="/decode/views/images/help128.png" width="64" height="64"></img></td>');

            foreach ($singleRule->nextsteps as $nextstep) {
                $this->document->write('<td>' . ButtonLink($nextstep[0], $nextstep[1]) . '</td>');
            }
            $this->document->write('</table>');

        }

        $this->document->write("</td></tr></table>\n"); // close the outer table

    }

    //////////////////////////////////////
    //// DictationSound
    ///////////////////////////////////////

    public function ViewDictationSound($singleRule)
    { // play a sound for dictiation

//          self::addrule('tabname', "dictationsound",   'practice',   array( array("bag", ""),
        //                                                                       array("pen", ""),
        //                                                                       array("wet", ""))  ,$practicetext);

        // create a function to send the array into the library...
        $wordlist = '';
        foreach ($singleRule->content as $iconpair) {
            if (!empty($wordlist)) {$wordlist .= ',';} // comma separators

            $sound = $this->sound($singleRule->key, strToLower($iconpair[0])); // "hen","pen","ten"
            $letters = $iconpair[0]; // let's kick out the sound applets now as well...
            $this->sound_only($letters, $sound);
            $wordlist .= '"' . $sound . '"'; // boring list, it's just "audio0","audio1","audio2"...

        }

        $this->document->write('
         <script type="text/javascript">
         function setupPlayWordList' . $singleRule->teachtype . '(){
             var wordList = new Array(' . $wordlist . ');
             return(wordList);
         }
      </script>'); // we call sendPlayWordList once, and then use playWordList onclick

        $this->document->write("<table><tr><td>"); // outer table has only 1 column

        // sound icon and counter here...

        $this->document->write("<table cellspacing=\"30\"><tr><td>"); // inner table
        $this->document->write(IconClick('sound48.png', $alt = 'Play', $script = "playWordList('{$singleRule->teachtype}');", $style = 'medium'));
        $this->document->write("</td><td>{$singleRule->ruletext}</td></tr></table>"); // close inner table

        $this->document->write("</td></tr>"); // outer table has only 1 column
        $this->document->write("<tr><td>");
        foreach ($singleRule->nextsteps as $nextstep) {
            $this->document->write(ButtonLink($nextstep[0], $nextstep[1]));
        }

        $this->document->write("</td></tr></table>\n"); // close the outer table

    }

    //
    ////////////////////////////////////////
    //////  Dictation - in same form as Read
    /////////////////////////////////////////
    //
    //function ViewDictation($singleRule){                // play a sound for dictiation
    //
    //   //self::addrule('Dictate', "dictation",  $dictatetext, $all_blending, 'skipif_VC');
    //
    //   // create a function to send the array into the library...
    //   $wordlist = '';
    //   foreach ($singleRule->words as $word){
    //      if(!empty($wordlist)) {$wordlist.=',';}   // comma separators
    //
    //      $sound = $this->sound($singleRule->key,strToLower($word));    // "hen","pen","ten"
    //      $letters = strtolower($word);                  // let's kick out the sound applets now as well...
    //      $this->sound_only($letters,$sound);
    //      $wordlist .= '"'.$sound.'"';        // boring list, it's just "audio0","audio1","audio2"...
    //
    //   }
    //   // hardcode teachtype as 'practice'.   The bigger function ViewDictationSound handles both 'practice' and 'test'.
    //
    //   $this->document->write('
    //      <script type="text/javascript">
    //      function setupPlayWordListpractice(){
    //          var wordList = new Array(' . $wordlist . ');
    //          return(wordList);
    //      }
    //   </script>');    // we call sendPlayWordList once, and then use playWordList onclick
    //
    //
    //
    //   $this->document->write("<table><tr><td>");   // outer table has only 1 column
    //
    //    // sound icon and counter here...
    //
    //            $this->document->write("<table cellspacing=\"30\"><tr><td>");   // inner table
    //                   $this->document->write(IconClick('sound48.png',$alt='Play',$script="playWordList('practice');",$style='medium'));
    //            $this->document->write("</td><td>{$singleRule->teachtype}</td></tr></table>");   // close inner table
    //
    //   $this->document->write("</td></tr>");   // outer table has only 1 column
    //   $this->document->write("<tr><td>");
    //      foreach($singleRule->nextsteps as $nextstep){
    //         $this->document->write(ButtonLink($nextstep[0],$nextstep[1]));
    //      }
    //
    //   $this->document->write("</td></tr></table>\n");   // close the outer table
    //
    //}

    public function ViewMultiDictation($singleRule)
    { // play THREE sounds for dictiation

        //self::addrule('Dictate', "dictation3",  $dictatetext, $all_blending, 'skipif_VC');

        // create a function to send the array into the library...
        $wordlist = '';

        $uniqueSounds = '';
        $uniqueWords = '';
        $uniqueCount = 0;

        // to find how many sounds we play, look in the student preferences...
        $n = $this->studentObject->PrefDictationCount;

        foreach ($singleRule->words as $word) {
            if (!empty($wordlist)) {$wordlist .= ',';} // comma separators

            $word = strtolower($word); // force lowercase
            $sound = $this->sound($singleRule->key, $word); // "hen","pen","ten"

            if (strpos($uniqueWords, $word) === false) { // build matching 'string 'arrays' of words and sounds
                if (!empty($uniqueWords)) { // use comma-separated, so easy to handle in javascript
                    $uniqueWords .= ',';
                    $uniqueSounds .= ',';
                }
                $uniqueWords .= $word; // will look like "OA,OB,OC..."
                $uniqueSounds .= $sound;
                $uniqueCount += 1;
            }
            $letters = strtolower($word); // let's kick out the sound applets now as well...
            $this->sound_only($letters, $sound); // write out the sound, no graphics required
            $wordlist .= '"' . $sound . '"'; // boring list, it's just "audio0","audio1","audio2"...

        }

        $this->document->write("<table><tr><td>"); // outer table has only 1 column

        // sound icon and counter here...

        $this->document->write("<table cellspacing=\"30\"><tr><td>"); // inner table
        //$soundCount = $this->soundObject->soundCount();    // total number of sounds
        //$this->document->write(IconClick('sound48.png',$alt='Play',$script="playCurrentSounds($n,$uniqueCount,'$uniqueWords','$uniqueSounds')",$style='medium'));    // param to playCurrentSound is max # of sounds
        $this->document->write($this->soundObject->dictateSoundIcon('sound48.png', $n, $singleRule->words, $alt = 'Play', $style = 'medium'));
        $this->document->write("</td><td>{$singleRule->teachtype}</td></tr></table>"); // close inner table

        $this->document->write("</td></tr>"); // outer table has only 1 column

        $this->document->write("<tr><td align=right>");
        $this->document->write("<FORM NAME=\"answer\">\n");
        $this->document->write("<INPUT TYPE=\"text\" Name=\"answer\" Size=\"85\"></input>\n");
        $this->document->write("</FORM>\n");
        $this->document->write("</td></tr>");

        $this->document->write("<tr><td>");
        foreach ($singleRule->nextsteps as $nextstep) {
            $this->document->write(ButtonLink($nextstep[0], $nextstep[1]));
        }

        $this->document->write("</td></tr></table>\n"); // close the outer table

    }

    //////////////////////////////////////
    //// wordlist Page
    ///////////////////////////////////////

    public function ViewWordList($singleRule)
    {

        $cellspacing = 18;
        $fontSize = 6;

//      assertTRUE(count($singleRule->words)==20,"Looking for 20 words in ".print_r($singleRule->words,true));
        while (count($singleRule->words) < 20) {
            $singleRule->words[] = '';
        }

        $this->document->write("<table cellspacing=$cellspacing>");

        for ($i = 0; $i < 10; $i++) {

            //$sound = $this->sound($singleRule->key,$singleRule->words[$i]);   // no guarantee we will find it

            $this->document->write("<tr>");
            $this->document->write("<td>&nbsp;</td>"); // the extra columns are a separater
            $this->document->write("<td><font size=\"$fontSize\" face=\"Century Gothic\"" . $this->soundObject->onClick($singleRule->words[$i]) . ">" . $singleRule->words[$i] . "</font></td>");
            if ($singleRule->teachtype == 'test') {
                $this->document->write("<td>" . IconLink('accept16.png', $alt = 'Error',
                    $script = DECODE . '/firstpage/FPCompleted/' . $singleRule->page . '/' . strval($i + 1),
                    $style = 'tiny') . "</td>"); // error
            } else {
                $this->document->write("<td>&nbsp;</td>");

            }

            $this->document->write("<td>&nbsp;</td>"); // the extra columns are a separator
            $this->document->write("<td>&nbsp;</td>");

            $sound = $this->sound($singleRule->key, $singleRule->words[$i + 10]); // no guarantee we will find it

            $this->document->write("<td><font size=\"$fontSize\" face=\"Century Gothic\" " . $this->soundObject->onClick($sound) . ">" . $singleRule->words[$i + 10] . "</font></td>");
            if ($singleRule->teachtype == 'test') {
                $this->document->write("<td>" . iconlink('accept16.png', $alt = 'Error',
                    $script = DECODE . '/firstpage/FPCompleted/' . $singleRule->page . '/' . strval($i + 11),
                    $style = 'tiny') . "</td>"); // error
            } else {
                $this->document->write("<td>&nbsp;</td>");

            }
            $this->document->write("</tr>");
        }

        $this->document->write("</table>");

    }

    //////////////////////////////////////
    //// SoundTest Page
    ///////////////////////////////////////

    public function ViewSoundTest($singleRule)
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        //we receive a rule that might look like this....  (letter, right answer, first sound, second sound, third sound)
        //
        //
        //          self::addrule('Test',  "soundtest",      $text,      array(array('a','2', 'o', 'a', 'ah'),
        //                                                                     array('e','3', 'ee','u', 'e' ),
        //                                                                     array('i','1', 'i', 'ee','e' ) ) );
        //
        //when we finish, we need an array in javascript that looks like this...
        //
        //   var SoundTestData = new Array('a','3','Audio0','Audio0','Audio0',
        //                                 'e','2','Audio0','Audio0','Audio0',
        //                                 'i','1','Audio0','Audio0','Audio0');
        //

        $javaArray = ""; // we'll send it as a string
        foreach ($singleRule->content as $soundRule) {
            if (!empty($javaArray)) {
                $javaArray .= ',';
            }
            // separator

            $javaArray .= "'{$soundRule[0]}',"; // the target letter
            $javaArray .= "'{$soundRule[1]}',"; // the right answer
            $javaArray .= "'{$this->sound($singleRule->key, $soundRule[2])}',";
            $javaArray .= "'{$this->sound($singleRule->key, $soundRule[3])}',";
            $javaArray .= "'{$this->sound($singleRule->key, $soundRule[4])}'";

        }
        //echo "$javaArray <br />";

        $this->document->write('
         <script type="text/javascript">
         function setupPlaySoundTest(){
             var wordList = new Array(' . $javaArray . ');
             return(wordList);
         }
      </script>'); // we call sendPlayWordList once, and then use playWordList onclick

        $this->document->write("<table><tr><td>"); // outer table has only 1 column

        // sound icon and counter here...

        $this->document->write("<table cellspacing=\"30\"><tr><td>"); // inner table
        $this->document->write(IconClick('sound48.png', $alt = 'Play', $script = "playSoundTest();", $style = 'medium'));
        $this->document->write("</td><td>{$singleRule->teachtype}</td></tr></table>"); // close inner table

        $this->document->write("</td></tr>"); // outer table has only 1 column
        $this->document->write("<tr><td>");

        $this->document->write("<table><td>");

        $this->practice_letter_card('', '');

        $this->document->write("</td><td width='20%'></td><td valign='top'>");

        $this->document->write("<table><td>");

        $this->test_letter_card_small('1', '1'); // put up a big '1', and look for a sound called '1'
        $this->test_letter_card_small('2', '2');
        $this->test_letter_card_small('3', '3');

        $this->document->write("</td></table>");

        $this->document->write("</td></table>");

        $this->document->write("</td></tr><tr><td align=\"left\">"); // the outer table again

        $this->document->write('<table>');
        $this->document->write('<td><img id="cwrong" src="/decode/views/images/help128.png" width="64" height="64"></img></td>');

        foreach ($singleRule->nextsteps as $nextstep) {
            $this->document->write('<td>' . ButtonLink($nextstep[0], $nextstep[1]) . '</td>');
        }
        $this->document->write('</table>');

        $this->document->write("</td></tr></table>\n"); // close the outer table

    }

    //////////////////////////////////////
    //// Sentences Page
    ///////////////////////////////////////

    public function ViewSentences($singleRule)
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        //font-family: Century, "Century Gothic"

        // we get 6 strings (eg: 'The quick brown fox',...)

        //TODO ("link to Joomla's font size");
        $fontSize = 6;
        $cellspacing = 30;

        $this->document->write("<table><tr><td>");

        $this->document->write("<table cellspacing=\"$cellspacing\">");

        foreach ($singleRule->content as $thissentence) {
            $this->document->write("<tr><td align=\"left\"><font size=\"$fontSize\" face=\"Century Gothic\">$thissentence</font></td></tr>");
        }

        $this->document->write("</table>");

        foreach ($singleRule->nextsteps as $nextstep) {
            $this->document->write(ButtonLink($nextstep[0], $nextstep[1]));
        }

        $this->document->write("</td></tr></table>");

    }

    public function ViewNavigation()
    {

        $maxColumn = 20; // break the columns here...

        $auth = singleton('authentication');
        if ($auth->isValid()) {
            $identity = $auth->getIdentity(); // an array of UserID, UserRole, StudentID
        } else {
            trace("No identity, can't navigate.");
            requestLogon();
            return;
        }

        $navigation = $this->rules->navigation_headers(); // pull in an array of page => array(level,group,firstrule)
        $lastLevel = "";
        $lastGroup = "";

        $firstTime = true;
        $newGroup = true;
        $newLevel = true;

        $studentTrainingTBL = singleton('studenttrainingTBL');
        $row = $studentTrainingTBL->GetStudentRecord($identity['StudentID']); //returns the student record
        $applicableRules = unserialize($row['ApplicableRules']);

        $trainingLogTBL = singleton('traininglogTBL'); // returns list of mastered lessons
        $mastered = $trainingLogTBL->GetMasteredLessons();

        $linecount = 0; // used to break pages into multiple columns

        // there is an outer table for columns within a tab, and an inner table that contains rows
        // each row has 3 cells - 'button', text, gauge,

        foreach ($navigation as $key => $value) { // $value is the array(level,group,firstrule)

            if (!$firstTime and !($lastGroup == $value['group'])) { // we have changed groups
                $newGroup = true;
                $lastGroup = $value['group'];
            }

            if (!$firstTime and !($lastLevel === $value['level'])) { // we have changed levels
                $this->document->write("</table></td></table>"); // close both inner and outer table, and prepare for next tab if necessary
                $newLevel = true;
            }

            if ($newLevel) { // could have just tested the substr again
                $this->document->setTrainingPage($value['level']);
                $this->document->write("<table><td width='20%' valign='top'><table width='100%'> "); // open both inner and outer table
                $newLevel = false;
                $linecount = 0;
            }

            if ($newGroup) { // we have changed groups
                if ($linecount > $maxColumn) { // do we want to change columns as well?
                    $this->document->write("</table></td><td width='20%' valign='top'><table width='100%'>");
                    $linecount = 0;
                }
                $this->document->write("<tr><td colspan=3 width='100%'><span style=\"font-size:16px;\">{$value['group']}</span></td></tr>\n"); //rule ($value) is param1
                $newGroup = false;
                $linecount++;
            }

            // hunt through the applicable rules to see if this rule is in it
            $tool = IconLink("help16.png", $alt = "", $script = DECODE . "/training/addToActive/$key", $style = 'gauge');
            $gauge = ""; // nothing to show

            if (!is_array($applicableRules)) {
                assertTRUE(false, "Expecting 'applicableRules' to be an array, got " . serialize($applicableRules));
                $applicableRules = array();
            }

            // if we have already mastered, show a gold star.  but click has the same action as clicking on a disabled icon
            if (in_array($value['firstrule'], $mastered)) {
                $tool = IconLink("favorite16.png", $alt = "", $script = DECODE . "/training/addToActive/$key", $style = 'gauge');
            }

            if (array_key_exists($value['firstrule'], $applicableRules)) {
                $thatRule = $applicableRules[$value['firstrule']];
                if ($thatRule['mastery'] < 5) {
                    $tool = IconLink("accept16.png", $alt = "", $script = DECODE . "/training/deleteFromActive/$key", $style = 'gauge');
                    $gauge = IconLink("gauge{$applicableRules[$value['firstrule']]['mastery']}.jpg", $alt = "", $script = '', $style = 'gauge');
                }
            }

            $this->document->write("<tr><td nobreak>&nbsp;$tool</td><td nobreak>"); //button to set or reset
            $this->document->write("<span style=\"font-size:12px;\"><a href=\"" . DECODE . "/training/navigationParam/{$value['firstrule']}\">{$value['page']}</a></span>"); //rule ($value) is param1
            //$this->document->write("</td><td nobreak>$gauge");     //maybe show a gauge
            $this->document->write("</td></tr>"); //maybe show a gauge
            $linecount++;

            $lastLevel = $value['level'];
            $firstTime = false;
        }
        $this->document->write("</table></td></table>"); // close group and level and outer table

    }

    public function ViewPathing()
    { // never completed...

        $auth = singleton('authentication');
        if ($auth->isValid()) {
            $identity = $auth->getIdentity(); // an array of UserID, UserRole, StudentID
        } else {
            trace("No identity, can't navigate.");
            requestLogon();
            return;
        }

        $MainPath = array("Introduction to Vowels aoi" => true,
            "Sort Vowel a" => true,
            "Short Vowel o" => true,
            "Short Vowel i" => true,
            "aoi VC Emphasis" => true,
            "aoi VC to CV Pairs" => true,
            "aoi CV Emphasis" => true,
            "aoi VC-CV Contrast" => true,
            "Short Vowel u" => true,
            "aoiu VC Emphasis" => true,
            "aoiu VC to CV Pairs" => true,
            "aoiu CV Emphasis" => true,
            "aoiu VC-CV Contrast" => true,
            "Short Vowel e" => true,
            "ei VC and CV" => true,
            "5-Vowel VC Emphasis" => true,
            "5-Vowel VC to CV Pairs" => true,
            "5-Vowel CV Emphasis" => true,
            "5-Vowel VC-CV Contrast" => true,
            "Real Word Sentences" => true);

        $FourVowels = array("Introduction to Vowels aoi" => true,
            "Sort Vowel a" => true,
            "Short Vowel o" => true,
            "Short Vowel i" => true,
            "aoi VC Emphasis" => false,
            "aoi VC to CV Pairs" => false,
            "aoi CV Emphasis" => false,
            "aoi VC-CV Contrast" => false,
            "Short Vowel u" => true,
            "aoiu VC Emphasis" => true,
            "aoiu VC to CV Pairs" => true,
            "aoiu CV Emphasis" => true,
            "aoiu VC-CV Contrast" => true,
            "Short Vowel e" => true,
            "ei VC and CV" => false,
            "5-Vowel VC Emphasis" => true,
            "5-Vowel VC to CV Pairs" => true,
            "5-Vowel CV Emphasis" => true,
            "5-Vowel VC-CV Contrast" => true,
            "Real Word Sentences" => true);

        $FiveVowels = array("Introduction to Vowels aoi" => true,
            "Sort Vowel a" => true,
            "Short Vowel o" => true,
            "Short Vowel i" => true,
            "aoi VC Emphasis" => false,
            "aoi VC to CV Pairs" => false,
            "aoi CV Emphasis" => false,
            "aoi VC-CV Contrast" => false,
            "Short Vowel u" => true,
            "aoiu VC Emphasis" => false,
            "aoiu VC to CV Pairs" => false,
            "aoiu CV Emphasis" => false,
            "aoiu VC-CV Contrast" => false,
            "Short Vowel e" => true,
            "ei VC and CV" => false,
            "5-Vowel VC Emphasis" => true,
            "5-Vowel VC to CV Pairs" => true,
            "5-Vowel CV Emphasis" => true,
            "5-Vowel VC-CV Contrast" => true,
            "Real Word Sentences" => true);

        $firstTime = true;
        $newLevel = true;
        $linecount = 0; // used to break pages into multiple columns

        // wire this in once we are ready
        $navigation = $this->rules->pathing_headers(); // pull in an array of page => array(level,group,firstrule)

        $this->document->setTrainingPage('Vowels');

        foreach ($navigation as $group => $above) {
            echo $group, "<br /><br />";
        }

        $paths = array("MainPath" => $MainPath, "FourVowels" => $FourVowels, "FiveVowels" => $FiveVowels);

        $this->document->write("<table rules=\"all\" cellpadding=\"10px\"><td><table>"); // open both inner and outer table

        foreach ($paths as $title => $path) {
            $this->document->write("<strong>$title</strong><br />"); //rule ($value) is param1

            foreach ($path as $key => $value) { // $value is the array(level,group,firstrule)

                $this->document->write("<tr><td>"); //rule ($value) is param1

                if ($value) {
                    $this->document->write("<img src=\"" . DECODE . "/views/images/accept16.png\"></img>");
                } else {
                    $this->document->write("<img src=\"" . DECODE . "/views/images/delete16.png\"></img>");
                }

                $this->document->write("</td><td width='100%'>$key</td></tr>\n"); //rule ($value) is param1
            }
            $this->document->write('</table></td><td><table>');

        }
        $this->document->write("</table></td></table>"); // close both inner and outer table
    }

    public function practice_letter_card($letters, $word)
    {
        //$this->document->write("<td onClick=\"EvalSound('{$sound}')\" style=\"text-align:center; border:2px solid black; \" width=\"{$this->cardwidth}\" height=\"".strval($this->cardheight-30)."\" >");
        $this->document->write("<td " . $this->soundObject->onClick($word) . " style=\"text-align:center; border:0px solid black; \" width=\"{$this->cardwidth}\" height=\"" . strval($this->cardheight - 30) . "\" >");
        $this->document->write("<span id='cletter_card' style=\" font-family: 'Century Gothic',cursive; font-size:72px; padding-top=30px;\">$letters");
        $this->document->write("</span>"); //$this->document->write("</span>");
        $this->document->write("</td>");
    }
    public function practice_image_card($image, $word)
    {
        $this->document->write("<td>");
        $this->document->write("<img src=\"$image\" ");
        $this->document->write(" style=\"border:2px solid black\" width=\"{$this->cardwidth}\" height=\"{$this->cardheight}\" ");

        //$this->document->write(" onClick=\"EvalSound('{$sound}')");
        $this->document->write($this->soundObject->onClick($word));

        $this->document->write(" ></img></td>");

    }
    public function test_letter_card($letters, $sound)
    {
        $this->document->write("<td  onClick=\"clickOnCard('{$sound}')\" style=\"border:2px solid black;\" width=\"{$this->cardwidth}\" height=\"" . strval($this->cardheight - 30) . "\" >");
        $this->document->write("<span style=\" font-family: 'Century Gothic',cursive; font-size:72px; padding-left:60px; padding-top=30px;\">$letters");
        //$this->document->write(" style=\"border:2px solid black; text-align:center; font-family: 'Century Gothic',cursive; font-size:100px; margin=30px; margin-bottom=50px;\">&nbsp;{$letters}&nbsp;");
        $this->document->write("</span>"); //$this->document->write("</span>");
        $this->document->write("</td>");

    }
    public function test_letter_card_small($letters, $sound)
    {
        $this->document->write("<td  onClick=\"clickOnSmallCard('{$sound}')\" style=\"border:2px solid black;\" width=\"{$this->cardwidthSmall}\" height=\"" . strval($this->cardheightSmall) . "\" >");
        $this->document->write("<span style=\" font-family: 'Century Gothic',cursive; font-size:60px; margin=20px; margin-bottom=50px;\">&nbsp;{$letters}&nbsp;");
        $this->document->write("</span>"); //$this->document->write("</span>");
        $this->document->write("</td>");

    }
    public function test_image_card($image, $sound)
    {
        $this->document->write("<td>");
        $this->document->write("<img src=\"$image\" ");
        $this->document->write(" style=\"border:2px solid black\" width=\"{$this->cardwidth}\" height=\"{$this->cardheight}\" ");
        $this->document->write(" onClick=\"clickOnCard('{$sound}')\"></img>");
        $this->document->write("</td>");
    }

    public function sound_only($image, $sound)
    {
        $this->document->write("<td>");
        $this->document->write("</td>");
    }

    public function SurveyPage($singleRule)
    {

        $this->document->write("<div style=\"padding-right: 8px; float: left; padding-bottom: 8px;\">" .
            $singleRule->ruletext . "</div>");

        $this->document->write("<form action=\"{$singleRule->nextsteps[0][1]}\" method=\"post\" name=\"survey\">");
        foreach ($singleRule->content as $statement) { // statement is form   {$stmt,$ID}

            $this->document->write("<strong>{$statement[0]}</strong><br /><br />");
            $this->document->write("<input type=\"radio\" name=\"{$statement[1]}\" value=\"StronglyDisagree\">Strongly Disagree</input>&nbsp;&nbsp;");
            $this->document->write("<input type=\"radio\" name=\"{$statement[1]}\" value=\"Disagree\"        >Disagree         </input>&nbsp;&nbsp;");
            $this->document->write("<input type=\"radio\" name=\"{$statement[1]}\" value=\"Neutral\"         >Neutral          </input>&nbsp;&nbsp;");
            $this->document->write("<input type=\"radio\" name=\"{$statement[1]}\" value=\"Agree\"           >Agree            </input>&nbsp;&nbsp;");
            $this->document->write("<input type=\"radio\" name=\"{$statement[1]}\" value=\"StronglyAgree\"   >Strongly Agree   </input>&nbsp;&nbsp;");
            $this->document->write("<br /><br /><br /><br />");

        }

        // only allowed one button
        $this->document->write("<input class=\"readon2\" type=\"submit\" value=\"{$singleRule->nextsteps[0][0]}\"></input>");

        $this->document->write("</form><br /><br />");

    }
} // class

class sounds
{

    public $studentObject; // eventually this will tell us what directory to pull from
    public $document;
    public $soundApplets = '';

    public $soundCount = 0;
    public $soundArray = array(); // holds $word=>$soundID, for reuse of sounds

    public function __construct()
    { // constructor
        $this->studentObject = singleton('student');
        $this->document = singleton('document');
    }

    public function filePath($soundRoot, $word, $soundType = ".wav")
    { // returns something like "/decode/views/sounds/c.wav"   or FALSE if file doesn't exist

        $soundDirectory = DECODE . $soundRoot;
        $soundFile = $soundDirectory . '/' . strToLower($word) . $soundType;

        // the filepath is NOT the same as what the webserver hands out
        $config = singleton('config');
        $filepath = $config->get('system', 'filepath') . $soundRoot . '/';

        //echo $filepath.strToLower($word).'.wav<br />';
        if (file_exists($filepath . strToLower($word) . $soundType)) {

            $this->logSoundfile($word, true, $soundDirectory); // record that we found this file
            return ($soundFile);
        } else {
            $this->logSoundfile($word, false, $soundDirectory); // record that we did NOT find this file
            return (false);
        }

    }

    public function singleSound($word, $forceDuplicates = false)
    { // $rule is something like 'a0001', used to find the right directory
        //trace(__CLASS__,__METHOD__,__FILE__,"rule = '$rule', word = '$word'");

        // we sometimes have to force duplicates for some of the tests

        if (!$forceDuplicates and array_key_exists($word, $this->soundArray)) { // have already processed this sound, don't need to duplicate it again
            return ($this->soundArray[$word]);
        }

        $soundID = "";

        $soundRoot = "/views/sounds";
        if ($soundFile = $this->filePath($soundRoot, $word, ".wav")) {

            $soundID = 'Audio' . strval($this->soundCount++); //eg:  Audio15

            // use single quotes for clarity, but be careful because doesn't convert $soundID automatically
            $this->soundApplets .= "\n" . '<applet CODEBASE="/decode/views" code="AudioPlay.class" id="' . $soundID . '" width="1" height="1">
                                         <param name="image" value="AudioPlay.gif">
                                         <param name="audio" value="' . $soundFile . '">
                                       </applet>';

            $this->soundArray[$word] = $soundID; // save this index for this word
        }

        // we accumulate them and write them all out together at the end
        return ($soundID);
    }

    // some javascript functions need to know how many sounds there are
    public function soundCount()
    {
        return ($this->soundCount);
    }

    // close out the page by writing out the various sound applets we have accumulated
    public function writeOut()
    {
        $this->document->postwrite($this->soundApplets); // if we created any sounds, write them out now
        //trace ("writing out sound applets " .  print_r($this->soundApplets,true));

    }

    public function onClick($word)
    {

        $sound = $this->singleSound(strToLower($word));

        if (!empty($sound)) { // only write out the onClick() if the sound exists
            return ("onClick=\"EvalSound('{$sound}')\"");
        } else {
            return ('');
        }
    }

    public function logSoundfile($word, $isFound, $filepath = "")
    { // record that we found this file
        static $tableExists = false;

        global $captureSoundfiles; // check the global setting - are we capturing soundfiles FOR TOM ONLY
        if (!$captureSoundfiles) {
            return;
        }

        $acl = singleton("acl");
        if ($acl->isAdmin()) {

            $soundfiles = singleton('soundfilesTBL');
            $soundfiles->addSound($word, $isFound, $filepath);

            if (!$isFound) {
                echo " $word "; // only tom sees these ALL THE TIME
            }
        }
    }

    public function multiSoundIcon($icon, $wordArray, $alt = '', $style = 'medium')
    {
        $soundCount = $this->soundCount(); // total number of sounds
        return (IconClick($icon, $alt, $script = "playCurrentSound($soundCount," . count($wordArray) . ')', $style)); // param to playCurrentSound is max # of sounds
    }
}

// variant class if no sounds...
class soundsNone extends sounds
{
    public function sound($rule, $word, $forceDuplicates = false)
    { // $rule is something like 'a0001', used to find the right directory
        return ('');
    }
}

class soundsMP3 extends sounds
{

    public function singleSound($word, $forceDuplicates = false)
    { // $rule is something like 'a0001', used to find the right directory
        //trace(__CLASS__,__METHOD__,__FILE__,"rule = '$rule', word = '$word'");

        // the MP3 version doesn't care about $forceDuplicates

        $soundRoot = "views/sounds/mp3/";
        $soundFile = $soundRoot . $word . ".mp3";
        if (file_exists($soundFile)) {
            return ('/decode/' . $soundRoot . $word . ".mp3");
        } else {
            $acl = singleton("acl");
            if ($acl->isAdmin()) {
                echo $word, ' ';
            }
        }

        return ('');
    }

//        <a href="">load file 2 and play it</a>

    public function onClick($word)
    {

        $sound = $this->singleSound(strToLower($word));
        return ('');
    }

    public function multiSoundIcon($icon, $wordArray, $alt = '', $style = 'medium')
    {

        $wordString = $this->wordArray2wordString($wordArray);
        return (IconClick($icon, $alt, $script = "MP3playCurrentSound('$wordString')", $style));

    }

    public function dictateSoundIcon($icon, $n, $wordArray, $alt = '', $style = 'medium')
    { // $n is the number of words to dictate, usually 3

        $wordString = $this->wordArray2wordString($wordArray);
        $soundString = strtolower(implode(",", $wordArray));

        //$soundCount = $this->soundObject->soundCount();    // total number of sounds
        //$this->document->write(IconClick('sound48.png',$alt='Play',$script="playCurrentSounds($n,$uniqueCount,'$uniqueWords','$uniqueSounds')",$style='medium'));    // param to playCurrentSound is max # of sounds
        //$this->document->write($this->soundObject->dictateSoundIcon('sound48.png',$singleRule->content,$alt='Play',$style='medium'));

        $html = singleton('document'); // prefetch the sounds for dictation
        $html->onLoadFunctions("MP3preloadSounds('$wordString')");

        return (IconClick($icon, $alt, $script = "MP3playCurrentSounds($n,'$wordString','$soundString')", $style));

    }

    public function wordArray2wordString($wordArray)
    { // convert array of sound names to string-array of soundfile names  eg;  'blech' to '/decode/views/blech.mp3'
        $wordArray2 = array();
        $soundRoot = "/decode/views/sounds/mp3/";
        foreach ($wordArray as $word) // wordString2 is the proper name of the file, eg:  /decode/views/sounds/mp3/blech.mp3
        {
            $wordArray2[] = $soundRoot . strtolower($word) . '.mp3';
        }

        $wordString = implode(",", $wordArray2);
        return ($wordString);
    }
}

class viewpages extends UnitTestCase
{ // a prototype f view pages

/*
public function DUMMY_COPY_ME(){
trace(__CLASS__,__METHOD__,__FILE__);

// code here

$document = document::singleton();
$document->writeTab('??????',$HTML);
}
 */

    public $systemStuff;
    public $document;
    public $post = array();

    public function __construct()
    {
        $this->systemStuff = new systemStuff();
        $this->document = document::singleton();
    }

    public function testViewpages()
    {
        return (true);
    }

// command to copy an icon from the library (replace ??? with icon name)
    // xcopy d:\open_icon_library-standard-0.11\open_icon_library-standard\???.* d:\html\phonics\icons /s

    public function navbar($title, $page, $where, $p1 = '', $p2 = '', $p3 = '')
    { // navigation buttons on tab bar
        $systemStuff = new systemStuff();
        $link = $systemStuff->buildURL($page, $where, $p1, $p2, $p3);

        $document = document::singleton();
        $document->setTitleBar('header', 'left', $title, $link);
    }
}

///////////////////  for word matrix //////////////////////

class matrixShow extends defaultDisplay implements BasicDisplayFunctions
{

    //layout:   $this->layout <br />
    //style:    $this->style <br />
    //tabName:  $this->tabName  <br />
    //dataParm: $this->dataParm  <br />
    //data:     $this->data   // array
    //note:     $this->note <br />";

    public function __construct($tabName = "", $data = "")
    {
        $this->data = $data;
        $this->tabName = $tabName;
    }

    public function above()
    {
        $this->controls = 'note'; // override the default controls
        $m = new matrixDispatch();
        $HTML = $m->dispatch('load', $this->dataParm); // LOAD ignored second time
        return ($HTML);
    }

//   function aside(){    // our own custom version
    //      return($this->note);
    //   }
}

class matrixIntro extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $this->controls = ''; // override the default controls

        $HTML = '';

        $HTML .= "<b>{$this->lesson->lessonName}</b>";
        $HTML .= '<br />' . $this->dataParm;

        return ("<div style=\"max-width:600px;font-size:150%;line-height:150%;\">$HTML</div>");
    }

}

class matrixWordList extends defaultDisplay implements BasicDisplayFunctions
{
    // badly named - it's just the list of words created IN a matrix
    // there is another function called wordListMatrix that is a standard
    //     phonics-style wordlist

    public function above()
    {

        $this->controls = 'note'; // override the default controls

        $data9 = $this->randomize($this->dataParm); // split data into an array

        $HTML = '<table>';
        foreach ($data9 as $word) {
            $HTML .= "<tr><td style=\"width:200px;font-size:300%\">" . $word . "</td></tr>";
        }

        $HTML .= '</table>';

        return ($HTML);

    }

    public function randomize($aString)
    { // split data into an array

        $aString = str_replace(" ", '', $aString); // lose spaces
        $aString = str_replace("\n", '', $aString); // lose CRs
        $aString = str_replace("\r", '', $aString); // lose LFs

        assert(!empty($aString) and is_string($aString));

        $wordArray = explode(',', $aString);

        //shuffle($wordArray);          // weird function, sorts in place

        $result = array_slice($wordArray, 0, 9); // trim it down

        return ($result); // a randomized array
    }

}

class matrixFinal extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = '';

        $this->controls = 'completed'; // override the default controls

        return ("<div style=\"max-width:600px;font-size:110%;line-height:110%;\">$HTML</div>");
    }

}

class wordListMatrixTriple extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $this->controls = ''; // override the default controls

        switch ($this->style) {
        }

        // test whether we build connectors properly
        $m = new matrixAffix(MM_POSTFIX);
        $m->testConnectorStrategy();

        $data9 = $this->generate9($this->dataParm, $this->layout, $this->data); // split data into an array

        // only use the 'wordlist' class for no styling, otherwise use the wordard
        $HTML .= '<table class="wordlist">';

        $d = new nextWordDispenser($this->note); // something like 'ed,ing'

        $n = 8; // usually we have 9 elements (0 to 8)

        $m = new matrixDispatch; // create the matrix

        for ($i = 0; $i <= $n; $i++) {
            $word = trim(strtolower($data9[$i]));
            $triples = $m->triples($word, $d->pull());

            $rewritten = "&nbsp;<span style='font-size:20%;'> rewritten </span>&nbsp;";
            $toproduce = "&nbsp<span style='font-size:20%;'> produces </span>&nbsp;";

            foreach ($triples as $nw) {
                if ($nw['plus'] == $word) {
                    continue;
                }
                // the original word with no affixes
                $HTML .= '<tr>';
                $HTML .= "<td class=\"processed\">{$nw['plus']}</td>
                                  <td class=\"processed\"> $rewritten </td>
                                  <td class=\"processed\">{$nw['graphic']}</td>
                                  <td class=\"processed\"> $toproduce </td>
                                  <td class=\"processed\">{$nw['final']}</td>";
                $HTML .= '</tr>';
            }
        }
        $HTML .= '</table>';

//         $HTML .= '</div>';

        return ($HTML);

    }
}

class wordListMatrixScramble extends wordListComplete
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        // this class just sets up the data and then calls the standard
        // wordlist class

        $d1 = new nextWordDispenser($this->data);
        $d2 = new nextWordDispenser($this->dataParm);

        // create a list of 40 words, that should be enough for four cols

        $m = new matrixDispatch; // create the matrix
        $words = '';
        for ($i = 0; $i < 40; $i++) {

            $triples = $m->triples($d1->pull(), $d2->pull()); // beg + ed
            foreach ($triples as $nw) {
                //if($nw['plus'] == $word)
                //   continue;   // the original word with no affixes
                if (!empty($words)) {
                    $words .= ',';
                }

                $words .= $nw['plus'];
            }
        }

        $this->data = $words; // fix up for wordlist
        $this->dataParm = 'normal';
        return (parent::above()); // and let wordlist take it...
    }
}

class wordListMatrixTimed extends wordListTimed
{

    public function above()
    {

        // this class just sets up the data and then calls the standard
        // wordlist class

        $d1 = new nextWordDispenser($this->data);
        $d2 = new nextWordDispenser($this->note);

        // create a list of 10 words for the test

        $m = new matrixDispatch; // create the matrix
        $words = '';
        for ($i = 0; $i < 10; $i++) {

            $triples = $m->triples($d1->pull(), $d2->pull()); // beg + ed
            foreach ($triples as $nw) {
                //if($nw['plus'] == $word)
                //   continue;   // the original word with no affixes
                if (!empty($words)) {
                    $words .= ',';
                }

                $words .= $nw['final'];
            }
        }

        $this->data = $words; // fix up for wordlist
        $this->note = '';
        return (parent::above()); // and let wordlist take it...
    }
}

/////////////////////////////////////////
/////// affix spinner ////////////////////
/////////////////////////////////////////

class affixSpinner extends defaultDisplay implements BasicDisplayFunctions
{

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        // the data array has FIVE elements
        //   definition   (' <b>Ease</b> from the latin...')
        //   prefix list  ('pre1,pre2,pre3')
        //   base list    ('base1,base2')
        //   suffix1 list  ('post1,post2,post3')
        //   suffix2 list  ('post1,post2,post3')
        //   suffix3 list  ('post1,post2,post3')

        // valid styles:  'prefix'  always shows the prefix
        //                'suffix'  always shows the first suffix

        // current value are in $_SESSION[page] so that EVERY PAGE has its own value (usually unset)
        // click is in P3

        // if those don't exist, then set Click to 'base1'  and current to '++' (empty+empty+empty)

        $this->controls = 'none'; // override the default controls

        // draw the controls at the top

        // prefixes go in column 1-2-3-4
        // basees    go in column 6-7
        // suffixes go in column 9-10,11,12

        while (count($this->data) < 6) {
            // add empty affixes if not enough provided  (we once allowed four)
            $this->data[] = '';
        }

        $definition = $this->data[0];
        $prefix = explode(',', $this->data[1]);
        $bases = explode(',', $this->data[2]);
        $suffix1 = explode(',', $this->data[3]);
        $suffix2 = explode(',', $this->data[4]);
        $suffix3 = explode(',', $this->data[5]);

        // get the $current and $click values so we know what we are processing

        parse_str($_SERVER["REQUEST_URI"], $query); // load $query, $click is in P3
        $update = '';

        if (isset($query['P1']) and isset($query['P3'])) {
            if ($query['P1'] == $this->tabName) // was the click on this tab?
            {
                $update = $query['P3'];
            }
        }

        $current = '+' . $bases[0] . '++++'; // first time default - just the base
        $sessionPage = $this->tabName . '_' . $this->lesson->lessonKey; // unique for each page
        //$sessionPage = 'X'.hash('crc32', $sessionPage);                     // $sessionPage must be a valid PHP variable name

        if (isset($_SESSION[$sessionPage])) {
            $current = $_SESSION[$sessionPage];
        }

//$HTML .= "<br />session page:  $sessionPage     isset is ".(isset($_SESSION[$sessionPage])?'true':'false')." value is {$_SESSION[$sessionPage]}";
        //$HTML .= "<br />pre-current:  '". substr($current,0,strlen($prefix[0])) ."'";
        //$HTML .= "<br />prefix:  ". serialize($prefix);

        //if(isset($query['P1']))
        //$HTML .= "P1 {$query['P1']} <br>";
        //if(isset($query['P2']))
        //$HTML .= "P2 {$query['P2']} <br>";

        // special case.  if there is only one prefix (and not already in), always put it in
        if ($this->style == 'prefix') {
            $current = $prefix[0] . $current;
        }

        // special case for always-on suffix
        if ($this->style == 'suffix') {
            $temp = explode('+', $current);
            $temp[2] = $suffix1[0];
            $current = implode('+', $temp);
        }

        $clause = substr($update, 0, 1); // update looks like '3ly' which means change clause 3 to 'ly'
        $value = rtrim(substr($update, 1));

//$HTML .= "<br />current:  '$current' ";   // eg:   +ease+y+
        //$HTML .= "<br />update:   '$update' ";      // eg:  '3ly'   or suffix2 gets 'ly'
        //$HTML .= "<br />clause:   '$clause' ";
        //$HTML .= "<br />value:    '$value' ";

        ////////////// use the matrix class to process the input /////////////////////

        $morphs = explode('+', $current);
        switch ($clause) {
            case '0':$morphs[0] = $value;
                break;
            case '1':$morphs[1] = $value; // reset to the base
                if ($this->style !== 'prefix') {
                    $morphs[0] = '';
                } else {
                    $morphs[0] = $prefix[0];
                }
                // prefix always included
                if ($this->style !== 'suffix') {
                    $morphs[2] = '';
                } else {
                    $morphs[2] = $suffix1[0];
                }
                // prefix always included
                $morphs[3] = '';
                $morphs[4] = '';
                break;
            case '2':$morphs[2] = $value;
                $morphs[3] = '';
                $morphs[4] = '';
                break;
            case '3':if (!empty($morphs[2])) {
                    $morphs[3] = $value;
                    $morphs[4] = '';
                }
                break;
            case '4':if (!empty($morphs[2]) and !empty($morphs[3])) {
                    $morphs[4] = $value;
                }

                break;
            default:break;
        }
        $current = implode('+', $morphs);
        $current = str_replace('&nbsp;', ' ', $current);
        $current = str_replace(' +', '+', $current);

//$HTML .= "<br />newcurrent:  '$current' ";   // eg:   +ease+y+

        $_SESSION[$sessionPage] = $current; // update the session for next time
        //$HTML .= "<br />session page:  $sessionPage     isset is ".(isset($_SESSION[$sessionPage])?'true':'false')." value is {$_SESSION[$sessionPage]}";

/*
printNice('abc','start');
$m = new matrixAffix(MM_POSTFIX);
$ret = $m->connectorStrategy('ease','y','easy');
printNice('abc',$ret);
$ret = $m->connectText('ease','y');
printNice('abc',$ret);
printNice('abc','end');
 */

        // use the matrix functions to get the correct spelling

        $m = new matrixDispatch();
        $list = $m->dispatch('simpleBuild', $current);

        ////////////// end of calculating, now process the output /////////////////////

        if (!empty($definition)) {
            $HTML .= "<span style='font-size:24px;'>";
            $HTML .= "<p>$definition</p>";
            $HTML .= "</span><br />";
        }

        $HTML .= $m->simpleRender($prefix, $bases, $suffix1, $suffix2, $suffix3, $this->tabName, $this->lesson->lessonKey);

        $HTML .= "<span style='font-size:48px;'>";
        $HTML .= $list['plus'];
        $HTML .= "<br />" . $list['graphic'];
        $HTML .= "<br />" . $list['final'];
        $HTML .= "</span>";

        return ($HTML);
    }
}

class affixSpinnerLast extends affixSpinner implements BasicDisplayFunctions
{
    // same as affexSpinner but with controls - use this for the last page

    public function above()
    {
        $HTML = parent::above();
        $this->controls = 'completed'; // override the default controls
        return ($HTML);
    }
}

/////////////////////////////////////////
/////// partsOfSpeech ////////////////////
/////////////////////////////////////////

class partsOfSpeech extends defaultDisplay implements BasicDisplayFunctions
{

    public $showClauseBreaks = false;

    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $this->controls = 'refresh'; // override the default controls

        // draw the controls at the top

        $parts = array('1' => array('name' => 'Noun',
            'bgnd' => '#CC1100'),

            '2' => array('name' => 'Verb',
                'bgnd' => 'yellow'),

            '3' => array('name' => 'Adjective',
                'bgnd' => 'grey'),

            '4' => array('name' => 'Adverb',
                'bgnd' => 'green'),

            '5' => array('name' => 'Pronoun',
                'bgnd' => 'purple'),

            '6' => array('name' => 'Conjunction',
                'bgnd' => 'orange'),

            '7' => array('name' => 'Preposition',
                'bgnd' => '#CCFF00'), // a lime yellow

            '8' => array('name' => 'Interjection',
                'bgnd' => 'cyan'),
        );

        // don't need buttons, but I like the styling
        $HTML .= '<table class="POS"><tr>';
        foreach ($parts as $key => $value) {
            $HTML .= "<td><button style=\"background:{$value['bgnd']}\" onClick=\"POSselectType($key)\">{$value['name']}</button></td>";

        }
        $HTML .= '</tr></table>';

        // now one cell for each word
        $aWords = explode('$', $this->data[0]);
        array_shift($aWords); // since the first char is always a '$' we get one extra

        $wCount = 0;
        $charCount = 0;

        $HTML .= '<span style="font-size:250%;font-weight:bold;table-layout:fixed;width:100%;">
                    <table class="POS"><tr>';

        foreach ($aWords as $value) {
            $type = substr($value, 0, 1); // digit 0 to 8
            $word = substr($value, 1);
            $word = str_replace(' ', '&nbsp;', $word); // non-collapsing spaces
            assertTRUE(strpos('01234567', $type) !== false, "$value missing type '$type' in {$this->data[0]}");

            $leftClause = $rightClause = "";
            if ($this->showClauseBreaks) {
                $leftClause = "<td class='POScorner' onClick=\"alert('LCorner')\"><sub>&lfloor;</sub></td>";
                $rightClause = "<td class='POScorner' onClick=\"alert('RCorner')\"><sub>&rfloor;</sub></td>";
            }
            $wordClause = "\n<td class='POShighlight' id=WC{$wCount} onClick=\"POS.clickWord('WC{$wCount}',$type)\"><sup>$word</sup></td>";
            if ($type == 0) {
                $wordClause = "\n<td><sup>$word</sup></td>";
            }
            // can't click on zeros

            $HTML .= $leftClause . // left bracket if enabled
            $wordClause . // word
            $rightClause; // right bracket if enables

            // calculate table breaks because JQUERY won't
            $charCount += strlen($word) + 1;
            if ($this->showClauseBreaks) {
                $charCount += 2;
            }

            if ($charCount > 45) {
                $HTML .= '</tr></table><table><tr>';
                $charCount = 0;
            }

            $wCount++;
        }

        $HTML .= '</tr></table></span>';

        return ($HTML);
    }
}

/////////////////////////////////////////
/////// assessmentSummary ///////////////
/////////////////////////////////////////

class assessmentSummary extends defaultDisplay implements BasicDisplayFunctions
{
    public function above()
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $identity = identity::singleton();
        $studentID = $identity->studentID();

        $TL = TrainingLog::singleton();
        $results = $TL->getAssessment($studentID);

        $HTML .= $results[0]['createdhuman'] . '<br />';

        // remove duplicates, but we want to remove the OLDEST ones and only keep the new ones
        $alreadySeen = array();
        foreach ($results as $key => $result) {
            // have we seen this rule before?
            if (isset($alreadySeen[$result['rule']])) {
                if (empty($result['comment'])) // didn't add a comment on the newer record
                {
                    unset($results[$alreadySeen[$result['rule']]]);
                }
            }

            // this is the BIG array

            // now update whether new or previously seen
            $alreadySeen[$result['rule']] = $key;
        }

        $HTML .= $TL->formatResultSet($results, 'Your Comments', array('rule', 'comment'));

        $this->controls = 'none'; // override the default controls

        return ($HTML);
    }
}

/////////////////////////////////////////
/////// decodable Readers   ///////////////
/////////////////////////////////////////

///  NOTE:  this extends 'sightWordEliminator', which extends...

class decodableReader1 extends sightWordEliminator implements BasicDisplayFunctions
{

    public function aside()
    { // no refresh or anything...
        //   return ('<img src="./images/scottlee1.png" width="200" />');
        return (''); // we do our own

    }

    public function above()
    {
        trace(__CLASS__, __METHOD__, __FILE__);

        printNice('decodable', 'dataParm');
        printNice('decodable', $this->dataParm);
        printNice('decodable', 'layout');
        printNice('decodable', $this->layout);
        printNice('decodable', 'data');
        printNice('decodable', $this->data);
        printNice('decodable', 'style');
        printNice('decodable', $this->style);
        printNice('decodable', 'note');
        printNice('decodable', $this->note);

        $format = unserialize($this->data[0]); // eg:  ['B/W,['th','ch']['credit to someone']]
        $wordArt = new wordArtDecodable();
        $wordArt->setHighColours($format[0]);
        $wordArt->setHighLightDigraph($format[1]);

        $chapter = $this->dataParm;
        $chapter = str_replace("\r", '', $chapter); // lose LFs
        $chapter = str_replace("\n", '', $chapter); // lose LFs
        $chapter = str_replace('  ', ' ', $chapter); // lose double spaces
        $chapter = str_replace('  ', ' ', $chapter); // lose double spaces
        $chapter = str_replace('  ', ' ', $chapter); // lose double spaces

        //   $this->preparePage($this->dataParm); // turn the text into wordart

        $aText = explode(' ', $chapter); // might have words like '\n'


        $HTML = '';


        // credit, if provided
        if (!empty($format[2])) {
            $HTML .= "<div style='align:right'>{$format[2]}</div>";
        }

        $HTML .= '<br>';     // start with a bit of space at the top



        ////////// some test code ////
        if ($GLOBALS['debugON']) {
            $phones = [];
            $fails = [];
            $festival = festival::singleton();


            foreach ($aText as $word) {
                $commaText = ',' . strtolower($word) . ','; // so don't get 'aid' from 'said'
                if (strpos(',' . memorize_words() . ',', $commaText) == false) {


                    $word = preg_replace('/[^a-z]+/i', '', strtolower($word));  // simplify
                    if(isset($festival->dictionary[$word])){
                        $aValues = unserialize($festival->dictionary[$word]);
                        if(empty($aValues[DICT_FAILPHONE])){    // if failphone is empty, then we were able to translate
                            $thisphones = $festival->word2Phone($word);
                            $thisphones = str_replace('/','.',$thisphones); // erase syllable breaks
                            $aThisphones = explode('.',$thisphones);  // phones in this word
                            foreach($aThisphones as $at){
                                $is_consonant = strpos(',b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,t,v,w,x,y,z,zh,kw,ks,ng,th,dh,sh,ch,', strtolower(substr($at,1,1)));
                                if($is_consonant == false){
                                    $phones[$at]=$at;       // creates if doesn't exist
                                }
                            }
                        }
                    }else{
                        array_push($fails,$word);
                    }
                }
            }
            $HTML .= implode(' ',$phones).'<br>';
            $HTML .= "<span style='color:red;'>".implode(' ',$fails).'</span><br>';
        }



        // artwork, if provided
        if (!empty($this->layout)) {
            $HTML .= "<img style='float:right;max-height=300px;' src='./images/{$this->layout}' height='300' />";
         }

         foreach ($aText as $text) {

            if (empty($text)) {
                continue;
            }

            // pre- process any post characters to remove the backslash (will be CRLFs)
            if ($text == '\\') {
                $HTML .= "<br style=' clear: left;'><br style='float:left;'>";
            } elseif ($text == '{') {
                $HTML .= '<b style="font-size:150%;">';
            } elseif ($text == '}') {
                $HTML .= '</b><br>';
            } else {

                $HTML .= "<div style='display:inline-block;padding-right:15px;border-top:0px;'>";

                $HTML .= $wordArt->render($text); // not in the list, format
                $HTML .= "</div>";
            }

        }

        if ($this->style == 'last') {
            $HTML .= "<div style='float:right;width=250px'>" . $this->masteryOrCompletion(false, false) . "</div>";
        }



        return ($HTML);
    }

}
