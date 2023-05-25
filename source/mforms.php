<?php


// this started as a wrapper for the Moodle Forms API.  But I got as
// far as the first demo in the docs and threw the Moodle API out.  No
// idea how they keep track of everything.




class MForms
{

    // usually the $id is a bakery ticket, we need the div to have an id
    static function rowOpen(int $cols, string $id = '', string $style = '')
    {

        // printNice('rowOpen');

        $thisID = $id ? "id='$id'" : '';
        $thisStyle = !empty($style) ? "style='$style'" : '';

        return ("<div class='row' $thisStyle><div class='col-$cols' $thisID $thisStyle>");
    }

    static function rowNextCol(int $cols, string $id = '', string $style = '')
    {
        $thisID = $id ? "id='$id'" : '';
        $thisStyle = $style ? "style='$style'" : '';

        return "</div><div class='col-$cols' $thisID $thisStyle>";
    }

    static function rowClose()
    {
        // printNice('rowClose');
        return ("</div></div>");
    }


    ///////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////



    static function security()
    {
        $f = "\n";
        $f .= "<input type='hidden' name='id' value='{$GLOBALS['id']}' />";
        $f .= "<input type='hidden' name='sesskey' value='{$GLOBALS['session']}' />";
        return ($f);
    }

    static function hidden(string $name, string $value, string $id = '')
    {
        $fid = ($id) ? "id='$id'" : '';
        return ("\n<input type='hidden' name='$name' value='$value' $fid />");
    }

    static function inputText(string $label, string $name, ?string  $value = '', string $id = '', string $inputAttr = '', bool $inline = false, string $placeholder = '', string $tooltip = '', string $trailer = '')
    {
        $label = MForms::string($label);
        $tooltip = MForms::string($tooltip);
        $placeholder = MForms::string($placeholder);

        // if the label is empty, then intent is to use the placeholder

        assertTrue(!(empty($name) and empty($id)), "no name, no ID, need at least one!");

        if (!empty($inputAttr)) {  // must be 'disabled' or 'required'
            assertTrue($inputAttr == 'disabled' or $inputAttr == 'required' or $inputAttr == 'readonly' or $inputAttr == 'autofocus');
        }

        $fid = (!empty($id)) ? $id : $name;
        $fidLabel = $fid . '_label';
        $place = (empty($placeholder)) ? '' : "placeholder='$placeholder'";

        // readonly instead of disabled

        $fdis = ($inputAttr == 'disabled') ? "readonly='readonly'" : '';
        $fdis = ($inputAttr == 'required') ? 'required' : $fdis;

        $title = empty($tooltip) ? '' : "title='" . neutered($tooltip) . "'";

        $inln = ($inline) ? "class='form-inline' style='margin-bottom:2px;'" : "class='form-group'";
        $f = "\n";
        $f .= "<div $inln>";
        $f .= (empty($name) and !empty($placeholder)) ? "" : "<label id='$fidLabel' for='$fid'>$label </label>";

        $name = (empty($name)) ? strval(MForms::bakeryTicket()) : $name;  // W3 says name should not be empty
        $f .= "<input type='text' class='form-control' name='$name' value = '$value' id='$fid' aria-labelledby='$fidLabel' $place $fdis $title  />";
        $f .= $trailer;
        $f .= "</div>";

        // printNice($f);
        return ($f);
    }



    static function inputNumber(string $label, string $name, float $value = 0, string $id = '', bool $disabled = false)
    {
        $label = MForms::string($label);

        $fid = (!empty($id)) ? "id='$id'" : "id='$name'";
        // readonly instead of disabled
        $fdis = ($disabled) ? "readonly='readonly'" : '';

        $f = "\n
        <div class='form-group'>
            <label>$label</label>
        <input type='number' class='form-control' name='$name' value = '$value' $fdis />
        </div>";

        return ($f);
    }

    static function textarea(string $label, string $name, $value, string $id = '', string $inputAttr = '', $rows = 5, $placeholder = '')
    {

        $label = MForms::string($label);
        $placeholder = MForms::string($placeholder);

        $fid = (!empty($id)) ? "id='$id'" : "id='$name'";

        $fdis = '';
        if (!empty($inputAttr)) {
            assertTrue($inputAttr == 'disabled' or $inputAttr == 'required' or $inputAttr == 'readonly' or $inputAttr == 'autofocus');
            $fdis = $inputAttr;
        }

        $fplace = ($placeholder) ? "placeholder='$placeholder'" : '';
        $value = strval($value); // might be null

        $f = "\n
        <div class='form-group'>
            <label>$label</label>
            <textarea class='form-control' wrap='soft'  name='$name' rows='$rows' $fid $fdis $fplace>$value</textarea>
        </div>";

        return ($f);
    }


    // if a button isn't in a form, we create a form for it.
    static function buttonForm(string $text, string $color, string $p = '', string $q = '', string $r = '', bool $solid = true, string $onClick = '', $extraStyle = '', $title = '')
    {
        // printNice("Button(text: $text, color: $color, p: $p, q: $q, r: $r, solid: $solid, onClick: $onClick)");

        $text = MForms::string($text);
        $title = MForms::string($title);

        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']), "'$color' is not a valid color");


        $bakeryTicket = MForms::bakeryTicket(); // mostly unique number
        $ret = "
         <form class='form-inline' style='float:left; margin:5px;' id='$bakeryTicket'>";

        $ret .= MForms::hidden('p', $p);
        if (empty($p))
            $bakeryTicket = '';     //disables the submit button

        // add them even if empty (because sometimes the empty string is the parameter)
        $ret .= MForms::hidden('q', strval($q));
        $ret .= MFORMS::hidden('r', strval($r));

        $ret .= MForms::submitButton($text, $color, $bakeryTicket, $solid, $onClick, $extraStyle, $title);
        $ret .= '</form>';

        // $HTMLTester = new HTMLTester();
        // $HTMLTester->validate($ret);
        return ($ret);
    }




    // for a disabled button, leave name empty
    static function submitBadge(string $text, string $color, string $name = '', bool $solid = true, string $onClick = '', $extraStyle = '', $title = '')
    {
        // printNice("static function submitButton(string $text, string $color, string $name = '', bool $solid = true, string onClick = '$onClick', extraStyle = '$extraStyle')");
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));


        $n = (empty($name)) ? 'disabled="disabled"' : "name='$name'"; // if no name, then disable button
        $bakeryTicket = $_SESSION['bakeryTicket'];  // was 'bakeryticket()' but don't want a new one
        $saver = "form=\'$bakeryTicket\'";

        $size = 'btn-sm';
        if ($extraStyle == 'btn-lg')
            $size = $extraStyle;

        $buttonClass = "badge $size btn-" . (($solid) ? '' : 'outline-') . "$color";

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"return confirm('{$onClick} -Are you sure?')\"";
        }

        $myTitle = (!empty($title)) ? "title='$title' " : '';
        $myAria = (!empty($title)) ? "aria-label='$title' " : "aria-label='$text'";
        $HTML =
            "<button type='submit' $myAria $myTitle class='$buttonClass rounded' $n $confirm style='margin:3px;{$extraStyle}'>$text</button>";

        $HTML .= MForms::security();
        // printNice($HTML,'submitButton');
        return ($HTML);
    }

    // for a disabled button, leave name empty
    static function submitButton(string $text, string $color, string $name = '', bool $solid = true, string $onClick = '', $extraStyle = '', $title = '')
    {
        // printNice("static function submitButton(string $text, string $color, string $name = '', bool $solid = true, string onClick = '$onClick', extraStyle = '$extraStyle')");
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $myTitle = empty($title) ? '' : " title='" . neutered($title) . "'";
        $n = (empty($name)) ? 'disabled="disabled"' : "name='" . neutered($name) . "'"; // if no name, then disable button
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

        $HTML .= MForms::security();
        // printNice($HTML,'submitButton');
        return ($HTML);
    }



    // same as 'submitButton' but expects to be handled by our Javascript
    static function popupSubmitButton(string $text, string $color, string $name = '', bool $solid = true, string $onClick = '', $extraClass = '', $extraStyle = '')
    {
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));


        $n = (empty($name)) ? 'disabled="disabled"' : "name='$name'"; // if no name, then disable button
        $bakeryTicket = $_SESSION['bakeryTicket'];  // was 'bakeryticket()' but don't want a new one
        $saver = "form=\'$bakeryTicket\'";

        $buttonClass = "btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color";


        $HTML =
            "<button type='submit' aria-label='$text' class='$buttonClass rounded' $n onclick='$onClick' style='margin:3px;{$extraStyle}'>$text</button>";

        $HTML .= MForms::security();
        // printNice($HTML,'submitButton');
        return ($HTML);
    }


    // submit buttons that are EXTERNAL to the form
    static function externalSubmitButton(int $formID, string $text, string $color, string $p, string $q,  bool $solid = true, string $onClick = '')
    {

        // $tf = ($solid)?'true':'false';
        // printNice("function externalSubmitButton(formID: $formID, text: $text, color: $color, p: $p, q: $q, solid: $tf, onClick $onClick )");

        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $n = (empty($p)) ? 'disabled="disabled"' : "name='$p'"; // if no name, then disable button
        // $saver = (empty($name)) ? '' : 'document.getElementById("'.$bakeryTicket.'").value = "'.$name.'" ';
        $saver = "form='$formID'";

        $buttonClass = "btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color";

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"$saver;return(confirm('$onClick - Are you sure?'));\"";
        }

        $style = "style='padding:1px 5px;'";

        $HTML =
            "<input type='submit' aria-label='$text' name='$p' value='$text' class='$buttonClass rounded' $style $confirm />";

        // printNice($HTML,'submitButton')
        return ($HTML);
    }


    // onClick is NOT optional javascript, it is the only action that happens
    // only use single quotes in onClick (eg:  "console.log('hello)" )
    static function onClickButton(string $text, string $color, bool $solid, string $onClick, string $id = '', string $btnSize = 'btn-sm')
    {
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $click = '';
        if (!empty($onClick)) {
            $click = "onclick=\"{$onClick}\"";
        }

        $myID = empty($id) ? '' : "id='$id'";   // in case we want to refer to this button

        $buttonClass = "btn $btnSize btn-" . (($solid) ? '' : 'outline-') . "$color rounded";

        $HTML = "<button type='button' aria-label='$text' class='$buttonClass' $click $myID>$text</button>";
        return ($HTML);
    }



    static function navButton(string $text, string $color, string $name = '')
    {
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $text = MForms::string($text);

        $n = (empty($name)) ? 'disabled="disabled"' : "name='$name'"; // if no name, then disable button
        $bakeryTicket = $_SESSION['bakeryTicket'];  // was 'bakeryticket()' but don't want a new one
        $saver = "form=\'$bakeryTicket\'";

        $buttonClass = "btn btn-$color";

        $confirm = '';

        $HTML =
            "<a  aria-label='$text' class='$buttonClass' $n $confirm >$text</a>";

        $HTML .= MForms::security();
        // printNice($HTML,'submitButton');
        return ($HTML);
    }

    static function navDropButton(string $text, string $color, string $extraStyle = '')
    {
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $textcolor = 'white';
        if ($color == 'secondary' or $color == 'warning' or $color == 'light')
            $textcolor = 'black';

        $style = "style='color:$textcolor;margin:3px;{$extraStyle}'";
        $HTML = "
            <a class='nav-link dropdown-toggle btn-$color rounded' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' $style>
                 $text
            </a>";
        return $HTML;
    }


    static function navDropdownButton(string $text, array $drop)
    {
        assertTrue(!empty($text));

        $HTML = '';
        $HTML .= "<li class='nav-item dropdown'>";

        $HTML .= "
        <a class='btn btn-link  dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
        $text
        </a> ";

        $HTML .= "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
        foreach ($drop as $line) {
            $HTML .= $line;
        }
        $HTML .= "</div>";

        $HTML .= "</li>";
        return ($HTML);
    }

    // build the 'drop' array of navDropdown with these
    static function navDropdownLine(string $text, bool $disabled, string $p, string $q = '', string $r = '', string $img = '', string $imgBackgndColor = '', string $aria = '')
    {
        $aria = (empty($aria)) ? $text : $aria; // default aria to text
        $imgBackgndColor = (empty($imgBackgndColor)) ? '' : "background-color:$imgBackgndColor;";
        $img = (empty($img)) ? '' : "<img style=\"{$imgBackgndColor}height:20px;\" src=\"pix/$img\" />";
        $disab = ($disabled) ? ' disabled ' : '';
        $msg = "{$img}$text";
        $HTML = "<button class='drop:down-item' style='border:none;width:300px;text-align:left;' $disab>$msg</button>";
        return $HTML;
    }


    // the old button is now called 'buttonForm', creates a form.  this is like 'badge'
    static function button($text, $color, string $p = '', string $q = '', string $r = '', bool $solid = true, string $onClick = '', string $extraStyle = '', string $title = '')
    {
        if (substr($text, 0, 2) != '&#') // don't translate icons
            $text = MForms::string($text);
        $title = MForms::string($title);

        assertTrue(!empty($text), "badge with no name (p = '$p')");
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']), $color);
        assertTrue(is_bool($solid));

        if (empty($p))      // disabled buttons are always NOT SOLID
            $solid = false;

        $textcolor = 'white';
        if ($color == 'secondary' or $color == 'warning' or $color == 'light')
            $textcolor = 'black';

        // if not solid, then text needs to be reversed
        if (!$solid and ($color == 'primary' or $color == 'success'))
            $textcolor = 'black';

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"return confirm('{$onClick} -Are you sure?')\"";
        }

        // return "<a href='reserve.php?p=$p&q=$q' class='$buttonClass' role='button' $confirm>$text</a>";

        $href = MForms::linkHref($p, $q, $r);

        $buttonClass = "class= 'button btn btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color'";


        // special case for disabled buttons
        if (empty($p)) {
            return "<a $buttonClass role='button'><i><s>$text</s></i></a>";
        }

        $aria = !empty($title) ? "aria-label='$title' title='$title'" : '';

        // $class = "class='badge bg-$color' role='button'";
        $style = "style='color:$textcolor;margin:3px;{$extraStyle}'";

        $ret = "<a type='button' role='button' $buttonClass $href $style $confirm $aria>$text</a>";

        // TODO: figure out why we can't validate this
        // $HTMLTester = new HTMLTester();
        // $HTMLTester->validate($ret);
        return ($ret);
    }


    static function badge($text, $color, string $p = '', string $q = '', string $r = '', bool $solid = true, string $onClick = '', string $extraStyle = '', string $title = '')
    {

        // don't translate badges HERE, do it in the calling program.
        // because most badges are user defined (eg: names of steps or titles of cards)

        assertTrue(!empty($text), "badge with no name (p = '$p')");
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']), $color);
        assertTrue(is_bool($solid));

        if (empty($p))      // disabled buttons are always NOT SOLID
            $solid = false;

        $textcolor = 'white';
        if ($color == 'secondary' or $color == 'warning' or $color == 'light')
            $textcolor = 'black';

        // if not solid, then text needs to be reversed
        if (!$solid and ($color == 'primary' or $color == 'success'))
            $textcolor = 'black';

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"return confirm('{$onClick} - " . MForms::string('Are you sure?') . "')\"";
            // printNice($confirm);
        }

        // return "<a href='reserve.php?p=$p&q=$q' class='$buttonClass' role='button' $confirm>$text</a>";

        $href = MForms::linkHref($p, $q, $r);

        $buttonClass = "class= 'badge btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color'";


        // special case for disabled buttons
        if (empty($p)) {
            return "<a $buttonClass role='button'><i><s>$text</s></i></a>";
        }
        $aria = !empty($title) ? "aria-label='$title' title='$title'" : '';

        // $class = "class='badge bg-$color' role='button'";
        $style = "style='color:$textcolor;margin:3px;{$extraStyle}'";
        $ret = "<a $buttonClass role='button' $href $style $confirm $aria>$text</a>";

        // TODO: figure out why we can't validate this
        // $HTMLTester = new HTMLTester();
        // $HTMLTester->validate($ret);
        return ($ret);
    }

    // sometimes just info
    static function deadbadge(string $text, string $color, string $id = '', string $onClick = '', string $extraStyle = '', string $title = '')
    {

        // don't translate badges HERE, do it in the calling program.
        // because most badges are user defined (eg: names of steps or titles of cards)

        assertTrue(!empty($text), "deadbadge with no name");
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']), $color);

        $textcolor = 'white';
        if ($color == 'secondary' or $color == 'warning' or $color == 'light')
            $textcolor = 'black';

        $buttonClass = "class= 'badge btn btn-sm btn-$color'";

        $confirm = '';
        if (!empty($onClick)) {
            $confirm = "onclick=\"$onClick\" ";
        }

        $fid = empty($id) ? '' : "id='$id'";
        $aria = !empty($title) ? "aria-label='$title' title='$title'" : '';

        // $class = "class='badge bg-$color' role='button'";
        $style = "style='color:$textcolor;margin:3px;'";
        $ret = "<a $buttonClass role='button' $style $aria $fid $confirm>$text</a>";
        return ($ret);
    }


    // returns the full  'href='?... ', don't add your own href=
    static function linkHref(string $p, string $q = '', string $r = ''): string  // only p is required
    {
        $qS = (!empty($q)) ? "&q=$q" : '';
        $rS = (strlen($r) > 0) ? "&r=$r" : '';  // horrible - string '0' is empty in PHP!

        $sess = "&sesskey={$GLOBALS['session']}";

        $href = "href='?id={$GLOBALS['id']}&p=$p{$qS}{$rS}{$sess}{$prev}'";
        return $href;
    }



    // Tcheckbox is really a 1-character text input
    static function Tcheckbox(string $label, string $name, string $value = '', string $id = '', bool $disabled = false, bool $inline = false, $placeholder = '', $tooltip = '')

    // static function checkbox(string $text, string $id, string $value = 'checked' /*, $defaultValue = null*/)
    {

        $label = MForms::string($label);
        $tooltip = MForms::string($tooltip);
        $placeholder = MForms::string($placeholder);

        //TODO: we don't use tooltip yet

        // if the label is empty, then intent is to use the placeholder
        assertTrue(!(empty($label) and empty($id)), "need at least one!");

        $fid = (!empty($id)) ? $id : $name;
        $fidLabel = $fid . '_label';
        $place = (empty($placeholder)) ? '' : "placeholder='$placeholder'";


        $HTML = "\n";
        // readonly instead of
        $fdis = ($disabled) ? "readonly='readonly'" : '';
        $inln = ($inline) ? "class='form-inline'" : "class='form-group'";
        $HTML .= "<div $inln>";
        $HTML .= "<input type='text' class='input-sm' size='1' maxlength='1'  name='$name' value = '$value' id='$fid' aria-labelledby='$fidLabel' $place $fdis/>";
        $HTML .= (empty($name) and !empty($placeholder)) ? "" : "<label id='$fidLabel' for='$fid'>&nbsp;$label</label>";
        $HTML .= "</div>";

        // $HTML .= "</div>";
        return $HTML;
    }

    static function singleCheckbox(string $text, string $id, string $value = 'checked' /*, $defaultValue = null*/)
    {
        // printNice("function checkbox(string $text, string $id, '$value'");
        // TODO:  this doen't work at all, especially in popups where I need it most

        // $checked = 'checked';   // naive guess
        // if (!is_null($value) and !empty($value)) {
        //     $checked = $value=='checked' ? 'checked' : 'notchecked';  //
        // }

        $checked = $value == '1' ? 'checked' : '';  //

        $HTML = "\n";
        $HTML .= "<div class='form-check'>";
        $HTML .= "<input type='checkbox' class='form-check-input' $checked name='$id' $checked id='$id' style='vertical-align: middle;position: relative;bottom: 1px;' />";
        $HTML .= "<label class='form-check-label'>&nbsp;" . MForms::string($text) . "</label>";
        $HTML .= "</div>";
        return $HTML;
    }

    static function select(string $label, string $id, string $options, string $tooltip = '')
    {
        // use MForms:;formSelectList() to create the options
        $label = MForms::string($label);
        $tooltip = MForms::string($tooltip);

        $HTML = "\n";
        // $HTML .= '<div class="form-group">';
        $HTML .= "<label>$label</label>";
        $HTML .= "<select class='form-control' name='$id' id='$id'>";
        $HTML .= $options;
        $HTML .= '</select>';
        //   $HTML .= '</div>';

        return ($HTML);
    }

    static function formSelectList($aArray, $selected = '', $blank = true) // simple array ('a','b','c'), key is same as value
    {
        // creates <option>something</option>
        $HTML = '\n';

        if ($blank) { // default is trueu
            if ($selected == '' or (!in_array($selected, $aArray))) {
                // blank at top should be selected
                $HTML .= "<option selected='selected'></option>"; // an empty at the top
            } else {
                $HTML .= "<option></option>"; // an empty at the top
            }
        } else { // user has made a selection - can we find it?
            if (!in_array($selected, $aArray)) {
                // we don't have a blank, and we don't have a default
                $selected = $aArray[0]; // use the first value
            }
        }
        // printNice($aArray);
        // now build the selection list

        $optList = '';
        foreach ($aArray as $key => $option) {
            if (is_numeric(($key)))
                $key = $option;     // we only got options, not keys=>options.  don't i18n translate the keys
            $s = ($selected == $key) ? " selected='selected' " : ''; // is this the current value?
            $optList .= "<option{$s} value='$key'>" . MForms::string($option) . "</option>";
            // printNice("<option $s option='$key'>" . MForms::string($option) . "</option>");
        }

        // printNice($optList);
        $HTML .= $optList;
        return ($HTML);
    }


    static function tinyselect(string $text, string $name, string $id, string $options)
    {
        $HTML = "\n";
        $HTML .= "<div style='float:left;position:relative;margin-top:-5px'>&nbsp;";
        if (!empty($text))
            $HTML .= "<span style='font-size:10px;position:relative;'>$text<br></span>";

        $HTML .= "<select name='$name',id='$id'>
                    $options
                    </select>";
        $HTML .= "</div>";
        return ($HTML);
    }

    static function checkboxGroup(string $text, array $values)
    {
        $HTML = "\n";

        if (!empty($text))
            $HTML .= "<legend>$text</legend>";

        $HTML .= "  <div class='checkbox-wrapper d-flex flex-column align-items-start'>";
        foreach ($values as $value) {
            // $checked = ($value==$default) ? "checked" : '';  // changes empty or null string to definitely 0 or 1
            $HTML .= "<div class='form-check'>";
            $HTML .= "  <input  class='form-check-input' type='checkbox' name='$value' value='$value' />";
            $HTML .= "  <label class='form-check-label' for='$value'>$value</label>";
            $HTML .= "</div>";
        }
        $HTML .= '</div>';
        return $HTML;

        // $HTML .= "  <div class='checkbox-wrapper d-flex flex-column align-items-start'>";
        // foreach ($values as $value) {
        //     // $checked = ($value==$default) ? "checked" : '';  // changes empty or null string to definitely 0 or 1
        //     $HTML .= "<div class='form-check'>";
        //     $HTML .= "  <input  class='form-check-input' type='checkbox' id='{$id}_$value' name='$value' value='$value' />";
        //     $HTML .= "  <label class='form-check-label' for='{$id}_$value'>$value</label>";
        //     $HTML .= "</div>";
        // }
        // $HTML .= '</div>';


        return ($HTML);
    }



    static function fileForm(string $text, string $action, string $stuffToInsertIntoForm = '')
    {
        $text = MForms::string($text);

        $HTML = "\n";
        // $HTML = "<div style='border:solid blue 1px;margin:1px;'>";
        $HTML .= "<form method='post' enctype='multipart/form-data'>";
        $HTML .= $stuffToInsertIntoForm;        // because this is a fancy type of form

        $HTML .= MForms::security();
        $HTML .= MForms::hidden('p', $action);
        $HTML .= "<input type='file' name='fileToUpload' id='fileToUpload' />";
        $HTML .= "<input type='submit' value='$text' name='performFileSelect' style='float:right;' />";
        // $HTML .= "<input type='submit' value='&#128194; Open File' name='submit'>";
        $HTML .= "</form>";
        // $HTML .= "</div>";
        return ($HTML);
    }


    // this is just a the onclick of a button....
    static function popupOnclick(string $p = '', string $q = '', string $r = '')
    {
        $qS = (!empty($q)) ? "&q=$q" : '';
        $rS = (!empty($r)) ? "&r=$r" : '';

        return "window.open('?id={$GLOBALS['id']}&sesskey={$GLOBALS['session']}&p=$p{$qS}{$rS}','popupView');";
    }

    // use a function in case we have to move the images.  also different code for jpg, svg, maybe others
    static function image(string $assetPath, string $imageFile, string $caption = '', string $licence = '',  string $imageAlt = '', string $width = '250px', string $height = 'auto', string $extraStyle = '')
    {
        $HTML = '';

        // default height different for .svg
        $defaultWidth = (empty($imagewidth)) ? "250px" : "{$imagewidth}px";
        $defaultHeight = (empty($imageheight)) ? 'auto' : "{$imageheight}px";
        if (strtolower(substr($imageFile, -4)) == '.svg') {
            $defaultHeight = (empty($imageheight)) ? $defaultWidth : "{$imageheight}px";  // set height same as width
        }


        $assetPathImg = "{$assetPath}{$imageFile}";
        // printNice($assetPathImg);
        // if (!file_exists($assetPathImg)) {
        //     $assetPathImg = "pix/missingImage.jpg";
        //     $imageCaption = "$caption<br>MISSING:  {$assetPath}{$imageFile}";
        // }

        $HTML .= "<div style='width:$width;height:$height;$extraStyle;margin:5px;'>";

        if (strtolower(substr($imageFile, -4)) == '.svg') {
            $height = ($height == 'auto;') ? $width : $height;  // set height same as width if defaulted to auto
            // $HTML .= "<figure class='figure' $style >";
            $HTML .= "<svg viewBox='0 0 $width $height'  style='height:{$height}px; width:{$width}px;padding:3px;'>
                            <use href='$assetPathImg'></use>
                      </svg>";
            $HTML .= "<object viewBox='0 0 $width $height' data='$assetPathImg' height='$height' width='$width'></object>";
            // $HTML .= "</figure>";
        } else {
            // $style = "style='margin:5px;width:$width;height:$height;{$extraStyle}'";
            // $HTML .= "<figure class='figure' $style>";
            $HTML .= "<img style='width:$width;height:$height;padding:3px;' src='$assetPathImg' alt='$imageAlt' />";
            // $HTML .= "</figure>";
        }
        // $HTML .= "<hr />";
        if (!empty($licence)) {
            // printNice($licence);
            $HTML .= "<figcaption class='figure-caption' style='line-height:88%;text-align:right;vertical-align: top;font-size:12px;float:right;padding:2px;'>$licence</figcaption>";
        }
        if (!empty($caption)) {
            // printNice($caption);
            $HTML .= "<span style='padding:5px;line-height:92%;'>$caption</span>";
        }
        $HTML .= '</div>';

        // printNice($HTML);
        return $HTML;
    }

    // this is a wrapper for the Moodle String API  https://docs.moodle.org/dev/String_API
    static function string(string $identifier, string $a = ''): string
    {

        if (str_contains($identifier, "'")) {
            assertTrue(false, "i18n must not contain a single-quote - " . neutered($identifier));
            return $identifier;
        }

        if (empty($identifier))
            return '';

        if ($GLOBALS['debugMode']) {
            // just a check in case we don't have an open database
            if (!empty($_SESSION['currentOpenTextbook'])) {
                collectForMoodleStringApi($identifier);
            }
            return ($GLOBALS['showi18n']) ? "$identifier***" : $identifier;
        }

        return $identifier;
    }

    // a nice way of saying ...             $HTML .= "<h1>".Mforms::string('EDIT ACTIVITY')."</h1>";
    static function heading(string $level, string $text)
    {
        assertTrue(str_contains('h1.h2.h3.h4.h5.h6.p', $level), "level should be 'h1', 'h2', or similar, got '$level'");
        return  "<$level>" . neutered(Mforms::string($text)) . "</$level>";
    }

    static function render_from_template(string $template, array $data): string
    {
        global $PAGE;
        $OUTPUT = $PAGE->get_renderer('mod_mathcode');
        $output = $OUTPUT->render_from_template("mathcode/$template", $data);
        return $output;
    }

    static function bakeryTicket(): int
    {
        if (!isset($_SESSION['bakeryTicket'])) {
            $_SESSION['bakeryTicket'] = 1;
        }
        $_SESSION['bakeryTicket'] += 1;
        return ($_SESSION['bakeryTicket']);
    }
}


function collectForMoodleStringApi($translation)
{
    // very short strings don't go into translator
    if (strlen($translation) <= 2)
        return false;

    // strings that start with a number don't go into translator
    if (is_numeric(substr($translation, 0, 1)))
        return false;

    // might be a unicode character (icon)
    if (substr($translation, 0, 1) == '#')
        return false;

    if (str_contains($translation, '_'))
        return false;

    // create a 'short' version of the string

    // firststrip out some control characters
    $stripped = $translation;
    foreach (['/', '(', '=', ')', ',', ':', ';', '&', '-', '?'] as $badChar)
        $stripped = str_replace($badChar, '', $stripped);

    // then ucword the first three words
    $explod = explode(' ', ucwords(strtolower($stripped)));
    $identifier = $explod[0];
    if (count($explod) > 1)
        $identifier .= $explod[1];
    if (count($explod) > 2)
        $identifier .= $explod[2];


    // over time, this will create a full translation table
    // TODO lock, and then go over the code looking for
    //      records where the short is different from the actual, and
    //      update the actual with the short

    // // just a check in case we don't have an open database
    // if (!empty($_SESSION['currentOpenTextbook'])) {
    //     $i18n = new i18n();
    //     $i18n->addString($identifier, $translation);
    // }
    return $translation;

    // future ///////////////

    global $string;
    if (!isset($string)) {
        printNice('loading strings');
        $string = [];
        require_once('./lang/en/mathcode.php');
    }
    if (!isset($string[$translation])) {
        $date = date('y-m-d');
        $str = '$string';
        printNice("$str ['$translation'] = '$translation';  // added  $date ");
        $ret = "[[$translation]]";
    } else {
        // get_string is defined in Moodle
        // $ret = get_string($translation, 'mathcode', $a);
    }
    return $ret;
}

/*
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class simplehtml_form extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'email', get_string('email')); // Add elements to your form
        $mform->setType('email', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('email', 'Please enter email');        //Default value

    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
*/
