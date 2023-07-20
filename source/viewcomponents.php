<?php



// these are tools and components used in BlendingViews
class ViewComponents
{

    public function loadLibraries(): string
    {
        $HTML = '';

        $HTML .= "<script type='text/javascript' src='source/blending.js'></script>";

        // $HTML .= "<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>";



        // $JSONret = '"hellow world"';
        // $HTML .= "<script> MathcodeAPI.loader($JSONret)</script>";

        return $HTML;
    }




    // $tabs are in form ['name'=>'content', ...]
    function tabs(array $tabs, int $showTab = 1): string
    {

        printNice("function tabs(array tabs, int $showTab): string");
        $HTML = '';

        ///////////////////
        // $HTML .= "<div style='background-color:#ffd3ff;border:solid 1px black;border-radius:10px;'>";
        $tightStyle = "style='padding-left:3px;padding-right:3px;'";
        $HTML .= "
        <ul class='nav nav-pills'>
  <li class='nav-item'>
    <a class='nav-link active' $tightStyle aria-current='page' href='#'>Active</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link' $tightStyle href='#'>Link</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link' $tightStyle href='#'>Link</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link' $tightStyle disabled' href='#' tabindex='-1' aria-disabled='true'>Disabled</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link' $tightStyle href='#'>Almost</a>
  </li>
  <li class='nav-item'>
    <a class='nav-link' $tightStyle href='#'>Last</a>
  </li>
</ul>";
        // $HTML .= "</div>";
        $HTML .= "<br>";

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
        foreach ($tabNames as $name) {
            $HTML .= "<li  class='nav nav-tabs nav-pills flex-column flex-sm-row text-center border-0 rounded-nav'>";
            $HTML .= "<a id='{$uniq}tab$i' class='nav-link' onclick='window.blendingTabButton($i,$nTabs,\"{$uniq}\",\"$active\",\"$notactive\")' data-toggle='tab' href='#tabs-$i' role='tab'><h4>$name</h4></a>";
            $HTML .= "</li>&nbsp;&nbsp;";
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

          <div class='card' >
            <div class='card-header' style='max-height:60px;' id='heading$i'>
              <h5 class='mb-0'>
                <button class='btn btn-sm btn-link' data-toggle='collapse' data-target='#collapse$i' aria-expanded='false' aria-controls='collapse$i' >
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
    function lessonAccordian(int $studentID): string
    {
        $views = new Views();

        $tabs = [];     // final product

        $clusterWordsWithMastery = $this->addMastery($studentID);
        // now 'mastered' field is 0-no,1-yes,2-current

        // first pass creates an string of  tablenames table in each $tab entry
        foreach ($clusterWordsWithMastery as $lessonName => $lessonData) {
            if (!isset($tabs[$lessonData['group']]))    // make sure
                $tabs[$lessonData['group']] = '';

            $tabs[$lessonData['group']] .= $lessonName . '$$';  // use $$ as delimiter
        }


        // second pass expands the $$ string to a table of entries
        foreach ($tabs as $group => $lessons) {
            $entries = explode('$$', $lessons);
            $display = "<table class='table'>";
            foreach ($entries as $entry) {  // there is an empty one at the end
                if (!empty($entry)) {

                    $lessonData = $clusterWordsWithMastery[$entry];

                    // put up mastery symbol (no, yes, current)
                    $unicode = ['O',  '&#x2705;', '&#10004;'];

                    $display .= "<tr>";
                    $display .= "<td>{$unicode[$lessonData['mastery']]}</td>";
                    $display .= "<td>$entry</td>";
                    $display .= "</tr>";
                }
            }
            $display .= "</table>";
            $tabs[$group] = $display;  // replace $$ string with table html
        }
        return $this->accordian($tabs);
    }


    // add mastery field to $clusterWords.  note: we reuse array
    function addMastery(int $studentID): array
    {
        $bTable = new BlendingTable();
        $lessons = new Lessons();

        // assume nothing has been mastered
        $clusterWords = $bTable->clusterWords;
        foreach ($clusterWords as $lessonName => $lessonData) {
            $clusterWords[$lessonName]['mastery'] = 0;      // make sure each has a mastery field
        }

        // mark the ones that have been mastered
        $logTable = new LogTable();
        $allMastered = $logTable->getAllMastered($studentID);
        printNice($allMastered, 'allMastered');
        foreach ($allMastered as $record) {
            if (isset($clusterWords[$record['lesson']])) {  // safety in case we modify clusterlessons
                $clusterWords[$record['lesson']]['mastery'] = 1;
            }
        }

        // mark the lesson we are currently working on
        $lessons = new Lessons();
        $lessonName = $lessons->getNextLesson($studentID);
        $clusterWords[$lessonName]['mastery'] = 2;

        // debug
        // printNice($clusterWords);
        foreach ($clusterWords as $lessonName => $lessonData) {
            // printNice($lessonName,$clusterWords[$lessonName]['mastery']);      // make sure each has a mastery field
        }

        return $clusterWords;
    }


    /////////////////////////////////////////
    /////// word spinner ////////////////////
    /////////////////////////////////////////

    function wordSpinner(string $pre, string $vow, string $suf,  bool $plusE = false): string
    {
        // eg:      $HTML .= $v->wordSpinner('b,c,d,f,g,h','a,e,i,o,u','b,c,d,f,g,h,j,k');

        $affixWidth = 4;

        $HTML = '';

        // don't need buttons, but I like the styling
        $HTML .= "<table style = 'width:100%; max-width:800px;' class='wordspinner'><tr>";

        $HTML .= "<td colspan= $affixWidth >";
        $HTML .= $this->submitButton('Prefixes', 'primary');
        $HTML .= "</td><td colspan= $affixWidth >";
        $HTML .= $this->submitButton('Vowels', 'danger');
        $HTML .= "</td><td colspan= $affixWidth >";
        $HTML .= $this->submitButton('Suffixes', 'primary');
        $HTML .= "</td></tr>";

        // prefixes go in column 1-2-3-4
        // vowels go in column 6-7
        // suffixes go in column 9-10,11,12

        $prefixes = explode(',', $pre);
        $vowels = explode(',', $vow);
        $suffixes = explode(',', $suf);

        // decide which form of word spinner to use
        $jsFunc = 'wordSpinner';
        if ($plusE) {
            $jsFunc = 'wordSpinnerPlusE';
        }

        while (count($prefixes) > 0 or count($vowels) > 0 or count($suffixes) > 0) {

            $button = array();

            for ($i = 0; $i < $affixWidth; $i++) { // work on prefixes
                $top = array_shift($prefixes); // grab the FIRST one
                if (!empty($top)) {
                    $action = $jsFunc . "('p','$top');"; // p for prefix, v for vowel, etc
                    $button[] = $this->onClickButton($top, 'primary', false, $action, '', 'btn-lg'); // "<button onClick='$action' style='background-color:blue;'>$top</button>";
                } else {
                    $button[] = '';
                }
            }

            $button[] = ''; // first and last is always a spacer
            for ($i = 0; $i < 2; $i++) { // work on vowels

                $top = array_shift($vowels); // grab the FIRST one
                if (!empty($top)) {
                    $action = $jsFunc . "('v','$top');"; // p for prefix, v for vowel, etc
                    $button[] = $this->onClickButton($top, 'danger', false, $action, '', 'btn-lg');
                } else {
                    $button[] = '&nbsp;&nbsp;';
                }
            }

            $button[] = ''; // first and last is always a spacer

            for ($i = 0; $i < $affixWidth; $i++) { // work on prefixes
                $top = array_shift($suffixes); // grab the FIRST one
                if (!empty($top)) {
                    $action = $jsFunc . "('s','$top');"; // p for prefix, v for vowel, etc
                    $button[] = $this->onClickButton($top, 'primary', false, $action, '', 'btn-lg');
                } else {
                    $button[] = '';
                }
            }

            // now output this line...
            $HTML .= "<tr>";
            for ($i = 0; $i < (2 * $affixWidth) + 4; $i++) {
                $bkgnd = ($i == 4 or $i == 5) ? '#FFFFE0' : '#E0FFFF';
                $HTML .= "<td style='background-color:$bkgnd;'>{$button[$i]}</td>";
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

        $HTML .=  MForms::button('test', 'primary', 'blendingLesson', $text, '');  // key is also index to lesson

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
        return $text;
        $HTML = '';
        $HTML .= "<span style='font-family:san-serif;
                                font-size:120%;
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
                                font-size:120%;
                                color:red;
                                border:solid 1px grey;
                                border-radius:5px;
                                text-align:center;
                                background:#ffff66;
                                margin:0px;' &nbsp;[$text]&nbsp;</span>";
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
