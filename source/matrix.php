<?php  namespace Blending;


// implements the morpheme matrix, inspired by Pete Bowers
//

// incomplete - final-L on multi-syllable words gets doubled.  we need to check whether the stress
//     is on the final syllable or not.




define('MM_PREFIX',  1);
define('MM_POSTFIX', 2);

// list of recommended prefixes and postfixes
$prefixList  = "a,an,con,de,en,em,ev,ex,re,un";
$postfixList = "age,al,an,ant,ance,ard,art,ary,ate,cy,ed,en,
        ence,ency,ent,er,ery,es,est,ful,ian,ic,ice,ile,ing,ion,
        ish,ism,ist,ite,ive,ize,less,ly,ment,ness,or,ory,s,ship";    // connectors


// connector strategies
define('CS_NONE',   16);   // use a range of non-printing characters
define('CS_DROP_E', 17);
define('CS_DOUBLE', 18);
define('CS_IE_Y',   19);
define('CS_Y_I',    20);
define('CS_ADD_K',  21);
define('CS_DROP_LE', 22);
define('CS_MB_MM',  23);

define('STYLE_FINAL',    1);
define('STYLE_INTERNAL', 2);
define('STYLE_PLUS',     3);




// implements some shared functions
class matrix_common
{

    // for some reason, this isn't accessible by classes that subclass this class ?!?
    //var $matrixURL = 'matrix';   // which method in firstpage.php do we invoke?

    var $allowNewBase  = false;
    var $allowLoad     = false;         // not implemented
    var $allowSave     = false;         // not implemented
    var $allowPrint    = false;         // not implemented

    var $type = '';
    var $connectImage;

    var $debug = '';


    function connectorStrategy(string $base, string $suffix)
    {
        // we return our best guess of the strategy

        // sometimes $base has dashes, filter them out
        //      get more sophisticated later...
        $base = str_replace('-', '', $base);

        // prefixes only have the '-' connector for now
        if ($this->type == MM_PREFIX)
            return (CS_NONE);

        $retval = false;        // don't have a value yet

        $vowels = ',aeiou';   // trick to avoid === false
        $consonants = ',bdfgklmnprstvz';

        $this->debug = "connectorStrategy($base,$suffix):<br>";

        $base_final_is_vowel = (strpos($vowels, substr($base, -1, 1)) !== false);
        $this->debug .= 'base_final_is_vowel: ' . ($base_final_is_vowel ? 'true' : 'false') . '<br>';

        $base_final_is_consonant = (strpos($consonants, substr($base, -1, 1)) !== false);
        $this->debug .= 'base_final_is_consonant: ' . ($base_final_is_consonant ? 'true' : 'false') . '<br>';

        $before_final_is_vowel = (strpos($vowels, substr($base, -2, 1)) !== false);
        $this->debug .= 'before_final_is_vowel: ' . ($before_final_is_vowel ? 'true' : 'false') . '<br>';

        $suffix_starts_with_vowel = (strpos($vowels, substr($suffix, 0, 1)) !== false or $suffix == 'y');
        $this->debug .= 'suffix_starts_with_vowel: ' . ($suffix_starts_with_vowel ? 'true' : 'false') . '<br>';

        $two_before_final_is_vowel = (strpos($vowels, substr($base, -3, 1)) !== false);
        $this->debug .= 'two_before_final_is_vowel: ' . ($two_before_final_is_vowel ? 'true' : 'false') . '<br>';

        $three_before_final_is_vowel = (strpos($vowels, substr($base, -4, 1)) !== false);
        $this->debug .= 'three_before_final_is_vowel: ' . ($three_before_final_is_vowel ? 'true' : 'false') . '<br>';

        $base_final_is_x_or_w = (substr($base, -1) == 'x' or substr($base, -1) == 'w');
        $this->debug .= 'base_final_is_x_or_w: ' . ($base_final_is_x_or_w ? 'true' : 'false') . '<br>';

        $suffix_starts_with_i = (substr($suffix, 0, 1) == 'i');
        $this->debug .= 'suffix_starts_with_i: ' . ($suffix_starts_with_i ? 'true' : 'false') . '<br>';

        $suffix_is_ed_er_ing = ($suffix == 'ed' or $suffix == 'er' or $suffix == 'ing');
        $this->debug .= 'suffix_is_ed_er_ing: ' . ($suffix_is_ed_er_ing ? 'true' : 'false') . '<br>';

        $base_ends_with_ce = (substr($base, -2, 2) == 'ce');
        $this->debug .= 'base_ends_with_ce: ' . ($base_ends_with_ce ? 'true' : 'false') . '<br>';

        $base_ends_with_ge = (substr($base, -2, 2) == 'ge');
        $this->debug .= 'base_ends_with_ge: ' . ($base_ends_with_ge ? 'true' : 'false') . '<br>';

        $base_ends_with_le = (substr($base, -2, 2) == 'le');
        $this->debug .= 'base_ends_with_le: ' . ($base_ends_with_le ? 'true' : 'false') . '<br>';

        $base_ends_with_ye = (substr($base, -2, 2) == 'ye');
        $this->debug .= 'base_ends_with_ye: ' . ($base_ends_with_ye ? 'true' : 'false') . '<br>';

        $base_ends_with_oe = (substr($base, -2, 2) == 'oe');
        $this->debug .= 'base_ends_with_oe: ' . ($base_ends_with_oe ? 'true' : 'false') . '<br>';

        $base_ends_with_ee = (substr($base, -2, 2) == 'ee');
        $this->debug .= 'base_ends_with_ee: ' . ($base_ends_with_ee ? 'true' : 'false') . '<br>';

        $base_ends_with_ie = (substr($base, -2, 2) == 'ie');
        $this->debug .= 'base_ends_with_ie: ' . ($base_ends_with_ie ? 'true' : 'false') . '<br>';

        $base_ends_with_al = (substr($base, -2, 2) == 'al');
        $this->debug .= 'base_ends_with_al: ' . ($base_ends_with_al ? 'true' : 'false') . '<br>';

        $base_ends_with_a_e = ((!$three_before_final_is_vowel) and $two_before_final_is_vowel and (!$before_final_is_vowel) and (substr($base, -1, 1) == 'e'));
        $this->debug .= 'base_ends_with_a_e: ' . ($base_ends_with_a_e ? 'true' : 'false') . '<br>';

        $base_ends_with_nonSyllabic_e = ($base_ends_with_ce or $base_ends_with_ge
            or $base_ends_with_a_e
            or $base_ends_with_ye
            or $base_ends_with_ie
            or $base_ends_with_ge);
        $this->debug .= 'base_ends_with_nonSyllabic_e: ' . ($base_ends_with_nonSyllabic_e ? 'true' : 'false') . '<br>';

        $base_ends_with_syllabic_e = (strpos("be,recipe,acne,epitome,apostrophe", $base) !== false);
        $this->debug .= 'base_ends_with_syllabic_e: ' . ($base_ends_with_syllabic_e ? 'true' : 'false') . '<br>';

        $base_ends_with_ic = (substr($base, -2, 2) == 'ic');
        $this->debug .= 'base_ends_with_ic: ' . ($base_ends_with_ic ? 'true' : 'false') . '<br>';

        $suffix_starts_eiy = (!strpos(',eiy', substr($suffix, -1)));
        $this->debug .= '$suffix_starts_eiy: ' . ($suffix_starts_eiy ? 'true' : 'false') . '<br>';

        $suffix_starts_ao = (strpos(',ao', substr($suffix, 0, 1)));
        $this->debug .= '$suffix_starts_ao: ' . ($suffix_starts_ao ? 'true' : 'false') . '<br>';

        $final_is_L = (substr($base, -1, 2) == 'l');
        $this->debug .= '$final_is_L: ' . ($final_is_L ? 'true' : 'false') . '<br>';




        // use festival dictionary to determine if monosyllable and if stress-on-last
        require_once("source/dictionary.php");
        global $spellingDictionary;

        $base_is_monosyllable = false;  // defaults in case we don't find base
        $stress_is_final = false;

        $lcBase = strtolower($base);
        if (isset($spellingDictionary[$lcBase])) {
            $temp = $spellingDictionary[$lcBase];

            $this->debug .= "Dictionary:  $temp<br>";

            $base_is_monosyllable = (strpos($temp, '/') === false);  // no slash? it's a monosyllable
            $this->debug .= '$base_is_monosyllable: ' . ($base_is_monosyllable ? 'true' : 'false') . '(from festival) <br>';

            $stress_is_final = (strpos($temp, '!') !== false);  // found exclaimation? stress is final syllable
            $this->debug .= '$stress_is_final: ' . ($stress_is_final ? 'true' : 'false') . '(from festival ' . $temp . ') <br>';
        } else {

            $this->debug .= "NOT in Dictionary<br>";

            // this is an awful kludge, but I have no better ideas
            $maxLen = 4;
            if (strpos('.bl/br/st', substr($base, 0, 2)) > 0)   $maxLen += 1;
            if (substr($base, 0, 3) == 'str')  $maxLen += 2;
            if (substr($base, -3, 3) == 'dge') $maxLen += 2;

            $base_is_monosyllable = strlen($base) < $maxLen;
            $this->debug .= '$base_is_monosyllable: ' . ($base_is_monosyllable ? 'true' : 'false') . '(from kluge)<br>';

            $stress_is_final = false;
            $this->debug .= '$stress_is_final: ' . ($stress_is_final ? 'true' : 'false') . '(from kluge) <br>';
        }




        // just to get this out


        // some exceptions come first
        if ($base == 'true' and $suffix == 'ly')                   // three 'ly' exceptions
            $retval = CS_DROP_E;
        elseif ($base == 'due' and $suffix == 'ly')
            $retval = CS_DROP_E;
        elseif ($base == 'whole' and $suffix == 'ly')
            $retval = CS_DROP_E;


        // just to get this out
        // infer -> inferring, inference
        elseif (($base == 'prefer' or $base == 'refer') and $suffix == 'ence')
            $retval = CS_NONE;

        elseif ($base == 'fer' and ($suffix == 'ing' or $suffix == 'ed' or $suffix == 'er'))
            $retval = CS_DOUBLE;

        elseif ($base == 'forget' and ($suffix == 'able' or $suffix == 'ing'))
            $retval = CS_DOUBLE;

        // 'qu' is a consonant for CVC doubling rule
        elseif (($base == 'squat' or $base == 'quit')  and ($suffix == 'ed' or $suffix == 'ing' or $suffix == 'ed'))
            $retval = CS_DOUBLE;
        // lay+ed => laid
        elseif (($base == 'lay' or $base == 'pay'  or $base == 'say')  and ($suffix == 'ed' or $suffix == 'ing' or $suffix == 'ed'))
            $retval = CS_Y_I;




        elseif ($base == 'argue' and $suffix == 'ment')
            $retval = CS_NONE;
        //        elseif($base=='canoe' and $suffix=='ing')
        //            $retval = CS_NONE;
        //        elseif($base=='hoe' and $suffix=='ing')
        //            $retval = CS_NONE;
        //        elseif($base=='shoe' and $suffix=='ing')
        //            $retval = CS_NONE;

        elseif ($base == 'focus' and $suffix == 'ed')
            $retval = CS_DOUBLE;
        elseif ($base == 'crystal' and $suffix == 'ize')
            $retval = CS_DOUBLE;
        elseif ($base == 'tranquil' and $suffix == 'ize')
            $retval = CS_DOUBLE;
        elseif ($base == 'man' and $suffix == 'ish')
            $retval = CS_DOUBLE;
        elseif ($base == 'snap' and $suffix == 'ish')
            $retval = CS_DOUBLE;
        elseif ($base == 'snob' and $suffix == 'ish')
            $retval = CS_DOUBLE;
        elseif ($base == 'music')
            $retval = CS_NONE;

        // check for base ending in ie + ing (die->dying)
        elseif (
            substr($base, -2) == 'ie'                                     // ends in ie
            and $suffix == 'ing'
        ) {                                           // only for 'ing'
            $retval = CS_IE_Y;
            $this->debug .= 'line=' . __line__;
        }

        // check for base ending in ic + ing (picnic (k) ing)
        // this is a pretty defective rule, we have an exception
        // for music.  but politician like politic-K-ing...
        elseif (
            $base_ends_with_ic
            and $suffix_is_ed_er_ing
        ) {                                           // only for 'ing'
            $retval = CS_ADD_K;
            $this->debug .= 'line=' . __line__;
        }

        // syllabic-e  exceptions
        elseif (
            $suffix_starts_with_vowel
            and $base_ends_with_syllabic_e
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }

        // w and x exceptions
        elseif (
            $suffix_starts_with_vowel
            and $base_final_is_x_or_w
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }

        // check for final ye or oe + ing (eyeing, toeing, canoeing)
        elseif (($base_ends_with_ye or $base_ends_with_oe or $base_ends_with_ee)                   // ends in ee, ie or oe
            and ($suffix == 'ing' or $suffix == 'able')
        ) {                                                                // only for 'ing'
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }


        // check for silent ce - keep soft requires e/i/y in suffix
        elseif (
            $base_ends_with_ce
            and !$base_ends_with_a_e
            and !$suffix_starts_eiy
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        } elseif (($base_ends_with_ce or $base_ends_with_ge)
            and $suffix == 'able'
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }

        // check for silent ge - keep soft requires a/o in suffix
        elseif (
            $base_ends_with_ge
            and $suffix_starts_ao
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }

        // check for final-e ending and vowel beginning
        elseif (
            substr($base, -1) == 'e'
            and $suffix_starts_with_vowel
        ) {
            $retval = CS_DROP_E;
            $this->debug .= 'line=' . __line__;
        }

        // check for final-e ending and 'y' suffix
        elseif (
            substr($base, -1) == 'e'
            and $suffix == 'y'
        ) {
            $retval = CS_DROP_E;
            $this->debug .= 'line=' . __line__;
        }

        // check for final-le ending and 'ly' suffix
        elseif (
            substr($base, -2) == 'le'
            and $suffix == 'ly'
        ) {
            $retval = CS_DROP_LE;
            $this->debug .= 'line=' . __line__;
        }

        // final+ize is finalize, we don't double the ll.
        elseif (
            $base_ends_with_al
            and $suffix == 'ize'
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }

        // this is the second column from the left
        elseif (
            $base_ends_with_nonSyllabic_e
            and (substr($base, -2) == 'ie')
            and $suffix == 'ing'
        ) {
            $retval = CS_DROP_E;
            $this->debug .= 'line=' . __line__;
        } elseif (
            $base_ends_with_nonSyllabic_e
            and (substr($base, -2) == 'ie')
            and $suffix !== 'ing'
        ) {
            $retval = CS_IE_Y;
            $this->debug .= 'line=' . __line__;
        } elseif (
            $base_ends_with_nonSyllabic_e
            and ((substr($base, -2) == 'ye') or (substr($base, -2) == 'oe'))
            and $suffix !== 'ing'
        ) {
            $retval = CS_DROP_E;
            $this->debug .= 'line=' . __line__;
        } elseif (
            $base_ends_with_nonSyllabic_e
            and ((substr($base, -2) == 'ye') or (substr($base, -2) == 'oe'))
            and $suffix == 'ing'
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        } elseif (
            $base_ends_with_nonSyllabic_e
            and $suffix_starts_with_vowel
        ) {
            $retval = CS_DROP_E;
            $this->debug .= 'line=' . __line__;
        }


        // check for crumb->crummy or dumb->dummy
        elseif (
            substr($base, -2) == 'mb'
            and $suffix == 'y'
        ) {
            $retval = CS_MB_MM;
            $this->debug .= 'line=' . __line__;
        }


        // check for y -> i  (requires consonant affix beginning)
        elseif (
            substr($base, -1) == 'y'                          // ends in y
            and !$before_final_is_vowel                        // before the y is a consonant
            and !$suffix_starts_with_vowel
        ) {                 // and affix does not start with vowel
            $retval = CS_Y_I;
            $this->debug .= 'line=' . __line__;
        }

        // second test for y -> i  (for vowel suffix)  (armies)
        elseif (
            substr($base, -1) == 'y'                       // base ends in y
            //and $base_final_is_vowel   <IT IS A 'Y' !>
            and !$before_final_is_vowel                    // before the y is a consonant
            and $suffix_starts_with_vowel
            and !$suffix_starts_with_i
        ) {                 // and affix starts with i
            $retval = CS_Y_I;
            $this->debug .= 'line=' . __line__;
        }

        // third test for y -> i  (for vowel suffix)
        elseif (
            substr($base, -1) == 'y'                       // base ends in y
            //and $base_final_is_vowel   <IT IS A 'Y' !>
            and $before_final_is_vowel                    // before the y is a consonant
            and $suffix_starts_with_vowel
            and $suffix_starts_with_i
        ) {                   // and affix starts with i
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }


        // forth test for y -> i  (for vowel suffix) (flies)
        elseif (
            substr($base, -1) == 'y'                       // base ends in y
            //and $base_final_is_vowel   <IT IS A 'Y' !>
            and !$before_final_is_vowel                    // before the y is a consonant
            and $suffix_starts_with_vowel
            and !$suffix_starts_with_i
        ) {                   // and affix starts with i
            $retval = CS_Y_I;
            $this->debug .= 'line=' . __line__;
        }


        // exception to final-letter doubling for suffixes (not 'ing') that start with 'i'  (ity,ive,ion)
        elseif (
            $base_final_is_consonant       // final is a consonant
            and $before_final_is_vowel         // and letter before is a vowel
            and !$two_before_final_is_vowel    // and not 2nd letter before is a vowel
            and ($suffix_starts_with_i          // and suffix starts with i or 'ure' (see lesson 4F)
                or $suffix == 'ure')
            and $suffix !== 'ing'
        ) {
            $retval = CS_NONE;
            $this->debug .= 'line=' . __line__;
        }

        // check for final-letter doubling
        elseif (
            $base_final_is_consonant       // final is a consonant
            and $before_final_is_vowel         // and letter before is a vowel
            and !$two_before_final_is_vowel    // and not 2nd letter before is a vowel
            and $suffix_starts_with_vowel      // and suffix starts with vowel or y
            and ($base_is_monosyllable
                or $final_is_L)
        ) {        // and base is monosyllable (except for L)
            $retval = CS_DOUBLE;
            $this->debug .= 'line=' . __line__;
        }


        // check for final-letter doubling for multi-syllable
        elseif (
            $base_final_is_consonant       // final is a consonant
            and $before_final_is_vowel         // and letter before is a vowel
            and !$two_before_final_is_vowel    // and not 2nd letter before is a vowel
            and $suffix_starts_with_vowel      // and suffix starts with vowel or y
            and !$base_is_monosyllable
            and $stress_is_final
        ) {
            $retval = CS_DOUBLE;
            $this->debug .= 'line=' . __line__;
        }





        if ($retval === false) {
            $retval = CS_NONE;   // always an option
            $this->debug .= 'Defaults to CS_NONE at line=' . __line__;
        }

        return ($retval);
    }

    function connectorStrategyName(int $strategy):string {
                //echo "connectorStrategy($base,$suffix) returns '$retval'<br>";
        // connector strategies
        $result = '';
        $result = ($strategy == CS_NONE) ? 'CS_NONE' : $result;
        $result = ($strategy == CS_DROP_E) ? 'CS_DROP_E' : $result;
        $result = ($strategy == CS_DOUBLE) ? 'CS_DOUBLE' : $result;
        $result = ($strategy == CS_IE_Y) ? 'CS_IE_Y' : $result;
        $result = ($strategy == CS_Y_I) ? 'CS_Y_I' : $result;
        $result = ($strategy == CS_ADD_K) ? 'CS_ADD_K' : $result;
        $result = ($strategy == CS_DROP_LE) ? 'CS_DROP_LE' : $result;
        $result = ($strategy == CS_MB_MM) ? 'CS_MB_MM' : $result;

        return $result;
    }

    // connectText returns a clean version of the word with the rules applied
    function connectText($base, $affix, $strategy)
    {         // apply rules like doubling or i->y


        switch ($strategy) {
            case CS_NONE:
                $ret =  ($base . $affix);
                break;
            case CS_DOUBLE:
                $ret = ($base . substr($base, -1) . $affix);
                break;
            case CS_DROP_E:
                $ret =  (substr($base, 0, strlen($base) - 1) . $affix);
                break;
            case CS_IE_Y:
                $ret =  (substr($base, 0, strlen($base) - 2) . 'y' . $affix);
                break;
            case CS_Y_I:
                $ret =  (substr($base, 0, strlen($base) - 1) . 'i' . $affix);
                break;
            case CS_NONE:
                $ret = ($base . $affix);
                break;
            case CS_ADD_K:
                $ret = ($base . 'k' . $affix);
                break;
            case CS_DROP_LE:
                $ret =  (substr($base, 0, strlen($base) - 2) . $affix);
                break;
            case CS_MB_MM:
                $ret =  (substr($base, 0, strlen($base) - 2) . 'mm' . $affix);
                break;
            default:
                assertTrue(false, "Unexpected CS_*** value '{$this->connectImage}'in connectText()");
                $ret =  ($base . $affix);

        }
        // printNice("function connectText($base, $affix, $strategy) returns $ret");

        return $ret;
    }


    // similar to connectText, but adds the graphics or + markers

    function connectPlus($base, $affix, $strategy=CS_NONE)   // strategy is always ignored
    {
            return ($base . ' + ' . $affix);
    }


    function connectDisplay($base, $affix, $strategy = CS_NONE): string
    {         // apply rules like doubling or i->y
        $connector = '<img src="pix/' . $this->connectLookupPng($base, $strategy) . '" height="30"  />';

        switch ($strategy) {
            case CS_NONE:
                $ret  = $base . $connector . $affix;
                break;
            case CS_DOUBLE:
                $ret = $base . $connector . $affix;
                break;
            case CS_DROP_E:
                $ret = substr($base, 0, strlen($base) - 1) . $connector . $affix;
                break;
            case CS_IE_Y:
                $ret = substr($base, 0, strlen($base) - 2) . $connector . $affix;
                break;
            case CS_Y_I:
                $ret = substr($base, 0, strlen($base) - 1) . $connector . $affix;
                break;
            case CS_NONE:
                $ret = $base . $connector . $affix;
                break;
            case CS_ADD_K:
                $ret = $base . $connector . $affix;
                break;
            case CS_DROP_LE:
                $ret = substr($base, 0, strlen($base) - 2) . $connector . $affix;
                break;
            case CS_MB_MM:
                $ret = substr($base, 0, strlen($base) - 2) . $connector . $affix;
                break;
            default:
                $ret = $base . '&nbsp;' . $affix;
                assertTrue(false, "Unexpected CS_*** value '$strategy' in connectText()");
        }
        return ($ret);
    }

    function connectLogic($base, $affix, $strategy = CS_NONE): string{

        return '';
    }


    function connectLookupPng($base, $strategy)
    {
        // printNice("connectLookupPng($base)");

        if ($strategy !== CS_DOUBLE) {
            switch ($strategy) {       // this are the operations
                case CS_NONE:
                    return ('sep-none.PNG');
                case CS_DROP_E:
                    return ('sep-drop-e.PNG');
                case CS_IE_Y:
                    return ('sep-ie-y.PNG');
                case CS_Y_I:
                    return ('sep-y-i.PNG');
                case CS_ADD_K:
                    return ('sep-add-k.PNG');
                case CS_DROP_LE:
                    return ('sep-drop-le.PNG');
                case CS_MB_MM:
                    return ('sep-mb.PNG');
            }
        }

        // it is CS_DOUBLE, we look at the root to see what to double
        assertTrue($strategy == CS_DOUBLE, "Unexpected value for connectLookupPng($strategy) in " . __METHOD__);

        $c = substr($base, -1);
        $png = 'sep-' . $c . '-' . $c . $c . '.PNG';   //'sep-d-dd.PNG'
        //echo "connectLookupPng($this->connectImage,$base) returns $png<br>";
        return ($png);
    }


    function affixMeaning($affixType, $affix)
    {
        assertTRUE($affixType == MM_PREFIX or $affixType == MM_POSTFIX);

        $return = '';       // default if we don't have it

        $prefixMeanings = array(

            'a'     =>  'up,out,not,without<br>eg:aside,alone,afloat,apathy,apolitical',
            'ac'    =>  'near,at,towards<br>eg:accompany,accord,acquit,acclaim',
            'ad'    =>  'near,at,towards<br>eg:adapt,adrift,adorn,adjust',
            'af'    =>  'near,at,towards<br>eg:affix,affirm,afflict,afford',
            'ag'    =>  'near,at,do,move,go<br>eg:aggression,aggravate,aggregate',
            'al'    =>  'near,at,towards<br>eg:allocate,allure',
            'an'    =>  'near,at,towards<br>eg:anoint,annihilate',
            'ap'    =>  'near,at,towards<br>eg:approve,appraise,appease,applaud',

            'ab'    =>  'away from,off<br>eg:absolve,abrupt,abduct',
            'abs'   =>  'away from,off<br>eg:absent,abscond,abstract',

            'ambi'  =>  'both<br>eg:ambiguous,ambidextrous',
            'anti'  =>  'against<br>eg:antifreeze,anticipate',

            'co'    =>  'together,jointly<br>eg:cohabit,cohesive,',
            'col'   =>  'together,jointly<br>eg:collapse,collect,collaborate',
            'com'   =>  'together,jointly<br>eg:combat,comply,commit',
            'con'   =>  'together,jointly<br>eg:concede,convert,concede',
            'cor'   =>  'together,jointly<br>eg:corrupt,correct,correspond',

            'de'    =>  'opposite<br>eg:depart,deface,demise',
            'dis'   =>  'not,opposite<br>eg:disrupt,dispute,distort',
            'ef'    =>  'out of,from,not<br>eg:effect,effort,effusion',
            'en'    =>  'cause to<br>eg:enjoy,enrage,ensure',
            'epi'   =>  'upon,close to,over,after<br>eg:epicenter,epilogue,epidermis',
            'equi'  =>  'equal<br>eg:equidistant,equilateral',
            'em'    =>  'cause to<br>eg:embark,embroil,empathy',
            'ex'    =>  'out of,from,not<br>eg:extract,except,exclude',
            'fore'  =>  'before<br>eg:foresee,forecast',
            'in'    =>  'in,not<br>eg:incite,infest,invert',
            'im'    =>  'in,not<br>eg:impact,immoral,imperil',
            'il'    =>  'not<br>eg:illegal,illogical,illicit',
            'ir'    =>  'not<br>eg:irrelevant,irregular',
            'inter' =>  'between<br>eg:interface,interrupt',
            'mid'   =>  'middle<br>eg:midsize,midpoint,midtown',
            'mis'   =>  'wrongly<br>eg:misfire,miscount,mislead',
            'non'   =>  'not<br>eg:nonviolent,nonstop,nonsense',
            'over'  =>  'above or higher<br>eg:overlay,overcome,overdose,overcoat',
            'pre'   =>  'before<br>eg:preset,predict,prelude',
            'pro'   =>  'forward,in advance,in place of<br>eg:proclaim,proceed,prohibit,pronoun',
            're'    =>  'again<br>eg:reborn,recite,react',
            'semi'  =>  'half<br>eg:semitone,semifinal,semicircle',
            'sub'   =>  'under<br>eg:subway,subtitle,sublet',
            'super' =>  'above<br>eg:supervise,superstar,supersonic',
            'trans' =>  'across<br>eg:transact,transmit,transport<br>
                                    <red>Consider -tran instead eg:transpire,transcribe</red>',
            'un'    =>  'not<br>eg:uncurl,unfair,unwrap',
            'under' =>  'beneath,below<br>eg:underarm,underwear',

        );
        // not ready yet

        /*            '/ac/ad/af/ag/al/an/ap/as/at/' => 'toward,near,in addition',
            '/a/an/'     => 'not, without',
            '/ab/abs/'   => 'away from',
            '/acer/acid/acri/' => 'bitter,sour,sharp',
            '/act/ag/'   => 'do,act,drive',
            '/acu/'      => 'sharp',
            '/aer/aero/' => 'air,atmosphere',
            '/ag/agi/ip/act'  => 'do,move,go',
            '/agri/agro/' => 'fields or soil',
            '/alb/albo/'  =>  'white',
            '/ali/allo/alter' => 'other',
            '/alt/'       => 'high,deep',
            '/am/ami/amor'=> 'like,love',
            '/ambi/'      => 'both',
            '/ambul/'     => 'to walk',
            '/ana/ano/'   => 'up,back,again',
            '/andr/andro/'=> 'male',


            '/contra/' => 'against, opposite'
        );
*/


        $falseSuffixes = array(
            'ial'       => 'y+al or i+al',
            'ical'      => 'ic+al or ice+al',
            'ancy'      => 'ance+y',
            'ient'      => 'y+ent',
            'ation'     => 'ate+ion',
            'ency'      => 'ence+y',
            'ier'       => 'y+er',
            'ies'       => 'y+es',
            'iest'      => 'y+est',
            'ious'      => 'i+ous',
            'itude'     => 'i+tude',
            'ization'   => 'ize+ate+ion',
            'loger'     => 'log+er',
            'logist'    => 'log+ist',
            'logia'     => 'log+ia',
            'logy'      => 'log+y',
            'ologer'    => 'olog+er',
            'ologist'   => 'olog+ist',
            'ologia'    => 'olog+ia',
            'ology'     => 'olog+y',
            'opic'      => 'ope+ic',
            'omic'      => 'ome+ic',
            'phobia'    => 'phobe+ia',
            'phobic'    => 'phobe+ic',
            'plegia'    => 'pleg+ia',
            'plegic'    => 'pleg+ic',
            'plegy'     => 'pleg+y',
            'sion'      => 's+ion',
            'uous'      => 'u+ous'
        );



        $suffixExamples  = array();

        $postfixExamples = array(
            'a'             => '-- separator --',
            'able'          => 'worth,ability<br>eg:durable,equable,pliable',
            'ac'            => 'pertaining to<br>eg:maniac,cardiac,insomniac',
            'ace'           => 'noun-forming (feminine)<br>eg:solace,populace,menace',
            'acle'          => '<red>Use -ace + -le instead</red>',
            'acy'           => '<red>Use -ace + -y instead</red>',
            'ade'           => 'act,product<br>eg:abrade,cascade,tirade',
            'age'           => 'activity,result<br>eg:hostage,carnage,leakage',
            'al'            => 'relation,result,quality<br>eg:pedal,equal,metal,legal',
            'an'            => 'relating to<br>eg:median,vegan,organ',
            'ance'          => 'action,state,quality<br>eg:advance,finance,reliance',
            'ant'           => 'agent<br>eg:mutant,tenant,hydrant',
            'ar'            => 'resembling,related to<br>eg:polar,pulsar,ocular',
            'ary'           => '<red>Use -ar + -y instead</red>',
            'ard'           => 'characterized<br>eg:lizard,wizard,coward',
            'art'           => '<red>are you sure?</red>',
            'ate'           => 'act,product<br>eg:mutate,negate,animate',
            'cade'          => 'procession<br>eg:motorcade,cavalcade,barricade',
            'crat'          => 'person with power<br>eg:autocrat,democrat,bureaucrat',
            'cy'            => 'state,condition<br>eg:policy,latency,accuracy',
            'dom'           => 'condition,realm<br>eg:wisdom,freedom,stardom,kingdom',
            'dox'           => 'belief,praise<br>eg:paradox,orthodox',
            'ed'            => 'past-tense,quality<br>eg:used,aimed,iced,dried',
            'ee'            => 'receiver,performer<br>eg:devotee,amputee,nominee',
            'eer'           => 'associated with<br>eg:pioneer,engineer,volunteer',
            'en'            => 'to become<br>eg:liken,fasten,deafen,bitten',
            'ence'          => 'action,process,quality<br>eg:absence,silence,violence,credence',
            'ent'           => 'agent<br>eg:agent,parent,invent',
            'eous'          => 'full of<br>eg:righteous,beauteous,hideous',
            'er'            => 'comparative,action,actor<br>eg:alter,user,bigger,after',
            'ern'           => 'state,quality<br>eg:govern,western,modern',
            'es'            => 'plural<br>eg:armies,boxes',
            'ess'           => 'female<br>eg:actress,hostess',
            'est'           => 'superlative<br>eg:cutest,oldest',
            'fold'          => 'manner<br>eg:sixfold,manifold',
            'ful'           => 'amount,manner<br>eg:awful,fistful,gleeful',
            'fy'            => 'cause<br>eg:unify,verify,purify',
            'gon'           => 'angle<br>eg:hexagon,octagon',
            'hood'          => 'state,condition<br>eg:parenthood,likelihood',
            'i'             => '-- separator --',
            'ia'            => 'abstract nouns,collections,plurals<br>eg:inertia,trivia,academia,ganglia',
            'ial'           => '<red>Use -i + -al instead</red>',
            'ian'           => '<red>Use -i + -an instead</red>',
            'ible'          => 'worth,ability<br>eg:visible,horrible,eligible',
            'ic'            => 'pertaining to<br>eg:sonic,cleric,cubic',
            'icle'          => '<red>Use -ic/-ice + -le instead</red>',
            'ide'           => 'act of<br>eg:decide,reside,divide',
            'ing'           => 'act of,materials<br>eg:doing,baking,icing',
            'ile'           => 'pertaining to<br>eg:fragile,domicile,hostile',
            'ion'           => 'process,action<br>eg:action,option,caution,fashion',
            'ish'           => 'relating to<br>eg:finish,abolish,childish',
            'ism'           => 'state or quality<br>eg:racism,tourism,baptism',
            'ist'           => 'actor<br>eg:artist,typist,florist',
            'ite'           => 'product of<br>eg:polite,erudite,favorite',
            'ity'           => 'state,condition<br>eg:equity,cavity,dignity<br>
                                    <red>Consider -ite + -y instead eg:unity,infinity</red>',
            'ive'           => 'quality of<br>eg:active,abusive,massive',
            'ise'           => 'cause,treat,become<br>eg:devise,surprise',
            'ize'           => 'cause,treat,become<br>eg:sanitize,idolize,realize',
            'less'          => 'without<br>eg:endless,topless,fearless',
            'let'           => 'version of<br>eg:triplet,droplet,inlet',
            'like'          => 'resembling<br>eg:warlike,childlike,lookalike',
            'ling'          => 'inferior<br>eg:underling,duckling
                                    <red>Consider -le + -ing instead eg:gambling,handling</red>',
            'log'           => 'study of,speaker<br>eg:dialog,travelog',
            'ly'            => 'in manner of<br>eg:badly,daily,only',
            'ment'          => 'action,result<br>eg:argument,document,shipment',
            'ness'          => 'state,quality<br>eg:fitness,sadness,shyness',
            'oid'           => 'resembling<br>eg:cuboid,humanoid,hemorrhoid',
            'oma'           => 'tumor,swelling<br>eg:glaucoma,melanoma',
            'ome'           => 'tumor,object of study<br>eg:biome,carcinome,genome',
            'onym'          => 'name,word<br>eg:antonym,pseudonym',
            'or'            => 'action,actor<br>eg:actor,doctor,sailor',
            'osis'          => '(diseased) condition<br>eg:hypnosis,prognosis,neurosis',
            'ous'           => 'full of<br>eg:famous,curious,furious',
            'ose'           => 'full of<br>eg:purpose,sucrose,verbose',
            'path'          => 'engaged in<br>eg:homeopath,psychopath',
            'phile'         => 'having affinity to<br>eg:anglophile,bibliophile',
            'phobe'          => 'abnormal fear of<br>eg:xenophobe,technophobe',
            'phone'         => 'sound<br>eg:telephone,anglophone,homophone',
            'phyte'         => 'plant,to grow<br>eg:neophyte,arthrophyte<br>
                                    also used as prefix phyto-',
            'pleg'          => 'paralysis<br>eg:paraplegic',
            'pnea'          => 'air,spirit<br>eg:orthopnea',
            's'             => 'plural<br>eg:books,stairs',
            'ship'          => 'state,condition,skill<br>eg:hardship,citizenship,workmanship',
            'sis'           => '(diseased) condition<br>eg:hypnosis,psychosis,halitosis<br>
                                    <red>Consider -se + -is instead eg:ellipsis,catalysis</red>',

            'sy'            => 'condition<br>eg:antsy,whimsy,tipsy',
            'some'          => 'characterized by<br>eg:awesome,irksome,lonesome',
            'ster'          => 'associated with<br>eg:hipster,trickster<br>',
            'tion'          => '<red>-tion is not a suffix.  look for -ite+ion or similar</red>',
            'ster'          => 'associated with<br>eg:hipster,trickster<br>',

            'th'            => 'noun of action<br>eg:growth,stealth,wealth',
            'tude'          => '<red>-tude is not a suffix.  look for -ite+ude or similar</red>',
            'ture'          => '<red>-ture is not a suffix.  look for -ite+ure or similar</red>',
            'ty'            => '<red>consider -y, eg: fatty, or -ity, eg: equity</red>',
            'u'             => '-- separator --',
            'ule'           => 'small one<br>eg:globule,vestibule',
            'ure'           => 'action,condition<br>eg:tenure,picture,rapture',
            'y'             => 'having,result<br>eg:juicy,grouchy,dreamy'
        );


        $baseMeanings = array(
            'scope'         => 'visual',
            'scribe/script' => 'to write',
            'sect'          => 'to cut',
            'soph'          => 'wisdom',
            'trope'         => 'turning',
        );


        $errStyle = 'style="font-color:red;background-color:yellow;"';

        if ($affixType == MM_PREFIX) {

            // hunt through the list of prefix meanings
            if (isset($prefixMeanings[$affix]))
                return ($prefixMeanings[$affix]);
        } else {

            // hunt through the false suffixes
            if (key_exists($affix, $falseSuffixes)) {
                return ("<span $errStyle>Probably '{$falseSuffixes[$affix]}'</span>");
            }

            // hunt through the list of suffix meanings
            if (isset($postfixExamples[$affix]))
                return ($postfixExamples[$affix]);


            return ("<span $errStyle>Are you sure?</span>");
        }

        return ('');
    }
}



class matrixAffix extends matrix_common{

    var $type;              // either MM_PREFIX or MM_POSTFIX
    var $text   = '';       // affix text
    var $root   = '';       // root is simply what is above, not the other side of the tree
    var $connectImage;      // the CS_*** value of the connect strategy - an integer !!
    var $aSub   = array();  // sub objects further ahead or behind
    var $uniqid;            // so we can search for this object
    var $gloss;             // the meaning of this affix

    var $storage;           // for dehydration and rehydration

    function __construct($type){
        $this->type   = $type;
        $this->uniqid = uniqid();
    }

    function affixRender($depth,$base){
        // mark the depth in decending colors
        $colors = array('#D0F8F0','#C0F8C0','#B0F8A0','#A0F880','#90F860','#80F840','#70F820','#60F800');

        // prefixes and postfixes build in different orders

        // first we put in the connector
        $connector = $left = $right = '';

        assertTrue(is_integer($this->connectImage),"Expected one of CS_****, got '".serialize($this->connectImage)."' in ".__METHOD__);
        $cPng = $this->connectLookupPng($base,$this->connectImage);                // get the name of the image file

        $cImg = "<img src=\"images/$cPng\" height=\"30\" />";
        $connector = "<td class=connector>$cImg</td>";


        $allowDelete = empty($this->aSub)?'':'nodelete';
        $style = "style='background-color:{$colors[$depth]};'";
        $left .= "<td $style>".$this->addControls($this->text,$allowDelete,$style,$this->gloss)."</td>";

        if(!empty($this->aSub)){
           $right .= "<td><table>";
            foreach($this->aSub as $sub){

                            // then we put in the affix itself
                $right .= "<tr><td width='100%' style='background-color:{$colors[$depth]};'>"
                            .$sub->affixRender($depth+1,$this->text).
                            "</td></tr>";
            }
            $right .= "</table></td>";           // and a recursive call to the sub-object
        }

        $HTML = "\n<table style=\"border-spacing:0px;
                                    border-collapse:collapse;
                                    min-width:200px;
                                    align:left;\"><tr>";
        if($this->type == MM_POSTFIX)             // postfix is left-to-right
            $HTML .= $connector.$left.$right;
        else
            $HTML .= $right.$left.$connector;       // but prefix is right-to-left
        $HTML .= "</tr>";

        $HTML .= "</table>";

        return($HTML);
    }


    function testConnectorStrategy(){
        $testSuite = array(


            array('prefer','ing','preferring'),     // multi-syllable where SECOND is stressed
            array('prefer','ed','preferred'),
            array('prefer','ence','preference'),    // stress moved from preFER to PREference



            array('notice','able','noticeable'),           // able after ce ending
            array('replace','able','replaceable'),         // able after ce ending

            array('be','ing','being'),                    // final sylabic e  + initial not-e
            array('see','ing','seeing'),
            array('agree','able','agreeable'),
            array('forsee','able','forseeable'),
            array('agree','ed','agreed'),

            array('canoe','ing','canoeing'),               // final oe,ye is like final ee
            array('canoe','ed', 'canoed'),
            array('toe','ing', 'toeing'),
            array('toe','ed', 'toed'),
            array('eye','ing', 'eyeing'),
            array('eye','ed', 'eyed'),

            array('due','ly', 'duly'),                     // three 'ly' exceptions
            array('whole','ly', 'wholly'),
            array('true','ly', 'truly'),



            array('ease','y','easy'),               // final e + y

            array('cancel','ing','cancelling'),     // final L on a multi-syllable word
            array('open','ing','opening'),

            array('box','ing','boxing'),
            array('pry','ing','prying'),
            array('pry','ed','pried'),

            array('pony','es','ponies'),

            array('pack','ing','packing'),
            array('care','ing','caring'),

            array('pet','ing','petting'),       // monosyllable rule
            array('carpet','ing','carpeting'),
            array('pen','ed','penned'),
            array('happen','ed','happened'),

            array('focus','ing','focusing'),
            array('focus','ed','focussed'),

            array('crumb','y','crummy'),

            array('star','ing','starring'),
            array('die','ing','dying'),
            array('try','ing','trying'),
            array('fly','es','flies'),
            array('sew','ing','sewing'),
            array('wax','ing','waxing'),
            array('play','ing','playing'),
            array('pay','ment','payment'),
            array('army','es','armies'),
            array('quilt','ing','quilting'),
            array('bridge','ed','bridged'),
            array('calm','ed','calmed'),

            // som silent-e tests from http://www.resourceroom.net/readspell/silentedrop.asp
            array('home','less','homeless'),
            array('shape','ing','shaping'),
            array('shape','ed','shaped'),
            array('become','ing','becoming'),
            array('waste','ing','wasting'),
            array('fame','ous','famous'),
            array('hope','ful','hopeful'),

            array('duce','ate','ducate'),               // e+duce+ate+ion
            array('cate','ion','cation'),               // educate+ion

            array('place','ate','placate'),
            array('plate','ing','plating'),
            array('toe','ing','toeing'),
            array('toe','ed','toed'),
            array('eye','ed','eyed'),
            array('eye','ing','eyeing'),


            // this one fails....
            array('grime','y','grimy'),
            array('fun','y','funny'),

            //http//www.ryerson.ca/learningsuccess/resources/appendixg.pdf
            array('true','ly','truly'),
            //Keep the final silent e when it is preceded by a soft g or soft c:
            array('change','able','changeable'),
            array('courage','ous','courageous'),
            array('enforce','able','enforceable'),
            array('peace','able','peaceable'),
            array('produce','ing','producing'),

            // <panicking> <picnicking> <politicking> <rollicking>
            array('music','al','musical'),
            array('panic','ing','panicking'),
            array('picnic','ing','picnicking'),
            array('picnic','er','picnicker'),
            array('politic','ing','politicking'),
            array('traffic','ing','trafficking'),
            array('traffic','ed','trafficked'),

            array('humble','ly','humbly'),
            array('hap','y','happy'),
            array('barge','ed','barged'),

            // test the 'double-l'
            array('legal','ize','legalize'),   // -al endings don't double (except for crystal)
            array('final','ize','finalize'),
            array('propel','ed','propelled'),

            // exceptions for -ish, ite
            array('mon','ish','monish'),    //admonish
            array('mon','ite','monite'),    //premonition
            array('fin','ite','finite'),
            array('min','ion','minion'),
            array('trin','ity','trinity'),
            array('nat','ive','native'),
            array('nat','ure','nature')

        );

        foreach($testSuite as $test)
            $this->connectorStrategy($test[0],$test[1],$test[2]);
    }



    function listAll($base){
        // every affix returns an array of every legal variation including blank
        //  each element of the array is a triple:  array(internal, plus, graphic)

        $sublist = array();
        foreach ($this->aSub as $sub){      // first visit every possible sublist
//echo "in {$this->text}, sub is '{$sub->text}'<br>";
            $sublist = array_merge($sublist,$sub->listAll($this->text));
        }
//echo "in {$this->text}, sublist is [".implode(',',$sublist)."]<br>";

        //$list = array();
        //$list[] = array('graphic' => $this->connectDisplay($base,$this->text),
        //                'plus'    => empty($base)?"$this->text":"$base + $this->text",  // first prefix has empty base
        //                'final'   => $this->connectText($base,$this->text));
        //
        //
        //foreach($sublist as $sl)
        //    $list[] =  array('graphic' => $this->connectDisplay($base,$sl['graphic']),
        //                     'plus'    => empty($base)?"{$sl['plus']}":"$base + {$sl['plus']}",   // first prefix has empty base
        //                     'final'   => $this->connectText($base,$sl['final']));
        //

        $list = array();
        $list[] = array('graphic' => $this->connectDisplay($base,$this->text),
                        'plus'    => $this->connectPlus($base,$this->text),
                        'final'   => $this->connectText($base,$this->text,$this->connectImage));


        foreach($sublist as $sl)
            $list[] =  array('graphic' => $this->connectDisplay($base,$sl['graphic']),
                             'plus'    => $this->connectPlus($base,$sl['plus']),
                             'final'   => $this->connectText($base,$sl['final'],$this->connectImage));
        return($list);
    }

    function addControls($base,$control='',$style='',$gloss=''){

        $js_a = "onclick=makeButtonUnique(\"add\",\"$this->uniqid\");";
        $addIcon = ' <a href="#popupAffix" data-rel="popup" data-position-to="origin" data-role="button" data-icon="plus" data-theme="e" data-iconpos="notext" '.$js_a.'></a> ';

        // $deleteIcon =  $systemStuff->buildIconSubmit('process-stop',16,'actions','Delete','firstpage',$GLOBALS["matrixURL"],'delete','',$this->uniqid);

        // we are already in a <td $style>...

        $glossStyle = 'style="font-size:30%;font-style:italic;"';
        $HTML = '';
        if($this->type == MM_PREFIX){
            // if($control<>'nodelete')
            //     $HTML .= "</td><td>$deleteIcon</td><td $style>";  // extra <td> to avoid the $style
            $HTML .= "$addIcon</td>".
                    "<td $style>$base
                    <span $glossStyle><p>$gloss</p></span>
                        </td>";
        }else{
            $HTML .=  "$base
                    <span $glossStyle><p>$gloss</p></span>
                        </td>
                      <td $style>$addIcon";
            // if($control<>'nodelete')
                // $HTML .= "</td><td>$deleteIcon";
        }
        return($HTML);
    }


    // add a new affix
    function add($affix,$uniqid,$root){       // root is simply what is above, not the other side of the tree
        //  trace(__CLASS__,__METHOD__,__FILE__,"affix='$affix',$uniqid,root='$root'");

        // you can't just add $root and $this->text, always use connectText($root,$this->text)

        // if we are the 'uniqid' object, then add this affix,
        // otherwise pass it to our children
        if($this->uniqid != $uniqid){
            foreach($this->aSub as $sub)
                $sub->add($affix,$uniqid,$this->connectText($root,$this->text,$this->connectImage));
            return;
        }

        foreach($this->aSub as $sub){  // sanity check - if we already have this word, then ignore
            if($sub->text == $affix) return; }

        // move the new word to an object and add to our list
        $this->aSub[] = $sub = new matrixAffix($this->type);
        $sub->text = $affix;
        $sub->root = $root;

        $sub->connectImage = $this->connectorStrategy($this->connectText($root,$this->text,$this->connectImage),$affix);            // list of options

        $sub->gloss = $this->affixMeaning($this->type,$affix);
    }

    function delete($uniqid){

        // if we are the 'uniquid' object, then delete this affix,
        // otherwise pass it to our children
        if($this->uniqid != $uniqid){
            foreach($this->aSub as $k=>$p){
                if($p->delete($uniqid)){
                    unset($this->aSub[$k]);
                }
            }
            return(false);
        }else{

            // it's us! , but we don't have to do anything.  just tell our parent to delete us.
            // this will get more complicated if we allow delete for inside nodes.
            return(true);
        }
    }

    // every object has to know how to dehydrate and rehydrate itself.
    function dehydrate(){
        $this->storage = array();
        foreach($this->aSub as $sub){
            $sub->dehydrate();
            $this->storage[] = serialize($sub);
        }
    }
    function rehydrate(){
        $this->aSub = array();
        foreach ($this->storage as $sub)
            $this->aSub[] = unserialize($sub);
    }


    // // connectText returns a clean version of the word with the rules applied
    // function connectText($base,$affix){         // apply rules like doubling or i->y

    //     if($this->type == MM_POSTFIX){          // only apply rules to postfixes for now
    //         switch($this->connectImage){
    //             case CS_DOUBLE:
    //                 return($base.substr($base,-1).$affix);
    //             case CS_DROP_E:
    //                 return(substr($base,0,strlen($base)-1).$affix);
    //             case CS_IE_Y:
    //                 return(substr($base,0,strlen($base)-2).'y'.$affix);
    //             case CS_Y_I:
    //                 return(substr($base,0,strlen($base)-1).'i'.$affix);
    //             case CS_NONE:
    //                 return($base.$affix);
    //             case CS_ADD_K:
    //                 return($base.'k'.$affix);
    //             case CS_DROP_LE:
    //                 return(substr($base,0,strlen($base)-2).$affix);
    //             case CS_MB_MM:
    //                 return(substr($base,0,strlen($base)-2).'mm'.$affix);
    //             default:
    //                 assertTrue(false,"Unexpected CS_*** value '{$this->connectImage}'in connectText()");
    //         }
    //     }
    //     // this is for prefixes
    //         return($affix.$base);     // we'll put the base back in upstairs
    // }

    // similar to connectText, but adds the graphics or + markers



    // function connectDisplay($base,$affix){         // apply rules like doubling or i->y
    //     $connector = '<img src="images/'.$this->connectLookupPng($base).'" height="30"  />';

    //     if($this->type == MM_POSTFIX){          // only apply rules to postfixes for now
    //         switch($this->connectImage){
    //             case CS_DOUBLE:
    //                 return($base.$connector.$affix);
    //             case CS_DROP_E:
    //                 return(substr($base,0,strlen($base)-1).$connector.$affix);
    //             case CS_IE_Y:
    //                 return(substr($base,0,strlen($base)-2).$connector.$affix);
    //             case CS_Y_I:
    //                 return(substr($base,0,strlen($base)-1).$connector.$affix);
    //             case CS_NONE:
    //                 return($base.$connector.$affix);
    //             case CS_ADD_K:
    //                 return($base.$connector.$affix);
    //             case CS_DROP_LE:
    //                 return(substr($base,0,strlen($base)-2).$connector.$affix);
    //             case CS_MB_MM:
    //                 return(substr($base,0,strlen($base)-2).$connector.$affix);
    //             default:
    //                 assertTrue(false,"Unexpected CS_*** value '{$this->connectImage}'in connectText()");
    //         }
    //     }
    //     // this is for prefixes
    //     return($affix.$connector.$base);     // we'll put the base back in upstairs
    // }



//     function connectLookupPng($base){                      // this is the function belonging to this class


//         if($this->connectImage !== CS_DOUBLE){
//             switch($this->connectImage){       // this are the operations
//                 case CS_NONE:
//                     return ('sep-none.PNG');
//                 case CS_DROP_E:
//                     return ('sep-drop-e.PNG');
//                 case CS_IE_Y:
//                     return ('sep-ie-y.PNG');
//                 case CS_Y_I:
//                     return ('sep-y-i.PNG');
//                 case CS_ADD_K:
//                     return ('sep-add-k.PNG');
//                 case CS_DROP_LE:
//                     return ('sep-drop-le.PNG');
//                 case CS_MB_MM:
//                     return ('sep-mb.PNG');

//             }
//         }

//         // it is CS_DOUBLE, we look at the root to see what to double
//         assertTrue($this->connectImage==CS_DOUBLE,"Unexpected value for connectLookupPng($this->connectImage) in ".__METHOD__);

//         $c = substr($base,-1);
//         $png = 'sep-'.$c.'-'.$c.$c.'.PNG';   //'sep-d-dd.PNG'
// //echo "connectLookupPng($this->connectImage,$base) returns $png<br>";
//         return ($png);
//     }
 }
