<?php


class DisplayPages
{

    // this is a parent class for InstructionPage, WordListPage and similar classes
    // it generates a SINGLE TAB

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

    var $leftWidth = 8; // default (range 1-12 in columns)
    var $colSeparator = '';

    var $showPageName = false;
    var $defaultDifficulty = 2;

    // feel free to subclass these functions for any page...

    // function header()
    // {
    //     return ('');
    // }
    // function above()
    // {
    //     return ('');
    // }
    // function below()
    // {
    //     return ('');
    // }
    // function aside()
    // {
    //     return ('');
    // }
    // function footer()
    // {
    //     return ($this->footer);
    // }


    function setupLayout($layout)
    {
        $this->layout = $layout;
    }

    function render(string $lessonName, int $nTabs = 1): string
    {

        // $this->lessonName = $lessonName;
        // $this->nTabs = $nTabs;          // so refresh knows which tab to initialize

        // $bTable = new BlendingTable();
        // assertTrue(isset($bTable->clusterWords[$lessonName]), "could not find lesson '$lessonName'");
        // $this->lessonData = $bTable->clusterWords[$lessonName];

        // logic for now is that

        // // only call them once, and call them all early, in case of side effects.
        // $above = $this->above(); // and call above() FIRST because it tends to
        // // do things like move the controls to the header
        // $below = $this->below();
        // $header = $this->header();
        // $footer = $this->footer();
        // $aside = $this->aside();



        $HTML = '';

        if (!empty($header)) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= $this->header;
            $HTML .= MForms::rowClose();
        }

        if ($GLOBALS['mobileDevice']) {
            $HTML .= MForms::rowOpen(1); // this skips over the drawer symbol on mobile
            if (!empty($this->aside)) {
                $HTML .= MForms::rowNextCol($this->leftWidth - 1);
            } else {
                $HTML .= MForms::rowNextCol(11);
            }
        } else {  // not mobile, respect the request
            $HTML .= MForms::rowOpen($this->leftWidth);
        }

        $HTML .= $this->above;


        if ($GLOBALS['mobileDevice']) { // this skips over the drawer symbol on mobile
            $HTML .= MForms::rowNextCol(12 - $this->leftWidth);  // separator but side-by-side
        } else {
            $HTML .= MForms::rowNextCol(2);  // separator but side-by-side
            $HTML .= MForms::rowNextCol(max(12 - ($this->leftWidth + 2), 4));
        }

        if (!empty($this->aside)) {
            $HTML .= $this->aside;      // controls beside exercise, but text below
            $HTML .= "<br>";  // reset
        }

        // we have an open row.  on mobile, close it and open a new row.  on browser, just move to next column
        if (!empty($this->below)) {
            if ($GLOBALS['mobileDevice']) {
                $HTML .= MForms::rowClose();
                $HTML .= MForms::rowOpen(1);   // this skips over the drawer symbol on mobile
                $HTML .= MForms::rowNextCol(11);
            }

            $HTML .= $this->below;
        }
        $HTML .= MForms::rowClose();

        if (!empty($footer)) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= $footer;
            $HTML .= MForms::rowClose();
        }

        // if ($GLOBALS['debugON']) {
        require_once('source/htmltester.php');

        $HTMLTester = new HTMLTester();

        $HTMLTester->validate($this->above);
        $HTMLTester->validate($this->below);
        $HTMLTester->validate($this->aside);
        $HTMLTester->validate($this->footer);
        // }

        return $HTML;
    }


    public function instructionTab(int $nTab = 1): string
    {
        $HTML = PHP_EOL . '<div class="row">';
        $HTML .= "<div class='col header'>";
        $HTML .= "$this->HTMLContent";
        $HTML .= PHP_EOL . '</div>';
        $HTML .= '</div>';

        if ($this->controls == 'mastery') {
            $HTML .= $this->masteryControls('');
        }

        return $HTML;
    }

    // public function pronouncePage()
    // {

    //     $vc = new ViewComponents();

    //     $this->controls = '';
    //     // $HTML = $this->debugParms(__CLASS__); // start with debug info

    //     $style = "border:3px solid black;";

    //     $HTML = "<br><span style='font-size:30px;'>
    //                 We are starting the vowel " . $vc->sound($this->dataParm) . "as in Bat.
    //                 <br>Practice pronouncing it.<br>  Make shapes with
    //                 your mouth, exaggerate it, play
    //                 with it.
    //                 <br>Find other words that sound like 'bat'.</span><br><br><br>";

    //     $HTML .= "<img style='$style' src='pix/b-{$this->dataParm}.jpg' />";

    //     return ($HTML);
    // }

    public function wordListPage(): string
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
            case 'test':
                $this->wordArt = new wordArtNone();
                $this->wordArt->dimmable = true;
                break;
            default:
                assertTRUE(false, "wordArt style is '{$this->style}', must be 'full', 'simple', 'test', or 'none'");
                $this->wordArt = new wordArtNone();
        }

        if (!isset($this->lessonData['words'])) {
            assertTrue(false, "wordlist page without 'words'");
            return '';
        }


        $HTML .= $this->wordartlist($this->lessonData['words']);
        // $this->above = $HTML;
        return ($HTML);
    }

    public function wordContrastPage(): string
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

        $HTML .= $this->wordartList(array($this->lessonData['stretch']));  // double arrow separator
        // $this->above = $HTML;
        return ($HTML);
    }


    function generate(string $aString, int $n = 10)
    { // given a comma string "a,b,c..." generate 10 (or n) words in random order

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

            case '':
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
                assertTRUE(false, "dataParm is '{$this->dataParm}', must be 'normal', 'reverse', 'noSort', or scramble'");
        }

        return ($result);
    }

    // function wordartlist(array $data): string
    // {
    //     // printNice($data, 'wordartlist data');

    //     $HTML = $this->debugParms(__CLASS__); // start with debug info

    //     $HTML .= '<div id="wordArtList">';

    //     $data9 = $this->generate9($data); // split data into an array

    //     // printNice($data, 'data');
    //     // printNice($data9, 'data9');

    //     // printNice($data9, 'wordartlist data9');


    //     $n = 9; // usually we have 9 elements (0 to 8)
    //     // if ($this->style == 'full' or $this->style == 'simple') {
    //     //     $n -= 2;
    //     // }
    //     // two less if we use wordart
    //     $HTML .= "<table style='width:100%;'>";
    //     for ($i = 0; $i < $n; $i++) {

    //         //  turn make/made/mate into an array

    //         // printNice($triple,'triple');

    //         $HTML .= "<tr>";

    //         // either looks like 'word' or 'word/word/word'
    //         if (str_contains($data9[$i], '/')) {
    //             $triple = explode('/', $data9[$i]);   // array to spread across a line
    //         } else {
    //             $triple = [$data9[$i]];  // simple word into array so can use foreach
    //         }

    //         // now looks like ['word','word']
    //         foreach ($triple as $word) {
    //             if ($this->style == 'full') {
    //                 $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";
    //             } elseif ($this->style == 'simple') {
    //                 $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";
    //             } else {
    //                 $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";
    //             }
    //         }

    //         $HTML .= '</tr>';
    //     }
    //     $HTML .= '</table>';

    //     $HTML .= '</div>';
    //     return ($HTML);
    // }


    function wordartList(array $data): string
    {
        // printNice($data, 'wordartlist data');

        $HTML = $this->debugParms(__CLASS__); // start with debug info

        $HTML .= "<div id='wordArtList'>";

        $data9 = $this->generate9($data); // split data into an array


        $n = 9; // usually we have 9 elements (0 to 8)
        $HTML .= "<table style='width:100%;'>";
        for ($i = 0; $i < $n; $i++) {

            $HTML .= "<tr>";


            // either looks like 'word' or 'word/word/word'
            if (str_contains($data9[$i], '/')) {
                $separator = true;
                $triple = explode('/', $data9[$i]);   // array to spread across a line
            } else {
                $separator = false;
                $triple = [$data9[$i]];  // simple word into array so can use foreach
            }

            // now looks like ['word','word']
            for ($j = 0; $j < count($triple); $j++) {
                $word = $triple[$j];

                $tdStyle = $GLOBALS['mobileDevice'] ?
                    "style='padding-top:0px;padding-bottom:0px;'" :
                    "style='padding-top:10px;padding-bottom:10px;'";
                $HTML .= "<td>" . $this->wordArt->render($word) . "</td>";

                if ($this->colSeparator and $j < count($triple) - 1) {      // separator, not after last one
                    $HTML .= "<td style='font-size:40px;text-align:center;'>$this->colSeparator</td>";
                }
            }

            $HTML .= '</tr>';
        }
        $HTML .= '</table>';

        $HTML .= '</div>';
        return ($HTML);
    }



    // masteryControls uses $this->controls, eg:  'refresh.timer.comment'

    function masteryControls(string $style, int $nTab = 1): string
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
            $HTML .= MForms::imageButton('refresh.png', 48, 'Refresh', 'refresh', $this->lessonName, $nTab + 1);
            $HTML .= '<br />Refresh<br /><br />';
            $HTML .= MForms::rowClose();
        }

        $HTML .= "<form>";
        $HTML .= MForms::hidden('p', 'lessonTest');
        $HTML .= MForms::security();  // makes moodle happy
        $HTML .= MForms::hidden('lesson', $this->lessonName);
        $HTML .= MForms::hidden('score', '0', 'score');

        if (str_contains($style, 'stopwatch')) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= "<div style='background-color:#ffffe0;float:left;width:$watchSize;height:$watchSize;border:solid 5px grey;border-radius:30px;'>";

            $HTML .= "<div name='timer' id='timer' style='font-size:$fontSize;text-align:center;padding:$fontPadding;'>";
            // $HTML .= "<input type='text' name='timer' id='timer'  placeholder='' value='0' class='' />";
            $HTML .= '0';
            $HTML .= "</div>";
            $HTML .= "</div>";

            $HTML .= "<table style='float:left;$buttonSpacing'><tr><td>";  // use table to give nice vertical spacing
            $HTML .= MForms::onClickButton('Start', 'success', true, "StopWatch.start()");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Stop', 'danger', true, "StopWatch.stop()");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Reset', 'secondary', true, "StopWatch.reset()");

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


    function decodableTab(string $story, string $image, string $title = ''): string
    {
        $HTML = '';

        $wordArt = new WordArtDecodable();

        // gather the title.  it will display differently if there is an image or not.
        $titleHTML = '';
        if (!empty($title)) {    // empty input string results in one element in output array.  Blecch.
            $aTitle = explode(' ', $title);
            foreach ($aTitle as $titleWords) {
                $titleHTML .= "<div style='float:left;padding:20px;border-bottom:solid 4px black;'>";

                $titleHTML .= $wordArt->render($titleWords);
                $titleHTML .= "</div>";
            }
        }


        if (!empty($image)) {
            // top line has title and image
            $HTML .= MForms::rowOpen(9);
            $HTML .= $titleHTML;
            $HTML .= MForms::rowNextCol(3);
            $HTML .= "<img style='float:right;width:100%;''; src='pix/$image' />";
            $HTML .= MForms::rowClose();
        } else {
            // top line is just title
            $HTML .= MForms::rowOpen(12);
            $HTML .= $titleHTML;
            $HTML .= MForms::rowClose();
        }

        $margin = $GLOBALS['mobileDevice']?'5px':'18px';

        $floatWord = "<div style='white-space:nowrap;float:left;margin:$margin;'>";
        $newParagraph = "<div style='white-space:nowrap;float:left;margin:$margin;'></div>";

        // now include the rest of the story
        $story = str_replace("\n", ' ', $story);      // cr is just whitespace
        $aWords = explode(' ', $story);

        $HTML .= MForms::rowOpen(12);
        $HTML .= $newParagraph;

        foreach ($aWords as $word) {
            if (!empty(trim($word))) {

                // special case for \ paragraph break
                if ($word == '\\') {
                    $HTML .= MForms::rowClose();
                    $HTML .= MForms::rowOpen(12);
                    $HTML .= $newParagraph;
                }
                $HTML .= $floatWord;
                $wordArt->useSmallerFont = true;
                $HTML .= $wordArt->render($word);
                $HTML .= "</div>";   // end of floatWord div
            }
        }
        $HTML .= MForms::rowClose();
        return $HTML;
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


    public function count()
    {
        return (count($this->wordArrays)); // simply the number of arrays
    }

    public function load($wordStrings)    // string of comma-separated words: 'a,b,c'
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





// class InstructionPage extends DisplayPages
// {



//     public function render(string $lessonName, int $nTab = 1): string
//     {
//         $HTML = PHP_EOL . '<div class="row">';
//         $HTML .= "<div class='col header'>";
//         $HTML .= "$this->HTMLContent";
//         $HTML .= PHP_EOL . '</div>';
//         $HTML .= '</div>';

//         if ($this->controls == 'mastery') {
//             $HTML .= $this->masteryHTML();
//         }

//         return $HTML;
//     }
// }



function displayAvailableCourses(): string
{
    //  <p><span style='background-color:yellow;'>Drill for 20 minutes, EVERY DAY!</span></p>"

    $views = new Views();
    $HTML = $views->navbar(['exitCourse']);

    $sound = $views->sound('ay');

    $spelling1 = $views->spelling('ai');  // maid
    $spelling2 = $views->spelling('a_e');  // make
    $spelling3 = $views->spelling('ea');   // break

    $eeSound = $views->sound('ee');
    $eeSpelling = $views->spelling('ee');

    $data = [
        [
            "blending", "<br>Blending", 'fathatsat.png',
            "<p><span style='background-color:yellow;'><b>Start with BLENDING</b></span>
                if your student barely reads or guesses from context or first-letters.  </p>

             <p>BLENDING provides a focused attack for building phonological
                    skills using the five short vowels. It drills blending and segmentation,
                    and retrains first-letter readers to look at all the letters. </p>

                    <p> Not sure?  Start with BLENDING anyhow. It will be quickly obvious if your student needs this.</p>",
        ],
        [
            "phonics", "<br>Phonics", "phonics.png",
            "<p>PHONICS is the two-way mapping of spoken sounds to written spellings.</p>
            <p>In the word 'maid', the sound $sound has the spelling $spelling1. That same sound is spelled differently in 'bake', 'tray', 'break', 'taste, 'eight', 'straight', and other words.  It's complicates, but there are only about 35 sounds, and Phonics helps a student organize his understanding of the mappings.<p>
            <p>Most students learn phonics just by practicing reading, but time is short and your student is far behind.
                Use these drills and texts to accelerate learning.</p>"
        ],
        [
            "assisted", "Assisted<br>Reading", "decodable.png",
            "<p>These stories offer the decoding hints developed in BLENDING and PHONICS, which
                    can be turned down as your student progresses.  </p>
            <p>Older students often resist reading 'baby books' only to get frustrated with harder texts
                    that they cannot yet decode.  Assists help an older
                    student succeed with more complex stories, and builds confidence."
        ],
        [
            "spelling", "<br>Spelling", "",
            "<p>Spelling....</p>",
        ],

    ];

    $HTML = "";
    foreach ($data as $course) {

        assert(in_array($course[0], $GLOBALS['allCourses']), 'sanity check - unexpected courses?');
        $href = "window.location." . MForms::linkHref('selectCourse', $course[0]);

        $HTML .= $GLOBALS['mobileDevice'] ? MForms::rowOpen(6) : MForms::rowopen(2);
        $HTML .= "<button onclick=$href type='button' class='btn btn-light btn-outline btn-lg'>";
        $HTML .= "   <h1 style='color:darkblue;font-weight:900;transform: scaleX(0.80) translateZ(0);text-shadow: 0.125em 0.125em #C0C0C0;'><i>{$course[1]}</i></h1>";
        $HTML .= '</button>';
        $HTML .= $GLOBALS['mobileDevice'] ? MForms::rowNextCol(6) : MForms::rowNextCol(1);
        $HTML .= "<img src='pix/{$course[2]}' width='150px' />";

        $HTML .= $GLOBALS['mobileDevice'] ?  MForms::rowClose() . MForms::rowOpen(12) : MForms::rowNextCol(5);

        $HTML .= $course[3];
        $HTML .= MForms::rowClose();
        $HTML .= "<hr>";
    }
    return $HTML;
}




class Lessons
{
    public $course;
    public $clusterWords = [];   // the current lesson

    function __construct(string $course)
    {
        assert(in_array($course, $GLOBALS['allCourses']), "sanity check - unexpected course '' ?");
        require_once("courses/$course.php");

        $this->course = ucfirst(($course));
        $lessonTable = new $this->course;  // 'blending' becomes 'Blending'
        $this->clusterWords = $lessonTable->clusterWords;
    }

    // given a key, find the NEXT key (basically the NEXT button, but used elsewhere)
    function getNextKey(string $key = ''): string
    {
        reset($this->clusterWords);
        if (empty($key)) {
            return key($this->clusterWords);  // returning the first key
        }
        while (key($this->clusterWords) !== $key) {  // loop through looking...
            if (!next($this->clusterWords))
                return '';      // out of data
        }
        // found a match, now need the next element
        if (next($this->clusterWords))
            return key($this->clusterWords);     // success
        return ''; // we were at the last element
    }

    function getLesson(string $lessonName): array
    {

        if (empty($lessonName)) {  // first lesson (or maybe completed last lesson?)
            reset($this->clusterWords);
            $lessonName = key($this->clusterWords);
        }

        assert(isset($this->clusterWords[$lessonName]), "didn't find lesson '$lessonName' in {$this->course}");
        $lessonData = $this->clusterWords[$lessonName];
        return $lessonData;
    }


    // for drawing a lesson accordian.  returns [  [lesson=>group], [lesson=>group] ...]
    function getLessonsByGroups(): array
    {
        $groups = [];
        foreach ($this->clusterWords as $key => $value) {
            $groups[$key] = $value['group'] ?? '';  // might not be set
        }
        return $groups;
    }



    function getNextLesson(int $studentID): string
    {

        $logTable = new LogTable();
        $lastMasteredLesson = $logTable->getLastMastered($studentID, $this->course);  // log table
        printNice($lastMasteredLesson, 'lastMasteredLesson');

        if ($lastMasteredLesson) {  // if we found a lesson record
            $currentLesson = current($lastMasteredLesson)->lesson;
            $nextLesson = $this->getNextKey($currentLesson);
        } else {
            $nextLesson = $this->getNextKey('');  // first key
        }
        printNice($nextLesson, "next lesson");

        return $nextLesson;     // empty string if no next lesson
    }




    function render(string $lessonName, int $showTab = 1): string
    {
        // printNice("function render(string $lessonName, nTab $nTab): string");


        $HTML = '';

        // printNice($lessonData, 'lessonData');

        $views = new Views();

        assertTrue(isset($this->clusterWords[$lessonName]), "Couldn't find lesson '$lessonName' in course $this->course");
        $lessonData = $this->clusterWords[$lessonName];
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
                    $HTML .= $this->decodablePage($lessonName, $lessonData,$showTab);
                    break;
                default:
                    assertTrue(false, "Don't seem to have a handler for pagetype '{$lessonName['pagetype']}'");
            }
        } else {
            // anything that doesn't have a pagetype is a drill lesson
            // printNice($lessonData, $lessonName);
            $HTML .= $views->navbar(['navigation'], $lessonName);
            $HTML .= $this->drillPage($lessonName, $lessonData, $showTab);
        }

        return $HTML;
    }




    function drillPage(string $lessonName, array $lessonData, int $showTab): string
    {
        // printNice("    function drillPage(string $lessonName, array lessonData, int $nTab): string  ");

        $HTML = '';

        $views = new Views();
        $tabs = [];

        // printNice($lessonData,'drillPage()');

        if ($GLOBALS['mobileDevice']) {
            $textSpan = "<span style='font-size:1.2em;'>";
        } else {
            $textSpan = "<span style='font-size:2em;'>";
        }
        $textSpanEnd = "</span>";



        if (isset($lessonData['pronounce'])) {
            $vPages = new DisplayPages();

            if (isset($lessonData['instruction'])) {
                $vPages = new DisplayPages();

                $vPages->above = $textSpan . $lessonData['instruction'] . $textSpanEnd;
                if ($GLOBALS['mobileDevice'])
                    $vPages->leftWidth = 12;
                else
                    $vPages->leftWidth = 6;

                $tabs['Instructions'] = $vPages->render($lessonName, $showTab);
            }


            if ($GLOBALS['mobileDevice'])
                $vPages->leftWidth = 12;
            else
                $vPages->leftWidth = 5;

            $style = "align:center;width:90%;border:3px solid black;max-width:500px;";
            $vPages->above = "<img style='$style' src='pix/b-{$lessonData['pronounce']}.jpg' />";

            if (isset($lessonData['pronounceSideText']))
                $vPages->below =  $textSpan . $lessonData['pronounceSideText'] . $textSpanEnd;

            $tabs['Pronounce'] = $vPages->render($lessonName, count($tabs));
        }




        if (isset($lessonData['stretch'])) {

            $aTemp = explode(',', $lessonData['stretch']);

            $vPages = new DisplayPages();
            $vPages->lessonName = $lessonName;
            $vPages->lessonData = $lessonData;

            $vPages->aside = $vPages->masteryControls('refresh', count($tabs));

            if (isset($lessonData['stretchSideText']))
                $stretchText = $lessonData['stretchSideText'];
            else
                $stretchText = "Contrast the sounds across the page. Ask the student to exaggerate the sounds and feel the difference in their mouth.<br><br>
                If your student struggles, review words up and down, and then return to contrasts.<br><br>";

            $vPages->below = $textSpan . $stretchText . $textSpanEnd;


            $vPages->style = 'simple';
            $vPages->layout = '1col';
            $vPages->colSeparator = '&#11020;';


            if ($GLOBALS['mobileDevice'])
                $vPages->leftWidth = 8;   // make the words a bit narrower
            else
                $vPages->leftWidth = 4;

            $vPages->dataParm = 'scramble';

            $vPages->above = $vPages->wordContrastPage();
            $tabs['Stretch'] = $vPages->render($lessonName, count($tabs));
        }

        // list of words with vowel highlighted
        $vPages = new DisplayPages();
        $vPages->lessonName = $lessonName;
        $vPages->lessonData = $lessonData;
        $vPages->style = 'simple';
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->aside = $vPages->masteryControls('refresh', count($tabs));
        if (!$GLOBALS['mobileDevice'])
            $vPages->leftWidth = 4;   // make the words a lot narrower
        $vPages->above = $vPages->wordListPage();

        if (isset($lessonData['sidenote'])) {
            $vPages->aside .= $textSpan . $lessonData['sidenote'] . $textSpanEnd;
        }

        $tabs['Words'] = $vPages->render($lessonName, count($tabs));



        // scramble of plain words
        $vPages = new DisplayPages();
        $vPages->lessonName = $lessonName;
        $vPages->lessonData = $lessonData;
        $vPages->style = 'none';
        $vPages->layout = '3col';
        $vPages->dataParm = 'scramble';
        $vPages->aside = $vPages->masteryControls('refresh', count($tabs));
        $vPages->above = $vPages->wordListPage();

        if ($GLOBALS['mobileDevice']) {
            $vPages->leftWidth = 10;   // make the words a bit narrower
        } else {
            $vPages->leftWidth = 6;   // make the words a bit narrower
        }
        $tabs['Scramble'] = $vPages->render($lessonName, count($tabs));


        if (isset($lessonData['spinner'])) {
            $tempHTML ='';
            $spinner = $views->wordSpinner($lessonData['spinner'][0], $lessonData['spinner'][1], $lessonData['spinner'][2]);
            if(!isset($lessonData['spinnertext'])){  // easy case, no text
                $tempHTML .= $spinner;
            }else{

                // complicate case, need to add instructions
                if($GLOBALS['mobileDevice']){
                    $tempHTML .= MForms::rowOpen(12);   // phone gets top-and-bottom
                    $tempHTML .= $spinner;
                    $tempHTML .= MForms::rowClose();
                    $tempHTML .= MForms::rowOpen(12);
                    $tempHTML .= $textSpan;
                    $tempHTML .= $lessonData['spinnertext'];
                    $tempHTML .= $textSpanEnd;
                    $tempHTML .= MForms::rowClose();
                }else{
                    $tempHTML .= MForms::rowOpen(8);   // laptop gets side-by-side
                    $tempHTML .= $spinner;
                    $tempHTML .= MForms::rowNextCol(4);
                    $tempHTML .= $textSpan;
                    $tempHTML .= $lessonData['spinnertext'];
                    $tempHTML .= $textSpanEnd;
                    $tempHTML .= MForms::rowClose();
                }
            }

            $tabs['Spinner'] = $tempHTML;

        }

        $vPages = new DisplayPages();
        $vPages->lessonName = $lessonName;
        $vPages->lessonData = $lessonData;
        $vPages->style = 'test';                    // dims the words
        $vPages->layout = '1col';
        $vPages->dataParm = 'scramble';
        $vPages->controls = 'refresh.note.stopwatch.mastery.comments'; // override the default controls
        $vPages->leftWidth = 6;   // make the words a bit narrower so all these controls fit


        $vPages->above = $vPages->wordListPage();
        $vPages->aside = $vPages->masteryControls('refresh.note.stopwatch.mastery.comments', count($tabs));
        $tabs['Test'] = $vPages->render($lessonName, count($tabs));

        // printNice($tabs);

        // have tabs array set up, now render it....
        $HTML .= $views->tabs($tabs, $showTab);

        return $HTML;
    }


    function matrixPage(string $lessonName, array $lessonData, int $nTab): string
    {

        $HTML = '';


        $views = new Views();
        $tabs = [];

        // printNice($lessonData,'drillPage()');

        if ($GLOBALS['mobileDevice']) {
            $textSpan = "<span style='font-size:1.2em;'>";
        } else {
            $textSpan = "<span style='font-size:2em;'>";
        }
        $textSpanEnd = "</span>";




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
            $vPages = new DisplayPages();
            $vPages->lessonName = $lessonName;
            $vPages->HTMLContent = $content;

            if ($last == $tab)
                $vPages->controls = 'mastery'; // override the default controls

            $HTML = '';

            $HTML .=  PHP_EOL . '<div class="row">';
            $HTML .= "   <div class='col header'>";
            $HTML .= $content;
            $HTML .= '   </div>';
            $HTML .= '</div>';

            $vPages->above = $HTML;
            if ($last == $tab)
                $vPages->aside = $vPages->masteryControls('completion'); // override the default controls

            $tabs[$tab] = $vPages->render($lessonName, count($tabs));
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


    //     $HTML .= $views->tasbs($tabNames,  $tabContents);

    //     return $HTML;
    // }

    function decodablePage(string $lessonName, array $lessonData,int $showTab): string
    {
        $views = new Views();
        $tabs = [];

        printNice($lessonData, 'decodable page');

        $HTML = '';
        $views = new Views();
        $tabs = [];

        if (!isset($words['credit']))  $words['credit'] = '';
        $colour = 'colour';


        $format = serialize(['colour', [], $words['credit']]);  // default is colour, not B/W.  no phonemes are highlighted

        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $page) {
            if (isset($lessonData["words$page"])) {
                $vPages = new DisplayPages();
                $vPages->above = 'hello world above';

                $credit = '';

                $title = $lessonData["title$page"] ?? '';
                $image = $lessonData["image$page"] ?? '';
                $story = $lessonData["words$page"] ?? '';
                $vPages->above = $vPages->decodableTab($story, $image, $title, $credit);

                $tabs["Page $page"] = $vPages->render($lessonName, count($tabs));
            }
        }

        // if (!isset($words['image1']))  $words['image1'] = '';
        // if (!isset($words['image2']))  $words['image2'] = '';
        // if (!isset($words['image3']))  $words['image3'] = '';
        // if (!isset($words['image4']))  $words['image4'] = '';
        // if (!isset($words['image5']))  $words['image5'] = '';



        // for ($wordN = 1; $wordN < 10; $wordN++) {

        //     if (isset($lessonData["words{$wordN}"])) {
        //         printNice($lessonData["words{$wordN}"], "words{$wordN}");
        //         $vPages = new DisplayPages();

        //         $vPages->above = $this->decodableTab($lessonData["words{$wordN}"], $lessonData["image{$wordN}"], "Page $wordN");
        //         if ($GLOBALS['mobileDevice'])
        //             $vPages->leftWidth = 12;
        //         else
        //             $vPages->leftWidth = 5;

        //         $tabs['Instructions'] = $vPages->render($lessonName, count($tabs));
        //     }

        // }

        $HTML .= $views->tabs($tabs, $showTab);

        return $HTML;
    }


    // function decodableTab(string $text, string $image, string $page): string
    // {
    //     $wordArt = new wordArtFull;

    //     //    $image = '';
    //     //     if (isset($lessonData['image']))
    //     //         $image =  "<img src='pix/{$lessonData['image']}' style='float:right;height:200px;' />";


    //     $HTML = '';

    //     $HTML .= "<div style='float:left>";

    //     foreach (explode(' ', $text) as $word) {

    //         if (empty($word))    // skip the spaces
    //             continue;

    //         $word = strtolower($word);
    //         $HTML .= "<div style='float:left>";

    //         // $word = preg_replace('/[^a-z]+/i', '', strtolower($word));  // simplify
    //         // if (isset($festival->dictionary[$word])) {
    //         //     $aValues = unserialize($festival->dictionary[$word]);
    //         //     if (empty($aValues[DICT_FAILPHONE])) {    // if failphone is empty, then we were able to translate
    //         //         $thisphones = $festival->word2Phone($word);
    //         //         $thisphones = str_replace('/', '.', $thisphones); // erase syllable breaks
    //         //         $aThisphones = explode('.', $thisphones);  // phones in this word
    //         //         foreach ($aThisphones as $at) {
    //         //             $is_consonant = strpos(',b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,t,v,w,x,y,z,zh,kw,ks,ng,th,dh,sh,ch,', strtolower(substr($at, 1, 1)));
    //         //             if ($is_consonant == false) {
    //         //                 $phones[$at] = $at;       // creates if doesn't exist
    //         //             }
    //         //         }
    //         //     }
    //         // } else {
    //         //     array_push($fails, $word);
    //         // }
    //         // $HTML .= implode(' ', $phones) . '<br>';
    //         // $HTML .= "<span style='color:red;'>" . implode(' ', $fails) . '</span><br>';




    //         // // artwork, if provided
    //         // if (!empty($this->layout)) {
    //         //     $HTML .= "<img style='float:right;max-height=300px;' src='./images/{$this->layout}' height='300' />";
    //         // }


    //         // pre- process any post characters to remove the backslash (will be CRLFs)
    //         // if ($word == '\\') {
    //         //     $HTML .= "<br style=' clear: left;'><br style='float:left;'>";
    //         // } elseif ($word == '{') {
    //         //     $HTML .= '<b style="font-size:150%;">';
    //         // } elseif ($word == '}') {
    //         //     $HTML .= '</b><br>';
    //         // } else {

    //         //     $HTML .= "<div style='display:inline-block;padding-right:15px;border-top:0px;'>";
    //         //     if ($lookup = $wordArt->lookupDictionary($word)) {
    //         //         printNice($lookup, $word);
    //         //         $HTML .= $wordArt->render($word); // not in the list, format
    //         //     } else {
    //         //         $HTML .= strtoupper($word);
    //         //         printNice("^$word^");
    //         //     }
    //         //     $HTML .= "</div>";
    //         // }
    //     }
    //     return $HTML;
    // }
}
