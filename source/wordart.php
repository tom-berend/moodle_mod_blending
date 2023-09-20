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

    public $consonantDigraphs = ['th', 'sh', 'ch', 'kn', 'igh', 'ough', 'se', 'ge', 've', 'ce', 'the', 'ph', 'wr', 'ck', 'tch'];

    public $aPhones; // array of phones like   o_e;oa.  (the o_e spelling of /oa/ with a . separator)
    //    valid separators are . (small space)
    //                         + (join with next)
    //                         / (syllable mark)
    //                         > (last char)

    // array of prefixs, base, postfixes
    public array $affixes = [];   // ['prefix' => [], 'base' => '', 'postfix' => []];

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

    // this is the global list of words that must be memorized
    public $memorize_words = ['I', 'you', 'our', 'the', 'was', 'so', 'to', 'no', 'do', 'of', 'too', 'one', 'two', 'he', 'she', 'be', 'are', 'said', 'their'];



    public function reset()
    {
        $this->affix = [];
        $this->prefix = [];
        $this->aSyllableBreaks = [];
        $this->first = '';
        $this->last = '';
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
            // printNice("Did not find '$lcWord' in dictionary, with wordcount " . count($spellingDictionary));
            return '';
        }
    }



    // words can look like:
    // ride
    // ride>ing   // note extra e
    // un<ride>able

    // this is render EXCEPT for WordArtNone() which has its own.
    public function render(string $word): string
    { // single word render

        $this->affixes = $this->parseMorphology($word);
        // printNice($this->affixes, htmlentities($word));

        $this->expandBase();  // manipulates $this->affixes, collapsing affixes into expanded base

        $phoneString = $this->lookupDictionary($this->affixes['base']);


        // TEMP:  remove the dash from, -le spellings if encountered (not sure it is ever necessary)
        $phoneString = str_replace('-le', 'le', $phoneString);



        // sometimes we just render the word as best we can
        if (empty($phoneString) or in_array($word, $this->memorize_words)) {  // not found in dictionary

            $character = new SingleCharacter();

            $character->spelling = $this->affixes['base'];  // hide
            $character->sound = '';   //hide

            // treat the whole character as an affix
            $character->textcolour = 'black';
            $character->fontSize = $character->affixfontSize;
            $character->lineHeight = $character->affixlineHeight;

            $character->dimmable = $this->dimmable;     // might be set by Lesson, if this is a 'test'

            $character->addToCollectedHTML();

            // $this->addPostfixesToBase($character);   // if there are any

            // ok, we have a word, collect it
            $HTML = $character->collectedHTML();
            return $HTML;
        }


        return ($this->renderPhones($phoneString)); // returns an HTML string

    }



    // renderPhones handles a full word (with syllable breaks, etc)
    public function renderPhones(string $phoneString): string
    {
        $character = new SingleCharacter();
        $this->addPrefixesToBase($character);   // if there are any

        $syllables = explode('/', $phoneString);
        $needSyllableSeparator = false;


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

        $this->addPostfixesToBase($character);


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

    public function expandBase()
    {
        assertTrue(false, 'should never get here, define this in each subclass');
        return '';
    }

    public function addPrefixesToBase(SingleCharacter $character)
    {
        assertTrue(false, 'should never get here, define this in each subclass');
        return '';
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        assertTrue(false, 'should never get here, define this in each subclass');
        return '';
    }

    // take a word with embed morphology eg: com<pute>ate>ion>al from putare (to reckon)
    // maybe for students skip latin and just offer compute>ate>ion>al  ==> computational
    // but still need prefixes for multi<million>aire

    public function parseMorphology(string $word): array
    {
        //  "re<con<struct>ion>s" =>  ['prefix'=>['re'=>CS_NONE,'con'=>CS_NONE],'base'=>'struct','postfix'=>['ion'=>CS_NONE,'s'->CS_NONE]]

        $this->affixes = ['prefix' => [], 'base' => '', 'postfix' => []];   // array of [array,base,array]
        // prefix and suffix have form 'ing'=>CS_NONE
        // danger: works in PHP because array is an unordered map

        $pre = explode('<', $word);  // last element is base>suffix>suffix
        for ($i = 0; $i < count($pre) - 1; $i++) {
            $this->affixes['prefix'][$pre[$i]] = CS_NONE;      // always NONE, can't think of a counter example
        }
        $post = explode('>', $pre[count($pre) - 1]);        // base>suffix>suffix
        $this->affixes['base'] = $post[0];

        // create the structure with default CS_NONE
        for ($i = 1; $i < count($post); $i++) {
            $this->affixes['postfix'][$post[$i]] = CS_NONE;      // default NONE and fix up below
        }

        // now go through and fix the postfix structure with the right connectors
        $runningBase = $this->affixes['base'];
        $mc = new matrixAffix(MM_POSTFIX);

        foreach ($this->affixes['postfix'] as $affix => &$strategy) {      // by reference !!
            $strategy = $mc->connectorStrategy($runningBase, $affix);
            // printNice($mc->connectorStrategyName($strategy), "$word + $runningBase + $key");
            $runningBase .= $affix;
        }
        return $this->affixes;      // only for testing

    }
}





interface wordArtOutputFunctions
{
    function outputInsideGroup(SingleCharacter $character, string $phone);
    function expandBase();   // manipulates $this->affixes
    function addPrefixesToBase(SingleCharacter $character);   // manipulates $this->affixes
}





class wordArtNone extends wordArtAbstract implements wordArtOutputFunctions
{

    // this is the only concrete class that has a render, everyone else goes through abstract->render()
    public function render(string $word): string
    {
        // turn bake>ing into baking
        $this->affixes = $this->parseMorphology($word);
        $this->expandBase();  // manipulates $this->affixes, collapsing affixes into expanded base

        $character = new SingleCharacter();

        $character->spelling = $this->affixes['base'];  // all affixes have been collapsed
        $character->sound = '';   //hide

        // treat the whole character as an affix
        $character->textcolour = 'darkblue';
        $character->fontSize = $character->affixfontSize;
        $character->lineHeight = $character->affixlineHeight;

        $character->dimmable = $this->dimmable;     // might be set by Lesson, if this is a 'test'

        $character->addToCollectedHTML();

        // ok, we have a word, collect it
        return  $character->collectedHTML();
    }



    // create a long word from the affixes
    public function expandBase()
    {
        // consolidate all affixes into the base to create a single word
        $mc = new matrixAffix(MM_POSTFIX);      // doesn't matter

        $reverse = array_reverse($this->affixes['prefix'], true);     // process un<re<con<struct>ed in order con then re then un
        foreach ($reverse as $affix => $strategy) {

            // printNice($this->affixes, "WordArtNone expandBase {$mc->connectorStrategyName($strategy)} '$affix'");
            $this->affixes['base'] = $mc->connectText($this->affixes['base'], $affix, $strategy);
        }
        $this->affixes['prefix'] = [];      // now in base, so erase these

        $mc = new matrixAffix(MM_POSTFIX);
        foreach ($this->affixes['postfix'] as $affix => $strategy) {
            $this->affixes['base'] = $mc->connectText($this->affixes['base'], $affix, $strategy);
        }
        $this->affixes['postfix'] = [];      // now in base, so erase these
    }


    public function addPrefixesToBase(SingleCharacter $character)
    {
        return '';
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        return '';
    }

    public function outputInsideGroup(SingleCharacter $character, string $phone)
    {

        $spelling = $this->adjustedSpelling($phone, false);
        // $spelling = $this->phoneSpelling($phone);

        $character->underline = false;  // always
        $character->syllableSeparators = false;

        $character->spelling = $spelling;
        $character->sound = '';   //hide

        $character->dimmable = $this->dimmable;     // might be set by Lesson, if this is a 'test'

        $character->addToCollectedHTML();
    }
}

class wordArtMinimal extends wordArtAbstract implements wordArtOutputFunctions
{

    public function expandBase()
    {
        return;
    }

    public function addPrefixesToBase(SingleCharacter $character)
    {
        return;
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        return '';
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

        $character->addToCollectedHTML();
    }
}

class wordArtSimple extends wordArtAbstract implements wordArtOutputFunctions
{

    // keep the affixes in full form, just add pluses
    public function expandBase()
    {
        // don't expand the base, add plusses before and after render
    }


    public function addPrefixesToBase(SingleCharacter $character)
    {
        $character->affixBorder = false;
        $character->connectImages = false;

        foreach ($this->affixes['prefix'] as $affix => $strategy) {
            $character->addAffix($this->affixes['base'], $affix, MM_PREFIX, $strategy);  // plus after
        }
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        $character->affixBorder = false;
        $character->connectImages = false;

        foreach ($this->affixes['postfix'] as $affix => $strategy) {
            $character->addAffix($this->affixes['base'], $affix, MM_POSTFIX, $strategy);  // plus in front
        }
    }

    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {

        $spelling = $this->phoneSpelling($phone, false);
        $sound = $this->phoneSound($phone);

        $character->underline = false;
        if ($this->silent_e_follows) {
            $character->underline = true;
        }

        $character->syllableSeparators = false;

        $character->consonantDigraph = (in_array($spelling, $this->consonantDigraphs));

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
        } else {
            $character->textcolour = ($this->is_consonant($spelling)) ? 'darkblue' : 'red';
        }

        $character->addToCollectedHTML();
    }
}





class wordArtColour extends wordArtAbstract implements wordArtOutputFunctions
{

    public function expandBase()
    {
        return;
    }

    public function addPrefixesToBase(SingleCharacter $character)
    {
        return;
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        return '';
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
            $textcolour = 'darkblue';          // consonants get blue
        }

        $character->addToCollectedHTML();
    }
}


class wordArtDecodable extends wordArtAbstract implements wordArtOutputFunctions
{

    public function expandBase()
    {
        return;
    }

    public function addPrefixesToBase(SingleCharacter $character)
    {
        $character->affixBorder = true;
        foreach ($this->affixes['prefix'] as $affix => $strategy) {
            $character->addAffix($this->affixes['base'], $affix, MM_PREFIX, $strategy);  // plus after
        }
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        $character->affixBorder = true;
        foreach ($this->affixes['postfix'] as $affix => $strategy) {
            $character->addAffix($this->affixes['base'], $affix, MM_POSTFIX, $strategy);  // plus in front
        }
    }


    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {
        $spelling = $this->phoneSpelling($phone);
        $sound = $this->phoneSound($phone);

        $character->phonics = !$this->is_consonant($sound); // show the topline phonics?

        $character->syllableSeparators = true;

        $character->consonantDigraph = (in_array($spelling, $this->consonantDigraphs));

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
        $character->addToCollectedHTML();
    }
}

class wordArtAffixed extends wordArtAbstract implements wordArtOutputFunctions
{


    public function expandBase()
    {
        return;
    }

    public function addPrefixesToBase(SingleCharacter $character)
    {
        $character->affixBorder = true;
        $character->connectImages = true;

        foreach ($this->affixes['prefix'] as $affix => $strategy) {
            $character->addAffix($this->affixes['base'], $affix, MM_PREFIX, $strategy);  // plus after
        }
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        $character->affixBorder = true;
        $character->connectImages = true;

        foreach ($this->affixes['postfix'] as $affix => $strategy) {
            $character->addAffix($this->affixes['base'], $affix, MM_POSTFIX, $strategy);  // plus in front
        }
    }


    public function outputInsideGroup(SingleCharacter $character,  string $phone)
    {

        $spelling = $this->phoneSpelling($phone);
        $sound = $this->phoneSound($phone);

        $character->phonics = !$this->is_consonant($sound); // show the topline phonics?

        $character->syllableSeparators = true;

        $character->consonantDigraph = (in_array($spelling, $this->consonantDigraphs));

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
        $character->addToCollectedHTML();
    }
}


class wordArtFull extends wordArtAbstract implements wordArtOutputFunctions
{

    public function expandBase()
    {
        return;
    }

    public function addPrefixesToBase(SingleCharacter $character)
    {
        return;
    }

    public function addPostfixesToBase(SingleCharacter $character)
    {
        return '';
    }


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
        $character->addToCollectedHTML();
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
    public $pronFontSize = '1.3rem';
    public $lineHeight; // assigned in constructor
    public $fontSize;  // assigned in constructor
    public $affixfontSize;  // assigned in constructor
    public $affixlineHeight;  // assigned in constructor
    public $textcolour = 'darkblue';
    public $background = 'white';
    public $underline = false;
    public $border = false;
    public $dimmable = false;
    public $phonics = false;
    public $syllableSeparators = false;
    public $consonantDigraph = false;
    public $affixBorder = true;
    public $connectImages = false;  // default is plus signs, bu$connectImt may want connector images
    public $imgHeight = 50;

    // the output consists of a table with three rows (top, middle, bottom)
    public $bottomHTML = '';
    public $middleHTML = '';
    public $topHTML = '';

    function __construct()
    {

        $this->fontSize = $GLOBALS['mobileDevice'] ? '2.0em' : '5.5em';
        $this->affixfontSize = $GLOBALS['mobileDevice'] ? '1.8em' : '4.1em';
        $this->lineHeight = $GLOBALS['mobileDevice'] ? '0.6em' : '1.2em';
        $this->affixlineHeight = $GLOBALS['mobileDevice'] ? '0.8em' : '1.7em';
    }

    function addToCollectedHTML()
    {

        $digraph = $this->consonantDigraph ? 'border:solid 1px grey;border-radius:20px;' : '';
        $opacity = $this->dimmable ? 'opacity:0.1;' : '';

        $spanClass = 'sp_spell' . ($this->dimmable ? ' dimmable' : '');

        $spanStyle = "line-height:{$this->lineHeight};font-size:{$this->fontSize};color:$this->textcolour;$digraph $opacity;";

        $topborder = ($this->border) ? 'border-top:solid 1px darkblue;' : '';
        $sideborder = ($this->border) ? 'border-right:solid 1px darkblue;border-left:solid 1px darkblue;' : '';
        $bottomborder = ($this->border) ? 'border-bottom:solid 1px darkblue;' : '';

        // top row (not used yet)
        $this->topHTML .= "<td  style='padding:0;$topborder;'></td>\n";

        // middle row
        $underline = ($this->underline) ? 'border-bottom:solid 4px red;' : '';  // a_e underline?

        $this->middleHTML .= "<td style='text-align:center;padding:{$this->vSpacing}px 0 {$this->vSpacing}px 0;background-color:{$this->background};$sideborder $underline'>";
        $this->middleHTML .= "    <span class='$spanClass' style='$spanStyle'>";
        $this->middleHTML .=          $this->spelling;
        $this->middleHTML .= "    </span>";
        $this->middleHTML .= "</td>";

        // bottom row
        $this->bottomHTML .= "<td style='text-align:center;padding:10px 0 0 0;;background-color:{$this->background};$bottomborder;$sideborder'>";

        if ($this->phonics) {  // do we show the phonics row?
            if (empty($this->sound)) {
                $this->bottomHTML .= "    <span class='sp_pron'  font-size:{$this->pronFontSize}'>&nbsp</span>";
            } else {
                $view = new ViewComponents();
                $this->bottomHTML .= $view->sound($this->sound);
            }
        } else {
            $this->bottomHTML .= "    <span class='sp_pron' font-size:{$this->pronFontSize}'>&nbsp</span>";
        }
        $this->bottomHTML .= '</td>';
    }

    function addAffix(string $base, string $text, int $MM, $strategy)
    {
        $basicStyle = "padding:0;font-size:{$this->affixfontSize};line-height:{$this->affixlineHeight};";
        $border = "border:solid 1px grey;border-radius:15px;";

        $tdStyle = "<td style='text-align:center;line-height:{$this->affixlineHeight};padding:{$this->vSpacing}px 0 {$this->vSpacing}px 0;'";
        $noBorderStyle = "style='$basicStyle'";

        // no borders
        if ($this->affixBorder) {
            $borderStyle = "style='$basicStyle $border'";
        } else {
            $borderStyle = "style='$basicStyle'";
        }

        // if showing affixImage then need last letter of base to calculate
        if ($this->connectImages) {
            $png = $this->connectImage($base, $strategy);

            $plusBefore = ($MM == MM_POSTFIX) ? $png : '';
            $plusAfter = ($MM == MM_PREFIX) ? $png : '';
        } else {

            $plusBefore = ($MM == MM_POSTFIX) ? "+" : '';
            $plusAfter = ($MM == MM_PREFIX) ? "+" : '';
        }

        $this->topHTML .= "<td style='padding:0;border:none;'></td>";

        $this->middleHTML .= "<td $tdStyle>";
        $this->middleHTML .= "  <span $noBorderStyle>$plusBefore</span>";
        $this->middleHTML .= "  <span class='sp_spell' $borderStyle>$text</span>";
        $this->middleHTML .= "  <span $noBorderStyle>$plusAfter</span>";
        $this->middleHTML .= "</td>";

        $this->bottomHTML .= "<td style='padding:0;;border:none;'></td>";
    }

    function connectImage(string $base, int $strategy): string
    {
        // get the right PNG for this strategy

        if ($strategy !== CS_DOUBLE) {
            switch ($strategy) {       // this are the operations
                case CS_NONE:
                    $png = 'sep-none.PNG';
                    break;
                case CS_DROP_E:
                    $png = 'sep-drop-e.PNG';
                    break;
                case CS_IE_Y:
                    $png = 'sep-ie-y.PNG';
                    break;
                case CS_Y_I:
                    $png = 'sep-y-i.PNG';
                    break;
                case CS_ADD_K:
                    $png = 'sep-add-k.PNG';
                    break;
                case CS_DROP_LE:
                    $png = 'sep-drop-le.PNG';
                    break;
                case CS_MB_MM:
                    $png = 'sep-mb.PNG';
                    break;
                default:
                    assertTrue(false, "Unexpected value for connectImage($strategy)");
            }
        } else {

            // it is CS_DOUBLE, we look at the root to see what to double
            $c = substr($base, -1);
            $png = 'sep-' . $c . '-' . $c . $c . '.PNG';   //'sep-d-dd.PNG'

        }
        $image = "<img src='pix/$png' height='$this->imgHeight' style='border:solid 5px white;'  />";
        return $image;
    }


    function addSyllableSeparator()
    {
        $spanClass = 'sp_spell' . ($this->dimmable ? ' dimmable' : '');

        // no borders
        if ($this->syllableSeparators) {
            $this->topHTML .= "<td style='padding:0;border:none;'></td>";
            $this->middleHTML .= "<td class='$spanClass' style='padding:10px 0 0 0;font-size:3rem;'>&nbsp;&sol;&nbsp;</td>";
            $this->bottomHTML .= "<td style='padding:0;;border:none;'></td>";
        }
    }

    // public function addPrefixesToBase(SingleCharacter $character)
    // {
    //     assertTrue(!empty($prefix));

    //     $this->topHTML .= "<td></td>";
    //     $this->middleHTML .= "<td>$prefix&nbsp;+&nbsp;</td>";
    //     $this->bottomHTML .= "<td></td>";
    // }

    // function addPostfix(string $postfix)
    // {
    //     assertTrue(!empty($prefix));
    //     $style = $this->affixBorder ? "style='border:solid 1px darkblue;border-radius:15px;'" : '';

    //     $this->topHTML .= "<td></td>";
    //     $this->middleHTML .= "<td style>&nbsp;+&nbsp;$prefix</td>";
    //     $this->bottomHTML .= "<td></td>";
    // }

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
