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
    var $note;   // TODO:  see what 'note' does.  probably nothing.


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

    var $leftWidth = 10; // default (1-12 in columns)

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
        $HTML = $this->masteryControls($this->controls);
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
        assertTrue(isset($bTable->clusterWords[$lessonName]), "could not find lesson '$lessonName'");
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
                $above .= "<br>$lessonName";
            }

            $border = '';

            $HTML = '';

            if (!empty($header)) {
                $HTML .= MForms::rowOpen(12);
                $HTML .= $header;
                $HTML .= MForms::rowClose();
            }

            if (!empty($aside)) {   // we have both left and right

                if ($GLOBALS['mobileDevice']) { // this skips over the drawer symbol on mobile
                    $HTML .= MForms::rowOpen(1);
                    $HTML .= MForms::rowNextCol($this->leftWidth - 1);
                } else {
                    $HTML .= MForms::rowOpen($this->leftWidth);
                }
                $HTML .= $above;
                $HTML .= $below;
                $HTML .= MForms::rowNextCol(12 - $this->leftWidth);
                $HTML .= $aside;
                $HTML .= MForms::rowClose();
            } else { // no aside, take the full page if we need to
                $HTML .= MForms::rowOpen(12);
                $HTML .= $above;
                $HTML .= $below;
                $HTML .= MForms::rowClose();
            }

            if (!empty($footer)) {
                $HTML .= MForms::rowOpen(12);
                $HTML .= $footer;
                $HTML .= MForms::rowClose();
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
        // printNice($data, 'wordartlist data');

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $HTML .= '<div id="wordArtList">';

        $data9 = $this->generate9($data); // split data into an array

        // printNice($data, 'data');
        // printNice($data9, 'data9');

        // printNice($data9, 'wordartlist data9');


        $n = 9; // usually we have 9 elements (0 to 8)
        // if ($this->style == 'full' or $this->style == 'simple') {
        //     $n -= 2;
        // }
        // two less if we use wordart
        $HTML .= "<table style='width:100%;'>";
        for ($i = 0; $i < $n; $i++) {

            //  turn make/made/mate into an array

            // printNice($triple,'triple');

            $HTML .= "<tr>";

            // either looks like 'word' or 'word/word/word'
            if (str_contains($data9[$i], '/')) {
                $triple = explode('/', $data9[$i]);   // array to spread across a line
            } else {
                $triple = [$data9[$i]];  // simple word into array so can use foreach
            }

            // now looks like ['word','word']
            foreach ($triple as $word) {
                if ($this->style == 'full') {
                    $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";
                } elseif ($this->style == 'simple') {
                    $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";
                } else {
                    $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";
                }
            }

            $HTML .= '</tr>';
        }
        $HTML .= '</table>';

        $HTML .= '</div>';
        return ($HTML);
    }


    // masteryControls uses $this->controls, eg:  'refresh.timer.comment'

    function masteryControls(string $style): string
    {
        $HTML = '';

        if ($GLOBALS['mobileDevice']) {
            $watchSize = '80px';
            $fontSize = '36px';
            $fontPadding = '12px';
            $buttonSpacing = ' border-collapse: separate;border-spacing: 2px 2px;';
            $commentWidth = 12;
        } else {
            $watchSize = '150px';
            $fontSize = '64px';
            $fontPadding = '30px';
            $buttonSpacing = ' border-collapse: separate;border-spacing: 2px 16px;';
            $commentWidth = 8;
        }

        if (str_contains($style, 'refresh')) {
            $HTML .= MForms::rowOpen(3);
            $HTML .= MForms::rowNextCol(9);
            $HTML .= MForms::imageButton('refresh.png', 48, 'Refresh', 'refresh', $this->lessonName, $this->nTabs + 1);
            $HTML .= '<br />Refresh<br /><br />';
            $HTML .= MForms::rowClose();
        }


        $HTML .= "<form>";
        $HTML .= MForms::hidden('p', 'lessonTest');
        $HTML .= MForms::security();  // makes moodle happy
        $HTML .= MForms::hidden('lesson', $this->lessonName);
        $HTML .= MForms::hidden('score', '0');

        if (str_contains($style, 'stopwatch')) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= "<div style='background-color:#ffffe0;float:left;width:$watchSize;height:$watchSize;border:solid 5px grey;border-radius:30px;'>";
            $HTML .= "<div style='font-size:$fontSize;text-align:center;padding:$fontPadding;'>";
            $HTML .= '10';
            $HTML .= "</div>";
            $HTML .= "</div>";

            $HTML .= "<table style='float:left;$buttonSpacing'><tr><td>";  // use table to give nice vertical spacing
            $HTML .= MForms::onClickButton('Start', 'success', true, "alert('start')");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Stop', 'danger', true, "alert('stop')");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Reset', 'secondary', true, "alert('stop')");

            $HTML .= "</td></tr></table>";
            $HTML .= MForms::rowClose();
            // $HTML .= "<br>";
        }

        // remark element
        if (str_contains($style, 'comment')) {
            $HTML .= MForms::rowOpen($commentWidth);
            $HTML .= MForms::textarea('', 'remark', '', '', '', 3, 'Optional comment...');
            $HTML .= MForms::rowClose();
            $HTML .= "<br>";
        }

        // mastery element
        if (str_contains($style, 'mastery')) {
            $HTML .= MForms::submitButton('Mastered', 'primary', 'mastered');
        }
        // completion element
        if (str_contains($style, 'mastery')) {
            $HTML .= MForms::submitButton('In Progress', 'warning', 'inprogress');
        }


        $HTML .= "</form>";

        return $HTML;
    }



    // code for a stopwatch plus learning curve
    function stopwatchHTML()
    {

        $HTML = '';
        return '';   // stopwatch is breaking html

        $HTML .= PHP_EOL . '<form>';
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

    public function above(): string
    {
        $HTML = '';


        switch ($this->style) {
            case 'full':
                $this->wordArt = new wordArtColour();
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

        if (($GLOBALS['mobileDevice'])) {     // smaller for mobile
            $this->wordArt->vSpacing = '8px';
            $this->wordArt->fontSize = '36px';
        }

        if (!isset($this->lessonData['words'])) {
            printNice($this);
            assertTrue(false, "wordlist page without 'words'");
            return '';
        }


        $HTML .= $this->wordartlist($this->lessonData['words']);
        return ($HTML);
    }
}


class WordContrastPage extends DisplayPages
{

    public function above(): string
    {
        $HTML = '';


        switch ($this->style) {
            case 'full':
                $this->wordArt = new wordArtColour();
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

        if (($GLOBALS['mobileDevice'])) {     // smaller for mobile
            $this->wordArt->vSpacing = '8px';
            $this->wordArt->fontSize = '36px';
        }

        $HTML .= $this->wordartlist(array($this->lessonData['stretch']));
        return ($HTML);
    }
}




class InstructionPage extends DisplayPages
{



    public function render(string $lessonName, int $nTab = 1): string
    {
        $HTML = PHP_EOL . '<div class="row">';
        $HTML .= "<div class='col header'>";
        $HTML .= "$this->HTMLContent";
        $HTML .= PHP_EOL . '</div>';
        $HTML .= '</div>';

        if ($this->controls == 'mastery') {
            $HTML .= $this->masteryHTML();
        }

        return $HTML;
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
        // printNice("function render(string $lessonName, nTab $nTab): string");

        $HTML = '';

        $bTable = new BlendingTable();
        // printNice($bTable->clusterWords);

        if (empty($lessonName)) {  // first lesson (or maybe completed last lesson?)
            reset($bTable->clusterWords);
            $lessonName = key($bTable->clusterWords);
        }

        assertTrue(isset($bTable->clusterWords[$lessonName]), "didn't find lesson '$lessonName' in blendingTable");
        $lessonData = $bTable->clusterWords[$lessonName];

        // printNice($lessonData, 'lessonData');

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

        if (isset($lessonData['pronounce'])) {
            $vPages = new WordListPage();
            $vPages->style = 'simple';
            $vPages->dataParm = $lessonData['pronounce'];
            $tabs['Stretch'] = $vPages->render($lessonName, count($tabs));
        }

        if (isset($lessonData['stretch'])) {

            $aTemp = explode(',', $lessonData['stretch']);
            assert(count($aTemp) == 2);
            $first = $aTemp[0];
            $second = $aTemp[1];

            $beside =      "<br><span style='font-size:20px;'>
            Contrast the pronunciation of <sound>$first</sound> and <sound>$second</sound>.<br>
            Feel the difference in your mouth.  Practice contrasting them.</span><br><br><br>";


            $vPages = new WordContrastPage();
            $vPages->style = 'simple';
            $vPages->layout = '1col';
            if (!$GLOBALS['mobileDevice'])
                $vPages->leftWidth = 6;   // make the words a bit narrower

            $vPages->dataParm = 'scramble';
            $tabs['Stretch'] = $vPages->render($lessonName, count($tabs));
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
            $tabs['Spinner'] = wordSpinner($lessonData['spinner'][0], $lessonData['spinner'][1], $lessonData['spinner'][2]);
        }

        $vPages = new WordListPage();
        $vPages->style = 'none';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh.note.stopwatch.mastery.comments'; // override the default controls
        $vPages->leftWidth = 6;   // make the words a bit narrower so all these controls fit
        $tabs['Test'] = $vPages->render($lessonName, count($tabs));

        // printNice($tabs);

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

            $tabs[$tab] = $vPages->render($tab, count($tabs));
        }

        $HTML .= $views->tabs($tabs);

        return $HTML;
    }

    // function contrastPage($lessonName, $lessonData): string
    // {
    //     $HTML = '';
    //     return '';

    //     $views = new Views();
    //     $tabNames = ['Instruction', 'Words', 'Test'];
    //     $tabContents = ['', '', '', ''];

    //     $vPages = new WordListPage();
    //     $vPages->style = 'simple';
    //     $vPages->layout = '1col';
    //     $vPages->dataParm = 'scramble';
    //     $tabContents[0] = $vPages->render($lessonName, $lessonData);

    //     $vPages = new WordListPage();
    //     $vPages->style = 'none';
    //     $vPages->layout = '3col';
    //     $vPages->dataParm = 'scramble';
    //     $tabContents[1] = $vPages->render($lessonName, $lessonData);


    //     $vPages = new WordListPage();
    //     $vPages->style = 'none';
    //     $vPages->layout = '1col';
    //     $vPages->dataParm = 'scramble';
    //     $vPages->controls = 'refresh.note.stopwatch.comments'; // override the default controls

    //     $tabContents[3] = $vPages->render($lessonName, $lessonData);


    //     $HTML .= $views->tabs($tabNames,  $tabContents);

    //     return $HTML;
    // }

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
