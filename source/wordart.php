<?php  namespace Blending;


// // this is the global list of words that must be memorized
// function memorize_words()
// {
//     return ('I,you,our,the,was,so,to,no,do,of,too,one,two,he,she,be,are,said,their');
// }
// function memorize_words_count()
// {
//     return (count(explode(',', memorize_words())));
// }

class wordArtAbstract
{

    public $red = '#D21f3c';    // red is too jarring, this is a softer crimson

    public $showPhones = true;          // only works in debug

    public $bigrams = true; // process bigrams like 'pr'

    public $groupEVowels = true;
    public $groupBigrams = true;

    public $phoneString = '';

    public $consonantDigraphs = ['th', 'sh', 'kn', 'ch', 'ph', 'wr', 'ck', 'tch', 'ng', 'wh'];

    // public $consonantDigraphs = ['th', 'sh', 'ch', 'kn', 'ng', 'nk', 'igh', 'ough', 'se', 'ge', 've', 'ce', 'the', 'ph', 'wr', 'ck', 'tch'];

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

    public $punchList = [];     // punctuation to fix on words

    // this is for punctuation and surrounding quotes - we turn "Stop!" into stop
    // we have to remember the first and last stuff we strip off.
    public $first = '';
    public $last = '';

    // these may be overridden and must be copied into  SingleCharacter
    public $dimmable = false;
    public $useSmallerFont = false;

    // this is the global list of words that must be memorized
    // capital 'I' causes trouble sometimes
    public $memorize_words = ['you', 'our', 'the', 'is', 'was', 'so', 'to', 'no', 'do', 'of', 'too', 'one', 'two', 'he', 'she', 'be', 'are', 'said', 'their', 'was', 'were', 'what', 'have'];

    // was, of, the, to, you,
    // I, is, said, that, he,
    // his, she, her, for, are,
    // as, they, we, were, be,
    // this, have, or, one, by,
    // what, with, then, do, there



    public $functionWords = ",a,the,of,and,a,to,in,is,you,that,it,he,was,for,on,are,as,with,his,they,i,at,be,this,have,from,or,one,had,by,
               ,but,not,what,all,were,we,when,your,can,said,there,use,an,each,which,she,do,how,their,if,will,up,other,about,out,
               ,many,then,them,these,so,some,her,would,like,him,into,time,has,look,two,more,go,see,no,way,could,
               ,my,than,first,been,who,its,now,long,down,did,get,may,
               ,over,new,take,only,little,place,me,back,most,very,after,thing,our,just,
               ,name,good,say,great,where,through,much,before,too,any,same,
               ,want,also,around,three,small,put,end,does,another,well,large,must,
               ,big,even,such,because,here,why,ask,went,need,different,us,try,
               ,again,off,away,still,should,high,every,near,between,below,last,under,saw,few,while,might,
               ,something,seem,next,always,those,both,got,often,until,once,without,later,enough,far,really,almost,let,above,sometimes,
               ,soon,being,it's,knew,since,ever,usually,didn't,become,across,during,however,several,I'll,less,behind,
               ,yes,yet,full,am,among,cannot,";





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
    public function stripPunctuation(string $word): string
    {
        // might have a period or a quote at start or end...

        $this->punchList = [];

        $this->first = '';
        $this->last = '';
        // add these first

        // italics and bold get removed here, never restored
        if (substr($word, 0, 2) == '**') {
            $this->punchList['bold'] = 'bold';      // must do bold first !!
            $word = substr($word, 2);
        }
        if (substr($word, 0, 1) == '*') {
            $this->punchList['italic'] = 'italic';
            $word = substr($word, 1);
        }




        // check for punctuation at end
        if (substr($word, -1) == '.') {   // special case for period, because it is use in phonestrings
            $this->punchList['period'] = "period";
            $word = substr($word, 0, strlen($word) - 1);
        }

        // check for punctuation at end
        if (ctype_punct(substr($word, -1))) {
            $punct = substr($word, -1);
            // $phone = $punct; //".[$punct^$punct]";
            $this->punchList[$punct] = "addEnd3";       // trap!"  quote removed first, must be restored last
            $word = substr($word, 0, strlen($word) - 1);
        }

        // check for punctuation at end again
        if (substr($word, -1) == '.') {   // special case for period, because it is use in phonestrings
            $this->punchList['period'] = "period";
            $word = substr($word, 0, strlen($word) - 1);
        }

        // check for punctuation at end second time  ("Ants",)
        if (ctype_punct(substr($word, -1))) {
            $punct = substr($word, -1);
            $this->punchList[$punct] = "addEnd3";
            $word = substr($word, 0, strlen($word) - 1);
        }

        // check for punctuation at start
        if (ctype_punct(substr($word, 0, 1))) {
            $punct = substr($word, 0, 1);
            $phone = "[$punct^$punct].";
            $this->punchList[$phone] = "addStart3";
            $word = substr($word, 1);
        }

        if (ctype_punct(substr($word, 0, 1)) or substr($word, 0, 1) == '”') {
            $punct = substr($word, 0, 1);
            $phone = "[$punct^$punct].";
            $this->punchList[$phone] = "addStart";
            $word = substr($word, 1);
        }


        // PUT THE LONGEST TESTS FIRST !!

        if (substr($word, -3) == "'ll") {   // i'll, we'll
            $word = substr($word, 0, strlen($word) - 3);
            $this->punchList[".['ll^l]"] = "addEnd";
        }
        if (substr($word, -3) == "n't") {   // doesn't, isn't
            $word = substr($word, 0, strlen($word) - 3);
            $this->punchList[".[n't^nt]"] = "addEnd";
        }

        if (substr($word, -2) == "'s") {   // Scott's
            $word = substr($word, 0, strlen($word) - 2);
            $this->punchList[".['s^s]"] = "addEnd";
        }

        // /////////////////////////

        // if (substr($word, -2) == "'s" or substr($word, -2) == "'t" or substr($word, -2) == "'d") {   // can't  bill's
        //     $this->last = '[' . substr($word, -2) . ";*]"; // want the 's in consonant colour
        //     $word = substr($word, 0, strlen($word) - 2);
        // }
        // // special case for ants," (comma quote, or period quote, ...)
        // if (substr($word, -2) == ",\"" or substr($word, -2) == ".\"" or substr($word, -2) == "!\"" or substr($word, -2) == "?\"" or substr($word, -2) == "-\"") {
        //     $this->last = '[' . substr($word, -2) . ";*]"; // want the 's in uncoded colour
        //     $word = substr($word, 0, strlen($word) - 2);
        // }

        // if (ctype_punct(substr($word, 0, 2))) {
        //     // convert the punctuation to a phone block
        //     $this->first = '[' . substr($word, 0, 2) . ';*]'; // something like [(";*]
        //     $word = substr($word, 2);
        // }

        // if (ctype_punct(substr($word, 0, 1))) {
        //     // convert the punctuation to a phone block
        //     $this->first = '[' . substr($word, 0, 1) . ';*]'; // something like [";*]
        //     $word = substr($word, 1);
        // }

        // if (ctype_punct(substr($word, -3))) {       // might have !",
        //     $this->last = '[' . substr($word, -3) . ';*]';
        //     $word = substr($word, 0, strlen($word) - 3);
        // }

        // if (ctype_punct(substr($word, -2))) {       // might have ",
        //     $this->last = '[' . substr($word, -2) . ';*]';
        //     $word = substr($word, 0, strlen($word) - 2);
        // }



        if (substr($word, 0, 1) == '"') {     // starting quote
            $word = substr($word, 1);
            $this->punchList["[&ldquo;^]."] = "addStart";
        }


        if (ctype_upper(substr($word, 0, 1))) {
            $word = strtolower(substr($word, 0, 1)) . substr($word, 1);
            $this->punchList["capFirst"] = "capFirst";
        }


        // printNice('xxx', "Extracted Punctuation   '$this->first'   '$this->last'");

        return ($word);
    }

    // this version for most wordArt except memorize words
    function addBackPunctuation(string $phoneString): string
    {
        // printNice($this->punchList, $phoneString);
        foreach ([1, 2, 3] as $phase) {  // rebuild in several passes
            foreach ($this->punchList as $parm => $punc) {
                switch ($punc) {
                    case "addEnd":
                        if ($phase == 2)
                            $phoneString .= $parm;
                        break;
                    case "addEnd3":
                        // if ($phase == 3)
                        //     $phoneString .= $parm;
                        break;
                    case "period":
                        // if ($phase == 1)
                        //     $phoneString .= '.[&period;^]';
                        break;
                    case "capFirst":
                        if ($phase == 1)
                            $phoneString = '[' . strtoupper(substr($phoneString, 1, 1)) . substr($phoneString, 2);
                        break;
                    case "addStart":
                        if ($phase == 2)
                            $phoneString = $parm . $phoneString;   // stuff in front&per
                        break;
                    case "addStart3":
                        if ($phase == 3)
                            $phoneString = $parm . $phoneString;   // stuff in front&per
                        break;

                    case 'bold':
                    case 'italic':
                        break;      // do nothing

                    default:
                        assertTrue(false, "did not expect punchlist element '$punc'");
                }
                // printNice($phoneString);
            }
        }

        return $phoneString;
    }

    // this version of addbackPunction() for memorize words
    function addBackPunctuation2(string $word): string
    {
        // printNice($this->punchList, $phoneString);
        foreach ([1, 2, 3] as $phase) {  // rebuild in several passes
            foreach ($this->punchList as $parm => $punc) {
                switch ($punc) {
                    case "addEnd":
                        if ($phase == 2)
                            $word .= get_string_between($parm, '[', '^');
                        break;
                    case "addEnd3":
                        // if ($phase == 3)
                        //     $word .= get_string_between($parm, '[', '^');
                        break;
                    case "period":
                        // if ($phase == 1)
                        //     $word .= '&period;';
                        break;
                    case "capFirst":
                        if ($phase == 1)
                            $word = strtoupper(substr($word, 0, 1)) . substr($word, 1);
                        break;
                    case "addStart":
                        // if ($phase == 2)
                        //     $word = get_string_between($parm, '[', '^') . $word;
                        break;
                    case "addStart3":
                        // if ($phase == 3)
                        //     $word = get_string_between($parm, '[', '^') . $word;
                        break;

                    case 'bold':
                    case 'italic':
                        break;      // do nothing

                    default:
                        assertTrue(false, "did not expect punchlist element '$punc'");
                }
                // printNice($phoneString);
            }
        }

        return $word;
    }

    // this version of addbackPunction() for memorize words
    function addBackPunctuation3(): string
    {
        $phoneString = '';
        foreach ($this->punchList as $parm => $punc) {
            switch ($punc) {
                case "addEnd3":
                    $phoneString .= $parm;
                    break;
                case "period":
                    $phoneString .= '.';
                    break;
                default:
                    // assertTrue(false, "did not expect punchlist element '$punc'");
            }
        }
        return $phoneString;
    }

    // this version of addbackPunction() for memorize words
    function addBackPunctuation4(): string
    {
        $phoneString = '';
        foreach ($this->punchList as $parm => $punc) {
            switch ($punc) {
                case "addStart":
                    $phoneString .= $parm;
                    break;
                case "addStart3":
                    $phoneString .= $parm;
                default:
                    // assertTrue(false, "did not expect punchlist element '$punc'");
            }
        }
        return $phoneString;
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
    // can/not      // override pronunciation syllable

    // this is render EXCEPT for WordArtNone() which has its own
    public function render(string $word): string
    { // single word render

        $stripword = convertCurlyQuotes($word);
        $stripword = $this->stripPunctuation($stripword);   // but remember them

        $this->affixes = $this->parseMorphology($stripword);
        // printNice($this->affixes, htmlentities($word));

        $this->expandBase();  // manipulates $this->affixes, collapsing affixes into expanded base

        $phoneString = $this->phonesWithSuggestedSyllableBreaks($this->affixes['base']);   // can/not


        // // TEMP:  remove the dash from, -le spellings if encountered (not sure it is ever necessary)
        // $phoneString = str_replace('-le', 'le', $phoneString);



        // sometimes we just render the word as best we can
        if (empty($phoneString) or in_array(strtolower($stripword), $this->memorize_words) or $stripword == 'I') {  // not found in dictionary

            // simple word, we know there are no prefixes or postfixes

            $character = new SingleCharacter();

            $character->dimmable = $this->dimmable;     // might be set by Lesson'
            $character->useSmallerFont = $this->useSmallerFont;

            $character->boldface = in_array('bold', $this->punchList);
            $character->italic = in_array('italic', $this->punchList);

            // punction marks should be outside lettering
            $punctuation = $character->phoneSpelling($this->addBackPunctuation4());
            if (!empty($punctuation)) {
                $character->textcolour = 'darkblue';
                $character->consonantDigraph = false;
                $character->addSpecialCharacter($punctuation);
            }

            if (in_array(strtolower($stripword), $this->memorize_words, true) or $stripword == 'I') {
                $character->memorizeWord = true;
            }

            $wordstring =  $this->addBackPunctuation2($this->affixes['base']);

            $character->spelling = $wordstring;
            $character->sound = '';   //hide

            // special case, the word I is always caps, and given some extra space
            if ($word == 'i' or $word == 'I')
                $character->spelling = "&nbsp;I&nbsp;";   // otherwise ends up lowercase in some contexts

            // treat the whole character as an affix
            $character->textcolour = 'black';


            $character->addToCollectedHTML();  // add the sound and spelling

            $character->boldface = false;
            $character->italic = false;

            // punction marks should be outside lettering
            $punctuation = $this->addBackPunctuation3();
            if (!empty($punctuation)) {
                // $character = new SingleCharacter();
                $character->textcolour = 'darkblue';
                $character->consonantDigraph = false;

                $character->addSpecialCharacter($punctuation);
                // $HTML .= $character->collectedHTML();
            }
            $HTML = $character->collectedHTML();
        } else {
            // complex, use the renderer
            $phoneString = $this->addBackPunctuation($phoneString);
            $phoneString .= '[' . $this->addBackPunctuation3() . '^]';
            $HTML = $this->renderPhones($phoneString); // returns an HTML string
        }



        return $HTML;
    }



    // renderPhones handles a full word (with syllable breaks, etc)
    public function renderPhones(string $phoneString): string
    {
        $character = new SingleCharacter();
        $this->addPrefixesToBase($character);   // if there are any

        $syllables = explode('/', $phoneString);
        $needSyllableSeparator = false;

        $character->boldface = in_array('bold', $this->punchList);
        $character->italic = in_array('italic', $this->punchList);

        foreach ($syllables as $syllable) {

            $syllableSeparator = "&nbsp;&sol;&nbsp;";
            if ($needSyllableSeparator) {
                $character->consonantDigraph = false;       // might still be set
                $character->addSpecialCharacter($syllableSeparator);
                $needSyllableSeparator = false;
            }

            $aPhones = explode('.', $syllable); // explode into phones

            $this->silent_e_follows = false;
            for ($i = 0; $i < count($aPhones); $i++) {   // hard to look ahead with foreach()


                // // collapse bigrams - collapse consonant with a . separator followed by another consonant, combine them
                // if (
                //     $this->is_consonant($this->phoneSound($aPhones[$i]))
                //     and $i < count($aPhones) - 1   // there remains a phone
                //     and $this->is_consonant($this->phoneSound($aPhones[$i + 1]))
                // ) {
                //     // printNice($aPhones, "collapsing {$aPhones[$i]} and {$aPhones[$i + 1]}");

                //     // but ONLY if the spelling == sound (not for f ph)
                //     //and $this->phoneSound($aPhones[$i+1]) == $this->phoneSpelling($aPhones[$i+1]))
                //     // need to merge [p;p].[r;r].  => [pr;pr].
                //     $newPhone = '[' . $this->phoneSpelling($aPhones[$i]) . $this->phoneSpelling($aPhones[$i + 1]) . ';' .
                //         $this->phoneSound($aPhones[$i]) . ' ' . $this->phoneSound($aPhones[$i + 1]) . ']';

                //     $aPhones[$i + 1] = $newPhone;
                //     $i = $i + 1;  // skip this character


                //     // check the next one too, in case we have a trigram like 'str'

                // }


                $spelling = $this->phoneSpelling($aPhones[$i]);
                $sound = $this->phoneSound($aPhones[$i]);

                // patch the silent-e
                if (in_array($spelling, ['a_e', 'e_e', 'i_e', 'o_e', 'u_e'])) {

                    $spelling = substr($spelling, 0, 1);
                    $aPhones[$i] = "[$spelling^$sound]"; //substitute  a if was a_e

                    $this->silent_e_follows = true;  // but is the LAST character in the syllable
                    $character->underline = true;
                }

                // create the HTML for a single phone
                $this->outputInsideGroup($character, $aPhones[$i]);
            }
            // add the silent_e if we must
            if ($this->silent_e_follows) {
                $this->outputInsideGroup($character, '[e^]');   // add an extra phone

                $this->silent_e_follows = false;
                $character->underline = false;
            }


            $needSyllableSeparator = true;
        }

        $this->addPostfixesToBase($character);

        // punction marks should be outside lettering
        $punctuation = $this->addBackPunctuation3();
        if (!empty($punctuation)) {
            $character->textcolour = 'darkblue';
            $character->consonantDigraph = false;
            $character->addSpecialCharacter($punctuation);
            // $HTML .= $character->collectedHTML();
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
        return (get_string_between($phone, '^', ']'));
    }
    public function phoneSpelling($phone): string
    {
        return (get_string_between($phone, '[', '^'));
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
        $ret = false; // default

        if (empty($sound)) {
            return (false);
        }
        // protect against...

        // sounds like es;z
        if (str_contains(',es,', strtolower($sound))) {
            $ret = true;
        }

        // quote marks and stuff are also 'consonants', eg i'll
        if (strpos('.,\',!,?,b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,.t,v,w,x,y,z', substr(strtolower($sound), 0, 1))) {
            $ret = true;
        }
        if (strpos(',zh,kw,ks,ng,th,dh,sh,ch', substr(strtolower($sound), 0, 1))) {
            $ret = true;
        }

        if ($sound == '"' or $sound == "'" or $sound == '.' or $sound == ',') {
            $ret = true;
        }
        // treat quotes as consoants

        // printNice("is_consonant($sound) is ".($ret?'true':'false'));
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


    function phonesWithSuggestedSyllableBreaks(string $word): string
    {

        if (!str_contains($word, '/')) {        // no suggested breaks?  almost always
            $phoneString = $this->lookupDictionary($word);
            $phoneString = str_replace('!', '', $phoneString);     // don't need last-syllable emphasis mark in wordart
            return $phoneString;
        }

        $phoneString = $this->lookupDictionary(str_replace('/', '', $word));
        if (empty($phoneString)) // not found in dictionary
            return $phoneString;

        $phoneString = str_replace('!', '', $phoneString);     // don't need last-syllable emphasis mark in wordart

        // printNice($phoneString, "phonesWithSuggestedSyllableBreaks($word)");

        $pString = '';
        $aLetters = str_split($word);   // can/not
        $aLetterPtr = 0;

        $syllable = str_replace('/', '.', $phoneString); //explode('/', $phoneString);
        $broken = false;        // if our algorithm gets broken...

        // foreach ($aSyllables as $syllable) {
        $aPhones = explode('.', $syllable);

        $i = 0;
        for ($i = 0; $i < count($aPhones); $i++) { // ($aPhones as $phone) {


            // printNice($syllable, "spelling='$spelling' looking at {$aLetters[$aLetterPtr]} ");




            // TODO:  so far only works with 2- letter spellings  must fix for 3- letter phones

            $spelling = $this->phoneSpelling($aPhones[$i]);
            // two letters in phone, want the break between them
            if (strlen($spelling) == 2 and $aLetters[$aLetterPtr + 1] == '/') {

                // special handling
                $s1 = substr($spelling, 0, 1);
                $s2 = substr($spelling, 1, 1);

                $pString .= (empty($pString) ? '' : '.') . "[$s1^$s1]/[$s2^$s2]";
                $aLetterPtr  += 3; //strlen($spelling);
                continue;   // ok, on to the next phone
            }

            if ($aLetters[$aLetterPtr] == '/') {
                $pString .= '/' . $aPhones[$i];   // don't expect / at front of word
                $aLetterPtr += 2;   // slash plus letter in phone
            } else {
                $pString .= (empty($pString) ? '' : '.') . $aPhones[$i];
                $aLetterPtr += 1;   //  move by the letter in the phone
            }
        }
        return $pString;
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

        $stripword = convertCurlyQuotes($word);   // convert html quotes to ordinary quotes
        $stripword = str_replace('/', '', $stripword);    // remove suggested punctuation breaks
        $stripword = $this->stripPunctuation($stripword);   // but remember them

        // turn bake>ing into baking
        $this->affixes = $this->parseMorphology($stripword);
        $this->expandBase();  // manipulates $this->affixes, collapsing affixes into expanded base

        $character = new SingleCharacter();

        $addBackWord =  $character->phoneSpelling($this->addBackPunctuation4());
        $addBackWord .= $this->addBackPunctuation2($this->affixes['base']);   // all affixes have been collapsed
        $addBackWord .= $this->addBackPunctuation3();

        $character->spelling = $addBackWord;
        $character->sound = '';   //hide

        $character->boldface = in_array('bold', $this->punchList);
        $character->italic = in_array('italic', $this->punchList);

        // treat the whole character as an affix
        $character->textcolour = 'darkblue';

        $character->dimmable = $this->dimmable;     // might be set by Lesson, if this is a 'test'
        $character->useSmallerFont = $this->useSmallerFont;     // might be set by Lesson, if this is a 'test'

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
        $character->useSmallerFont = $this->useSmallerFont;

        $character->addToCollectedHTML();
    }
}







class wordArtFunction extends wordArtAbstract implements wordArtOutputFunctions
{

    // this is the only concrete class that has a render, everyone else goes through abstract->render()
    public function render(string $word): string
    {

        $stripword = convertCurlyQuotes($word);   // convert html quotes to ordinary quotes
        $stripword = str_replace('/', '', $stripword);    // remove suggested punctuation breaks
        $stripword = $this->stripPunctuation($stripword);   // but remember them

        // turn bake>ing into baking
        $this->affixes = $this->parseMorphology($stripword);
        $this->expandBase();  // manipulates $this->affixes, collapsing affixes into expanded base

        $character = new SingleCharacter();

        $character->dimmable = $this->dimmable;     // might be set by Lesson, if this is a 'test'
        $character->useSmallerFont = $this->useSmallerFont;     // might be set by Lesson, if this is a 'test'

        // punction marks should be outside lettering
        $punctuation = $character->phoneSpelling($this->addBackPunctuation4());
        if (!empty($punctuation)) {
            $character->textcolour = 'darkblue';
            $character->addSpecialCharacter($punctuation);
        }

        $addBackWord = $this->addBackPunctuation2($this->affixes['base']);   // all affixes have been collapsed

        $character->spelling = $addBackWord;
        $character->sound = '';   //hide

        $character->textcolour = 'darkblue';

        if (str_contains($this->functionWords, $stripword) or $stripword == 'I') {
            $character->memorizeWord = true;
        }

        $character->addToCollectedHTML();

        // punction marks should be outside lettering
        $punctuation = $this->addBackPunctuation3();
        if (!empty($punctuation)) {
            $character->textcolour = 'darkblue';
            $character->addSpecialCharacter($punctuation);
        }

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
        $character->useSmallerFont = $this->useSmallerFont;

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
        $character->affixBorder = true;
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

        $character->syllableSeparators = true;

        $character->consonantDigraph = (in_array(strtolower($spelling), $this->consonantDigraphs));

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        $consonant = $this->is_consonant($sound);

        // vowels get red, consonants get blue, silent-E gets green
        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
        } elseif ($sound == 'aw') {
            $character->textcolour = 'magenta';
        } elseif ($spelling == 'ee') {
            $character->textcolour = 'green';
        } else {
            $character->textcolour = ($consonant) ? 'darkblue' : $this->red;
        }

        $character->sound = '';   // never show sounds


        $character->dimmable = $this->dimmable;     // might be set by Lesson'
        $character->useSmallerFont = $this->useSmallerFont;

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



        $textcolour = $this->red; // default colour for vowels
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

        $character->phonics = !$this->is_consonant($sound); // show the phonics?

        $character->syllableSeparators = true;

        $character->consonantDigraph = (in_array(strtolower($spelling), $this->consonantDigraphs));

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        if ($this->silent_e_follows) {
            $character->underline = true;
        }


        $consonant = $this->is_consonant($sound);

        // vowels get red, consonants get blue, silent-E gets green
        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
        } elseif ($sound == 'aw') {
            $character->textcolour = 'magenta';
        } elseif ($spelling == 'ee') {
            $character->textcolour = 'green';
        } else {
            $character->textcolour = ($consonant) ? 'darkblue' : $this->red;
        }

        // final fix - if the sound is identical to the spelling (ie: basic spelling) don't show it
        $character->sound = $this->phoneSound($phone);
        if ($consonant) {   // only show vowels
            $character->sound = '';
        }

        $character->dimmable = $this->dimmable;     // might be set by Lesson'
        $character->useSmallerFont = $this->useSmallerFont;

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

        $character->consonantDigraph = (in_array(strtolower($spelling), $this->consonantDigraphs));

        $character->spelling = $this->adjustedSpelling($phone, false);
        $character->sound = '';   //hide

        if ($this->silent_e_follows) {
            $character->underline = true;
        }

        $consonant = $this->is_consonant($sound);

        // vowels get red, consonants get blue, silent-E gets green
        if (empty($sound)) {
            $character->textcolour = 'green';   // silent E
        } elseif ($sound == 'aw') {
            $character->textcolour = 'magenta';
        } elseif ($spelling == 'ee') {
            $character->textcolour = 'green';
        } else {
            $character->textcolour = ($consonant) ? 'darkblue' : $this->red;
        }

        // final fix - if the sound is identical to the spelling (ie: basic spelling) don't show it
        $character->sound = $this->phoneSound($phone);
        if ($sound == $spelling) {
            $character->sound = '';
        }

        $character->dimmable = $this->dimmable;     // might be set by Lesson'
        $character->useSmallerFont = $this->useSmallerFont;


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

        // don't need ovals in WordArtFull
        // $character->consonantDigraph = (in_array(strtolower($spelling), $this->consonantDigraphs));


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
                $character->textcolour = $this->red;
                $character->background = '#ffe0ff';
            }
            $character->textcolour = ($this->is_consonant($spelling)) ? 'darkblue' : $this->red;
        }

        // final fix - if the sound is identical to the spelling (ie: basic spelling) don't show it
        $character->sound = $this->phoneSound($phone);
        if ($sound == $spelling) {
            $character->sound = '';
        }

        $character->dimmable = $this->dimmable;     // might be set by Lesson'
        $character->useSmallerFont = $this->useSmallerFont;

        $character->addToCollectedHTML();
    }
}




// this accepts a single phone [a;ah] and returns a <td>something</td>
class SingleCharacter
{

    // these are for rendering a single character
    public $spelling = '';
    public $sound = '';
    public $pronFontSize; // assigned in constructor
    public $pronLineHeight; // assigned in constructor
    public $lineHeight; // assigned in constructor
    public $fontSize;  // assigned in constructor
    public $affixfontSize;  // assigned in constructor
    public $borderRadius;
    public $textcolour = 'darkblue';
    public $background = 'white';
    public $underline = false;
    public $border = false;
    public $dimmable = false;
    public $useSmallerFont = false;
    public $phonics = false;
    public $syllableSeparators = false;
    public $consonantDigraph = false;
    public $affixBorder = true;
    public $connectImages = false;  // default is plus signs, bu$connectImt may want connector images
    public $imgHeight = 50;
    public $memorizeWord = false;
    public $boldface = false;
    public $italic = false;

    // the output consists of a table with three rows (top, middle, bottom)
    public $bottomHTML = '';
    public $middleHTML = '';
    public $topHTML = '';


    function setFontSizes()
    {
        if ($this->useSmallerFont) {
            // smaller font, mostly for decodable texts
            $this->fontSize = $GLOBALS['mobileDevice'] ? '1.3em' : '3.3em';
            $this->affixfontSize = $GLOBALS['mobileDevice'] ? '1.1em' : '3.0em';
        } else {
            // larger font, mostly for word lists
            $this->fontSize = $GLOBALS['mobileDevice'] ? '1.8em' : '5.5em';
            $this->affixfontSize = $GLOBALS['mobileDevice'] ? '1.6em' : '4.1em';
        }

        $this->lineHeight = $GLOBALS['mobileDevice'] ? '1.2em' : '1.0em';
        $this->pronFontSize =  $GLOBALS['mobileDevice'] ? '0.4em' : '1.3em';
        $this->pronLineHeight = $GLOBALS['mobileDevice'] ? '0.3em' : '1.1em';
        $this->borderRadius = $GLOBALS['mobileDevice'] ? '5px' : '20px';
    }

    function addToCollectedHTML()
    {
        $this->setFontSizes();

        $digraph = $this->consonantDigraph ? "border:solid 1px grey;border-radius:{$this->borderRadius};" : '';
        $digraph = $this->memorizeWord ? "border:solid 1px red;border-radius:{$this->borderRadius};background-color:#e0ffff;" : $digraph;

        $opacity = $this->dimmable ? 'opacity:0.1;' : '';

        $spanClass = 'sp_spell' . ($this->dimmable ? ' dimmable' : '');

        // set boldface and italic
        $fontVariant = '';
        $fontVariant .= $this->boldface ? 'background-color:#FFFCB0;' : '';   // not really bold, just highlighted
        $fontVariant .= $this->italic ? 'font-style: italic;' : '';

        $spanStyle = "line-height:{$this->lineHeight};font-size:{$this->fontSize};color:$this->textcolour;$digraph;$fontVariant;$opacity;";

        // only for decodables
        $topborder = ($this->border) ? 'border-top:solid 1px darkblue;' : '';
        $sideborder = ($this->border) ? 'border-right:solid 1px darkblue;border-left:solid 1px darkblue;' : '';
        $bottomborder = ($this->border) ? 'border-bottom:solid 1px darkblue;' : '';

        // top row (not used yet)
        $this->topHTML .= "<td  style='padding:0;$topborder;'></td>\n";

        // middle row
        $underline = ($this->underline) ? "border-bottom:solid 4px red;" : "border-bottom:solid 4px white;";  // a_e underline?




        $this->middleHTML .= "<td style='text-align:center;background-color:{$this->background};$sideborder $underline'>";
        $this->middleHTML .= "    <span class='$spanClass' style='$spanStyle'>";
        $this->middleHTML .=        $this->spelling;
        $this->middleHTML .= "    </span>";
        $this->middleHTML .= "</td>";

        // bottom row
        $this->bottomHTML .= "<td style='text-align:center;padding:0;background-color:{$this->background};$bottomborder;$sideborder'>";
        $this->bottomHTML .= "    <span class='sp_pron'  style='line-height:{$this->pronLineHeight};font-size:{$this->pronFontSize}'>";

        if ($this->phonics) {  // do we show the phonics row?
            if (!empty($this->sound)) {
                $view = new ViewComponents();
                $this->bottomHTML .= $view->sound($this->sound);
            }
        } else {
            $this->bottomHTML .= "&nbsp";
        }

        $this->bottomHTML .= "    </span>";
        $this->bottomHTML .= '</td>';
    }

    function addAffix(string $base, string $text, int $MM, $strategy)
    {
        $this->setFontSizes();

        $basicStyle = "padding:0;font-size:{$this->affixfontSize};line-height:{$this->lineHeight};";
        $border = "border:solid 1px grey;border-radius:15px;";
        $opacity = $this->dimmable ? 'opacity:0.1;' : '';

        $tdStyle = "text-align:center;line-height:{$this->lineHeight};";
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

        $this->middleHTML .= "<td style='$tdStyle'>";
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


    function addSpecialCharacter(string $specialChars)
    {
        $this->setFontSizes();

        $opacity = $this->dimmable ? 'opacity:0.1;' : '';
        $digraph = $this->consonantDigraph ? "border:solid 1px grey;border-radius:{$this->borderRadius};" : '';
        $tdStyle = "text-align:center;line-height:{$this->lineHeight};";

        // set boldface and italic
        $fontVariant = '';
        $fontVariant .= $this->boldface ? 'background-color:#FFFCB0;' : '';   // not really bold, just highlighted
        // $fontVariant .= $this->italic ? 'font-style: italic' : '';         // don't bother with italic special chars

        $spanStyle = "line-height:{$this->lineHeight};font-size:{$this->fontSize};color:$this->textcolour;$digraph;$fontVariant;$opacity;";

        $spanClass = 'sp_spell' . ($this->dimmable ? ' dimmable' : '');

        // no borders
        // if ($this->syllableSeparators) {
        $this->topHTML .= "<td style='padding:0;border:none;'></td>";
        $this->middleHTML .= "<td style='$tdStyle'><span class='$spanClass' style='$spanStyle'>$specialChars</span></td>";
        $this->bottomHTML .= "<td style='padding:0;;border:none;'></td>";
        // }
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
        return (get_string_between($phone, '^', ']'));
    }
    public function phoneSpelling($phone): string
    {
        return (get_string_between($phone, '[', '^'));
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
        if (strpos('.,",b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,.t,v,w,x,y,z', substr(strtolower($sound), 0, 1))) {
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


// https://www.ozzu.com/snippets/608141/convert-curly-quotes-to-regular-quotes-in-php
function convertCurlyQuotes($text): string
{
    $quoteMapping = [
        // U+0082⇒U+201A single low-9 quotation mark
        "\xC2\x82"     => "'",

        // U+0084⇒U+201E double low-9 quotation mark
        "\xC2\x84"     => '"',

        // U+008B⇒U+2039 single left-pointing angle quotation mark
        "\xC2\x8B"     => "'",

        // U+0091⇒U+2018 left single quotation mark
        "\xC2\x91"     => "'",

        // U+0092⇒U+2019 right single quotation mark
        "\xC2\x92"     => "'",

        // U+0093⇒U+201C left double quotation mark
        "\xC2\x93"     => '"',

        // U+0094⇒U+201D right double quotation mark
        "\xC2\x94"     => '"',

        // U+009B⇒U+203A single right-pointing angle quotation mark
        "\xC2\x9B"     => "'",

        // U+00AB left-pointing double angle quotation mark
        "\xC2\xAB"     => '"',

        // U+00BB right-pointing double angle quotation mark
        "\xC2\xBB"     => '"',

        // U+2018 left single quotation mark
        "\xE2\x80\x98" => "'",

        // U+2019 right single quotation mark
        "\xE2\x80\x99" => "'",

        // U+201A single low-9 quotation mark
        "\xE2\x80\x9A" => "'",

        // U+201B single high-reversed-9 quotation mark
        "\xE2\x80\x9B" => "'",

        // U+201C left double quotation mark
        "\xE2\x80\x9C" => '"',

        // U+201D right double quotation mark
        "\xE2\x80\x9D" => '"',

        // U+201E double low-9 quotation mark
        "\xE2\x80\x9E" => '"',

        // U+201F double high-reversed-9 quotation mark
        "\xE2\x80\x9F" => '"',

        // U+2039 single left-pointing angle quotation mark
        "\xE2\x80\xB9" => "'",

        // U+203A single right-pointing angle quotation mark
        "\xE2\x80\xBA" => "'",

        // HTML left double quote
        "&ldquo;"      => '"',

        // HTML right double quote
        "&rdquo;"      => '"',

        // HTML left sinqle quote
        "&lsquo;"      => "'",

        // HTML right single quote
        "&rsquo;"      => "'",
    ];

    return strtr(html_entity_decode($text, ENT_QUOTES, "UTF-8"), $quoteMapping);
}
