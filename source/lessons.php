<?php

namespace Blending;



class DisplayPages
{


    #       +---------------------------+
    #       |           HEADER          |
    #       +------------------+--------+
    #       |                  |        |
    #       |                  |        |
    #       |     ABOVE        | ASIDE  |
    #       |                  |        |
    #       |                  +--------|
    #       |                  | LAPTOP |
    #       |                  | BELOW  |
    #       |                  |        |
    #       +------------------+--------+
    #       |       MOBILE BELOW        |
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
    var $nCols = '1col';
    var $tabName;
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

    function setupLayout($layout)
    {
        $this->nCols = $layout;
    }

    function render(string $lessonName, int $nTabs = 1): string
    {
        $HTML = '';

        if (!empty($header)) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= $this->header;
            $HTML .= MForms::rowClose();
        }

        // if ($GLOBALS['mobileDevice']) {
        //     $HTML .= MForms::rowOpen(1); // this skips over the drawer symbol on mobile
        //     $HTML .= MForms::rowNextCol($this->leftWidth - 1);
        // } else {  // not mobile, respect the request
        //     $HTML .= MForms::rowOpen($this->leftWidth);
        // }

        // $HTML .= $this->above;


        if (!empty($this->aside)) {
            $HTML .= MForms::rowOpen($this->leftWidth);
            $HTML .= $this->above;
            $HTML .= MForms::rowNextCol(12 - $this->leftWidth);  // separator but side-by-side
            $HTML .= $this->aside;      // controls beside exercise, but text below
            $HTML .= "<br>";  // reset
            $HTML .= $this->below;        // controls below exercise
            $this->below = '';            // reset below because we put it aside
            $HTML .= MForms::rowClose();
        } else {

            // no aside- for mobile use full screen, for laptop only use leftwidth, put 'below' with aside

            if ($GLOBALS['mobileDevice']) {
                $HTML .= MForms::rowOpen(1); // this skips over the drawer symbol on mobile
                $HTML .= MForms::rowNextCol(11);
                $HTML .= $this->above;
                $HTML .= MForms::rowClose();
                $HTML .= MForms::rowOpen(1); // this skips over the drawer symbol on mobile
                $HTML .= MForms::rowNextCol(11);
                $HTML .= $this->below;        // controls below exercise
                $HTML .= MForms::rowClose();
                $HTML .= MForms::rowOpen(1); // this skips over the drawer symbol on mobile
                $HTML .= MForms::rowNextCol(11);
                $HTML .= $this->aside;      // controls beside exercise, but text below
                $HTML .= MForms::rowClose();
            } else {
                $HTML .= MForms::rowOpen($this->leftWidth);
                $HTML .= $this->above;
                $HTML .= MForms::rowNextCol(12 - $this->leftWidth);  // separator but side-by-side
                $HTML .= $this->aside;      // controls beside exercise, but text below
                $HTML .= "<br>";  // reset
                $HTML .= $this->below;        // controls below exercise
                $this->below = '';            // reset below because we put it aside
                $HTML .= MForms::rowClose();
            }
        }


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


    function generate9(array $data): array
    { // split data into an array

        // first we make up the dataset.  each ROW must look like word or word/word or word/word/word


        // every display column gets its own dispenser (because we want the
        // words DOWN to have that too-random feel, not be merely random.)

        // $d = array();
        // for ($j = 0; $j < $displayColumns; $j++) { // array from 1 to n
        //     $d[$j] = new nextWordDispenser($data);
        // }


        $dispenser = new nextWordDispenser($data);
        $userWords = array();
        while (count($userWords) < 9) {
            $lastword = '';

            // absolutely don't want the same word twice
            $candidate = $dispenser->pull();


            // if we have already seen this word in our list, then try again (up to 3 times)
            // but always try to avoid reusing the last word (so no immediate repeats)
            if (array_search($candidate, $userWords) !== false) {
                $candidate = $dispenser->pull();
                if ($candidate == $lastword)
                    $candidate = $dispenser->pull();
            }
            if (array_search($candidate, $userWords) !== false) {
                $candidate = $dispenser->pull();
                if ($candidate == $lastword)
                    $candidate = $dispenser->pull();
            }

            if (array_search($candidate, $userWords) !== false) {
                $candidate = $dispenser->pull();
                if ($candidate == $lastword)
                    $candidate = $dispenser->pull();
                if ($candidate == $lastword)
                    $candidate = $dispenser->pull();
            }

            $userWords[] = $lastword = $candidate;
        }

        return ($userWords);
    }




    function wordArtColumns(array $colData): string // $colData is array of 9-element arrays
    {
        $HTML = "<table  style='width:100%;'>";
        $n = 9; // usually we have 9 elements (0 to 8)

        switch ($this->style) {
            case 'full':
                $wordArt = new wordArtColour();
                break;
            case 'simple':
                $wordArt = new wordArtSimple();
                break;
            case 'none':
                $wordArt = new wordArtNone();
                break;
            case 'test':
                $wordArt = new wordArtNone();
                $wordArt->dimmable = true;
                break;

            default:
                assertTRUE(false, "wordArt style is '{$this->style}', must be 'full', 'simple', or 'none'");
                $wordArt = new wordArtNone();
        }

        //  padding is:   top | right | bottom | left
        $tdStyle = $GLOBALS['mobileDevice'] ?
            "style='padding:0 5px 0 10px;'" :
            "style='padding:0 5px 0 50px;'";

        for ($i = 0; $i < $n; $i++) {

            $HTML .= "<tr>";
            foreach ($colData as $column) {
                $HTML .= "<td $tdStyle>";
                $HTML .= $wordArt->render($column[$i]);
                $HTML .= '</td>';
            }
            $HTML .= '</tr>';
        }
        $HTML .= '</table>';
        return $HTML;
    }




    // masteryControls uses $this->controls, eg:  'refresh.timer.comment'

    function masteryControls(string $controls, int $nTab = 1): string
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
        $watchStyle ="background-color:#ffffe0;float:left;width:$watchSize;height:$watchSize;border:solid 5px grey;border-radius:30px;";
        $timerStyle ="font-size:$fontSize;text-align:center;padding:$fontPadding;";

        if (str_contains($controls, 'refresh')) {
            $HTML .= MForms::rowOpen(3);
            $HTML .= MForms::rowNextCol(9);
            $HTML .= MForms::imageButton('refresh.png', 48, 'Refresh', 'refresh', $this->lessonName, $nTab + 1);
            $HTML .= '<br />Refresh<br /><br />';
            $HTML .= MForms::rowClose();
        }

        $HTML .= "<form>";
        $HTML .= MForms::hidden('p', 'lessonTest');
        $HTML .= MForms::cmid();  // makes moodle happy
        $HTML .= MForms::hidden('lesson', $this->lessonName);
        $HTML .= MForms::hidden('score', '0', 'score');

        if (str_contains($controls, 'stopwatch')) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= "<div style='$watchStyle'>";

            $HTML .= "<div name='timer' id='timer' style='$timerStyle'>";
            $HTML .= '0';
            $HTML .= "</div>";
            $HTML .= "</div>";

            $HTML .= "<table style='float:left;$buttonSpacing'><tr><td>";  // use table to give nice vertical spacing
            $HTML .= MForms::onClickButton('Start', 'success', "StopWatch.start");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Stop', 'danger', "StopWatch.stop");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Reset', 'secondary', "StopWatch.reset");

            $HTML .= "</td></tr></table>";
            $HTML .= MForms::rowClose();
            // $HTML .= "<br>";
        }
        // remark element
        if (str_contains($controls, 'comment')) {
            $HTML .= MForms::rowOpen($commentWidth);
            $HTML .= MForms::textarea('', 'remark', '', '', '', 3, 'Optional comment...');
            $HTML .= MForms::rowClose();
            $HTML .= "<br>";
        }


        // mastery element
        if (str_contains($controls, 'mastery')) {
            $HTML .= MForms::submitButton('Mastered', 'primary', 'mastered');
            $HTML .= MForms::submitButton('In Progress', 'warning', 'inprogress');
            $HTML .= "<br /><br />";
        }

        // completion element
        if (str_contains($controls, 'completion')) {
            $HTML .= MForms::submitButton('Completed', 'primary', 'mastered');
            $HTML .= "<br /><br />";
        }
        $HTML .= "</form>";


        if (str_contains($controls, 'decodelevel')) {
            // $HTML .= "<div style='border:solid 1px black;border-radius:15px;'>Decode Level: ";
            $HTML .= MForms::rowOpen(12);
            $HTML .= "<h4>Decode Level</h4>";
            $HTML .= MForms::badge('Plain', 'success', 'decodelevel', '0', $nTab + 1);
            $HTML .= MForms::badge('Function', 'primary', 'decodelevel', '1', $nTab + 1);
            $HTML .= MForms::badge('Blending', 'info', 'decodelevel', '2', $nTab + 1);
            $HTML .= MForms::badge('Sounds', 'warning', 'decodelevel', '3', $nTab + 1);
            $HTML .= "<br /><br />";
            $HTML .= MForms::rowClose();
        }


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


    // this should match 'decodelevel' mastery control
    function getWordArt(): object
    {
        switch ($_SESSION['decodelevel']) {
            case 0:
                $wordArt = new WordArtNone();
                break;
            case 1:
                $wordArt = new WordArtFunction();
                break;
            case 2:
                $wordArt = new WordArtSimple();
                break;
            case 3:
                $wordArt = new WordArtDecodable();
                break;
            default:
                assertTrue(false, "did not expect value '{$_SESSION['decodelevel']}' when setting decodeLevel");
                $wordArt = new WordArtDecodable();
        }
        return $wordArt;
    }

    function decodableTab(string $story, string $title = '', string $credit = ''): string
    {
        $HTML = '';

        $wordArt = $this->getWordArt();

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

        // top line is title
        $HTML .= MForms::rowOpen(12);
        $HTML .= $titleHTML;
        $HTML .= MForms::rowClose();
        $HTML .= "<br /><br />";

        $margin = $GLOBALS['mobileDevice'] ? '5px' : '18px';

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
                if ($word == "\\") {
                    $HTML .= MForms::rowClose();
                    $HTML .= MForms::rowOpen(12);
                    $HTML .= $newParagraph;
                    continue;
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

    function sentenceTab(array $sentenceArray): string
    {
        $HTML = '';

        $wordArt = $this->getWordArt();


        // moodle steals some space on the left margin for the lessontab tool
        $margin = $GLOBALS['mobileDevice'] ? '5px' : '18px';

        $floatWord = "<div style='white-space:nowrap;float:left;margin:$margin;'>";

        $linesRemaining = 9;      // assume every sentence fits on a single line.  might be messy for mobile
        foreach ($sentenceArray as $sentencepairs) {
            // maybe two sentences split with a caret
            $sentences = explode('^', $sentencepairs);
            if ($linesRemaining < count($sentences))
                break;  // quit, don't mess with hunting for a shorter one

            foreach ($sentences as $sentence) {

                $aWords = explode(' ', $sentence);

                $HTML .= MForms::rowOpen(12);
                foreach ($aWords as $word) {
                    $HTML .= $floatWord;        // <div..>
                    if (!empty(trim($word))) {

                        $wordArt->useSmallerFont = true;
                        $HTML .= $wordArt->render($word);
                    }
                    $HTML .= "</div>";   // end of floatWord div
                }
                $HTML .= MForms::rowClose();
                $linesRemaining -= 1;
            }
            $HTML .= "<hr style='border:solid 1px red;' />";
        }
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


        if ($this->random) {
            $target = array_rand($this->depleteArrays[$index], 1);
        }
        // pick an target word
        else {
            reset($this->depleteArrays[$index]);
            $target = key($this->depleteArrays[$index]);
        }

        $word = $this->depleteArrays[$index][$target];
        unset($this->indexes[$index]);
        unset($this->depleteArrays[$index][$target]);

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

    $HTML = "";

    $views = new Views();
    $HTML .= $views->navbar(['exit']);

    $sound = $views->sound('ay');

    $spelling1 = $views->spelling('ai');  // maid
    $spelling2 = $views->spelling('a_e');  // make
    $spelling3 = $views->spelling('ea');   // break

    $eeSound = $views->sound('ee');
    $eeSpelling = $views->spelling('ee');

    $intro =
        "***
        This is an ***EMERGENCY*** intervention for an older \
        student still reading at a grade-1/2 level.  The purpose of these drills is to help a student \
        transition from using memorized words and guessing to sounding out.  It requires a one-on-one tutor and daily drills and \
        practice.";

    $data = [
        [
            "blending", "Blending", 'fathatsat.png',
            "***Start with BLENDING*** if your student barely reads, or guesses from context or first-letters.

             **BLENDING** is a focused attack for building phonological skills using the five short vowels. It drills blending and segmentation, \
                    and retrains first-letter readers to look at all the letters.  **BLENDING** also introduces function words, basic morphology, decodable texts, and writing skills.  Future modules   will build on these skills.

                    Not sure?  Start with **BLENDING** anyhow. It will be quickly obvious if your student needs \
                    this or not.  For a severe-deficit reader, **BLENDING** usually requires betwen 4 and 6 weeks of daily practice to complete.",
        ],
        [
            "phonics", "Phonics", "phonics.png",
            "**PHONICS** is the two-way mapping of spoken sounds to written spellings.  Most students can learn phonics just by practicing reading, but time is short and your student is behind.  \
             Use these drills and decodable texts to organize your student's understanding of phonics and accelerate learning..

             In the word 'maid', the sound %% sound('ay') %% has the spelling %% spelling('ai') %%. That same sound is spelled differently in 'bake', 'tray', 'break', 'taste, 'eight', 'straight', and other words. \
             The good news is that only the 17 vowel sounds pose any difficulty."
        ],
        [
            "decodable", "Decodable<br>Stories", "decodable.png",
            "<p>These stories use the decoding hints developed in BLENDING and PHONICS, which
                    can be turned down as your student becomes more confident.  </p>
            <p>Older students often resist reading 'baby books' only to get frustrated with harder texts
                    that they cannot yet decode.  These assists help an older
                    student succeed with more complex stories."
        ],
        [
            "spelling", "<br>Spelling", "accounting.jpg",
            "<p>Spelling....</p>",
        ],

    ];

    $HTML .= $GLOBALS['mobileDevice'] ? MForms::rowOpen(12) : MForms::rowopen(10);
    $HTML .= MForms::markdown($intro);
    $HTML .= MForms::rowClose();
    $HTML .= "<hr>";
    foreach ($data as $course) {

        assert(in_array($course[0], $GLOBALS['allCourses']), 'sanity check - unexpected courses?');

        // the button is SAFE because no user input.  But still
        $href = "window.location.href='" . MForms::linkHref('selectCourse', htmlentities($course[0])) . "'";

        $HTML .= $GLOBALS['mobileDevice'] ? MForms::rowOpen(6) : MForms::rowopen(2);
        $HTML .= "<button onclick=$href type='button' class='btn btn-light btn-outline btn-lg'>";
        $HTML .= "   <h1 style='color:darkblue;font-weight:900;transform: scaleX(0.80) translateZ(0);text-shadow: 0.125em 0.125em #C0C0C0;'><i>{$course[1]}</i></h1>";
        $HTML .= '</button>';
        $HTML .= $GLOBALS['mobileDevice'] ? MForms::rowNextCol(6) : MForms::rowNextCol(2);
        $HTML .= MForms::htmlUnsafeElement('img', '', [
            'src'=>"pix/{$course[2]}",
            'width'=> '150px',
        ]);

        $HTML .= $GLOBALS['mobileDevice'] ?  MForms::rowClose() . MForms::rowOpen(12) : MForms::rowNextCol(6);

        $HTML .= MForms::markdown($course[3]);
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
        // might use the default

        assert(in_array($course, $GLOBALS['allCourses']), "sanity check - unexpected course '' ?");
        require_once("courses/$course.php");

        $this->course = 'Blending\\' . ucfirst(($course));
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
        // printNice($nextLesson, "next lesson");

        return $nextLesson;     // empty string if no next lesson
    }




    function render(string $lessonName, int $showTab = 1): string
    {
        // printNice("function render(string $lessonName, nTab $showTab): string");


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
                    $HTML .= $this->decodablePage($lessonName, $lessonData, $showTab);
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
        $HTML = '';

        $views = new Views();
        $tabs = [];


        if ($GLOBALS['mobileDevice']) {
            $textSpan = "<span style='font-size:1.2em;'>";
        } else {
            $textSpan = "<span style='font-size:2em;'>";
        }
        $textSpanEnd = "</span>";


        if (isset($lessonData['instruction'])) {
            $vPages = new DisplayPages();

            // $vPages->above = $textSpan . $lessonData['instruction'] . $textSpanEnd;
            $vPages->above =  MForms::markdown($lessonData['instruction']);
            if ($GLOBALS['mobileDevice'])
                $vPages->leftWidth = 12;
            else
                $vPages->leftWidth = 6;

            $tabs['Instructions'] = $vPages->render($lessonName, $showTab);
        }



        if (isset($lessonData['pronounce'])) {
            $vPages = new DisplayPages();


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

            if (isset($lessonData['stretchText'])) {
                $vPages->below .= $textSpan . $lessonData['stretchText'] . "<br /><br />" . $textSpanEnd;
            }
            $stretchText = "Contrast the sounds across the page. Ask the student to exaggerate the sounds and feel the difference in their mouth.<br><br>
                If your student struggles, review words up and down, and then return to contrasts across.<br><br>";

            $vPages->below .= $textSpan . $stretchText . $textSpanEnd;


            $vPages->style = 'simple';
            $vPages->colSeparator = '&#11020;';

            if ($GLOBALS['mobileDevice'])
                $vPages->leftWidth = 8;   // make the words a bit narrower
            else
                $vPages->leftWidth = 4;


            $data9 = $vPages->generate9([$lessonData['stretch']]); // split data into an array
            // stretch must keep col1 and col2 synchronized
            $col1 = [];
            $col2 = [];
            for ($i = 0; $i < 9; $i++) {
                $stretch = explode('/', $data9[$i]);
                $col1[] = $stretch[0];
                $col2[] = $stretch[1];
            }

            $vPages->above = $vPages->wordArtColumns([$col1, $col2]);

            $tabs['Stretch'] = $vPages->render($lessonName, count($tabs));
        }



        // list of words with vowel highlighted
        $vPages = new DisplayPages();
        $vPages->lessonName = $lessonName;
        $vPages->lessonData = $lessonData;
        $vPages->style = 'simple';
        $vPages->aside = $vPages->masteryControls('refresh', count($tabs));
        if (!$GLOBALS['mobileDevice'])
            $vPages->leftWidth = 4;   // make the words a lot narrower

        $data9 = $vPages->generate9($lessonData['words']); // split data into an array
        $vPages->above = $vPages->wordArtColumns([$data9]);

        if (isset($lessonData['sidenote'])) {
            $vPages->aside .= $textSpan . $lessonData['sidenote'] . $textSpanEnd;
        }
        $tabs['Words'] = $vPages->render($lessonName, count($tabs));



        // scramble of plain words
        $vPages = new DisplayPages();
        $vPages->lessonName = $lessonName;
        $vPages->lessonData = $lessonData;
        $vPages->style = 'none';
        if (isset($lessonData['layout']))
            $vPages->nCols = $lessonData['layout'];   // override?
        $vPages->aside = $vPages->masteryControls('refresh', count($tabs));


        $col1 = $vPages->generate9($lessonData['words']); // split data into an array
        $col2 = $vPages->generate9($lessonData['words']); // split data into an array
        $col3 = $vPages->generate9($lessonData['words']); // split data into an array

        $hide3rdColumn = strlen($col1[0]) > 4 and strlen($col2[1]) > 4;  // first two words are tested
        if ($hide3rdColumn)
            $vPages->above = $vPages->wordArtColumns([$col1, $col2]);
        else
            $vPages->above = $vPages->wordArtColumns([$col1, $col2, $col3]);


        if ($GLOBALS['mobileDevice']) {
            $vPages->leftWidth = 10;   // make the words a bit narrower
        } else {
            $vPages->leftWidth = 6;   // make the words a bit narrower
        }

        if (!empty($lessonData['scrambleSideNote'])) {
            $vPages->below .= $textSpan;
            $vPages->below .= $lessonData['scrambleSideNote'];
            $vPages->below .= $textSpanEnd;
        }

        $tabs['Scramble'] = $vPages->render($lessonName, count($tabs));



        if (isset($lessonData['wordsplus'])) {
            // scramble of plain words
            $vPages = new DisplayPages();
            $vPages->lessonName = $lessonName;
            $vPages->lessonData = $lessonData;
            $vPages->style = 'none';
            if (isset($lessonData['layout']))
                $vPages->nCols = $lessonData['layout'];   // override?
            $vPages->aside = $vPages->masteryControls('refresh', count($tabs));

            $col1 = $vPages->generate9($lessonData['wordsplus']);
            $col2 = $vPages->generate9($lessonData['wordsplus']);
            $col3 = $vPages->generate9($lessonData['wordsplus']);
            $vPages->above = $vPages->wordArtColumns([$col1, $col2, $col3]);

            if (isset($lessonData['sidenoteplus'])) {
                $vPages->aside .= $textSpan . $lessonData['sidenoteplus'] . $textSpanEnd;
            }


            if ($GLOBALS['mobileDevice']) {
                $vPages->leftWidth = 10;   // make the words a bit narrower
            } else {
                $vPages->leftWidth = 6;   // make the words a bit narrower
            }

            // if (!empty($lessonData['scrambleSideNote'])) {
            //     $vPages->below .= $textSpan;
            //     $vPages->below .= $lessonData['scrambleSideNote'];
            //     $vPages->below .= $textSpanEnd;
            // }

            $tabs['Plus'] = $vPages->render($lessonName, count($tabs));
        }


        if (isset($lessonData['spinner'])) {
            $tempHTML = '';
            $spinner = $views->wordSpinner($lessonData['spinner'][0], $lessonData['spinner'][1], $lessonData['spinner'][2]);
            if (!isset($lessonData['spinnertext'])) {  // easy case, no text
                $tempHTML .= $spinner;
            } else {

                // complicate case, need to add instructions
                if ($GLOBALS['mobileDevice']) {
                    $tempHTML .= MForms::rowOpen(12);   // phone gets top-and-bottom
                    $tempHTML .= $spinner;
                    $tempHTML .= MForms::rowClose();
                    $tempHTML .= MForms::rowOpen(12);
                    $tempHTML .= $textSpan;
                    $tempHTML .= $lessonData['spinnertext'];
                    $tempHTML .= $textSpanEnd;
                    $tempHTML .= MForms::rowClose();
                } else {
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


        // This is roughly the same code as function decodablePage() (stories without a wordlist)
        // so maybe time to refactor some more
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $page) {
            if (isset($lessonData["words$page"])) {
                $vPages = new DisplayPages();
                $vPages->above = '';

                $credit = '';

                $title = $lessonData["title$page"] ?? '';
                $image = $lessonData["image$page"] ?? '';
                $story = $lessonData["words$page"] ?? '';
                $note = $lessonData["note$page"] ?? '';


                $vPages->lessonName = $lessonName;


                // put the image on the right (or below on mobile)
                $vPages->below = '';

                $vPages->below .=  $vPages->masteryControls('decodelevel', count($tabs));

                if (!empty($image)) {
                    if ($GLOBALS['mobileDevice']) {
                        $vPages->above .= MForms::rowOpen(12);
                        $vPages->above .=  "<img style='width:70%'; src='pix/$image' />";
                        $vPages->above .= '<br /><br />';
                        $vPages->above .= MForms::rowClose();
                    } else {
                        $vPages->below .= MForms::rowOpen(12);
                        $vPages->below .=  "<img style='width:70%'; src='pix/$image' />";
                        $vPages->below .= '<br /><br />';
                        $vPages->below .= MForms::rowClose();
                    }
                }

                $vPages->above .= $vPages->decodableTab($story, $title, $credit);

                if (!empty($note)) {
                    $vPages->below .= $textSpan;
                    $vPages->below .= $note;
                    $vPages->below .= $textSpanEnd;
                }


                $tabName = empty($title) ? "Page $page" : $title;
                $tabs[$tabName] = $vPages->render($lessonName, $showTab);
            }
        }


        if (isset($lessonData["sentences"])) {
            $vPages = new DisplayPages();
            $vPages->lessonName = $lessonName;
            $vPages->lessonData = $lessonData;

            $vPages->below .=  $vPages->masteryControls('decodelevel', count($tabs));
            $vPages->above .= $vPages->sentenceTab($lessonData["sentences"]);
            $tabs['Sentences'] = $vPages->render($lessonName, $showTab);
        }

        // finally the 'test' tab
        $vPages = new DisplayPages();
        $vPages->lessonName = $lessonName;
        $vPages->lessonData = $lessonData;
        $vPages->style = 'test';                    // dims the words
        $vPages->controls = 'refresh.note.stopwatch.mastery.comments'; // override the default controls
        $vPages->leftWidth = 6;   // make the words a bit narrower so all these controls fit


        if (isset($lessonData['wordsplus']))
            $words = $vPages->generate9($lessonData['wordsplus']);
        else
            $words = $vPages->generate9($lessonData['words']);


        $vPages->above = $vPages->wordArtColumns([$words]);


        $vPages->aside = $vPages->masteryControls('refresh.note.stopwatch.mastery.comments', count($tabs));
        if (isset($lessonData['testtext'])) {
            $vPages->below .= $textSpan;
            $vPages->below .= $lessonData['testtext'];
            $vPages->below .= $textSpanEnd;
        }
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

        $dispPages = new DisplayPages();

        $data9 = $dispPages->generate9($layout, $data); // split data into an array

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



    function decodablePage(string $lessonName, array $lessonData, int $showTab): string
    {
        $views = new Views();
        $tabs = [];

        if ($GLOBALS['mobileDevice']) {
            $textSpan = "<span style='font-size:1.2em;'>";
        } else {
            $textSpan = "<span style='font-size:2em;'>";
        }
        $textSpanEnd = "</span>";

        $HTML = '';
        $views = new Views();
        $tabs = [];


        if (isset($lessonData['instructions'])) {
            $vPages = new DisplayPages();

            $vPages->above = $textSpan . $lessonData['instructions'] . $textSpanEnd;
            if ($GLOBALS['mobileDevice'])
                $vPages->leftWidth = 12;
            else
                $vPages->leftWidth = 6;

            $tabs['Instructions'] = $vPages->render($lessonName, $showTab);
        }


        if (!isset($words['credit']))  $words['credit'] = '';
        $colour = 'colour';


        $format = serialize(['colour', [], $words['credit']]);  // default is colour, not B/W.  no phonemes are highlighted

        // first, get the last page (might skip one)
        $lastPage = 0;
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $page) {
            $lastPage = isset($lessonData["words$page"]) ? $page : $lastPage;
        }


        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $page) {
            if (isset($lessonData["words$page"])) {
                $vPages = new DisplayPages();
                $vPages->above = '';

                $credit = '';

                $title = $lessonData["title$page"] ?? '';
                $image = $lessonData["image$page"] ?? '';
                $story = $lessonData["words$page"] ?? '';
                $note = $lessonData["note$page"] ?? '';

                $vPages->lessonName = $lessonName;
                $vPages->above = $vPages->decodableTab($story, $title, $credit);

                // put the image on the right (or below on mobile)
                $vPages->below = '';

                $vPages->below .=  $vPages->masteryControls('decodelevel', count($tabs));
                if ($page == $lastPage) {
                    $vPages->below .=  $vPages->masteryControls('completion');
                }
                if (!empty($image)) {
                    $vPages->below .= MForms::rowOpen(12);
                    $vPages->below .=  "<img style='width:70%'; src='pix/$image' />";
                    $vPages->below .= '<br /><br />';
                    $vPages->below .= MForms::rowClose();
                }

                if (!empty($note)) {
                    $vPages->below .= $textSpan;
                    $vPages->below .= $note;
                    $vPages->below .= $textSpanEnd;
                }

                $tabName = empty($title) ? "Page $page" : $title;
                $tabs[$tabName] = $vPages->render($lessonName, $showTab);
            }
        }
        $HTML .= $views->tabs($tabs, $showTab);
        return $HTML;
    }
}
