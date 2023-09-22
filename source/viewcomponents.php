<?php



// these are tools and components used in BlendingViews
class ViewComponents
{

    public function loadLibraries(): string
    {
        $HTML = '';

        $HTML .= "<script type='text/javascript' src='source/blending.js'></script>";

        $HTML .= "<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>";



        // $JSONret = '"hellow world"';
        // $HTML .= "<script> MathcodeAPI.loader($JSONret)</script>";

        return $HTML;
    }


    function navbar(array $options, $title = 'BLENDING'): string
    {
        // printNice($options, "Navbar(title=$title)");
        $HTML = '';

        $buttons = '';
        if (in_array('addStudent', $options)) {
            $buttons .= MForms::button('Add Student', 'primary', 'showAddStudentForm');
        }

        if (in_array('exitCourse', $options)) {
            $buttons .= MForms::button('Exit', 'warning', 'showStudentList');
        }

        if (in_array('next', $options)) {
            $buttons .= MForms::button('Next', 'primary', '???AddStudentList');
        }

        if (in_array('navigation', $options)) {
            $buttons .= MForms::button('Exit', 'warning', 'selectCourse');
            $buttons .= MForms::button('Next', 'info', 'next');
            $buttons .= MForms::button('Navigation', 'info', 'navigation');
        }

        $HTML .= MForms::rowOpen(12);
        $HTML .= "<div style='float:left;'>$buttons</div>";

        $aboutButton = MForms::button('About', 'danger', 'about');

        //     "<form  action= 'source/blending.pdf' target='_blank'>
        //        <button type='submit' aria-label='About' class='btn-sm btn-danger rounded' style='margin:3px;'>About</button>
        //    </form>";

        $HTML .= "<div style='float:right;'>$aboutButton</div>";
        $HTML .= MForms::rowClose();

        if (!$GLOBALS['mobileDevice']) {   // a separater is nice for a laptop
            $HTML .= "<hr>";
        }

        return $HTML;










        // $HTML .= "<nav class='navbar navbar-light' style='background-color:#ffffb3;border:solid 2px blue;border-radius:10px;'>";
        // $HTML .= "  <a class='navbar-brand' href='#'>";
        // $HTML .= "    <img src='pix/blending.png' height='36' alt=''>";
        // $HTML .= "  </a>";

        // $HTML .= "  <form class='form-inline'>";
        // $HTML .= MForms::navButton('test', 'primary', 'test');
        // $HTML .= "$buttons";

        // $HTML .= "  </form>";
        // $HTML .= "</nav>";

        // return $HTML;
    }



    // $tabs are in form ['name'=>'content', ...]
    function tabs(array $tabs, int $showTab = 1): string
    {

        $HTML = '';

        ///////////////////
        // $HTML .= "<div style='background-color:#ffd3ff;border:solid 1px black;border-radius:10px;'>";

        ///////////////////

        // convert to two arrays (TODO: just process in combo form)
        $tabNames = [];
        $tabContents = [];

        foreach ($tabs as $key => $value) {
            $tabNames[] = $key;
            $tabContents[] = $value;
        }


        global $colours;
        $active = $colours['dark'];
        $notactive = $colours['light'];

        $uniq = 'blending' . MForms::bakeryTicket();

        // tab headers
        $HTML .= "<ul class='nav nav-tabs' role='tablist'>";
        $i = 1;
        $nTabs = count($tabNames);
        $tightStyle = "style='padding-left:3px;padding-right:3px;border:solid 1px black;'";

        foreach ($tabNames as $name) {

            $onClick = "onclick='window.blendingTabButton($i,$nTabs,\"{$uniq}\",\"$active\",\"$notactive\")'";
            if ($GLOBALS['mobileDevice']) { // this skips over the drawer symbol on mobile
                $HTML .= "<li class='nav-item'>";
                $HTML .= "<a id='{$uniq}tab$i' class='nav-link' $tightStyle $onClick>$name</a>";
                $HTML .= "</li>";
            } else {
                $HTML .= "<li  class='nav nav-tabs nav-pills flex-column flex-sm-row text-center border-0 rounded-nav' >";
                $HTML .= "<a id='{$uniq}tab$i' class='nav-link' $onClick  data-toggle='tab' href='#tabs-$i' role='tab' ><h4>$name</h4></a>";
                $HTML .= "</li>&nbsp;&nbsp;";
            }
            $i++;
        }

        $HTML .= "</ul>";

        // tab panes
        $i = 1;
        foreach ($tabContents as $content) {
            $hidden = ($i == $showTab) ? 'block;' : 'none;';
            $style = "style='display:$hidden'";
            $HTML .= "<div  $style id='{$uniq}tab-{$i}'>";
            $HTML .= "<p>$content</p>";
            $HTML .= "</div>";
            $i++;
        }

        // set the tab bar to the first element
        $HTML .= "<script>window.blendingTabButton($showTab,$nTabs,\"{$uniq}\",\"$active\",\"$notactive\")\n</script>";
        return $HTML;
    }


    ///////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////

    function accordian(array $tabs): string
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
            <div id='collapse$i' class='collapse' aria-labelledby='heading$i' data-parent='#accordion'>
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
    function lessonAccordian(int $studentID, $course): string
    {
        $views = new Views();

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

        $unicode = ['notYet' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'mastered' => '&#x2705;', 'current' => '&#9654;'];

        // second pass expands the $$ string to a table of entries
        $isCurrentInGroup = false;
        $anyMissingInGroup = false;

        foreach ($tabs as $group => $lessons) {
            $entries = explode('$$', $lessons);   // within a tab, lessons are a string first$$second$$third
            $display = "<table class='table table-sm'>";
            foreach ($entries as $entry) {  // there is an empty one at the end
                if (!empty($entry)) {
                    // put up mastery symbol (no, yes, current)

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

                    $link = MForms::linkHref('renderLesson', $entry);

                    $display .= "<td><a $link>$entry</td>";
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
        return $this->accordian($tabsWithCurrent);
    }



    /////////////////////////////////////////
    /////// word spinner ////////////////////
    /////////////////////////////////////////

    function wsHelper0()
    {
        $HTML = '';
        if ($GLOBALS['mobileDevice']) {
            $HTML .= "<table style = 'width:100%; max-width:400px;' class='table'><tr>";
        } else {
            $HTML .= "<table style = 'width:100%; max-width:800px;' class='table'><tr>";
        }
        return $HTML;
    }


    function wShelper1(string $position, string $letter, int $stretch = 1): string
    {
        $color = ($position == 'v') ? 'danger' : 'primary';
        $extraStyle =/*($stretch==1)?'':*/ "text-align:center;";  // font-family: monospace;

        $onClick = '';
        if ($stretch == 1) // not for titles
            $onClick  = "onclick= \"wordSpinner('$position','$letter');\"";


        if ($GLOBALS['mobileDevice']) {
            $style = "style='min-width:20px;font-size:24px;font-family:muli,monospace;$extraStyle'";
            $padding = "3px";
            $btnSize = 'md';
        } else {
            $style = "style='min-width:70px;font-size:48px;font-family:muli,monospace;$extraStyle'";
            $padding = "15px";
            $btnSize = 'lg';
        }

        $colspan = ($stretch == 1) ? '' : "colspan=$stretch";
        // $extraClass = 'sp_word ';

        $HTML =  '';
        $HTML .= "<td style='padding:$padding;text-align:center;' $colspan>";
        $HTML .= "<button type='button' class='btn btn-$color btn-$btnSize'  $style $onClick>$letter</button>";
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

        $HTML .= $this->wsHelper0();  // set up the table

        $HTML .= "<tr>";
        $HTML .= $this->wShelper1('p', 'Prefixes',  $affixWidth);
        $HTML .= $this->wShelper1('v', 'Vowels',  2);
        $HTML .= $this->wShelper1('s', 'Suffixes',  $affixWidth);
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



        $HTML .= '<br /></table><br /><span class="wordspinner" style="font-size:300%;line-height:200%;">
                  <table><tr><td><span style="font-size:300%;font-weight:bold;" id="spin0"></span></td></tr>
                         <tr><td id="spin1"></td></tr>
                         <tr><td id="spin2"></td></tr>
                         <tr><td id="spin3"></td></tr></table></span>';
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

        // $HTML .= "<form>";
        $HTML = "<button type='submit' aria-label='$text' $myTitle class='$buttonClass rounded' $n $confirm style='margin:3px;{$extraStyle}'>$text</button>";
        $HTML .= MForms::security();
        // $HTML .= "<form>";


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
        $HTML = '';
        $HTML .= "<span style='font-family:san-serif;
                                color:blue;
                                border:solid 1px grey;
                                border-radius:5px;
                                text-align:center;
                                background:#ffff66;
                                margin:0px;'><b>&nbsp;/$text/&nbsp;</b></span>";
        return $HTML;
    }
    function spelling($text): string
    {
        $HTML = '';
        $HTML .= "<span style='font-family:san-serif;
                                color:blue;
                                border:solid 1px grey;
                                border-radius:5px;
                                text-align:center;
                                background:#ffedff;
                                margin:0px;'>&nbsp;[$text]&nbsp;</span>";
        return $HTML;
    }

    // function soundInserter(string $input):string{



    //     preg_replace('\/([^\/]+)\/',
    //         string|array $pattern,
    //         string|array $replacement,
    //         string|array $subject,
    //         int $limit = -1,
    //         int &$count = null
    //     ): string|array|null
    // }

}
