<?php

namespace Blending;

use Exception;

// these functions are as XSS-resistant as I can make them.

class MForms
{

    // override Bootstrap style
    static $buttonStyle = "font-size:140%;border:solid 1px dimgrey;border-radius:10px;margin:3px;vertical-align:top;";
    static $badgeStyle = "border:solid 1px dimgrey;border-radius:5px;margin:3px;vertical-align:top;";



    //  MForms::rowOpen(5);
    //  MForms::rowNextCol(7);
    //  MForms::rowClose();
    static function rowOpen(int $cols)
    {
        return ("<div class='row'><div class='col-$cols'>");
    }
    static function rowNextCol(int $cols)
    {
        return "</div><div class='col-$cols'>";
    }
    static function rowClose()
    {
        return ("</div></div>");
    }



    ///////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////

    // version of moodle get_sting
    static function get_string(string $s)
    {
        if (empty($s))      // because we do this automatically, eg: textarea may use placeholder instead of label
            return '';

        //turn this on to quickly find untranslated labels
        if (ctype_upper(substr($s, 0, 1))) {        // is the first letter uppercase?
            printNice($s, "Did you intend NOT to translate '$s'");
            return $s;
        }


        $s2 = '';
        if (!$GLOBALS['isDebugging']) {   //can't access Moodle while using xDebug
            try {
                $s2 = \get_string($s, 'mod_blending');
            } catch (Exception $e) {
                assertTrue(false, $s);
                $s2 = $s;
            }
        }
        return $s2;
    }

    // eg:   MForms::htmlElement('input', ['type'=>'hidden','name'=>'name','value'=>'value','id'=>'id']);
    static function htmlUnsafeElement(string $tag, string $content = '', array $attributes = []): string
    {
        $HTML = "<" . htmlentities($tag);
        foreach ($attributes as $key => $value) {
            // some exceptions
            switch ($key) {
                case 'href':
                    $value = htmlentities($value, ENT_QUOTES);

                    $value = str_replace('(', '', $value);  // avoid javascript:alert(document.cookie)
                    $HTML .= " href='$value'";
                    break;
                case 'onclick':
                    if (!empty($value)) {
                        // no brackets in $message, stricter than htmlentities() because very dangerous
                        foreach (['(', ')', '{', '}', '[', ']', '\u', '\x', '$', '"', "'", "`", "/"] as $danger) {
                            $value = str_replace($danger, '', $value);
                        }
                        $HTML .= " onclick='return confirm(`$value`)'";
                    }
                    break;
                case 'onclickJS':
                    if (!empty($value)) {
                        // no brackets, backtics, or / in $message, stricter than htmlentities() because very dangerous
                        foreach (['(', ')', '{', '}', '[', ']', '\u', '\x', '$', '"', "'", "`", "/"] as $danger) {
                            $value = str_replace($danger, '', $value);
                        }
                        $HTML .= " onclick='$value();return false;'";
                    }
                    break;
                default:
                    if (!empty($value))  // usually don't include empty values
                        $HTML .= ' ' . htmlentities($key) . "='" . htmlentities($value, ENT_QUOTES) . "'";
            }
        }
        if ($content == '') {
            $HTML .= ' />';
        } else {
            $HTML .= '>';
            $markdown = new Markdown();
            $markdown->type = 'span';
            $HTML .= $markdown->render_block($content);   // also sanitizes
            $HTML .= "</{$tag}>";
        }
        return $HTML;
    }


    static function cmid()
    {
        return    MForms::htmlUnsafeElement('input', '', ['type' => 'hidden', 'name' => 'cmid', 'value' => $GLOBALS['cmid']]);
    }

    static function hidden(string $name, string $value, string $id = '')
    {
        return MForms::htmlUnsafeElement(
            'input',
            '',
            ['type' => 'hidden', 'name' => $name, 'value' => $value, 'id' => $id]
        );
    }

    static function inputText(string $label, string $name, ?string  $value = '', string $id = '', string $inputAttr = '', bool $inline = false, string $placeholder = '', string $tooltip = '', string $trailer = '')
    {
        $label = MForms::get_string($label);
        $tooltip = MForms::get_string($tooltip);
        $placeholder = MForms::get_string($placeholder);

        // if the label is empty, then intent is to use the placeholder

        assertTrue(!(empty($name) and empty($id)), "no name, no ID, need at least one!");

        // the XSStester keeps triggering this error
        // if (!empty($inputAttr)) {  // must be 'disabled' or 'required'
        //     assertTrue($inputAttr == 'disabled' or $inputAttr == 'required' or $inputAttr == 'readonly' or $inputAttr == 'autofocus');
        // }

        $fid = (!empty($id)) ? $id : MForms::bakeryTicket();
        $fidLabel = $fid . '_label';

        // readonly instead of disabled


        $title = empty($tooltip) ? '' : "title='" . htmlentities($tooltip) . "'";

        $inln = ($inline) ? "class='form-inline' style='margin-bottom:2px;'" : "class='form-group'";

        $f = "\n";
        $f .= "<div $inln>";
        // $f .= (empty($name) and !empty($placeholder)) ? "" : "<label id='$fidLabel' for='$fid'>$label </label>";

        if (!empty($name) or !empty($placeholder)) {
            $f .= MForms::htmlUnsafeElement(
                'label',
                $label,
                [
                    'id' => $fidLabel,
                    'for' => $fid,
                ]
            );
        }

        $name = (empty($name)) ? strval(MForms::bakeryTicket()) : $name;  // W3 says name should not be empty
        $f .= MForms::htmlUnsafeElement(
            'input',
            '',
            [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $name,
                'value' => $value,
                'aria-labelledby' => $fidLabel,
                'placeholder' => $placeholder,
                'title' => $tooltip,
                'readonly' => ($inputAttr == 'disabled') ? "readonly" : '',

                //  how to do 'required' ???
                // $fdis = ($inputAttr == 'required') ? 'required' : $fdis;
            ]
        );

        $f .= htmlentities($trailer);
        $f .= "</div>";

        return ($f);
    }



    static function inputNumber(string $label, string $name, float $value = 0, string $id = '', bool $disabled = false)
    {
        $label = MForms::get_string($label);

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

        $label = MForms::get_string($label);
        $placeholder = MForms::get_string($placeholder);

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



    // for a disabled button, leave name empty
    static function submitButton(string $text, string $color, string $name = '', bool $solid = true, string $areYouSure = '', $title = '', bool $isBadge = false)
    {
        $text = MForms::get_string($text);
        $title = MForms::get_string($title);
        $areYouSure = MForms::get_string($areYouSure);


        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $ret = MForms::htmlUnsafeElement(
            "button",
            $text,      // don't translate, often it's a name.
            [
                "type" => 'submit',
                'class' => (($isBadge) ? 'badge' : 'button') . " btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color",
                'style' => ($isBadge) ? MForms::$badgeStyle : MForms::$buttonStyle,
                'aria-label' => (!empty($title)) ? $title : $text,
                'name'=> $name,
                'title' => (!empty($title)) ? $title : '',
                'onclick' => $areYouSure,
            ]
        );


        return ($ret);
    }



    static function submitBadge(string $text, string $color, string $name = '', bool $solid = true, string $areYouSure = '', $title = '')
    {
        return MForms::submitButton($text, $color, $name, $solid, $areYouSure, $title, true);
    }




    // same as 'submitButton' but expects to be handled by our Javascript
    static function popupSubmitButton(string $text, string $color, string $name = '', bool $solid = true, string $areYouSure = '', $extraClass = '', $extraStyle = '')
    {
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $n = (empty($name)) ? 'disabled="disabled"' : "name='$name'"; // if no name, then disable button
        $bakeryTicket = $_SESSION['bakeryTicket'];  // was 'bakeryticket()' but don't want a new one
        $saver = "form=\'$bakeryTicket\'";

        $buttonClass = "btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color";


        $HTML =
            "<button type='submit' aria-label='$text' class='$buttonClass rounded' $n onclick='$areYouSure' style='margin:3px;{$extraStyle}'>$text</button>";

        $HTML .= MForms::cmid();
        return ($HTML);
    }


    // submit buttons that are EXTERNAL to the form
    static function externalSubmitButton(int $formID, string $text, string $color, string $p, string $q,  bool $solid = true, string $areYouSure = '')
    {
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $n = (empty($p)) ? 'disabled="disabled"' : "name='$p'"; // if no name, then disable button
        // $saver = (empty($name)) ? '' : 'document.getElementById("'.$bakeryTicket.'").value = "'.$name.'" ';
        $saver = "form='$formID'";

        $buttonClass = "btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color";

        $confirm = '';
        if (!empty($areYouSure)) {
            $areYouSure = str_replace("'", "’", $areYouSure);  // single quotes cause problems, use the tick instead
            $confirm = "onclick=\"$saver;return(confirm('$areYouSure - Are you sure?'));\"";
        }

        $style = "style='padding:1px 5px;'";

        $HTML =
            "<input type='submit' aria-label='$text' name='$p' value='$text' class='$buttonClass rounded' $style $confirm />";

        return ($HTML);
    }


    // onClickButton fires a javascript function $p with string parameters $q and $r
    // eg:   StopWatch.Start(q,r)  // $p = 'StopWatch.Start';
    static function onClickButton(string $text, string $color, string $onClick, bool $solid = true, string $title = '')
    {
        assertTrue(!empty($text));

        $ret = MForms::htmlUnsafeElement(
            "button",
            MForms::get_string($text),
            [
                'onclickJS' => $onClick,
                'style' => MForms::$buttonStyle,
                'class' => "button btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color",
                'aria-label' => (!empty($title)) ? $title : $text,
                'title' => (!empty($title)) ? $title : '',

            ]
        );
        return ($ret);
    }



    static function navButton(string $text, string $color, string $name = '')
    {
        assertTrue(!empty($text));
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $text = MForms::get_string($text);

        $n = (empty($name)) ? 'disabled="disabled"' : "name='$name'"; // if no name, then disable button
        $bakeryTicket = $_SESSION['bakeryTicket'];  // was 'bakeryticket()' but don't want a new one
        $saver = "form=\'$bakeryTicket\'";

        $buttonClass = "btn btn-$color";

        $confirm = '';

        $HTML =
            "<a  aria-label='$text' class='$buttonClass' $n $confirm >$text</a>";

        $HTML .= MForms::cmid();
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

    static function navLinkButton(string $text, string $color, string $URL, string $extraStyle = '')
    {
        assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']));

        $textcolor = 'white';
        if ($color == 'secondary' or $color == 'warning' or $color == 'light')
            $textcolor = 'black';

        $style = "style='color:$textcolor;margin:3px;{$extraStyle}'";
        $HTML = "
            <button type='submit' class='nav-link btn-$color rounded' href='$URL  role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' $style>
                 $text
            </button>";
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

    static function imageButton(string $imageName, int $size, string $title, string $p, string $q = '', string $r = '', string $color = 'blue')
    {

        $HTML = '';

        $buttonClass = '';
        $aria = "aria-label='$title' title='$title'";
        $href = "href='" . MForms::linkHref($p, $q, $r) . "'";
        $image = htmlentities($imageName);
        $aStyle = "style='border:solid 3px $color;border-radius:12px;filter: drop-shadow(6px 2px 2px $color);background-color:white;'";
        $HTML .= "<div style='border:solid white 20px;'>";   // surround button with a border
        $HTML .= "<a type='button' role='button' $buttonClass $href $aStyle $aria>";
        $HTML .= "<table><tr><td style='text-align:center;'><img src='pix/$image' height='$size' /></td></tr>";
        $HTML .= "<tr><td style='color:black;text-align:center;padding:3px;'>" . \get_string($title, 'mod_blending') . "</td></tr></table>";
        $HTML .= "</a>";
        $HTML .= "</div>";

        return $HTML;
    }


    static function unicodeButton(string $code, int $size, string $title, string $p, string $q = '', string $r = '')
    {

        $HTML = '';

        $buttonClass = '';
        $style = "style='font-size:{$size}px;";
        $confirm = '';
        $aria = "aria-label='$title' title='$title'";
        $href = MForms::linkHref($p, $q, $r);

        $HTML .= "<a type='button' role='button' $buttonClass $href $style $confirm $aria>$code</a>";

        return $HTML;
    }

    static function abstractButton(string $text, string $color, string $p = '', string $q = '', string $r = '', bool $solid = true, string $areYouSure = '', string $title = '', bool $isBadge = false)
    {
        assertTrue(!empty($text), "button with no name (p = '$p')");
        // assertTrue(in_array($color, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link']), $color);

        if (empty($p))      // disabled buttons are always NOT SOLID
            $solid = false;


        $ret = MForms::htmlUnsafeElement(
            "a",
            $text,      // don't translate, often it's a name.
            [
                'href' => MForms::linkHref($p, $q, $r),
                'style' => ($isBadge) ? MForms::$badgeStyle : MForms::$buttonStyle,
                'class' => (($isBadge) ? 'badge' : 'button') . " btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color",
                'onclick' => $areYouSure,
                'aria-label' => (!empty($title)) ? $title : $text,
                // 'title' => (!empty($title)) ? $title : '',

            ]
        );

        return ($ret);
    }

    // easier to read in code if we have separate button and badge calls, but they are really the same
    static function button(string $text, string $color, string $p = '', string $q = '', string $r = '', bool $solid = true, string $areYouSure = '', string $title = '')
    {
        return MForms::abstractButton(MForms::get_string($text), $color, $p, $q, $r, $solid, $areYouSure, $title, false);  // add isbadge=>true
    }


    // easier to read in code if we have separate button and badge calls, but they are really the same
    static function badge(string $text, string $color, string $p = '', string $q = '', string $r = '', bool $solid = true, string $areYouSure = '', string $title = '')
    {
        return MForms::abstractButton(MForms::get_string($text), $color, $p, $q, $r, $solid, $areYouSure, $title, true);  // add isbadge=>true
    }



    static function linkHref(string $p, string $q = '', string $r = ''): string  // only p is required
    {

        $qS = (strlen($q) > 0) ? "&q=" . urlencode($q) : '';
        $rS = (strlen($r) > 0) ? "&r=" . urlencode($r) : '';  // horrible - string '0' is empty in PHP!

        $cmid = $GLOBALS['cmid'];
        $href = "?cmid=$cmid&p=$p{$qS}{$rS}";
        return $href;
    }




    // static function fileForm(string $text, string $action, string $stuffToInsertIntoForm = '')
    // {
    //     $text = MForms::get_string($text);

    //     $HTML = "\n";
    //     // $HTML = "<div style='border:solid blue 1px;margin:1px;'>";
    //     $HTML .= "<form method='post' enctype='multipart/form-data'>";
    //     $HTML .= $stuffToInsertIntoForm;        // because this is a fancy type of form

    //     $HTML .= MForms::cmid();
    //     $HTML .= MForms::hidden('p', $action);
    //     $HTML .= "<input type='file' name='fileToUpload' id='fileToUpload' />";
    //     $HTML .= "<input type='submit' value='$text' name='performFileSelect' style='float:right;' />";
    //     // $HTML .= "<input type='submit' value='&#128194; Open File' name='submit'>";
    //     $HTML .= "</form>";
    //     // $HTML .= "</div>";
    //     return ($HTML);
    // }


    static function bakeryTicket(): int
    {
        if (!isset($_SESSION['bakeryTicket'])) {
            $_SESSION['bakeryTicket'] = 1;
        }
        $_SESSION['bakeryTicket'] += 1;
        return intval(($_SESSION['bakeryTicket']));
    }

    static function markdown(string $source): string
    {
        $md = new Markdown($source);
        return $md->render();
    }

    // Bootstrap modal.  Returns a badge that triggers modal.   Both the title and the text are markdown
    static function modalButton(string $buttonText, string $color, string $modalTitle, string $modalText, bool $solid = true, string $buttonTitle = '', bool $isBadge = false)
    {
        $HTML = '';
        $bakery = 'Modal' . MForms::bakeryTicket();       // unique number

        // button that launches
        $HTML .= MForms::htmlUnsafeElement(
            "a",
            $buttonText,      // don't translate, often it's a name.
            [
                'data-toggle' => 'modal',
                'data-target' => "#{$bakery}",
                'style' => ($isBadge) ? MForms::$badgeStyle : MForms::$buttonStyle,
                'class' => (($isBadge) ? 'badge' : 'button') . " btn btn-sm btn-" . (($solid) ? '' : 'outline-') . "$color",
                'aria-label' => (!empty($buttonTitle)) ? $buttonTitle : $buttonText,
                'title' => (!empty($title)) ? $title : '',
            ]
        );


        $HTML .= "<!-- Modal -->";
        $HTML .= "<div class='modal fade' id='$bakery' tabindex='-1' role='dialog' aria-labelledby='$bakery' aria-hidden='true'>";
        $HTML .= "  <div class='modal-dialog' role='document'>";
        $HTML .= "    <div class='modal-content'>";
        $HTML .= "      <div class='modal-header'>";
        $HTML .= "        <h5 class='modal-title' id='{$bakery}label'>" . MForms::markdown($modalTitle) . "</h5>";
        $HTML .= "        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        $HTML .= "          <span aria-hidden='true'>&times;</span>";
        $HTML .= "        </button>";
        $HTML .= "      </div>";
        $HTML .= "      <div class='modal-body'>";
        $HTML .= MForms::markdown($modalText);
        $HTML .= "      </div>";
        $HTML .= "      <div class='modal-footer'>";
        $HTML .= "        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
        $HTML .= "      </div>";
        $HTML .= "    </div>";
        $HTML .= "  </div>";
        $HTML .= "</div>";

        return $HTML;
    }


    static function alert($message, $color='primary')
    {
        $m = MForms::markdown($message);
        $c = htmlentities($color);

        $HTML =
            "<div style='border: 2px solid black;' class='alert alert-$c' role='alert'>
                    <b>$m</b>
                </div>";

       return($HTML);
    }

    // create a CC attribution
    static function ccAttribution(string $title, string $sourceURL, string $author, string $authorURL, string $ccOption, string $ccVersion = '', $prefix = ''): string
    {
        $licenseLink = '';
        $titleLink = '';

        $ccOptions = ['CC BY', 'CC BY-SA', 'CC BY-NC', 'CC BY-NC-SA', 'CC BY-ND', 'CC BY-NC-ND', 'CC0', 'Pixabay', 'GNU', 'Ignore', 'Unknown'];
        $ccVersions = ['4.0', '3.0', '2.5', '2.0', '1.0', ''];

        if ($ccOption == 'Ignore') return '';

        $debugMsg = htmlentities("$title, $sourceURL, $author, $authorURL, $ccOption, $ccVersion");
        assertTrue($a1 = in_array($ccOption, $ccOptions), "Could not find valid CC option for '$ccOption' in '$debugMsg'");
        assertTrue($a2 = in_array($ccVersion, $ccVersions), "Could not find valid CC version for '$ccVersion' in '$debugMsg'");
        assertTrue($a3 = !(empty($sourceURL) and empty($author) and empty($authorURL)), "No source or author? in '$debugMsg'");

        // don't go farther, might be an XSS attack
        if (!$a1 or !$a2 or !$a3) {
            printNice($a1);
            printNice($a2);
            printNice($a3);
            return "Invalid CC Attribution";
        }

        // Licence BY-ND-NC 1.0 was called BY-ND-NC.   just fix it here
        if ($ccOption == 'CC BY-NC-ND' and $ccVersion == '1.0')
            $ccOption = 'CC BY-ND-NC';   // change the name slightly

        $sourceURL = htmlentities($sourceURL);
        $title = htmlentities($title);
        $author = htmlentities($author);
        $authorURL = htmlentities($authorURL);

        // prepare title and link to original source
        $titleLink = '';
        if (!empty($title)) {
            if (!empty($sourceURL)) {
                $titleLink = "<a href='$sourceURL' target='_blank'>$title</a>";
            } else {
                $titleLink = $title;
            }
        } else {
            $title = 'Untitled';
            if (!empty($sourceURL)) {
                $titleLink = "<a href='$sourceURL' target='_blank'>Untitled</a>";
            }
        }

        // prepare the author and his link
        $authorLink = '';
        if (!empty($author)) {
            if (!empty($authorURL)) {
                $authorLink = "<a href='$authorURL' target='_blank'>$author</a>";
            } else {
                $authorLink = "$author";
            }
        }

        // special case - don't have a source but DO have an authorURL
        if (empty($author) and empty($sourceURL) and !empty($authorURL)) {
            $titleLink = "<a href='$authorURL' target='_blank'>$title</a>";
            $authorLink = '';
        }

        if (!empty($prefix)) {        // usually something like 'Adapted from'
            $titleLink = htmlentities($prefix) . ' ' . $titleLink;
        }

        if (substr($ccOption, 0, 2) == 'CC') {
            // handle the cc licences first
            if ($ccOption == 'CC0') {
                $ccBy = "CC0 - Public Domain";
                $licenseLink = "<a href='https://creativecommons.org/publicdomain/zero/1.0/' target='_blank'>CC0 - Public Domain</a>";
            } else {
                $ccBy = strtolower(substr($ccOption, 3));
                $licenseLink = "<a href='https://creativecommons.org/licenses/$ccBy/$ccVersion/' target='_blank'>$ccOption $ccVersion</a>";
            }
        }

        if ($ccOption == 'Pixabay') {
            $licenseLink = "<a href='https://pixabay.com/service/license/' target='_blank'>Pixabay License</a>";
            return "$authorLink<br />$licenseLink<br />";
        }

        // if ($ccOption == 'Unknown') {
        //     // $licenseLink = "";//<a href='https://pixabay.com/service/license/' target='_blank'>Pixabay License</a>";
        //     return "$authorLink<br />$licenseLink<br />";
        // }


        // assemble a nice license.  $titleLink or $authorLink might be empty
        if (empty($authorLink)) {
            return "$titleLink / $licenseLink<br />";
        } elseif (empty($titleLink)) {
            return "$authorLink / $licenseLink<br />";
        } else {
            return "$titleLink / $authorLink / $licenseLink<br />";
        }


        // // handles CC BY
        // if (substr($image['ccOption'], 0, 3) == 'CC ') {
        //     if (!empty($image['ccVersion'])) {
        //         $lnk = strtolower(substr($image['ccOption'], 3));
        //         $licence = "<a href='https://creativecommons.org/licenses/$lnk/{$image['ccVersion']}' target='_blank'>{$image['ccOption']} {$image['ccVersion']}</a>'";
        //     }
        // }
        // if ($image['ccOption'] == 'CC0') {
        //     $licence = "<a href='https://creativecommons.org/publicdomain/zero/1.0/' target='_blank'>CC 1.0 Public Domain</a>";
        // }

        assertTrue("did not expect to get here with $ccOption");
        return '';
    }


    //    <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank"> Creative Commons Attribution-ShareAlike 3.0 Unported</a>
}



// The Markdown class is based on
// https://github.com/pfalcon/udownmark
// Copyright (c) 2019 Paul Sokolovsky. MIT License.

class Markdown  // a tiny version of markdown
{

    // standard markdown
    //  **text**        BOLD
    //  *text*          ITALIC
    //  `teletype`      red monospace on grey, use of keys and keywords
    //  ~~strike~~      STRIKE
    //  ![alt](src)     IMAGE
    //  [alt](src)      URL
    //  * text          BULLET LIST
    //  1 text          NUMBER LIST  (always '1'  but list renumbers automatically)
    //  > blockquote

    // line markdown (alone on line)
    //  ***             HORIZONTAL RULE
    //  ```             PRE BLOCK
    //  #H1
    //  ## H2
    //  ### H3
    //  #### H4
    //  ##### H5
    //  ###### H6


    // custom markdown
    //   ***text***       BOLD and highlight in yellow
    // %% funct(param) %%   safe functions (only sound() and spelling() so far)

    var $line = '';
    var $output = '';
    var $block = '';
    var $type = 'None';

    function __construct(string $input = '')      // might be empty
    {
        $this->line = $input;
        $this->output = '';
        $this->block = '';
        $this->type = 'None';
    }



    function render_block($block)
    {
        if (empty($block))
            return '';


        // CUSTOM FUNCTION   %% f(n) %%
        // BUT ONLY THE TWO 'SAFE' FUNCTIONS I INCLUDE BELOW !!
        $block = preg_replace_callback(
            '/\%\%(.+?)\%\%/i',
            function ($matches) {
                $content = substr($matches[0], 2, -2);   // don't use htmlentities yet
                return SafeEval::eval($content);
            },
            $block
        );


        // CUSTOM BOLD   ***text***
        $block = preg_replace_callback(
            '/\*\*\*(.+?)\*\*\*/i',
            function ($matches) {
                return '<strong style="background-color:yellow;">' . htmlentities(substr($matches[0], 3, -3)) . '</strong>';
            },
            $block
        );


        // teletype   `text`
        $block = preg_replace_callback(
            '/`(.+?)`/i',
            function ($matches) {
                $content = htmlentities(substr($matches[0], 1, -1));
                return "<code>$content</code>";
            },
            $block
        );


        // bold   **text**      // use <em>
        $block = preg_replace_callback(
            '/\*\*(.+?)\*\*/i',
            function ($matches) {
                return '<strong>' . htmlentities(substr($matches[0], 2, -2)) . '</strong>';
            },
            $block
        );

        // // bold alt   _text_        // uses <strong>
        // $block = preg_replace_callback(
        //     '/_(.+?)_/i',
        //     function ($matches) {
        //         return '<strong>' . htmlentities(substr($matches[0], 1, -1)) . '</strong>';
        //     },
        //     $block
        // );

        // italic  *text*
        $block = preg_replace_callback(
            '/\*(.+?)\*/i',
            function ($matches) {
                return '<i><strong>' . htmlentities(substr($matches[0], 1, -1)) . '</strong></i>';
            },
            $block
        );

        // strike ~~text~~
        $block = preg_replace_callback(
            '/~~(.+?)~~/i',
            function ($matches) {
                return '<strike>' . htmlentities(substr($matches[0], 2, -2)) . '</strike>';
            },
            $block
        );

        // img  ![alt](url)
        $block = preg_replace_callback(
            '/!\[(.*?)\]\((.+?)\)/i',
            function ($matches) {
                $max = $GLOBALS['mobileDevice']?300:200;
                $img =  htmlentities($matches[2]);
                $alt = (!empty($matches[1])) ? htmlentities($matches[1]) . '"' : '';
                $return = "<figure style='float:right;border:solid 10px white;'>
                                <a href='pix/catinhat2.jpg' target='_blank'>
                                   <img style='width:100%;max-width:{$max}px;' src='$img' $alt />
                                </a>
                                <figcaption style='line-height:14px;'><span style='font-size:14px;'>$alt</span></figcaption>
                            </figure>";
                return $return;
            },
            $block
        );

        // ulr [text](url)
        $block = preg_replace_callback(
            '/\[(.*?)\]\((.+?)\)/i',
            function ($matches) {
                return '<a href="' . filter_var($matches[2], FILTER_SANITIZE_URL) . '" rel="noopener noreferrer nofollow" target="_blank">' . htmlentities($matches[1]) . '</a>';
            },
            $block
        );


        if ($this->type == "list")
            $tag = "li";
        elseif ($this->type == "nlist")
            $tag = "li";
        elseif ($this->type == "bquote")
            $tag = "blockquote";
        elseif ($this->type == "span")
            $tag = "span";
        else
            $tag = "p";

        $blockOutput = "<$tag>" . $block . "</$tag>";
        $this->output .= $blockOutput;  // if we are parsing something large
        return $blockOutput;        // some MForm functions call this method directly
    }

    function flush_block()
    {
        $this->render_block($this->block);
        $this->block = "";
        // $this->type = "None";
    }


    function render_line()
    {
        $line_trim = rtrim($this->line);

        // horizontal rule    *** on a line by itself
        if (trim($this->line) == "***") {
            $this->type = "None";
            $this->output .= "<hr />";
            $this->line = '';
            return;
        }


        # Handle pre block content/end
        if ($this->type == "```" or $this->type == "~~~") {
            if (str_contains($this->line, $this->type)) {
                $this->type = "None";
                $this->output .= "</pre>";
                $this->line = '';
            } else {
                $this->output .= htmlentities($this->line) . '<br>';
            }
            return;
        }

        # Handle pre block start
        if (str_contains($this->line, "```") or str_contains($this->line, "~~~")) {
            $this->type = '~~~';
            $this->output .= "<pre>";
            $this->line = '';
            return;
        }
        if ($this->type == "~~~") {    // but line doesn't start *
            $this->output .= "</pre>";
            $this->type  = 'None';
            return;
        }

        # Empty line ends current block
        if (empty($line_trim) and !empty($this->block)) {
            $this->flush_block();
            return;
        }

        # Repeating empty lines are ignored - TODO
        if (empty($line_trim))
            return;

        # Handle heading
        if (str_starts_with(trim($this->line), "#")) {
            $this->line = trim($this->line);        //
            $this->flush_block();
            $level = 0;
            while (str_starts_with($this->line, "#")) {
                $this->line = substr($this->line, 1);
                $level += 1;
            }
            $this->output .= "<h{$level}>" . htmlentities(trim($this->line)) . "</h{$level}>";
            $this->line = '';
            return;
        }



        /////////// bullet list
        if (str_starts_with($this->line, "* ")) {
            if ($this->type != "list") {
                $this->output .= "<ul>";
            }
            $this->type = "list";
            $this->block = substr($this->line, 2);
            $this->flush_block();
            return;
        }
        if ($this->type == "list") {    // but line doesn't start *
            $this->output .= "</ul>";
            $this->type  = 'None';
            return;
        }


        /////////// blockquote
        if (str_starts_with(trim($this->line), "&gt; ")) {    // mangled blockquote
            if ($this->type != "bquote") {
                $this->output .= "<blockquote>";
            }
            $this->type = "bquote";
            $this->block = substr(trim($this->line), 4);  // 4 because '&gt; '
            $this->flush_block();
            return;
        }
        if ($this->type == "bquote") {    // but line doesn't start *
            $this->output .= "</blockquote>";
            $this->type  = 'None';
            return;
        }




        /////////// number list
        if (str_starts_with($this->line, "1. ")) {
            if ($this->type != "nlist") {
                $this->output .= "<ol>";
            }
            $this->type = "nlist";
            $this->block = substr($this->line, 2);
            $this->flush_block();
            return;
        }
        if ($this->type == "nlist") {    // but line doesn't start *
            $this->output .= "</ol>";
            $this->type  = 'None';
            return;
        }

        $this->block .= $this->line;
    }
    function render(): string
    {
        // immediately - NO HTML <tags> allowed in markdown
        // DO NOT COMMENT THIS OUT.  REGEX WILL HIDE XSS ATTACKS FROM FILTERS
        $this->line = str_replace('<', '&lt;', $this->line);
        $this->line = str_replace('>', '&gt;', $this->line);   // conflicts with blockquote

        // to make my life easier treat a line ending with \  (space \)
        // as a join to the next line (not standard markup, actually opposite of commonmark)

        $this->line = str_replace(" \\\n", ' ', $this->line);

        $lines = explode("\n", $this->line);

        // blocks are possibley multiline, eg ~~~ to ~~~, have no markup inside
        // lines

        foreach ($lines as $line) {

            $this->line = rtrim($line);
            $this->render_line();     // line level cmds like ~~~

            if ($this->type == "None") {    // look for start of block
                $this->block = $this->line;
                $this->flush_block();    // everything inside a <p>...</p>
            }
        }
        return $this->output;
    }
}


// provide functions  sound('ah')  and  spelling('sp')
// not even an attempt to parse.  just keep it safe.
class SafeEval
{


    static function eval(string $f): string
    {
        $HTML = '';
        $f = trim($f);

        if (substr($f, 0, strlen('sound')) == 'sound') {

            $text = htmlentities(substr($f, strlen('sound') + 2, -2));

            $HTML .= "<span style='font-family:san-serif;
            color:blue;
            border:solid 1px grey;
            border-radius:5px;
            text-align:center;
            background:#ffff66;
            margin:0px;'><b>&nbsp;/$text/&nbsp;</b></span>";
        } elseif (substr($f, 0, strlen('spelling')) == 'spelling') {

            $text = htmlentities(substr($f, strlen('spelling') + 2, -2));

            $HTML .= "<span style='font-family:san-serif;
            color:blue;
            border:solid 1px grey;
            border-radius:5px;
            text-align:center;
            background:#ffedff;
            margin:0px;'>&nbsp;[$text]&nbsp;</span>";
        }


        return $HTML;
    }
}
