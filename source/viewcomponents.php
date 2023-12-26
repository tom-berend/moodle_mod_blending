<?php

namespace Blending;



/****************
 * CC BY-NC-SA 4.0
 * Attribution-NonCommercial-ShareAlike 4.0 International
 *
 * This license requires that reusers give credit to the creator. It allows
 * reusers to distribute, remix, adapt, and build upon the material in any
 * medium or format, for noncommercial purposes only. If others modify or
 * adapt the material, they must license the modified material under identical terms.
 *
 * BY: Credit must be given to the Community Reading Project, who created it.
 *
 * NC: Only noncommercial use of this work is permitted.
 *
 *     Noncommercial means not primarily intended for or directed towards commercial
 *     advantage or monetary compensation.
 *
 * SA: Adaptations must be shared under the same terms.
 *
 * see the license deed here:  https://creativecommons.org/licenses/by-nc-sa/4.0
 *
 ******************/



// these are tools and components used in BlendingViews
class ViewComponents
{

    // this nag message uses a tiny bit of JS
    function fullScreenSuggestion()
    {
        $HTML = '';
        if (!$GLOBALS['mobileDevice']) {
            if (!isset($_SESSION['FullScreenSuggestion'])) {
                $HTML .= "<div id='fullScreenMessage'></div>";
                $HTML .= "\n<script>if (window.innerHeight == screen.height) {
                            \n   console.log('FULL SCREEN');
                            \n  } else {
                            \n      console.log('NORMAL SCREEN');
                            \n      let msg = \"<div style='background-color:#FFF380;text-align:center'><p>For a better experience, set your Browser to 'fullscreen' mode.  On Windows press <code>F11</code>.  On Mac click green circle and select 'Enter Full Screen'</p></div>\";
                            \n      document.getElementById('fullScreenMessage').innerHTML += msg;
                            \n}</script>";

                $_SESSION['FullScreenSuggestion'] = true;
            }
        }
        return $HTML;


        if (!isset($_SESSION['FullScreenSuggestion'])) {
            $HTML .= "<script>";
            $HTML .= "if (!!(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement)) {
                     alert(\"For a better experience, set your Browser to 'fullscreen' mode (F11)\");
                    //  return true;
                }";
            $HTML .= "</script>";
            $_SESSION['FullScreenSuggestion'] = true;
        }
        return $HTML;
    }

    function navbar(array $options, $title = ''): string
    {
        // printNice($options, "Navbar(title=$title)");
        $HTML = '';
        $HTML .= MForms::rowOpen(12);

        $navHeight = $GLOBALS['mobileDevice'] ? '30px' : '45px';
        $HTML .= "<div style='height:$navHeight;background-color:#ffe5b4;'>";

        $buttons = '';
        if (in_array('exit', $options)) {
            if ($GLOBALS['mobileDevice']) {
                $buttons .= MForms::badge('exit', 'warning', 'showStudentList');
            } else {
                $buttons .= MForms::button('exit', 'warning', 'showStudentList');
            }
        }

        if (in_array('addStudent', $options)) {
            if ($GLOBALS['mobileDevice']) {
                $buttons .= MForms::badge('addstudent', 'info', 'showAddStudentForm');
            } else {
                $buttons .= MForms::button('addstudent', 'info', 'showAddStudentForm');
            }
        }


        if (in_array('next', $options)) {
            if ($GLOBALS['mobileDevice']) {
                $buttons .= MForms::badge('next', 'primary', 'AddStudentList');
            } else {
                $buttons .= MForms::button('next', 'primary', 'AddStudentList');
            }
        }

        if (in_array('navigation', $options)) {
            if ($GLOBALS['mobileDevice']) {
                $buttons .= MForms::badge('exit', 'warning', 'selectCourse');
                $buttons .= MForms::badge('next', 'info', 'next');
                $buttons .= MForms::badge('navigate', 'info', 'navigation');
            } else {
                $buttons .= MForms::button('exit', 'warning', 'selectCourse');
                $buttons .= MForms::button('next', 'info', 'next');
                $buttons .= MForms::button('navigate', 'info', 'navigation');
                $buttons .= "<button type='button' class='btn btn-lg'>&nbsp;&nbsp;&nbsp;$title</button>";
            }
        }

        $HTML .= "<div style='float:left;'>$buttons</div>";


        // $version = get_config('mod_blending')->release;
        $version = '';
        $aboutText =
            "<table clas='table'><tr><td>Version</td><td>$version</td></tr></table>";


        $views = new Views();
        $aboutButton = $views->modalAboutButton($GLOBALS['mobileDevice']);

        //('About','warning','About This Module',$aboutText,true,'',$GLOBALS['mobileDevice']);
        // $aboutButton = $views->about();

        if ($GLOBALS['debugMode']) {  // only available in testing, not in production
            $debugButtons = MForms::badge('dictionary', 'warning', 'generateDictionary');
            if (in_array('navigation', $options))   // only works where navigation is available
                $debugButtons .= MForms::badge('lessons', 'warning', 'navigation', 'debug');
            $HTML .= "<div style='float:right;'>$debugButtons</div>";
        }

        $HTML .= "<div style='float:right;'>$aboutButton</div>";
        $HTML .= "</div>";
        $HTML .= MForms::rowClose();



        $HTML .= "<div style='padding-bottom:5px;'>";   // small gap between navbar and tab buttons
        $HTML .= "</div>";

        return $HTML;
    }



    // $tabs are in form ['name'=>'content', ...]
    function tabs(array $tabs, int $showTab = 1): string
    {

        if ($GLOBALS['mobileDevice']) {
            $btnStyle = "style='font-size:15px;padding: 4px 4px;'";
        } else {
            $btnStyle = "style='font-size:20px;padding: 6px 8px;'";
        }

        $HTML = '';
        $HTML .= MForms::rowOpen(12);
        $HTML .= "<ul class='tab'>";

        $HTMLpage = '';
        $HTMLpage .= MForms::rowOpen(12);

        $i = 1;
        foreach ($tabs as $key => $value) {
            $key = str_replace('<', '', $key);        // affixes leak through from wordart
            $key = str_replace('>', '', $key);        // don't want &gt;, just erase them.
            $key = str_replace('`', '', $key);        // backtick will mess up key, not needed
            $key = htmlentities($key);

            // tab value  (class 'tablinks' and 'tabcontent' is how JS finds us)
            $active = ($i == $showTab) ? ' active' : '';

            $HTML .= "<li><a href='#' class='Btablinks $active' $btnStyle onclick='window.blendingTabButton(event, `$key`)'>$key</a></li>";

            // page value
            $active = ($i == $showTab) ? 'display:block;' : 'display:none;';
            $HTMLpage .= "<div id='$key'  style='$active' class='Btabcontent'>$value</div>";

            $i++;
        }
        $HTML .= "</ul>";
        $HTML .= MForms::rowclose();

        $HTMLpage .= MForms::rowclose();

        if ($GLOBALS['debugMode']) {
            require_once('source/htmltester.php');
            $HTMLTester = new HTMLTester();
            $HTMLTester->validate($HTML . $HTMLpage);
        }

        return ($HTML . $HTMLpage);   // combine tab and page
    }


    ///////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////

    function accordian(array $tabs, bool $debug = false): string
    {

        $HTML = '';

        // convert to two arrays (TODO: just process in combo form)
        $tabNames = [];
        $tabContents = [];

        foreach ($tabs as $key => $value) {
            $tabNames[] = $key;
            $tabContents[] = $value;
        }



        $HTML .= "<div id='accordion'>";

        for ($i = 0; $i < count($tabNames); $i++) {

            $HTML .= "
          <div class='card'>
            <div class='card-header' style='max-height:60px;padding:3px;' id='heading$i'>
              <h5 class='mb-0'>
                <button  style='text-decoration: none;' class='btn btn-sm btn-link' data-toggle='collapse' data-target='#collapse$i' aria-expanded='false' aria-controls='collapse$i' >
                  <h5>{$tabNames[$i]}</h5>
                </button>
              </h5>
            </div>
            <div id='collapse$i' class='" . ($debug ? 'collapse.show' : 'collapse') . "' aria-labelledby='heading$i' data-parent='#accordion'>
              <div class='card-body'>
                  {$tabContents[$i]}
              </div>
            </div>
          </div>
          ";
        }
        $HTML .= "</div>";  // id=accordian


        return $HTML;
    }


    // an accodian for a specific student (marks off what he has mastered)
    function lessonAccordian(int $studentID, string $course, $debug = false): string
    {
        $views = new Views();

        $lessonCounter = 1;
        $counter = 0;

        $tabs = [];     // final product
        $tabsWithCurrent = [];

        $lessons = new Lessons($course);  // 'blending' becomes 'Blending'
        $lessonsByGroup = $lessons->getLessonsByGroups();

        // get the ones that have been mastered
        $logTable = new LogTable();
        $allMastered = $logTable->getAllMastered($studentID);  //[id, lesson]

        $mastered = [];
        foreach ($allMastered as $m) {
            $mastered[] = $m->lesson;           // create array of mastered lesson keys
        }

        // printNice($mastered, 'allMastered');     // array


        // now create the accordian
        // first pass creates an string of  tablenames table in each $tab entry
        foreach ($lessonsByGroup as $lessonName => $group) {
            if (!isset($tabs[$group]))
                $tabs[$group] = '';
            $tabs[$group] .= $lessonName . '$$';  // use $$ as delimiter
        }

        $unicode = [
            'notYet' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            'mastered' => '&#x2705;',
            'current' => '&#9654;'
        ];

        // second pass expands the $$ string to a table of entries
        $isCurrentInGroup = false;
        $anyMissingInGroup = false;

        $course =  "Blending\\{$_SESSION['currentCourse']}";   // namespace trickery
        $lessonTable = new $course;

        foreach ($tabs as $group => $lessons) {
            $entries = explode('$$', $lessons);   // within a tab, lessons are a string first$$second$$third
            $display = "<table class='table table-sm'>";
            foreach ($entries as $entry) {  // there is an empty one at the end

                if (!empty($entry)) {
                    // put up mastery symbol (no, yes, current)

                    $lesson = $lessonTable->clusterWords[$entry];

                    $display .= "<tr>";
                    if (!in_array($entry, $mastered)) {
                        $masterySymbol = $unicode['notYet'];
                        $anyMissingInGroup = true;
                    } else {
                        $masterySymbol = $unicode['mastered'];
                    }

                    // $masterySymbol = (in_array($entry, $mastered)) ? $unicode[1] : $unicode[0];
                    if ($entry == $_SESSION['currentLesson']) {    // special case for current lesson
                        $isCurrentInGroup = true;
                        $masterySymbol = $unicode['current'];
                    }

                    $display .= "<td>$masterySymbol</td>";

                    $display .= "<td>$lessonCounter</td>";
                    $lessonCounter += 1;

                    $link = MForms::htmlUnsafeElement(
                        "a",
                        $entry,
                        [
                            'href' => MForms::linkHref('renderLesson', $entry),
                            'class' => 'link-underline-primary',            // blue underline
                        ]
                    );

                    $display .= "<td>$link</td>";

                    // show if there is a story
                    $display .= (isset($lesson['title1'])) ? "<td>{$lesson['title1']}</td>" : "<td></td>";
                    $display .= (isset($lesson['title2'])) ? "<td>{$lesson['title2']}</td>" : "<td></td>";
                    $display .= (isset($lesson['title3'])) ? "<td>{$lesson['title3']}</td>" : "<td></td>";



                    // if ($debug) {           // makes editing the lessons easier
                    //     $display .= "<td>$lessonCounter</td>";
                    //     $lessonCounter += 1;
                    //     // show what the lesson is
                    //     $display .= (isset($lesson['words'][0])) ? "<td>" . substr($lesson['words'][0], 0, 30) . "</td>" : "<td></td>";
                    // }

                    $display .= "</tr>";
                }
            }
            $display .= "</table>";

            // using &nbsp; is really sloppy, but don''t want fancy HTML in array keys
            $currentPlusGroup = $unicode['notYet'];    // ugly, but simple
            if (!$anyMissingInGroup)
                $currentPlusGroup = $unicode['mastered'];
            if ($isCurrentInGroup)  // overrides
                $currentPlusGroup = $unicode['current'];

            $currentPlusGroup .= '&nbsp;&nbsp;&nbsp;&nbsp;';   // add a bit of space

            $tabsWithCurrent[$currentPlusGroup . $group] = $display;  // replace $$ string with table html

            $isCurrentInGroup = false;      // reset for next group
            $anyMissingInGroup = false;
        }

        return $this->accordian($tabsWithCurrent, $debug);
    }




    /////////////////////////////////////////
    /////// word spinner ////////////////////
    /////////////////////////////////////////


    function wShelper1(string $position, string $letter, int $stretch = 1): string
    {
        $color = ($position == 'v') ? 'danger' : 'primary';


        if ($GLOBALS['mobileDevice']) {
            $style = "style='min-width:18px;font-size:18px;font-family:muli,monospace;text-align:center;border-radius:6px;'";
            $padding = "2px";
            $btnSize = 'sm';
        } else {
            $style = "style='min-width:70px;font-size:48px;font-family:muli,monospace;text-align:center;border-radius:10px;'";
            $padding = "15px";
            $btnSize = 'lg';
        }

        $colspan = ($stretch == 1) ? '' : "colspan=$stretch";
        // $extraClass = 'sp_word ';

        // allow a blank key with '@'
        $display = ($letter == '@') ? '&nbsp;' : $letter;
        $keyvalue = ($letter == '@') ? ' ' : $letter;

        $onClick = '';
        if ($stretch == 1) // not for titles
            $onClick  = "onclick= \"wordSpinner('$position','$keyvalue');\"";

        $HTML =  '';
        $HTML .= "<td style='padding:$padding;text-align:center;' $colspan>";
        $HTML .= "<button type='button' class='btn btn-$color btn-$btnSize'  $style $onClick>$display</button>";
        $HTML .= "</td>";

        return $HTML;
    }

    function wordSpinner(string $pre, string $vow, string $suf, int $affixWidth = 4, bool $plusE = false): string
    {
        if ($GLOBALS['mobileDevice'])   // looks nicer on laptop with wider keyboard
            $affixWidth = 3;
        else
            $affixWidth = 4;


        $plusE = false; // if true then we spin a_e (eg: cake)
        $spinnerName = ''; // name of this spinner (passed by caller)

        $HTML = '';
        $HTML .= MForms::rowOpen(12);
        if ($GLOBALS['mobileDevice'])   // looks nicer on laptop with wider keyboard
            $fontSize = 'font-size:300%';
        else
            $fontSize = 'font-size:1000%;';

        $HTML .= "<br />
                  <span class='sp_spell' style='line-height:150%;{$fontSize};' id='spin0'>
                    </span><br />";
        $HTML .= MForms::rowClose();


        if ($GLOBALS['mobileDevice']) {
            $HTML .= "<table style = 'width:100%; max-width:400px;' class='table'>";
        } else {
            $HTML .= "<table style = 'width:100%; max-width:800px;' class='table'>";
        }


        $HTML .= "<tr>";
        $HTML .= $this->wShelper1('p', 'Begin',  $affixWidth);
        $HTML .= $this->wShelper1('v', 'Vowels',  2);
        $HTML .= $this->wShelper1('s', 'End',  $affixWidth);
        $HTML .= "</tr>";

        // prefixes go in column 1-2-3-4
        // vowels go in column 6-7
        // suffixes go in column 9-10,11,12

        $prefixes = explode(',', $pre);
        $vowels = explode(',', $vow);
        $suffixes = explode(',', $suf);

        // decide which form of word spinner to use
        $jsFunc = 'MathcodeAPI.wordSpinner';
        if ($plusE) {
            $jsFunc = 'MathcodeAPI.wordSpinnerPlusE';
        }


        while (count($prefixes) > 0 or count($vowels) > 0 or count($suffixes) > 0) {

            $button = array();

            for ($i = 0; $i < $affixWidth; $i++) { // work on prefixes
                $top = array_shift($prefixes); // grab the FIRST one
                if (!empty($top)) {
                    $button[] = $this->wShelper1('p', $top);
                } else {
                    $button[] = '<td></td>';
                }
            }

            $button[] = ''; // first and last is always a spacer
            for ($i = 0; $i < 2; $i++) { // work on vowels

                $top = array_shift($vowels); // grab the FIRST one
                if (!empty($top)) {
                    $button[] = $this->wShelper1('v', $top);
                } else {
                    $button[] = '<td></td>';
                }
            }

            $button[] = ''; // first and last is always a spacer

            for ($i = 0; $i < $affixWidth; $i++) { // work on prefixes
                $top = array_shift($suffixes); // grab the FIRST one
                if (!empty($top)) {
                    $button[] = $this->wShelper1('s', $top);
                } else {
                    $button[] = '<td></td>';
                }
            }


            // now output this line...
            $HTML .= "<tr>";
            for ($i = 0; $i < (2 * $affixWidth) + 4; $i++) {
                // $HTML .= $button[$i];
                // $bkgnd = ($i==4 or $i==5) ?'#FFFFE0':'#E0FFFF';
                $HTML .= "{$button[$i]}";
            }
            $HTML .= "</tr>";
        }



        $HTML .= '</table>';

        if ($GLOBALS['debugMode']) {
            require_once('source/htmltester.php');
            $HTMLTester = new HTMLTester();
            $HTMLTester->validate($HTML);
        }

        return $HTML;
    }


    // for a disabled button, leave name empty
    function submitButton(string $text, string $color, string $name = '', bool $solid = true, string $onClick = '', $extraStyle = '', $title = '')
    {
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));
        $HTML = '';

        $myTitle = empty($title) ? '' : " title='" . $title . "'";
        $n = (empty($name)) ? 'disabled="disabled"' : "name='" . $name . "'"; // if no name, then disable button

        $size = 'btn-sm';
        if ($extraStyle == 'btn-lg')
            $size = $extraStyle;

        $buttonClass = "$size btn-" . (($solid) ? '' : 'outline-') . "$color";

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"return confirm('{$onClick} -Are you sure?')\"";
        }

        $HTML = "<button type='submit' aria-label='$text' $myTitle class='$buttonClass rounded' $n $confirm style='margin:3px;{$extraStyle}'>$text</button>";
        $HTML .= MForms::cmid();

        return ($HTML);
    }


    function accordianButton(string $text): string
    {
        $HTML = '';

        $HTML .=  MForms::button('test', 'primary', 'renderLesson', $text, '');  // key is also index to lesson

        $HTML .= "<button type='button'
                          style='font-family:sans-serif;
                                white-space: nowrap;
                                font-size:130%;
                                background:#ffeeff;
                                box-shadow: 4px 4px;
                                border:solid 1px black;
                                border-radius:5px;'>
                                &nbsp;$text&nbsp;
                    </button>";


        return $HTML;
    }

    function sound($text): string
    {
        printNice('dont use SOUND anymore');
        $HTML = '';
        return $HTML;
    }
    function spelling($text): string
    {
        printNice('dont use SPELLING anymore');

        $HTML = '';
        return $HTML;
    }

    function about(): string
    {
        $HTML = '';

        $HTML = MForms::modalButton('About', 'warning', 'About Blending', 'about text');

        return $HTML;
    }

    // Be careful here.
    static function modalAboutButton(bool $isBadge = false)
    {
        $HTML = '';
        $bakery = 'Modal' . MForms::bakeryTicket();       // unique number
        $title = 'About BLENDING';
        $buttonText = 'About';

        // button that launches
        $HTML .= MForms::htmlUnsafeElement(
            "a",
            $buttonText,
            [
                'data-toggle' => 'modal',
                'data-target' => "#{$bakery}",
                'style' => ($isBadge) ? MForms::$badgeStyle : MForms::$buttonStyle,
                'class' => (($isBadge) ? 'badge' : 'button') . " btn btn-sm btn-warning",
                'aria-label' => $title,
                'title' => $title,
            ]
        );


        $HTML .= "<!-- Modal -->";
        $HTML .= "<div class='modal fade' id='$bakery' tabindex='-1' role='dialog' aria-labelledby='$bakery' aria-hidden='true'>";
        $HTML .= "  <div class='modal-dialog' role='document'>";
        $extraStyle = ($GLOBALS['mobileDevice']) ? 'width:100%;' : 'min-width:600px;';
        $HTML .= "    <div class='modal-content' style='$extraStyle'>";  // added style here
        $HTML .= "      <div class='modal-header'>";
        $HTML .= "        <h5 class='modal-title' id='{$bakery}label'>$title</h5>";
        $HTML .= "        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        $HTML .= "          <span aria-hidden='true'>&times;</span>";
        $HTML .= "        </button>";
        $HTML .= "      </div>";
        $HTML .= "      <div class='modal-body'>";

        $HTML .= "<table class='table'>";

        $HTML .= "<tr><td colspan = 2>";
        $HTML .= "<img style='width:90%;float:left;' src='pix/toolsforstrugglingreaders.png'>";
        $HTML .= "<span style='font-size:10px;float:left;'>" . MForms::ccAttribution('Reading Man with Glasses', 'https://commons.wikimedia.org/wiki/File:Nlyl_reading_man_with_glasses.svg', 'nynl', '', 'CC0', '1.0') . "</span>";


        $message = "Interactive blending and phonics for tutor-led <b>intensive</b> interventions for older students reading at grade-1 or -2 level.";
        $button = MForms::badge('introduction', 'primary', 'introduction');
        $HTML .= "<tr><td>About:</td><td>$message<br><br>Click here for the $button pages.</td></tr>";


        $version = "{$GLOBALS['VER_Version']}.{$GLOBALS['VER_Revision']}.{$GLOBALS['VER_Patch']}";
        $HTML .= "<tr><td>Version:</td><td><p>$version</p></td></tr>";

        $website = "<a href='http://communityreading.org' target='_blank'>Community Reading Project</a>";
        $HTML .= "<tr><td>Website:</td><td>$website</td></tr>";

        $researchLink = "<a href='https://communityreading.org/wp/category/dyslexia-research/' target='_blank'>research</a>";;
        $HTML .= "<tr><td>Methods:</td><td>Here is the $researchLink that informs our methodology.</td></tr>";

        $contact = "Email: <a href='mailto:Tom@CommunityReading.org?subject=Emergency Reading Program Feedback'>Tom@CommunityReading.org</a><br /><br>
                        Questions, concerns, suggestions, feedback?  I would love to hear from you.";
        $HTML .= "<tr><td>Contact:</td><td>$contact</td></tr>";


        $licenceImg = "<img src='https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png' />";
        $licenceTxt = "This work is licensed under a <a rel='license' href='http://creativecommons.org/licenses/by-nc-sa/4.0/'>Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>";
        $HTML .= "<tr><td>$licenceImg</td><td>$licenceTxt</td></tr>";

        $copyright = "&copy; 2013-2023 Community Reading Project";
        $HTML .= "<tr><td>Copyright:</td><td>$copyright</td></tr>";


        $HTML .= "</table>";

        $HTML .= "      </div>";
        $HTML .= "      <div class='modal-footer'>";
        $HTML .= "        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
        $HTML .= "      </div>";
        $HTML .= "    </div>";
        $HTML .= "  </div>";
        $HTML .= "</div>";

        return $HTML;
    }
}
