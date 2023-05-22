<?php

/////////////////////////////////////////
/////// word spinner ////////////////////
/////////////////////////////////////////

function wordSpinner(string $pre, string $vow, string $suf, int $affixWidth = 4, bool $plusE = false): string
{
    $affixWidth = 4;


    $plusE = false; // if true then we spin a_e (eg: cake)
    $spinnerName = ''; // name of this spinner (passed by caller)

    $HTML = '';

    // don't need buttons, but I like the styling
    $HTML .= "<table style = 'width:100%; max-width:800px;' class='wordspinner'><tr>";

    $HTML .="<td colspan= $affixWidth >";
    $HTML .= MForms::submitButton('Prefixes','primary');
    $HTML .="</td><td colspan= $affixWidth >";
    $HTML .= MForms::submitButton('Vowels','danger');
    $HTML .="</td><td colspan= $affixWidth >";
    $HTML .= MForms::submitButton('Suffixes','primary');
    $HTML .= "</td></tr>";

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
                $action = $jsFunc . "('p','$top');"; // p for prefix, v for vowel, etc
                $button[] = MForms::onClickButton($top,'primary',false,$action,'','btn-lg');// "<button onClick='$action' style='background-color:blue;'>$top</button>";
            } else {
                $button[] = '';
            }
        }

        $button[] = ''; // first and last is always a spacer
        for ($i = 0; $i < 2; $i++) { // work on vowels

            $top = array_shift($vowels); // grab the FIRST one
            if (!empty($top)) {
                $action = $jsFunc . "('v','$top');"; // p for prefix, v for vowel, etc
                $button[] = MForms::onClickButton($top,'danger',false, $action,'','btn-lg');
            } else {
                $button[] = '&nbsp;&nbsp;';
            }
        }

        $button[] = ''; // first and last is always a spacer

        for ($i = 0; $i < $affixWidth; $i++) { // work on prefixes
            $top = array_shift($suffixes); // grab the FIRST one
            if (!empty($top)) {
                $action = $jsFunc . "('s','$top');"; // p for prefix, v for vowel, etc
                $button[] = MForms::onClickButton($top,'primary',false,$action,'','btn-lg');
            } else {
                $button[] = '';
            }
        }

        // now output this line...
        $HTML .= "<tr>";
        for ($i = 0; $i < (2 * $affixWidth) + 4; $i++) {
            $bkgnd = ($i==4 or $i==5) ?'#FFFFE0':'#E0FFFF';
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
