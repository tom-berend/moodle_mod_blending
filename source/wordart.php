<?php



// todo:  create WordArtMax which shows every consonant element (eg: kn and mb)
//        add the connector images to WordArtMax and WordArtFull
//        update FESTIVAL dict to use new syntax

////// old syntax
//    \[xplaid;*]                       // unknown word
//    +[plaid;plaid]                    // irregular word
//    [f;f].[i;igh]/[re;er].[d;d]       // decoded word  [spelling / sound]

////// new syntax
//    \[xplaid;*]                       // unknown word
//    [f;f].[i;igh]/[re;er]             // two-syllable base (assuming 'fire' has two syllables)
//     [l;l].[oo;oo].[k;k]-[ed;$]       // affixes use connectors

// don't use wordArtAbstract class directly, but instead use one of
//      wordArtNone
//      wordArtMinimal
//      wordArtDecodable
//      wordArtFull



// var $phoneSet = array(
//     // the keys are tom's phonics set
//     // note the schwa spellings gets removed after decoding the dictionary
//     'ah' => array('type' => 'v',  'key' => 'bad',   'spellings' => array('eah', 'ay', 'a')),
//     'eh' => array('type' => 'v',  'key' => 'egg',   'spellings' => array('ea', 'e', 'ai' /* schwa */, 'ou', 'a', 'i', 'o', 'u' /*plus 'eo' *//*add*/, 'ue', 'io', 'ay', 'ie', 'ah')),
//     'ih' => array('type' => 'v',  'key' => 'big',  'spellings' => array('i', 'ui', 'y' /*add*/, 'ea', 'ee', 'e')),
//     // note: o/aw will be remapped to o/ow
//     'aw' => array('type' => 'v',  'key' => 'pot',   'spellings' => array('aw', 'au', 'ou', 'ough', 'augh', 'a', 'o', 'oa', 'ho')),  // aa/aardvark
//     'uh' => array('type' => 'v',  'key' => 'tub',  'spellings' => array('u', 'ou', 'o_e' /*add*/, 'oo', 'o')),


//     'g' => array('type' => 'c',   'key' => 'got',   'spellings' => array('g', 'gg', 'gh', 'gu', 'gue')),
//     'j' => array('type' => 'c',   'key' => 'job',   'spellings' => array('j', 'ge', 'g', 'dge', 'd')),
//     'v' => array('type' => 'c',   'key' => 'van',   'spellings' => array('v', 've', 'f')),
//     'h' => array('type' => 'c',   'key' => 'hat',   'spellings' => array('h', 'wh')),
//     'w' => array('type' => 'c',   'key' => 'win',   'spellings' => array('w', 'wh', 'u', 'o_e')),
//     'z' => array('type' => 'c',   'key' => 'zip',   'spellings' => array('se', 'ze', 'z', 'zz', 's', 'x')),

//     'ay' => array('type' => 'v',  'key' => 'day',   'spellings' => array('a_e', 'ai', 'ay', 'ea', 'eigh', 'a', 'ei', 'aigh', 'ey' /* add */, 'au')),
//     'ee' => array('type' => 'v',  'key' => 'tree',  'spellings' => array('e_e', 'ee', 'ea', 'ei', 'ey', 'e', 'y', 'i', 'ie', 'i_e', 'eo')),
//     'igh' => array('type' => 'v', 'key' => 'high',  'spellings' => array('i_e', 'ie', 'i', 'igh', 'y', 'eigh', 'uy', 'eye')),
//     'oh' => array('type' => 'v',  'key' => 'coat',  'spellings' => array('o_e', 'oa', 'oe', 'o', 'ow', 'ough', 'owe', 'ou', 'oo' /*add*/, 'aw', 'au', 'oh', 'a')),
//     'ue' => array('type' => 'v',  'key' => 'rude',  'spellings' => array('ough', 'u_e', 'ew', 'ue', 'o', 'oo', 'ou', 'ui', 'u', 'eu')),

//     'r' => array('type' => 'c',   'key' => 'red',   'spellings' => array('r', 'wr', 'rr', 'rh', 're')),
//     'ye' => array('type' => 'c',   'key' => 'yam',   'spellings' => array('y')),
//     'th' => array('type' => 'c',  'key' => 'thin',  'spellings' => array('th', 'the')),
//     'dh' => array('type' => 'c',  'key' => 'then',  'spellings' => array('th', 'the')),
//     'sh' => array('type' => 'c',  'key' => 'shop',  'spellings' => array('sh', 'ch', 'ss', 's', 't', 'che', 'c', 'sc')),
//     'ch' => array('type' => 'c',  'key' => 'chin',  'spellings' => array('ch', 'tch', 't')),

//     'b' => array('type' => 'c',   'key' => 'big',   'spellings' => array('b', 'bb')),
//     'd' => array('type' => 'c',   'key' => 'dog',   'spellings' => array('d', 'ed', 'dd')),
//     'f' => array('type' => 'c',   'key' => 'fun',   'spellings' => array('f', 'ff', 'ph', 'gh', 'lf')),
//     'k' => array('type' => 'c',   'key' => 'kid',   'spellings' => array('ck', 'c', 'k', 'ch', 'x', 'lk', 'cc')), //que??
//     'l' => array('type' => 'c',   'key' => 'log',   'spellings' => array('l', 'll', 'le', 'el', 'il', 'al')),
//     'm' => array('type' => 'c',   'key' => 'man',   'spellings' => array('mn', 'mb', 'mm', 'm', 'lm')),
//     'n' => array('type' => 'c',   'key' => 'not',   'spellings' => array('nn', 'kn', 'gn', 'pn', 'n', 'ne', 'hn')),
//     'p' => array('type' => 'c',   'key' => 'pig',   'spellings' => array('pe','ppe','pp', 'p')),
//     's' => array('type' => 'c',   'key' => 'sat',   'spellings' => array('s', 'c', 'ss', 'ce', 'se', 'st', 'sc' /*add*/, 'z', 'sw')),
//     't' => array('type' => 'c',   'key' => 'top',   'spellings' => array('tt', 'bt', 'pt', 't', 'te', 'ed', 'tw')),

//     'ks' => array('type' => 'c',  'key' => 'tax',   'spellings' => array('x')),

//     // why do we need this twice? ('aquatic')
//     'kw' => array('type' => 'c',  'key' => 'quit',  'spellings' => array('qu', 'ck', 'cqu')),
//     'qu' => array('type' => 'c',  'key' => 'quit',   'spellings' => array('qu')),
//     'x' => array('type' => 'c',   'key' => 'tax',   'spellings' => array('x')),

//     'ow' => array('type' => 'v',  'key' => 'otter', 'spellings' => array('o', 'ou', 'ow', 'ough', 'au', 'aw', 'oa')), // au aw  and oa get transformed later
//     'oo' => array('type' => 'v',  'key' => 'book',  'spellings' => array('oo', 'ue', 'ew', 'ui', 'u_e', 'u', 'ou', 'oe', 'o', 'ough', 'oul')),
//     'oy' => array('type' => 'v',  'key' => 'boy',   'spellings' => array('oi', 'oy')),

//     'air' => array('type' => 'v', 'key' => 'fair',  'spellings' => array('ar', 'are', 'air', 'arr', 'err', 'ear' /*add*/, 'aire', 'aer', 'ehr', 'ere', 'er')),
//     'ar' => array('type' => 'v',  'key' => 'part',  'spellings' => array('ar', 'oar')),
//     'er' => array('type' => 'v',  'key' => 'bird',  'spellings' => array('er', 'ur', 'ure', 'ir', 'or', 'r', 're', 'ear', 'yr', 'or', 'our', 'ore', 'oar', 'oor', 'ar', 'ier')),
//     //            'or'=>array('type'=>'v',  'key'=>'bird',  'spellings'=>array('or','ore','oar','our','oor')      ),

//     'zh' => array('type' => 'c',  'key' => 'measure', 'spellings' => array('z', 's')),
//     'ng' => array('type' => 'c',  'key' => 'sing',   'spellings' => array('ng', 'n', 'ngue'))


//     // sort out OO and oo
//     // what about dh? qu?,

// );

























// this is the global list of words that must be memorized
function memorize_words()
{
    return ('I,you,our,the,was,so,to,no,do,of,too,one,two,he,she,be,are,said,their');
}
function memorize_words_count()
{
    return (count(explode(',', memorize_words())));
}

class wordArtAbstract
{

    public $showPhones = true;          // only works in debug

    public $bigrams = true; // process bigrams like 'pr'

    public $groupEVowels = true;
    public $groupBigrams = true;

    public $phoneString = '';

    public $digraphs = ",th,sh,ch,kn,igh,ough,se,ge,ve,ce,the,tch,";    // remember trailing comma

    public $aPhones; // array of phones like   o_e;oa.  (the o_e spelling of /oa/ with a . separator)
    //    valid separators are . (small space)
    //                         + (join with next)
    //                         / (syllable mark)
    //                         > (last char)

    // this is for decodables, where we can instrument with re<al/ign>ed marks
    public $affix = [];
    public $prefix = [];
    public $aSyllableBreaks = [];
    public $silent_e_follows = false;



    // this is for punctuation and surrounding quotes - we turn "Stop!" into stop
    // we have to remember the first and last stuff we strip off.
    public $first = '';
    public $last = '';

    public $vSpacing = '2rem';
    public $fontSize = '6rem';
    public $pronFontSize = '1.5rem';
    public $dimmable = false;

    // these are the style elements, can be reset...
    public $CSS_Consonant = 'sp_c2';
    public $CSS_Vowel = 'sp_v2';
    public $CSS_NotFound = 'sp_x2';
    public $CSS_Silent = 'sp_e2'; // a_e (e part)
    public $CSS_Addon = 'sp_m2'; // follows apostrophe
    public $CSS_Unknown = 'sp_x2';

    public $CSS_Black = 'sp_b2';
    public $CSS_HighLite = 'sp_b2plus';
    public $CSS_Yee = 'sp_yee';     // final y makes the sound 'ee'

    public $CSS_dipthong2c = 'sp_dipthong2c';   // for th, ch , sh
    public $CSS_dipthong2v = 'sp_dipthong2v';   // for igh, ough, etc


    // digraphs that we want to highlight in a specific page (not implemented yet)
    public $aHighlightDigraph = [];


    public function reset()
    {
        $this->affix = [];
        $this->prefix = [];
        $this->aSyllableBreaks = [];
        $this->first = '';
        $this->last = '';
    }




    public function setHighlightDigraph($aDigraph)
    {
        assertTrue(is_array($aDigraph));
        $this->aHighlightDigraph = $aDigraph;
    }

    // turn 'p+u+ll' into 'p+u+l+l'
    public function expandConsonantDoubles($phoneString)
    {
        $aP = explode('[', $phoneString);
        array_shift($aP);   // 'cuz the first element is empty

        $retPhone = '';
        foreach ($aP as $phone) {
            // simpleminded test - if we find 'aeiouy' then don't expand it
            // can't use is_consonant() because that is for SOUNDS
            $phoneSpell = $this->phoneSpelling($phone);
            $phoneSound = $this->phoneSound($phone);

            if (
                strlen($phoneSpell) > 1   // only bother if multi-character
                and strpos($this->digraphs, ',' . strtolower($phoneSpell) . ',') === false  // not in special digraphs
                and strpos($phoneSpell, 'a') === false
                and strpos($phoneSpell, 'e') === false
                and strpos($phoneSpell, 'i') === false
                and strpos($phoneSpell, 'o') === false
                and strpos($phoneSpell, 'u') === false
                and strpos($phoneSpell, 'y') === false
            ) {

                // if we are here, we have consonants
                $aChar = str_split($phoneSpell);
                foreach ($aChar as $char) {     // create a new multiple-phone version
                    if (!empty($retPhone)) $retPhone .= '.';   // period separator
                    $retPhone .= "[$char;$phoneSound]";  // gets written back by reference
                }
            } else {
                if (!empty($retPhone)) $retPhone .= '.';   // period separator
                $retPhone .= "[$phoneSpell;$phoneSound]";    // what we started with
            }
        }
        // printNice("ExpandConsonantDoubles $phoneString => $retPhone");
        return ($retPhone);
    }

    function testExpandConsonantDoubles()
    {
        $test = $this->expandConsonantDoubles('[w;w].[i;ih].[ll;l]');
        assertTrue($test == '[w;w].[i;ih].[l;l].[l;l]', "got $test");
        return (true);
    }


    // pull the decodable marks  < / > out of the word
    public function stripDecodableMarks($word)
    {
        // remove all affixes, but remember them
        while (strpos($word, '>') > 0) {
            // $affix = '('.substr($word,$star+1).')';
            $star = strpos($word, '>');
            $this->affix[] = substr($word, $star + 1);
            $word = substr($word, 0, $star);
        }

        // remove all prefixes, but remember them
        while (strpos($word, '<') > 0) {
            // $affix = '('.substr($word,$star+1).')';
            $star = strpos($word, '<');
            $this->prefix[] = substr($word, 0, $star);
            $word = substr($word, $star + 1);
        }


        // remove all the / syllable breaks, but remember where they were
        $this->aSyllableBreaks = [];
        while (strpos($word, '/') > 0) {
            $star = strpos($word, '/');
            array_push($this->aSyllableBreaks, $star);
            // $affix = '('.substr($word,$star+1).')';
            $word = substr($word, 0, $star) . substr($word, $star + 1);
            $star = strpos($word, '/');
        }
        return ($word);
    }


    // if we have a word like "Stop!", we only want 'Stop'
    // but we want to remember how to reassemble the punctuated word
    public function stripPunctuation($word)
    {
        // might have a period or a quote at start or end...

        $this->first = '';
        $this->last = '';
        // PUT THE LONGEST TESTS FIRST !!
        if (substr($word, -3) == "'ll") {   // i'll
            $this->last = '[' . substr($word, -3) . ";*]"; // want the 's in consonant colour
            $word = substr($word, 0, strlen($word) - 3);
        }

        if (substr($word, -2) == "'s" or substr($word, -2) == "'t" or substr($word, -2) == "'d") {   // can't  bill's
            $this->last = '[' . substr($word, -2) . ";*]"; // want the 's in consonant colour
            $word = substr($word, 0, strlen($word) - 2);
        }
        // special case for ants," (comma quote, or period quote, ...)
        if (substr($word, -2) == ",\"" or substr($word, -2) == ".\"" or substr($word, -2) == "!\"" or substr($word, -2) == "?\"" or substr($word, -2) == "-\"") {
            $this->last = '[' . substr($word, -2) . ";*]"; // want the 's in uncoded colour
            $word = substr($word, 0, strlen($word) - 2);
        }

        if (ctype_punct(substr($word, 0, 2))) {
            // convert the punctuation to a phone block
            $this->first = '[' . substr($word, 0, 2) . ';*]'; // something like [(";*]
            $word = substr($word, 2);
        }

        if (ctype_punct(substr($word, 0, 1))) {
            // convert the punctuation to a phone block
            $this->first = '[' . substr($word, 0, 1) . ';*]'; // something like [";*]
            $word = substr($word, 1);
        }

        if (ctype_punct(substr($word, -3))) {       // might have !",
            $this->last = '[' . substr($word, -3) . ';*]';
            $word = substr($word, 0, strlen($word) - 3);
        }

        if (ctype_punct(substr($word, -2))) {       // might have ",
            $this->last = '[' . substr($word, -2) . ';*]';
            $word = substr($word, 0, strlen($word) - 2);
        }

        if (ctype_punct(substr($word, -1))) {
            $this->last = '[' . substr($word, -1) . ';*]';
            $word = substr($word, 0, strlen($word) - 1);
        }

        // printNice('xxx', "Extracted Punctuation   '$this->first'   '$this->last'");

        return ($word);
    }



    public function testWordArt()
    {
        $HTML = '';

        $testArray = array(
            'scrap',
            'wholesome',
            'overstatement',
            'enterprise',
            'alphabetical',
            'straightening',
            'bride',
            'association',
            'plaid',
            'abbreviation',
            'ambassadorial',
            'boot',
            'foot',
            'strengths',
        );

        $testArray = array(
            'stairway',
            'phonics',
        );

        foreach ($testArray as $test) {

            for ($i = 0; $i < 6; $i++) {
                switch ($i) {
                    case 0:
                        $wordArt = new wordArtFull();
                        break;
                    case 1:
                        $wordArt = new wordArtDecodable();
                        break;
                    case 2:
                        $wordArt = new wordArtSimple();
                        break;
                    case 3:
                        $wordArt = new wordArtColour();
                        break;
                    case 4:
                        $wordArt = new wordArtMinimal();
                        break;
                    case 5:
                        $wordArt = new wordArtNone();
                        break;
                }

                $HTML .= '<br>' . $wordArt->render($test);
            }
        }
        return $HTML;
    }

    public function lookupDictionary($word): string
    {
        require_once('source/dictionary.php');
        global $spellingDictionary;

        $lcWord = strtolower($word);
        if (isset($spellingDictionary[$lcWord])) {
            return $spellingDictionary[$lcWord];
        } else {
            printNice("Did not find '$lcWord' in dictionary, with wordcount " . count($spellingDictionary));
            return '';
        }
    }

    public function render(string $word): string
    { // single word render

        // if the word starts with '[' then it is already a phonestring
        if (substr($word, 0, 1) == '[') {
            $phoneString = $word;
        } else {
            $phoneString = $this->lookupDictionary($word);
        }

        // printNice('WordArt', array($word, $phoneString));
        return ($this->renderPhones($phoneString)); // returns an HTML string
    }



    // renderPhones handles a full word (with syllable breaks, etc)
    public function renderPhones(string $phoneString): string
    {
        $syllables = explode('/', $phoneString);
        $needSyllableSeparator = false;

        $character = new SingleCharacter();

        foreach ($syllables as $syllable) {

            if ($needSyllableSeparator) {
                $character->addSyllableSeparator();
                $needSyllableSeparator = false;
            }

            $aPhones = explode('.', $syllable); // explode into phones

            $this->silent_e_follows = false;
            for ($i = 0; $i < count($aPhones); $i++) {   // hard to look ahead with foreach()


                // collapse bigrams - collapse consonant with a . separator followed by another consonant, combine them
                if (
                    $this->is_consonant($this->phoneSound($aPhones[$i]))
                    and $i < count($aPhones) - 1   // there remains a phone
                    and $this->is_consonant($this->phoneSound($aPhones[$i + 1]))
                ) {
                    // printNice($aPhones, "collapsing {$aPhones[$i]} and {$aPhones[$i + 1]}");

                    // but ONLY if the spelling == sound (not for f ph)
                    //and $this->phoneSound($aPhones[$i+1]) == $this->phoneSpelling($aPhones[$i+1]))
                    // need to merge [p;p].[r;r].  => [pr;pr].
                    $newPhone = '[' . $this->phoneSpelling($aPhones[$i]) . $this->phoneSpelling($aPhones[$i + 1]) . ';' .
                        $this->phoneSound($aPhones[$i]) . ' ' . $this->phoneSound($aPhones[$i + 1]) . ']';

                    $aPhones[$i + 1] = $newPhone;
                    $i = $i + 1;  // skip this character


                    // check the next one too, in case we have a trigram like 'str'

                }


                $spelling = $this->phoneSpelling($aPhones[$i]);
                $sound = $this->phoneSound($aPhones[$i]);

                // patch the silent-e
                if (in_array($spelling, ['a_e', 'e_e', 'i_e', 'o_e', 'u_e'])) {

                    $spelling = substr($spelling, 0, 1);
                    $aPhones[$i] = "[$spelling;$sound]"; //substitute  a if was a_e

                    $this->silent_e_follows = true;  // but is the LAST character in the syllable
                    $character->underline = true;
                }

                // create the HTML for a single phone
                $this->outputInsideGroup($character, $aPhones[$i]);
            }
            // add the silent_e if we must
            if ($this->silent_e_follows) {
                $this->outputInsideGroup($character, '[e;]');   // add an extra phone

                $this->silent_e_follows = false;
                $character->underline = false;
            }


            $needSyllableSeparator = true;
        }

        // ok, we have a word, collect it
        $HTML = $character->collectedHTML();

        return $HTML;
    }






    //////////////////////////
    // three quick functions to return the sound, spelling, and separator of a phone
    //////////////////////////

    public function phoneSound($phone): string
    {
        return (get_string_between($phone, ';', ']'));
    }
    public function phoneSpelling($phone): string
    {
        return (get_string_between($phone, '[', ';'));
    }
    public function phoneSeparator($phone)
    {
        return (substr($phone, -2, 1));
    }



    public function adjustedSpelling($phone, $outside = true): string
    {
        $spelling = $this->phoneSpelling($phone);
        if ($outside) {
            if (substr($spelling, -1) == '-') {
                return (substr($spelling, 0, strlen($spelling) - 1));
            }
            // a prefix-
            if (substr($spelling, 0, 1) == '-') {
                return (substr($spelling, 1));
            }
            // a -suffix
            if (substr($spelling, 0, 1) == '*') {
                return (substr($spelling, 1));
            }
            // not processed by CMUdict
        } else {
            if (empty($spelling)) {
                return $this->phoneSound($phone);
            }
            // usually 'e'                         // silent E
            if (substr($spelling, 1, 1) == '_') {
                return (substr($spelling, 0, 1));
            }
            // an o_e spelling
        }
        return ($spelling);
    }

    public function is_consonant($sound)
    {
        if (empty($sound)) {
            return (false);
        }
        // protect against...

        $ret = false; // default
        if (strpos('.,b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,.t,v,w,x,y,z', substr(strtolower($sound), 0, 1))) {
            $ret = true;
        }
        if (strpos(',zh,kw,ks,ng,th,dh,sh,ch', substr(strtolower($sound), 0, 1))) {
            $ret = true;
        }

        if ($sound == '"' or $sound == "'" or $sound == '.' or $sound == ',') {
            $ret = true;
        }
        // treat quotes as consoants

        return ($ret);
    }


    public function outputInsideGroup(SingleCharacter $character, string $phone)
    {
        assertTrue(false, 'should never get here, define this in each subclass');
        return '';
    }
}

interface wordArtOutputFunctions
{
    function outputInsideGroup(SingleCharacter $character, string $phone);
}





class wordArtNone extends wordArtAbstract implements wordArtOutputFunctions
{

    public function outputInsideGroup(SingleCharacter $character, string $phone)
    {

        $spelling = $this->phoneSpelling($phone);
        $sound = $this->phoneSound($phone);

        $character->underline = false;  // always
        $character->syllableSeparators = false;

        // if (in_array($spelling, ['a_e', 'e_e', 'i_e', 'o_e', 'u_e'])) {
        //     $spelling = substr($spelling, 0, 1);
        //     $phone = "[$spelling;$sound]"; //substitute  a if was a_e
        // }

        $character->spelling = $spelling;  // $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        // consonants get blue

        $sp = $this->phoneSpelling($phone);
        $colour = 'sp_e'; // default colour for simple wordArt


        $character->addToCollectedHTML($phone);
    }
}

class wordArtMinimal extends wordArtAbstract implements wordArtOutputFunctions
{

    public function render(string $word): string
    { // single word render - does NOT look up dictionary (because messes up morphemes)

        // just create a dummy phoneString
        $chars = str_split($word); // array of characters
        // printNice($chars, 'wordArtColor2');
        $phoneString = '';
        foreach ($chars as $char) {
            $phoneString .= "[$char;$char]";
        }
        // printNice('==>' . $word . '<==', 'wordArtColor2');
        // printNice($phoneString, 'wordArtColor2');
        $ret = $this->renderPhones($phoneString); // returns an HTML string
        // printNice($ret, 'wordArtColor2');
        return ($ret);
    }



    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {
        // $spelling = $this->adjustedSpelling($phone, false);
        $spelling = $this->phoneSpelling($phone, false);
        $sound = $this->phoneSound($phone);

        $character->underline = false;  // always

        // if (in_array($spelling, ['a_e', 'e_e', 'i_e', 'o_e', 'u_e'])) {
        //     $spelling = substr($spelling, 0, 1);
        //     $phone = "[$spelling;$sound]"; //substitute  a if was a_e
        // }

        $colour = 'sp_e'; // default colour for simple wordArt


        $character->addToCollectedHTML($phone);
    }
}

class wordArtSimple extends wordArtAbstract implements wordArtOutputFunctions
{

    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {

        $spelling = $this->phoneSpelling($phone, false);
        $sound = $this->phoneSound($phone);

        $character->underline = false;  // always
        $character->syllableSeparators = false;

        // if (in_array($spelling, ['a_e', 'e_e', 'i_e', 'o_e', 'u_e'])) {
        //     $spelling = substr($spelling, 0, 1);
        //     $phone = "[$spelling;$sound]"; //substitute  a if was a_e
        // }

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
        } else {
            $character->textcolour = ($this->is_consonant($spelling)) ? 'darkblue' : 'red';
        }

        $character->addToCollectedHTML($phone);
    }
}





class wordArtColour extends wordArtAbstract implements wordArtOutputFunctions
{

    public function render(string $word): string
    { // single word render - does NOT look up dictionary (because messes up morphemes)

        // just create a dummy phoneString
        $chars = str_split($word); // array of characters
        // printNice($chars, 'wordArtColor2');

        $phoneString = '';
        foreach ($chars as $char) {
            $phoneString .= "[$char;$char]";
        }
        // printNice('wordArtColor2', '==>' . $word . '<==');
        // printNice('wordArtColor2', $phoneString);
        $ret = $this->renderPhones($phoneString); // returns an HTML string
        // printNice('wordArtColor2', $ret);
        return ($ret);
    }

    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {

        $spelling = $this->phoneSpelling($phone);
        $sound = $this->phoneSound($phone);

        // if (in_array($spelling, ['a_e', 'e_e', 'i_e', 'o_e', 'u_e'])) {
        //     $spelling = substr($spelling, 0, 1);
        //     $phone = "[$spelling;$sound]"; //substitute  a if was a_e
        // }



        $textcolour = 'red'; // default colour for vowels
        if ($this->is_consonant($sound)) {
            $textcolour = 'darkblue';
        }
        // consonants get blue

        $sp = $this->phoneSpelling($phone);
        $colour = 'sp_none_narrow'; // no boxes


        $character->addToCollectedHTML($phone);
    }
}


class wordArtDecodable extends wordArtAbstract implements wordArtOutputFunctions
{


    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {
        $spelling = $this->phoneSpelling($phone);
        $sound = $this->phoneSound($phone);

        $character->phonics = !$this->is_consonant($sound); // show the topline phonics?

        $character->syllableSeparators = true;

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        if ($this->silent_e_follows) {
            $character->underline = true;
        }

        // vowels get red, consonants get blue, silent-E gets green
        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
        } else {
            $character->textcolour = ($this->is_consonant($spelling)) ? 'darkblue' : 'red';
        }

        // final fix - if the sound is identical to the spelling (ie: basic spelling) don't show it
        $character->sound = $this->phoneSound($phone);
        if ($sound == $spelling) {
            $character->sound = '';
        }
        $character->addToCollectedHTML($phone);
    }
}

class wordArtFull extends wordArtAbstract implements wordArtOutputFunctions
{


    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {
        $spelling = $this->phoneSpelling($phone);
        $sound = $this->phoneSound($phone);

        $character->phonics = !$this->is_consonant($sound); // show the topline phonics?

        $character->syllableSeparators = true;

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        if ($this->silent_e_follows) {
            $character->underline = true;
        }

        $character->border = true;
        // vowels get red, consonants get blue, silent-E gets green
        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
            $character->background = 'white';
        } else {
            if ($this->is_consonant($spelling)) {
                $character->textcolour = 'blue';
                $character->background = '#d0e0ff';
            } else {
                $character->textcolour = 'red';
                $character->background = '#ffe0ff';
            }
            $character->textcolour = ($this->is_consonant($spelling)) ? 'darkblue' : 'red';
        }

        // final fix - if the sound is identical to the spelling (ie: basic spelling) don't show it
        $character->sound = $this->phoneSound($phone);
        if ($sound == $spelling) {
            $character->sound = '';
        }
        $character->addToCollectedHTML($phone);
    }
}



// class sp_c    blue     consonants
// class sp_v    red      vowels
// class sp_e    white    silent letters (usually 'e')
// class sp_m    green    prefixes and suffixes





// this accepts a single phone [a;ah] and returns a <td>something</td>
class SingleCharacter
{

    // these are for rendering a single character
    public $spelling = '';
    public $sound = '';
    public $vSpacing = '1';
    public $pronFontSize = '1.5rem';
    public $lineHeight; // defined in constructor
    public $fontSize;  // defined in constructor
    public $textcolour = 'darkblue';
    public $background = 'white';
    public $underline = false;
    public $border = false;
    public $dimmable = '';
    public $phonics = false;
    public $syllableSeparators = false;

    // the output consists of a table with three rows (top, middle, bottom)
    public $topHTML = '';
    public $middleHTML = '';
    public $bottomHTML = '';

    function __construct()
    {

        $this->fontSize = $GLOBALS['mobileDevice'] ? '2.0em' : '5.4em';
        $this->lineHeight = $GLOBALS['mobileDevice'] ? '0.6' : '1.1';
    }

    function addToCollectedHTML(string $phone)
    {
        $this->sound = $this->phoneSound($phone);

        // $sp = $this->phoneSpelling($phone);
        // $colour = 'sp_e'; // default colour for simple wordArt

        $spanClass = 'sp_spell' . ($this->dimmable ? ' dimmable' : '');
        if ($this->underline)
            $spanClass .= " sp_spell2u";

        $topborder = ($this->border) ? 'border-top:solid 1px darkblue;' : '';
        $sideborder = ($this->border) ? 'border-right:solid 1px darkblue;border-left:solid 1px darkblue;' : '';
        $bottomborder = ($this->border) ? 'border-bottom:solid 1px darkblue;' : '';

        // top row
        $this->topHTML .= "<td style='text-align:center;padding:0;background-color:{$this->background};$topborder;$sideborder'>";

        if ($this->phonics) {  // do we show the phonics row?
            if (empty($this->sound)) {
                $this->topHTML .= "    <span class='sp_pron'  font-size:{$this->pronFontSize}'>&nbsp</span>";
            } else {
                // $this->topHTML .= "    <span class='sp_pron'  style='background-color:#e0ffff;border:solid 1px black; border-radius:10px;font-size:{$this->pronFontSize}'>&nbsp;$this->sound&nbsp;</span>";
                $view = new ViewComponents();
                $this->topHTML .= $view->sound($this->sound);
            }
        } else {
            $this->topHTML .= "    <span class='sp_pron'  font-size:{$this->pronFontSize}'>&nbsp</span>";
        }
        $this->topHTML .= '</td>';

        // middle row
        $this->middleHTML .= "<td style='line-height:{$this->lineHeight};padding:{$this->vSpacing}px 0px {$this->vSpacing}px 0px;background-color:{$this->background};$sideborder;'>";
        $this->middleHTML .= "    <span class='$spanClass' style='font-size:{$this->fontSize};color:$this->textcolour;'>";
        $this->middleHTML .=          $this->spelling;
        $this->middleHTML .= "    </span>";
        $this->middleHTML .= "</td>";

        // bottom row (not used yet)
        $this->bottomHTML .= "<td  style='padding:0;$bottomborder;'></td>\n";
    }

    function addSyllableSeparator()
    {
        $spanClass = 'sp_spell' . ($this->dimmable ? ' dimmable' : '');

        // no borders
        if ($this->syllableSeparators) {
            $this->topHTML .= "<td style='padding:0;border:none;'></td>";
            $this->middleHTML .= "<td class='$spanClass' style='padding:20px 0 0 0;font-size:3rem;'>&nbsp;&sol;&nbsp;</td>";
            $this->bottomHTML .= "<td style='padding:0;'></td>";
        }
    }

    function collectedHTML(): string
    {
        $border = ($this->border) ? "style='border-top:solid 1px darkblue;border-bottom:solid 1px darkblue;'" : '';

        $HTML = "<table>";
        $HTML .= "<tr>$this->topHTML</tr>";
        $HTML .= "<tr>$this->middleHTML</tr>";
        $HTML .= "<tr>$this->bottomHTML</tr>";
        $HTML .= '</table>';
        return $HTML;
    }


    public function phoneSound($phone): string
    {
        return (get_string_between($phone, ';', ']'));
    }
    public function phoneSpelling($phone): string
    {
        return (get_string_between($phone, '[', ';'));
    }
    public function phoneSeparator($phone)
    {
        return (substr($phone, -1));
    }
    public function adjustedSpelling($phone, $outside = true): string
    {
        $spelling = $this->phoneSpelling($phone);
        if ($outside) {
            if (substr($spelling, -1) == '-') {
                return (substr($spelling, 0, strlen($spelling) - 1));
            }
            // a prefix-
            if (substr($spelling, 0, 1) == '-') {
                return (substr($spelling, 1));
            }
            // a -suffix
            if (substr($spelling, 0, 1) == '*') {
                return (substr($spelling, 1));
            }
            // not processed by CMUdict
        } else {
            if (empty($spelling)) {
                return $this->phoneSound($phone);
            }
            // usually 'e'                         // silent E
            if (substr($spelling, 1, 1) == '_') {
                return (substr($spelling, 0, 1));
            }
            // an o_e spelling
        }
        return ($spelling);
    }



    public function is_consonant($sound)
    {
        if (empty($sound)) {
            return (false);
        }
        // protect against...

        $ret = false; // default
        if (strpos('.,b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,.t,v,w,x,y,z', substr(strtolower($sound), 0, 1))) {
            $ret = true;
        }
        if (strpos(',zh,kw,ks,ng,th,dh,sh,ch', substr(strtolower($sound), 0, 1))) {
            $ret = true;
        }

        if ($sound == '"' or $sound == "'" or $sound == '.' or $sound == ',') {
            $ret = true;
        }
        // treat quotes as consoants

        return ($ret);
    }
}




/// some utility functions

function get_string_between($string, $start, $end)
{
    if (empty($start)) {
        $ini = 0;
    }
    // by convention, the empty string returns from start
    else {
        $ini = strpos($string, $start);
    }

    if ($ini === false) {
        return ('');
    }

    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return (substr($string, $ini, $len));
}

// cannot replace just the FIRST occurance with str_replace(), didn't want to use regular expressions
function str_replace_single($needle, $replace, $haystack)
{ // like str_replace() but only first occurance
    $pos = strpos($haystack, $needle);
    if ($pos !== false) {
        $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    return ($haystack);
}
