<?php


class DisplayPages
{

    // this is a parent class for InstructionPage, WordListPage and similar classes

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
    var string $lessonName;
    var array $lessonData;
    var int $nTabs = 0;

    var $wordArt;

    var $lesson;
    var $style = 'simple';
    var $layout = '1col';
    var $tabName;
    var $dataParm;
    var $data;
    var $note;

    var $HTMLContent = '';      // for pages where we just stuff in the HTML we want

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
    {
        return ('');
    }
    function above()
    {
        return ('');
    }
    function below()
    {
        return ('');
    }
    function aside()
    {
        $r = $this->refreshNotes();
        $m = $this->masteryControls();
        //echo "'$r', '$m'";die;
        $HTML = '';
        if (!empty($r) or !empty($m)) {
            $HTML = "<table> $r $m </table>";
        }

        return ($HTML);
    }
    function footer()
    {
        return ($this->footer);
    }


    function setupLayout($layout)
    {
        $this->layout = $layout;
    }

    function render(string $lessonName, int $nTabs = 1): string
    {

        $this->lessonName = $lessonName;
        $this->nTabs = $nTabs;          // so refresh knows which tab to initialize

        $bTable = new BlendingTable();
        assertTrue(isset($bTable->clusterWords[$lessonName]));
        $this->lessonData = $bTable->clusterWords[$lessonName];

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

            // if ($GLOBALS['debugON']) {
            require_once('source/htmltester.php');

            $HTMLTester = new HTMLTester();
            $HTMLTester->validate($above);
            $HTMLTester->validate($below);
            $HTMLTester->validate($aside);
            $HTMLTester->validate($footer);
            // }

            return $HTML;
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

    function generate9(array $data): array
    { // split data into an array

        // first we make up the dataset.  each ROW must look like word or word/word or word/word/word


        assertTRUE(strpos('.1col.2col.3col.4col.5col', $this->layout) !== false, "layout is '$this->layout', must be '1col','2col','3col','4col',' or '5col'");

        $displayColumns = strval(substr($this->layout, 0, 1)); // 1col, etc

        $result = array();

        switch ($this->dataParm) {

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

    function wordartlist(array $data): string
    {

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $HTML .= '<div id="wordArtList">';

        $data9 = $this->generate9($data); // split data into an array

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


    function refreshHTML()
    {
        $HTML = '';
        if (strpos($this->controls, 'refresh') !== false) {

            $HTML .= '<tr><td>';
            $HTML .= MForms::unicodeButton('&#128260;', 48, 'Refresh', 'refresh', $this->lessonName, $this->nTabs + 1);
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

        $HTML .= "<form>";
        $HTML .= MForms::security();  // makes moodle happy
        $HTML .= MForms::hidden('lesson', $this->lessonName);
        $HTML .= MForms::hidden('score', '0');
        $HTML .= MForms::hidden('p', 'lessonTest',);
        $HTML .= MForms::textarea('', 'remark', '', '', '', 3, 'Optional comment...');
        $HTML .= MForms::submitButton('Mastered', 'primary', 'Mastered', $this->lessonName,);
        $HTML .= MForms::submitButton('In Progress', 'warning', 'InProgress', $this->lessonName,);


        // $loginForm->addTextFieldToForm("", "", "hidden", "action", "", "firstpage.mastery");
        // if ($includeTimer) {
        //     $loginForm->addTextFieldToForm("Timer", "", "text", "timer", "timer", "0");
        // }
        // $loginForm->addTextAreaToForm("", "Comment", "Comment", "Comment");
        // same URL in all cases, use the $action to capture the value
        // $URL = '';
        // if ($includeAdvancing) {
        //     $action = "TM_buttonSubmit('Advancing')";
        //     $loginForm->addSubmitButton("Advancing", YELLOW, $action);
        //     $action = "TM_buttonSubmit('Mastered')";
        //     $loginForm->addSubmitButton("Mastered", BLUE, $action);
        // } else {
        //     $action = "TM_buttonSubmit('Mastered')";
        //     $loginForm->addSubmitButton("Completed", BLUE, $action);
        // }

        $HTML .= "</form>";

        // $loginForm->addTextFieldToForm("", "", "hidden", "P1", "P1", ""); // P1 is the mastery level (eg: Completed)
        // $loginForm->addTextFieldToForm("", "", "hidden", "testwords", "", "");
        // $loginForm->addTextFieldToForm("", "", "hidden", "errors", "", "");
        // $loginForm->addTextFieldToForm("", "", "hidden", "lessonKey", "", $this->lesson->lessonKey);
        // $loginForm->addTextFieldToForm("", "", "hidden", "action", "", "firstpage.TimedSubmit");
        // $loginForm->addTextFieldToForm("", "", "hidden", "transaction", "", 'T' . uniqid());

        // $HTML .= $loginForm->render();
        return ($HTML);
    }

    // masteryControls uses $this->controls, but the whole thing can be overwritten
    function masteryControls()
    { // eg:  'refresh.timer.comment'
        $HTML = '';

        // printNice($this->controls, 'Mastery Controls');

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


        return ($HTML);
    }

    function debugParms($class, $override = false)
    {
        return '';
        
        $HTML = '';
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

        return ($HTML);
    }
}






class nextWordDispenser
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

    public function load(array $wordStrings)
    {
        // switch (gettype($wordStrings)) {
        //     case 'string':

        //         $wordStrings = str_replace(' ', '', $wordStrings); // lose spaces
        //         $wordStrings = str_replace("\n", '', $wordStrings); // lose CRs
        //         $wordStrings = str_replace("\r", '', $wordStrings); // lose LFs

        //         $this->wordArrays = array(explode(',', $wordStrings)); //one-element array
        //         break;

        // case 'array':
        $this->wordArrays = array();
        foreach ($wordStrings as $words) {
            $words = str_replace(' ', '', $words); // lose spaces
            $words = str_replace("\n", '', $words); // lose CRs
            $words = str_replace("\r", '', $words); // lose LFs


            if (!is_string(($words)))
                printNice($words, 'should be array');
            else
                $this->wordArrays[] = explode(',', $words);
        }
        //         break;

        //     default:
        //         assertTRUE(false, "Didn't expect type " . gettype($wordStrings));
        // }
        // ok, $wordArrays is set up with one or more arrays of words

        $this->depleteArrays = $this->wordArrays; // copy them
    }

    public function pull()
    {
        // first we check if there are any indexes left, refill if necessary
        if (count($this->indexes) == 0) {
            $this->indexes = array_keys($this->wordArrays);
        }

        assertTRUE(count($this->indexes) > 0, 'should not be empty', $this->indexes);

        if ($this->random) {
            assertTrue(!empty($this->indexes));
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



class WordListPage extends DisplayPages
{

    public function above()
    {

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
                $this->wordArt = new wordArtNone();
        }

        if (!isset($this->lessonData['words'])) {
            printNice($this);
            assertTrue(false, "wordlist page without 'words'");
            return '';
        }


        $HTML = $this->wordartlist($this->lessonData['words']);
        return ($HTML);
    }
}


class InstructionPage extends DisplayPages
{


    public function above()
    {
        return $this->HTMLContent;      // already set up.
    }
}


class PronouncePage extends DisplayPages
{


    public function above()
    {

        $vc = new ViewComponents();

        $this->controls = '';
        // $HTML = $this->debugParms(__CLASS__); // start with debug info

        $style = "border:3px solid black;";

        $HTML = "<br><span style='font-size:30px;'>
                    We are starting the vowel " . $vc->sound($this->dataParm) . "as in Bat.
                    <br>Practice pronouncing it.<br>  Make shapes with
                    your mouth, exaggerate it, play
                    with it.
                    <br>Find other words that sound like 'bat'.</span><br><br><br>";

        $HTML .= "<img style='$style' src='pix/b-{$this->dataParm}.jpg' />";

        return ($HTML);
    }
}


class Lessons
{

    function getNextLesson(int $studentID): string
    {


        $logTable = new LogTable();
        $lastMasteredLesson = $logTable->getLastMastered($studentID);
        printNice($lastMasteredLesson, 'lastMasteredLesson');

        if ($lastMasteredLesson) {  // if we found a lesson record
            $nextLesson = $this->getNextKey(current($lastMasteredLesson)->lesson);
        } else {
            $nextLesson = $this->getNextKey('');
        }
        printNice($nextLesson, "next lesson");

        return $nextLesson;     // empty string if no next lesson
    }


    // given an array and a key, find the NEXT key
    function getNextKey(string $key)
    {
        $bTable = new BlendingTable();
        $lessonData = $bTable->clusterWords;

        reset($lessonData);
        if (empty($key)) {
            return key($lessonData);  // returning the first key
        }
        while (key($lessonData) !== $key) {  // loop through looking...
            if (!next($lessonData))
                return '';      // out of data
        }
        // found a match, now need the next element
        if (next($lessonData))
            return key($lessonData);     // success
        return ''; // we were at the last element
    }


    function render(string $lessonName, int $nTab = 1): string
    {
        printNice("function render(string $lessonName, nTab $nTab): string");

        $HTML = '';

        $bTable = new BlendingTable();

        if (empty($lessonName)) {  // first lesson (or maybe completed last lesson?)
            reset($bTable->clusterWords);
            $lessonName = key($bTable->clusterWords);
        }

        $lessonData = $bTable->clusterWords[$lessonName];

        printNice($lessonData, 'lessonData');

        $views = new Views();


        if (isset($lessonData['pagetype'])) {
            // printNice($lessonData,$lessonName);

            switch ($lessonData['pagetype']) {
                case 'instruction':
                    $HTML .= $views->navbar(['navigation'], $lessonName);
                    $HTML .= $this->instructionPage($lessonName, $lessonData);
                    break;
                    // case 'lecture':
                    //     // printNice($lessonData, $lessonName);
                    //     break;
                case 'decodable':
                    $HTML .= $views->navbar(['navigation'], $lessonName);
                    $HTML .= $this->decodablePage($lessonName, $lessonData);
                    // this is a decodable lesson
                    break;
                default:
                    assertTrue(false, "Don't seem to have a handler for pagetype '{$lessonData['pagetype']}'");
            }
        } else {
            // anything that doesn't have a pagetype is a drill lesson
            // printNice($lessonData, $lessonName);
            $HTML .= $views->navbar(['navigation'], $lessonName);
            $HTML .= $this->drillPage($lessonName, $lessonData, $nTab);
        }

        return $HTML;
    }




    function drillPage(string $lessonName, array $lessonData, int $nTab): string
    {
        $HTML = '';

        $views = new Views();
        $tabs = [];

        // printNice($lessonData);

        if (isset($lessonData['pronounce'])) {
            $vPages = new PronouncePage();
            $vPages->style = 'simple';
            $vPages->dataParm = $lessonData['pronounce'];
            $tabs['Pronounce'] = $vPages->render($lessonName, count($tabs));
        }

        $vPages = new WordListPage();
        $vPages->style = 'simple';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh';
        $tabs['Words'] = $vPages->render($lessonName, count($tabs));

        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '3col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh';
        $tabs['Scramble'] = $vPages->render($lessonName, count($tabs));
        if (isset($lessonData['decodable'])) {
            $tabs['Decodable'] = $this->decodableTab($lessonData);
        }

        if (isset($lessonData['spinner'])) {
            $tabs['Word Spinner'] = wordSpinner($lessonData['spinner'][0], $lessonData['spinner'][1], $lessonData['spinner'][2]);
        }

        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh.note.timer.comments'; // override the default controls
        $tabs['Test'] = $vPages->render($lessonName, count($tabs));


        // have tabs array set up, now render it....
        $HTML .= $views->tabs($tabs, $nTab);

        return $HTML;
    }

    function decodableTab(array $lessonData): string
    {
        $image = '';
        if (isset($lessonData['image']))
            $image =  "<img src='pix/{$lessonData['image']}' style='float:right;height:200px;' />";

        return "<div>$image<p>{$lessonData['decodable']}</p></div>";
    }



    function instructionPage($lessonName, $lessonData): string
    {
        $HTML = '';

        printNice("    function instructionPage($lessonName, lessonData): string");

        $views = new Views();
        $tabs = [];
        // printNice($lessonData);
        // return '';

        // get the name of the LAST lesson
        end($lessonData['instructionpage']);
        $last = key($lessonData['instructionpage']);
        printNice($last, 'last');

        foreach ($lessonData['instructionpage'] as $tab => $content) {
            $vPages = new InstructionPage();
            $vPages->lessonName = $lessonName;
            $vPages->HTMLContent = $content;

            if ($last == $tab)
                $vPages->controls = 'mastery'; // override the default controls

            $tabs[$tab] = $vPages->render($tab, $lessonData);
        }

        $HTML .= $views->tabs($tabs);

        return $HTML;
    }

    function contrastPage($lessonName, $lessonData): string
    {
        $HTML = '';
        return '';

        $views = new Views();
        $tabNames = ['Instruction', 'Words', 'Test'];
        $tabContents = ['', '', '', ''];

        $vPages = new WordListPage();
        $vPages->style = 'simple';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $tabContents[0] = $vPages->render($lessonName, $lessonData);

        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '3col';
        $vPages->dataParm = 'scramble';
        $tabContents[1] = $vPages->render($lessonName, $lessonData);


        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh.note.timer.comments'; // override the default controls

        $tabContents[3] = $vPages->render($lessonName, $lessonData);


        $HTML .= $views->tabs($tabNames,  $tabContents);

        return $HTML;
    }

    function decodablePage($lessonName, $lessonData): string
    {
        $HTML = '';
        return '';

        $views = new Views();
        $tabNames = ['Instruction', 'Words', 'Test'];
        $tabContents = ['', '', '', ''];

        $vPages = new WordListPage();
        $vPages->style = 'simple';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $tabContents[0] = $vPages->render($lessonName, $lessonData);

        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '3col';
        $vPages->dataParm = 'scramble';
        $tabContents[1] = $vPages->render($lessonName, $lessonData);


        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh.note.timer.comments'; // override the default controls

        $tabContents[3] = $vPages->render($lessonName, $lessonData);


        $HTML .= $views->tabs($tabNames,  $tabContents);

        return $HTML;
    }
}
