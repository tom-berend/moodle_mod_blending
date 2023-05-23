<?php



// these are tools and components used in BlendingViews
class ViewComponents
{

    public function loadLibraries(): string
    {
        $HTML = '';

        $HTML .= "<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>";

        $HTML .= "<script type='text/javascript' src='source/blending.js'></script>";


        // $JSONret = '"hellow world"';
        // $HTML .= "<script> MathcodeAPI.loader($JSONret)</script>";

        return $HTML;
    }


    function tabs(array $tabNames, array $tabContents): string
    {
        $HTML = '';


        assert(count($tabNames) == count($tabContents));
        // $HTML .= printNice($tabNames);

        global $colours;
        $active = $colours['dark'];
        $notactive = $colours['light'];


        // tab headers
        $HTML .= "<ul class='nav nav-tabs' role='tablist'>";
        $i = 1;
        $nTabs = count($tabNames);
        foreach ($tabNames as $name) {
            $HTML .= "<li  class='nav nav-tabs nav-pills flex-column flex-sm-row text-center border-0 rounded-nav'>";
            $HTML .= "<a id='blendingtabheader$i' class='nav-link' onclick='blendingTabButton($i,$nTabs,\"blendingtab\",\"$active\",\"$notactive\")' data-toggle='tab' href='#tabs-$i' role='tab'><h4>$name</h4></a>";
            $HTML .= "</li>&nbsp;&nbsp;";
            $i++;
        }
        $HTML .= "</ul>";

        // tab panes
        $i = 1;
        foreach ($tabContents as $content) {
            $hidden = $i == 1 ? 'block;' : 'none;';
            $style = "style='display:$hidden'";
            $HTML .= "<div  $style id='blendingtab-$i'>";
            $HTML .= "<p>$content</p>";
            $HTML .= "</div>";
            $i++;
        }

        // set the tab bar to the first element
        $HTML .= "<script>blendingTabButton(1,$nTabs,\"blendingtab\",\"$active\",\"$notactive\")\n</script>";
        return $HTML;
    }


    ///////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////

    function accordian(array $titles, array $contents): string
    {
        assert(count($titles) == count($contents));

        $HTML = '';


        $HTML .= "<div id='accordion'>";

        for ($i = 0; $i < count($titles); $i++) {
            $HTML .= "

          <div class='card'>
            <div class='card-header' id='heading$i'>
              <h5 class='mb-0'>
                <button class='btn btn-link' data-toggle='collapse' data-target='#collapse$i' aria-expanded='false' aria-controls='collapse$i' >
                  <h4>{$titles[$i]}</h4>
                </button>
              </h5>
            </div>

            <div id='collapse$i' class='collapse' aria-labelledby='heading$i' data-parent='#accordion'>
              <div class='card-body'>
                  {$contents[$i]}
              </div>
            </div>
          </div>
          ";
        }
        $HTML .= "</div>";  // id=accordian


        return $HTML;
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
        assert(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $myTitle = empty($title) ? '' : " title='" . $title . "'";
        $n = (empty($name)) ? 'disabled="disabled"' : "name='" . $name . "'"; // if no name, then disable button
        $bakeryTicket = $_SESSION['bakeryTicket'];  // was 'bakeryticket()' but don't want a new one
        $saver = "form=\'$bakeryTicket\'";

        $size = 'btn-sm';
        if ($extraStyle == 'btn-lg')
            $size = $extraStyle;

        $buttonClass = "$size btn-" . (($solid) ? '' : 'outline-') . "$color";

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"return confirm('{$onClick} -Are you sure?')\"";
        }

        $HTML =
            "<button type='submit' aria-label='$text' $myTitle class='$buttonClass rounded' $n $confirm style='margin:3px;{$extraStyle}'>$text</button>";

        // add the security stuff
        $HTML .= "<input type='hidden' name='id' value='{$GLOBALS['id']}' />";
        $HTML .= "<input type='hidden' name='sesskey' value='{$GLOBALS['session']}' />";

        return ($HTML);
    }

    // only use single quotes in onClick (eg:  "console.log('hello)" )
    function onClickButton(string $text, string $color, bool $solid, string $onClick, string $id = '', string $btnSize = 'btn-sm')
    {
        assert(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $click = '';
        if (!empty($onClick)) {
            $click = "onclick=\"{$onClick}\"";
        }

        $myID = empty($id) ? '' : "id='$id'";   // in case we want to refer to this button

        $buttonClass = "btn $btnSize btn-" . (($solid) ? '' : 'outline-') . "$color rounded";

        $HTML = "<button type='button' aria-label='$text' class='$buttonClass' $click $myID>$text</button>";
        return ($HTML);
    }


    // usually the $id is a bakery ticket, we need the div to have an id
    function rowOpen(int $cols, string $id = '', string $style = '')
    {

        // printNice('rowOpen');

        $thisID = $id ? "id='$id'" : '';
        $thisStyle = !empty($style) ? "style='$style'" : '';

        return ("<div class='row' $thisStyle><div class='col-$cols' $thisID $thisStyle>");
    }

    function rowNextCol(int $cols, string $id = '', string $style = '')
    {
        $thisID = $id ? "id='$id'" : '';
        $thisStyle = $style ? "style='$style'" : '';

        return "</div><div class='col-$cols' $thisID $thisStyle>";
    }

    function rowClose()
    {
        // printNice('rowClose');
        return ("</div></div>");
    }


    // minimal safety string, won't disrupt HTML or SQL
    function neutered(string $string, bool $forJS = false)
    {

        $string = str_replace('&', '&amp;', $string);  // MUST BE FIRST (or will catch subsequent ones we insert)

        $string = str_replace('`', '&#96;', $string);  // backtick (JS template string)

        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);


        // $string = str_replace('$', '&#36;', $string);

        $string = str_replace('+', '&plus;', $string);
        $string = str_replace('=', '&equals;', $string);

        // JS engine converts HTML back to danger, need to escape twice
        //  https://stackoverflow.com/questions/26245955/encoded-quot-treated-as-a-real-double-quote-in-javascript-onclick-event-why
        if ($forJS) {
            $string = str_replace("'", '&#39;', $string);
            $string = str_replace('"', '&#34;', $string);
        } else {
            $string = str_replace("'", '&#39;', $string);
            $string = str_replace('"', '&#34;', $string);
        }

        // echo "neutered ", $oldString, ' ',$string;

        return ($string);
    }
}
