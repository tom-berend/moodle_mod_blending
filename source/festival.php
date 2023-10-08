<?php


// convert cmudict-0,4.out to dictionry.php

////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////



// messing up the dictionary.
//   -- declare that 'or' is NOT a vowel
//   -- pronounce fire, hire, wire, and tire in one syllable (but tireless, etc still rattle on)



// the dictionary array format
define('DICT_PHONES',   0);
define('DICT_SPELLING', 1);
define('DICT_STRESS',   2);
define('DICT_PART',     3);
define('DICT_FAILPHONE', 4);
define('DICT_FAILSPELL', 5);
define('DICT_DEBUG',    6);
define('DICT_ENTRY',    7);


// TODO certificates');//shift cer to c
// TODO explode - losing the syllble mark in k/s");  // i've commented it
// TODO why does extinction break when k/s is added?
// TODO actually ??


/////////////////  suffix rules
//   abhor     =>  abhorr>ed       // letter doubling on root (foggy, robber)
//   abuse     =>  abus>ive        // try the root with an 'e'
//   abundant  =>  abundan>ce      // for 'ce' try the root with a 't'
//   abilty    =>  abilities       // for 'ies' try the root with a 'y'
//   adultery  =>  adulterer       // for 'er' try the root with a 'y'
//   amiable   =>  amiability      // try the 'le' root for 'ibility'
//   amiable   =>  amiably         // try the 'le' root for 'ly'


///////////////// watch out for
//  double substitutes:  accent / accent>uate / accent>uat>ed  (lost a 'e' in 'uate')
//  compund suffixes:    access / assess>ible / access>ibility  (not a double suffix)
//  noun / verb:         he advocate>s for advocate>s

//  ("antagon" nil ((()))  // bogus root


///////////////// irregular allomorphs
// able => ability
// acquire => acquisition
// adequate  =>  adequacy
// adjudge => adjudicate
// admit => admiss>ion
// admonish => admonit>ion
// aero => aerial       // do we want to hyphen aero-dynamic ??
// number => numeric
// aluminium => aluminized
// antique =>   antiquity
// anxiety => anxious
// appear => apparent

//////////////// odd affixes
// ify  - acidify, beautify, certify  but watch for acid>if>ied  (y to i for >ied)

//////////////// the common three  (inflective)
// anticipate   >ed   >s   >ing


/*   FUNCTION WORDS
Prepositions    of, at, in, without, between
Pronouns        he, they, anybody, it, one
Determiners     the, a, that, my, more, much, either, neither
Conjunctions    and, that, when, while, although, or
Modal verbs     can, must, will, should, ought, need, used
Auxilliary verbs    be (is, am, are), have, got, do
Particles       no, not, nor, as


//  List of 37 Common Phonograms,
//  From Wylie and Durrell, 1970
-ack        -ain   -ake   -ale
-all          -ame -an    -ank
-ap          -ash   -at      -ate
-aw         -ay     -eat    -ell
-est         -ice    -ick    -ide
-ight       -ill     -in     -ine
-ing        -ink   -ip     -it
-ock        -oke   -op    -ot
-ore        -uck  -ug    -ump  -unk

*/




class festival
{

    private static $instance;

    var $shortDict = false;   //false or 1000, etc       // only use the first n entries
    //    forces loading the dictionary on every call?

    var $explainParse = false;      // explain parsing
    var $explainPostfix = false;
    var $explainLE_Ending = false;

    var $word   = '';
    var $part   = '';
    var $phones = '';
    var $stress = '';
    var $spelling = '';
    var $failPhone = '';
    var $failSpell = '';

    // each element of dictionary is serialized, otherwise the strings-in-array take
    //     way too much memory; this way each element is a simple string
    // $this->dictionary[word] = serialize (array(phones,p2s,stress,part,failphone,failSpell,debug));

    var $dictionary = [];
    var $dictionaryFailed = [];      // words that we couldn't parse
    var $fileHandle;

    // ax is very close to schwa, and subsequently dropped in CMUdict.
    //      in the Festival dictionary, all ax entries should be mapped to ah.

    //DH is suspect (the, them)

    //IH IY is too close   IH (near-front, near-high ear) and IY (front-high tree) are very close

    //ZH - measure, azure.   convert to z

    // McGuinness (2005) has separate phonemes for /o/ and /aw/, both are AO

    // lots of conflicts - CORE might be 'or' or o_e


    // there is a mess with 'oh' (dog) (do'aw' (law)


    // arpabet => custom
    var $CMUPhoneSet = array(
        'aa' => 'aw',
        'ae' => 'ah',
        'ah' => 'uh',
        'ao' => 'aw',   // broth
        'aw' => 'ow',
        'ax' => 'eh',  // low-mid back unrounded vowel converted to schwa
        'ay' => 'igh',
        'b' => 'b',
        'ch' => 'ch',
        'd' => 'd',
        'dh' => 'dh',
        'eh' => 'eh',
        'er' => 'er',
        'ey' => 'ay',
        'f' => 'f',
        'g' => 'g',
        'hh' => 'h',
        'ih' => 'ih',
        'iy' => 'ee',
        'jh' => 'j',
        'k' => 'k',
        'l' => 'l',
        'm' => 'm',
        'n' => 'n',
        'ng' => 'ng',
        'ow' => 'oh',     // bolt
        'oy' => 'oy',
        'p' => 'p',
        'r' => 'r',
        's' => 's',
        'sh' => 'sh',
        't' => 't',
        'th' => 'th',
        'uh' => 'oo',
        'uw' => 'ue',
        'v' => 'v',
        'w' => 'w',
        'y' => 'ye',
        'z' => 'z',
        'zh' => 'zh'
    );        // was z.h


    var $phoneSet = array(
        // the keys are tom's phonics set
        // note the schwa spellings gets removed after decoding the dictionary
        'ah' => array('type' => 'v',  'key' => 'bad',   'spellings' => array('eah', 'ay', 'a')),
        'eh' => array('type' => 'v',  'key' => 'egg',   'spellings' => array('ea', 'e', 'ai' /* schwa */, 'ou', 'a', 'i', 'o', 'u' /*plus 'eo' *//*add*/, 'ue', 'io', 'ay', 'ie', 'ah', 'iou', 'y', 'ia')),
        'ih' => array('type' => 'v',  'key' => 'big',  'spellings' => array('i', 'ui', 'y' /*add*/, 'ea', 'ee', 'e', 'a')),
        // note: o/aw will be remapped to o/ow
        'aw' => array('type' => 'v',  'key' => 'pot',   'spellings' => array('aw', 'au', 'ou', 'ough', 'augh', 'a', 'o', 'oa', 'ho')),  // aa/aardvark
        'uh' => array('type' => 'v',  'key' => 'tub',  'spellings' => array('u', 'ou', 'o_e' /*add*/, 'oo', 'o', 'a', 'hu')),

        'ay' => array('type' => 'v',  'key' => 'day',   'spellings' => array('a_e', 'ai', 'ay', 'ea', 'eigh', 'a', 'ei', 'aigh', 'ey' /* add */, 'au', 'e')),
        'ee' => array('type' => 'v',  'key' => 'tree',  'spellings' => array('e_e', 'ee', 'ea', 'ei', 'ey', 'e', 'y', 'i', 'ie', 'i_e', 'eo')),
        'igh' => array('type' => 'v', 'key' => 'high',  'spellings' => array('i_e', 'ie', 'i', 'igh', 'y', 'eigh', 'uy', 'eye', 'ai', 'ei', 'ye')),
        'oh' => array('type' => 'v',  'key' => 'coat',  'spellings' => array('o_e', 'oa', 'oe', 'o', 'ow', 'ough', 'owe', 'ou', 'oo' /*add*/, 'aw', 'au', 'oh', 'a')),
        'ue' => array('type' => 'v',  'key' => 'rude',  'spellings' => array('ough', 'u_e', 'ew', 'ue', 'o', 'oo', 'ou', 'ui', 'u', 'eu', 'io')),

        'ow' => array('type' => 'v',  'key' => 'otter', 'spellings' => array('o', 'ou', 'ow', 'ough', 'au', 'aw', 'oa')), // au aw  and oa get transformed later
        'oo' => array('type' => 'v',  'key' => 'book',  'spellings' => array('oo', 'ue', 'ew', 'ui', 'u_e', 'u', 'ou', 'oe', 'o', 'ough', 'oul')),
        'oy' => array('type' => 'v',  'key' => 'boy',   'spellings' => array('oi', 'oy')),

        'air' => array('type' => 'v', 'key' => 'fair',  'spellings' => array('are', 'air', 'arr', 'ar', 'err', 'ear' /*add*/, 'aire', 'aer', 'ehr', 'ere', 'er', 'ur')),
        'ar' => array('type' => 'v',  'key' => 'part',  'spellings' => array('arr', 'oar')),
        'er' => array('type' => 'v',  'key' => 'bird',  'spellings' => array('er', 'ur', 'ure', 'ir', 'or', 'r', 're', 'ear', 'yr', 'or', 'our', 'ore', 'oar', 'oor', 'arr', 'ar',  'ier', 'irr', 'urr', 'err', 'erre')),
        //            'or'=>array('type'=>'v',  'key'=>'bird',  'spellings'=>array('or','ore','oar','our','oor')      ),

        'g' => array('type' => 'c',   'key' => 'got',   'spellings' => array('g', 'gg', 'gh', 'gu', 'gue', 'ge')),
        'j' => array('type' => 'c',   'key' => 'job',   'spellings' => array('j', 'ge', 'g', 'dge', 'dj', 'd')),
        'v' => array('type' => 'c',   'key' => 'van',   'spellings' => array('v', 've', 'f')),
        'h' => array('type' => 'c',   'key' => 'hat',   'spellings' => array('h', 'wh')),
        'w' => array('type' => 'c',   'key' => 'win',   'spellings' => array('w', 'wh', 'u', 'o_e')),
        'z' => array('type' => 'c',   'key' => 'zip',   'spellings' => array('se', 'ze', 'z', 'zz', 's', 'es')),


        'r' => array('type' => 'c',   'key' => 'red',   'spellings' => array('wr', 'rr', 'rh', 'r', 're')),
        'ye' => array('type' => 'c',   'key' => 'yam',   'spellings' => array('y')),
        'th' => array('type' => 'c',  'key' => 'thin',  'spellings' => array('th', 'the')),
        'dh' => array('type' => 'c',  'key' => 'then',  'spellings' => array('th', 'the')),
        'sh' => array('type' => 'c',  'key' => 'shop',  'spellings' => array('sh', 'ch', 'ss', 's', 't', 'che', 'c', 'sc')),
        'ch' => array('type' => 'c',  'key' => 'chin',  'spellings' => array('ch', 'tch', 't')),

        'b' => array('type' => 'c',   'key' => 'big',   'spellings' => array('b', 'bb')),
        'd' => array('type' => 'c',   'key' => 'dog',   'spellings' => array('d', 'ed', 'dd', 't')),
        'f' => array('type' => 'c',   'key' => 'fun',   'spellings' => array('f', 'ff', 'ph', 'gh', 'lf')),
        'k' => array('type' => 'c',   'key' => 'kid',   'spellings' => array('ck', 'c', 'k', 'ch',  'che', 'x', 'lk', 'cc', 'qu', 'que')),
        'l' => array('type' => 'c',   'key' => 'log',   'spellings' => array('l', 'll', 'le', 'el', 'il', 'al', 'all')),
        'm' => array('type' => 'c',   'key' => 'man',   'spellings' => array('mn', 'mb', 'mm', 'm', 'lm')),
        'n' => array('type' => 'c',   'key' => 'not',   'spellings' => array('nn', 'kn', 'gn', 'pn', 'n', 'ne', 'hn')),
        'p' => array('type' => 'c',   'key' => 'pig',   'spellings' => array('p', 'pe', 'ppe', 'pp')),
        's' => array('type' => 'c',   'key' => 'sat',   'spellings' => array('s', 'c', 'ss', 'ce', 'se', 'st', 'sc' /*add*/, 'z', 'sw')),
        't' => array('type' => 'c',   'key' => 'top',   'spellings' => array('tt', 'bt', 'pt', 't', 'te', 'tte', 'ed', 'tw')),

        'ks' => array('type' => 'c',  'key' => 'tax',   'spellings' => array('x')),

        // why do we need this twice? ('aquatic')
        'kw' => array('type' => 'c',  'key' => 'quit',  'spellings' => array('qu', 'ck', 'cqu')),
        'qu' => array('type' => 'c',  'key' => 'quit',   'spellings' => array('qu')),
        'x' => array('type' => 'c',   'key' => 'tax',   'spellings' => array('x')),


        'zh' => array('type' => 'c',  'key' => 'measure', 'spellings' => array('z', 's')),
        'ng' => array('type' => 'c',  'key' => 'sing',   'spellings' => array('ng', 'n', 'ngue'))


        // sort out OO and oo
        // what about dh? qu?,

    );

    // these are spellings to improve the decoding, but don't count as formal spellings
    var $addedSpellings = array('ah' => 'ai');

    var $prefix  = array(
        'r.ee'      => 're',
        'ah.n/t.ee' => 'anti',
        //                         'k/s'       => 'x',     // was 'ih.k/s'
        'ih.g/z'    => 'ex'
    );

    var $prefixExclude = ',rea,read,reads,reader,reach,ready,real,reals,really,rebel,rebels,red,
                                    realm,ream,reamer,reams,reaping,rear,rearden,reas,reatta,reave,
                                    reed,reek,reel,reels,ref,reg,reich,reid,rein,
                               antic,antico,antioch';

    var $postfix = array(
        'ed'   => array(
            'replaces'   => array('', 'e'),    // for abate / abated, we replace 'e' in the root
            'doubling'   => array('rred,tted', 'gged'),            // for abet - abetted  /abhor - abhorred
            'patterns'   => '.t,.s.t,.ih.d,.ah.d,.eh.d,.d'
        ),

        'ment'  => array(
            'replaces'   => array(''),  // always need an empty here !!!
            'doubling'   => array(),
            'patterns'   => '.m.ah.n.t,/m.ah.n.t,.m.eh.n.t,/m.eh.n.t'
        ),

        'ments' => array(
            'replaces'   => array(''),  // always need an empty here !!!
            'doubling'   => array(),
            'patterns'   => '.m.ah.n.t.s,/m.ah.n.t.s,.m.eh.n.t.s,/m.eh.n.t.s'
        )

        //
        //        'ful'   => '/f.eh.l,.f.eh.l,/f.oo.l',
        //        'fuls'  => '/f.eh.l.z,.f.eh.l.z',
        //        'fulness' => '.f.eh.l/n.eh.s,/f.eh.l/n.eh.s',
        //
        //        'ation' => '.ay/sh.ah.n,/ay/sh.ah.n',
        //        'tion' => '/sh.ah.n,/ch.ah.n',
        //        'tions'=> '/sh.ah.n.z,/ch.ah.n.z',
        //        'ture' => '/ch.er',
        //        'ive'  => '/igh.v,.igh.v',       // parses correctly without suffix
        //        'ness' => '/n.ah.s',
        //        'ic'   => '/igh.k,.igh.k',
        //        'ous'  => '/ah.s,.ah.s',
        //
        //        'able' => '/ah/b.ah.l,.ah/b.ah.l,.eh/b.eh.l',
        //        'ible' => '/ah/b.ah.l,.ah/b.ah.l,/eh/b.ah.l,.eh/b.ah.l',
        //
        //        'less' => '/l.eh.s,.l.eh.s',
        //        'lessness'=> '.l.eh/s.n.eh.s,/l.eh/s.n.eh.s',
        //        'lessly'  => '.l.eh/s.l.ee,/l.eh/s.l.ee',
        //
        //        'ish'  => '/ih.sh,.ih.sh',
        //        'ily'  => '.ah/l.ee,.eh/l.ee,/eh/l.ee',
        //        'ity'  => '.eh/t.ee,.igh/t.ee',
        //        'ly'   => '/l.ee,.l.ee,/ee,.ee',
        //        'iest' => '.ee/ah.s.t',
        //
        //
        //        'ing'  => '/ih.ng,.ih.ng',
        //        'ings' => '/ih.ng.z,.ih.ng.z',
        //        'ingly'  => '/ih.ng/l.ee,.ih.ng/l.ee,/ah.ng/l.ee,.ah.ng/l.ee',
        //
        //
        //        'age'  => '/igh.j,.igh.j',
        //        'ism'  => '.igh/z.ah.m,.igh/s.ah.m,/igh/z.ah.m',
        //        'let'  => '/l.ah.t,.l.igh.t',
        //        'ory'  => '/er/ee,.er/ee,.aw/r.ee',
        //        'ery'  => '/er/ee,.er/ee,.aw/r.ee',
        //        'ward' => '/w.er.d',
        //        'ial'   => '.ee/ah.l,.ah.l',
        //        'al'   => '/ah.l,.ah.l',
        //        'ogy'  => '.ah/j.ee',
        //        'en'   => '.ah.n,/ah.n',
        ////        'y'    => '.ee,/ee',
        //
        //        'some' => '/s.eh.m'

        ///////////////////////////////////////
        // one solution to the 'table' problem
        //  ,'le'   => '.eh.l'
        ///////////////////////////////////////
    );

    // spelling of PAIRS of phonemes (eg: don't worry about spellings)
    //   eg: TABLE t.ay/b.eh.l    convert .eh.l into 'le'

    var $LE_Ending = array(
        'eh.l' => '[-le^eh+l]'
    );


    // substitute series of phonemes (don't worry about spellings)
    var $mapPhonemes = array(
        'k.w'      => 'qu',
        'k.s'      => 'x',
        'ch'       => 't.sh',
        'ng.k.th'  => 'ng.th',
        'k/s.'     => 'x/',
        'aw.r'     => 'oh.r',     //board,hoarse
        'ye.ue'    => 'ue',      // few
        'ye.er'    => 'er',      // figure
        'ye.oo'    => 'ue',       // fury
        'th.r'     => 'th',       // through
        'aw.r'     => 'oh.r',
        'ye.eh'    => 'ue',       // ambulance
        'aw.zh'    => 'ah.g',     // arbitrage
        'z.eh.m'   => 's.m',    // spasm, ..+ism
        'w.eh.l'   => 'ah.l',        // actual
        'w.eh/l'   => 'ah/l',        // actually

        'w.air'    => 'ah.r',        // actuary
        'er.n'     => 'r.aw.n',      // iron
        'er/n'     => 'r.aw/n',      //
    );
    //'k/s'      => 'ks'

    //'k.w.'     => 'qu',
    //'ah.l'     => 'le',


    //'t/s'      => 'zz',    //pizza
    //'uh.d'     => 'ould',  //would
    //'h.ah'     => 'ia',
    //'z.h'      => 'g',
    //'sh.ee'    => 'ci',
    //  'z.h'      => 's',
    //'dh.ah'    => 'th',
    //'w.ih'     => 'ui',
    //'w.ah'     => 'a',
    //
    //'aw.r'     => 'air',
    //'eh.r'     => 'air',
    //
    //'k.w'      => 'qu',
    //'k.s'      => 'x',
    //'k/s'      => 'x'      // warning - lose a syllable   eg; affixing , affixes



    //Lexical stress is indicated by means of a numeral [012] attached to a vowel:
    //  0 = no stress
    //  1 = primary stress
    //  2 = secondary stress


    var $postfixups = array(



        // way too many words pronounced 'eh',  fix them up.
        '[a^eh]' => '[a^ah]',
        '[i^eh]' => '[i^ih]',
        '[o^eh]' => '[o^uh]',
        '[u^eh]' => '[u^uh]',

        // wart from er to ar
        '[ar;er]' => '[ar;ar]',


        // OR fixup ->  CORN to 'O+R' (not 'AW-R")   // OR is considered two sounds
        '[o^aw].[r^r]' => '[o^oh].[r^r]',
        '[o^aw].[rr^r]' => '[o^oh].[rr^r]',
        '[o^aw].[re^r]' => '[o_e^oh].[r^r]',

        // sort is not [or;er]   but 'worth' is.  need to review this rule
        '[or^er]' => '[o^oh].[r^r]',

        // replace aw.r with ar (part, cart)
        '[a^aw].[r^r]' => '[ar^ar]',

        '[au^ow]' => '[au^aw]',           // applaud is /aw/, not /ow/
        '[aw^ow]' => '[aw^aw]',           // hawk    is /aw/, not /ow/

        // just eliminate the pronunce on the '[r;er]'
        '[i_e^igh]+[r^er]' => '[i_e^igh].[r^r]',
        '[i_e^igh]/[r^er]' => '[i_e^igh].[r^r]',      // require is not re/qui/re



        '[i^ih].[ve^v]' => '[i_e^ih].[v^v]'            // prefer i_e but ambiguous here

    );



    // from McGuinness(2004) Early Reading Instruction p58,
    var $sightWords = ",aunt,laugh,plaid,friend,leopard,busy,sieve,pretty,women,abroad,broad,cough,father,
                        ,gone,trough,yacht,because,does,blood,flood,once,straight,ski,
                        ,aisle,choir,height,sew,beauty,feud,queue,move,prove,shoe,deuce,
                        ,whom,should,heart,hearth,borrow,tomorrow,sorrow,sorry,acre,glamour,journey,syrup,
                        ,leisure,measure,pleasure,treasure,drawer,laurel,door,floor,poor,bury,heron,scarce,
                        ,they,arc,tic,ache,caulk,stomach,debt,doubt,subtle,
                        ,smooth,breathe,clothe,honest,honor,hour,whose,whole,";

    // removed: chalk, talk, walk (just a(l) spellings of aw)



    function __construct()
    {
        // $this->loadDictionary();
        // assert(count($this->dictionary) > 0, 'Nothing loaded in Dictionary');

        //echo "dictionary has ",count($this->dictionary)," elements<br>";
        //$count = 0;
        //foreach($this->dictionary as $k=>$v){
        //   echo $count, "  $k   $v <br>";
        //   $count++;
        //   if($count>5) break;
        //}
    }


    function fixPostfix($postfix = 'ing')
    {
        // $this->loadDictionary();

        while ($candidate = $this->CMUfgets(1500)) {
            $aPhones = $this->parseEntry($candidate);
            if (substr($this->word, -strlen($postfix)) == $postfix)

                // ok, this is an -ing word

                echo $this->word, '<br>';
        }
    }



    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    // function testSearch()
    // {    // testSearchOnly
    //     // don't bother if the dictionary is too big or too short
    //     if (!$this->shortDict or $this->shortDict < 200) return (true);

    //     assert(count($this->searchOnly('abandon', '', '')) == 1);
    //     assert(count($this->searchOnly('aband*n', '', '')) == 1);
    //     assert(count($this->searchOnly('abandon*', '', '')) == 6);  // abandon, abandonment, etc
    //     assert(count($this->searchOnly('abandon*', '', '', 3)) == 3);  // does MAX work?

    //     // check for phoneme
    //     assert(count($this->searchOnly('abandon*', 'ng', '')) == 1); // abandoning

    //     // check for spelling
    //     assert(count($this->searchOnly('abandon*', '', 'ng;ng')) == 1); // abandoning
    //     return (true);
    // }


    function searchOnly($wildcard, $phoneme, $spelling = '', $max = 1000, $failAt = '', $maxSyllables = 99)
    {
        // trace("searchOnly(wildcard=$wildcard,phoneme=$phoneme,spelling=$spelling,max=$max,failat=$failAt,maxSyllables=$maxSyllables");
        $found = array();

        if (empty($wildcard))  $wildcard = '*';      // default is everything
        foreach ($this->dictionary as $candidate => $serialized_aValues) {

            // first two cases just prevent a call to the compare function
            if ($wildcard == '*'  or $this->compare($wildcard, $candidate)) {           // matches search string)

                $aValues = unserialize($serialized_aValues);

                // array structure of $this->dictionary becomes too big if we don't
                //         compress each sub-array into a serialized string
                // $this->dictionary[word] = array(phones,p2s,stress,part,failphone,failspell,debug);

                $p2s = $aValues[DICT_SPELLING];

                // skip if syllable count isn't good
                if ($maxSyllables > 0) {        // zero is 'don't care'
                    if (substr_count($aValues[DICT_SPELLING], '/') > ($maxSyllables - 1))
                        continue;
                }

                // check for phonemes here...

                // check for spelling here...
                if (!empty($spelling) and strpos($aValues[DICT_SPELLING], '[' . $spelling . ']') === false) continue;

                // and skip if we are only checking one phone
                if (!empty($failAt) and $failAt !== $aValues[DICT_FAILPHONE]) continue;

                $found[$candidate] = $aValues;
            }
            // check if count exceeded
            if ($max > 0 and count($found) >= $max) break;
        }
        return ($found);
    }

    function formatOnly($findList, $showFound = 1, $showNotFound = 1)
    {

        $count   = 0;
        $decoded = 0;
        $pcnt    = 0;
        $nSpellings = 0;


        $HTML = '<table><span style="font-size:x-small">';

        foreach ($findList as $candidate => $aValues) {
            if (!empty($aValues[DICT_FAILPHONE])) $decoded++;  // track percentage of decoded
            //if((!empty($aValues[DICT_FAILPHONE]) and $showFound) or (empty($aValues[DICT_FAILPHONE]) and $showNotFound)){
            if (!empty($aValues[DICT_FAILPHONE])) {
                $aValues[DICT_PHONES] = '<span style="background-color:yellow;">' . $aValues[DICT_SPELLING] . $aValues[DICT_DEBUG] . '</span>';       // debug messages from phones2spelling()
                $HTML .=  "\n<tr><td nobr>$candidate &nbsp;</td><td colspan=2 nobr>{$aValues[DICT_PHONES]}</td></tr>";
            } else {
                $HTML .=  "\n<tr><td nobr>$candidate &nbsp;</td><td nobr>{$aValues[DICT_PHONES]}</td><td nobr>{$aValues[DICT_SPELLING]}</td></tr>";
            }
            //}
        }

        foreach ($this->phoneSet as $aPhone)
            $nSpellings = $nSpellings + count($aPhone['spellings']);

        $HTML .= '</span></table>';


        if ($count > 0)        // avoid division by zer problems
            $pcnt = round(($decoded / $count) * 100, 0);

        $HTML .= "\n<br><br>$count records returned, $decoded decoded ($pcnt percent)  ($nSpellings spellings)<br>";
        return ($HTML);
    }


    function search($wildcard, $phoneme, $spelling = '', $showFound = 1, $showNotFound = 1, $max = 100, $failAt = '', $maxSyllables = '')
    {

        $findList = $this->searchOnly($wildcard, $phoneme, $spelling, $max, $failAt, $maxSyllables);
        $HTML     = $this->formatOnly($findList, $showFound = 1, $showNotFound);
        return ($HTML);
    }


    /*
    function twoSyllables()
    {

        // get the vowel sequence
        // $systemStuff = new systemStuff();
        require_once $systemStuff->PHONICS_Base() . "/scripts/advancedCode.php";
        $advancedCode = new advancedCode();
        $vowelSequence = $advancedCode->vowelSequence();

        // we set up two arrays, one that gives a numeric sequence to a phoneme, the other that collects words
        // in the HIGHER of the two vowel phonemes

        $aSeq = array();            // the sequence array:   ('[a;ah]' => 1, ...)
        $aSeqr = array();                // the sequence reversed (1 => '[a;ah]')
        $aWords = array();
        $count = 1;                 // count from 1, so 0 can be 'not found'
        foreach ($vowelSequence as $vs) {
            $aSeqr[$count]  = $vs;
            $aSeq[$vs]  = $count++;
            $aWords[$vs] = '';
        }

        // find every two-syllable and longer word
        foreach ($this->dictionary as $candidate => $serialized_aValues) {
            if (strpos($candidate, '<') > 0 or strpos($candidate, '<') > 0) continue;  // skip words with morphines

            $aValues = unserialize($serialized_aValues);
            if (!empty($aValues[DICT_FAILPHONE])) continue;                  // skip words that we couldn't decode
            if (substr_count($aValues[DICT_SPELLING], '/') < 1) continue;      // skip words that don't have 2 syllables

            // explode them into phonemes
            $spelling = $aValues[DICT_SPELLING];
            $spelling = str_replace('/', '.', $spelling); // prepare for explode
            $spellPhones = explode('.', $spelling);      // now an array of phones

            $firstV  = 0;
            $secondV = 0;
            $thirdV  = 0;
            $fV = $sV = $tV = '';
            $tooLong = false;
            foreach ($spellPhones as $sPhone) {

                $soundPart = substr($sPhone, strpos($sPhone, ';') + 1);       // convert  [ab;cd] to cd
                $soundPart = substr($soundPart, 0, strlen($soundPart) - 1);   // and convert cd] to cd

                if (isset($this->phoneSet[$soundPart]) and $this->phoneSet[$soundPart]['type'] == 'v') {  // it's a vowel

                    if (isset($aSeq[$sPhone])) {       // it's a vowel in the sequence
                        if (empty($firstV)) {
                            $firstV = $aSeq[$sPhone];   // from 1 to 45 or so
                            $fV = $sPhone;
                        } elseif (empty($secondV)) {
                            $secondV = $aSeq[$sPhone];
                            $sV = $sPhone;
                        } elseif (empty($thirdV)) {
                            $thirdV = $aSeq[$sPhone];
                            $tV = $sPhone;
                        } else {
                            $tooLong = true;
                            continue;       // this is a fourth vowel
                        }
                    }
                }
            }
            if ($tooLong or empty($secondV)) continue;           // didn't find two acceptable vowels, for found too manyfs
            //echo $aValues[DICT_ENTRY],' ',$spelling,' ',$firstV,' ',$secondV,' ',$thirdV,' ',"$fV $sV $tV",'<br> ';

            if ($firstV >= $secondV and $firstV >= $thirdV)
                $aWords[$fV] .= ',' . $aValues[DICT_ENTRY];
            elseif ($secondV >= $firstV and $secondV >= $thirdV)
                $aWords[$sV] .= ',' . $aValues[DICT_ENTRY];
            else
                $aWords[$tV] .= ',' . $aValues[DICT_ENTRY];
        }
    }

*/

    // creates the dictionary if needed, then loads it
    function loadDictionary()
    {

        $phoneStats = array();
        $morphoWordList = array();
        $debug = '';


        // try the cache...
        if (!$this->shortDict or !is_array($this->dictionary)) {      // if the dictionary is loaded, we are done!

            $cacheName        = 'festivalDict';

            // is the cache loaded ?
            $debug .=  "about to try loading dictionary<br>";

            // if (!$this->shortDict and $this->dictionary = $cache->load($cacheName, $hash)) {  // got the dictionary from cache
            //     $debug .= "loading dictionary is successful<br>";
            // } else {

        }
    }









    // the problem with building a recursive function is that you can't
    // iterate an array recursively; the array pointer gets lost each time

    // so we use a different recursive technique.  we work through the phones
    // in the FIRST element of the array, and and recursively add every
    // possible phone;spelling pair onto the end of the same array.

    // the array doesn't grow much, because the 'possible' filter loses most of
    // them - i expect almost all words will end with only one candidate.

    // PHP supports this with array_shift and array_push

    function phones2spelling($word, $phones, $stress)
    {  // spell;phone pairs with ./- separators intact
        // absenteeism      ah.b/s.ah.n/t.ee/ih/z.ah.m    a;ah/b;b.s;s...-*;ism
        $debug = '';

        $result = array();
        $result['morphoword'] = strtolower($word);    // happen>ed

        $result['word']      = $word = strtolower(
            str_replace(
                '>',
                '',
                str_replace(
                    '<',
                    '',
                    str_replace('-', '', $word)
                )
            )
        );  // remove < > - (morpheme markers)

        // if this is not a root word (ie: has morphemes), then
        //      stop processing right away.   we will process these
        //      in a second pass.
        //if(!word !== $result['morphoword']){
        //    return($result);
        //}

        $result['phones']    = $phones;
        $result['part']      = '';
        $result['stress']    = $stress;
        $result['spelling']  = '';
        $result['failPhone'] = '';
        $result['failSpell'] = '';
        $result['debug']     = '';


        //$debug .= "phones2spelling($word,$phones)<br>";
        $aPlausible = array(array($word, $phones, ''));   // {$word, $phone, $spellingSoFar}

        if ($this->explainParse) $debug .= "<br>";

        //        if(strpos($this->sightWords,','.$word.',') !== false){     // if found in the oddball list BETWEEN COMMAS
        //            $result['spelling'] = "\[$word;+]";
        //            return($result);
        //        }

        while (!empty($aPlausible)) {

            // pull off the FIRST element of $aPlausible
            $candidate = array_shift($aPlausible);
            $wd   = $candidate[0];
            $ph   = $candidate[1];
            $spl  = $candidate[2];      // really "spelling so far"
            if ($this->explainParse) $debug .= "Candidate $word: (remaining word:'$wd'), $this->failPhone, remaining phones '$ph', - - - so far '$spl'<br>";


            //            if(empty($ph) and $wd=='e'){
            //                "$debug - $word<br> $debug $word,$phones  - ($wd, $ph, $spl) ran out of phones, still have an 'e' <br>";
            //                return($spl.'-'.'?'.$wd); // pretend it is a silent ending
            //                }
            if (empty($ph)) {
                //$debug .= "$debug - $word $debug $word,$phones  - ($wd, $ph, $spl) ran out of phones<br>";
                return ($result);
            }



            // check whether we have a phoneme mapping   // warning - this is a SLOW loop, called millions of times

            foreach ($this->mapPhonemes as $p => $s) {  // 'k.w'      => 'qu',

                if (strncmp($ph, $p, strlen($p)) == 0) {

                    //printNice('festival',"word: $word, ph: $ph, p: '$p', s: '$s'");

                    // we don't confuse k.s with k.sh but we ARE allowed to change k/s.
                    // so check that last of ph and first of p are not BOTH alphas
                    if (
                        !ctype_alpha(substr($ph, strlen($p) - 1, 1))
                        or !ctype_alpha(substr($ph, strlen($p), 1))
                    ) {

                        $left_ph = $p;
                        $separator = substr($ph, strlen($p), 1);
                        $remain_ph = substr($ph, strlen($p) + 1);

                        $newWd = $wd;  //substr($wd,strlen($s));

                        $remain_ph = str_replace_single($p, $s, $ph);  // but only change one occurance

                        $final = $spl; //spl.'['.$wd.';'.$left_ph.']'.$separator;
                        // printNice('festival', "word: $word, left_ph: $left_ph, separator: $separator, remain_ph: $remain_ph, final $final");

                        //$debug .= " $word - - - pushing DOUBLE-PHONE $s/$p and will try with (word='$newWd',phones='$remain_ph',spellingSoFar='$final')<br>";
                        $aPlausible[] = array($newWd, $remain_ph, $final);
                        if ($this->explainParse) $debug .= "adding to Plausible (phoneme mapping)  array($newWd, $remain_ph, $final) <br>";
                    }
                }
            }



            if (!ctype_alpha(substr($ph, 1, 1))) {
                $left_ph   = substr($ph, 0, 1);     // one-letter phone
                $separator = substr($ph, 1, 1);
                $remain_ph = substr($ph, 2);
            } elseif (!ctype_alpha(substr($ph, 2, 1))) {
                $left_ph   = substr($ph, 0, 2);     // two-letter phone
                $separator = substr($ph, 2, 1);
                $remain_ph = substr($ph, 3);
            } else {
                $left_ph   = substr($ph, 0, 3);     // three-letter phone
                $separator = substr($ph, 3, 1);
                $remain_ph = substr($ph, 4);
            }

            $result['failPhone'] = $left_ph;        // if we fail, the last update will be the culprit.
            $result['failSpell'] = substr($wd, 0, 2);

            if ($this->explainParse) $debug .= " - fail $word: (remaining word:'$wd'), fail on /$left_ph/ at ..$ph)<br>";
            //$this->debug .= " - fail $word: (remaining word:'$wd'), fail on /$left_ph/ at ..$ph)<br>";



            if (!isset($this->phoneSet[$left_ph])) assert(false, "phoneSet[$left_ph] is not set in $word");

            foreach ($this->phoneSet[$left_ph]['spellings'] as $spelling) {

                if (!isset($this->phoneSet[$left_ph]['spellings'])) assert(false, "phoneSet[$left_ph] is not set in $word");


                //$debug .= " - - examining '$spelling'<br>";

                //handle the a_e, e_e... spellings

                if (strpos($spelling, '_') > 0 and strlen($wd) >= 3 and $separator !== '/') {

                    // printNice($spelling,'spelling');
                    // printNice($wd,'wd');
                    // printNice($left_ph,'left_ph');
                    // printNice($separator,'separator');

                    //$debug .= "matched a_e spelling '$spelling' at $word<br>";
                    if (substr($wd, 0, 1) == substr($spelling, 0, 1) and substr($wd, 2, 1) == substr($spelling, 2, 1)) {

                        $newWd = substr($wd, 1, 1) . substr($wd, 3);  // take out the a_e  eg:  if ated then leave td
                        $final = $spl . '[' . $spelling . '^' . $left_ph . ']' . $separator;
                        //$debug .= " - - - pushing $spelling and will try with (word='$newWd',phones='$remain_ph',spellingSoFar='$final')<br>";
                        $aPlausible[] = array($newWd, $remain_ph, $final);
                        if ($this->explainParse) $debug .= "adding to Plausible (e_e)  array($newWd, $remain_ph, $final) <br>";
                    }
                }

                // TOM - we have a problem here - following code gets 'battle', misses 'battled'.
                // TOM - we have a problem here - following code gets 'battle', misses 'battled'.
                // TOM - we have a problem here - following code gets 'battle', misses 'battled'.


                // check whether we have an LE Ending(eg: the 'le' in table)

                foreach ($this->LE_Ending as $p => $s) {      //   'eh.l' => '[(e);eh][le;l]'
                    if ($this->explainParse or $this->explainLE_Ending) $debug .= "examinging LE Ending $p => $s for  wd = $wd,  ph = $ph<br>";

                    if ($wd === 'le') {       // only works at END of word

                        if ($this->explainParse or $this->explainLE_Ending) $debug .= "Found an LE Ending:  left_ph: '$left_ph', separator:'$separator', remain_ph:'$remain_ph', wd:'$wd' <br>";
                        $left_ph = '';
                        $separator = '';  // no need for special marker, last phone will be [-le;eh+l]
                        $remain_ph = '';

                        $newWd = $wd;  //substr($wd,strlen($s));
                        $remain_ph = str_replace_single($p, $s, $ph);

                        $result['spelling'] = $spl . $s . $separator;
                        $result['debug'] = $result['failPhone'] = $result['failSpell'] = '';   // success
                        if ($this->explainParse or $this->explainLE_Ending) $debug .= " - SUCCESS on LE Ending: $final()<br>";


                        return ($result);     // success criteria!

                    }
                }



                // note: we try BOTH the e_e and the normal one.  for example
                // in the word 'abided', the i_e rule kicks in but won't work.

                if (strncmp($wd, $spelling, strlen($spelling)) == 0) {
                    //$debug .= "matched spelling '$spelling'<br>";
                    $newWd = substr($wd, strlen($spelling));
                    // maybe this is an exact match
                    if (empty($newWd) and empty($remain_ph)) {
                        $result['spelling'] = $spl . '[' . $spelling . '^' . $left_ph . ']' . $separator;
                        $result['debug'] = $result['failPhone'] = $result['failSpell'] = '';   // success
                        if ($this->explainParse) $debug .= " - SUCCESS 2: {$result['spelling']}()<br>";
                        return ($result);     // success criteria!
                    }

                    $result['spelling'] = $spl . '[' . $spelling . '^' . $left_ph . ']' . $separator;
                    //$debug .= " - - - pushing $spelling and will try with (word='$newWd',phones='$remain_ph',spellingSoFar='$final')<br>";
                    $aPlausible[] = array($newWd, $remain_ph, $result['spelling']);
                    if ($this->explainParse) $debug .= "adding to Plausible (regular)  array($newWd, $remain_ph) <br>";
                }
            }
        } // while


        return ($result);     // can't report DEBUG
    }


    function phones2spellingFixup($p2s, $word, $phone, $stress)
    {    // fixup for SCHWA and other post-processing

        //  NOTE: the phoneme string from festival isn't updated

        $oldp2s = $p2s;

        foreach ($this->postfixups as $before => $after) {
            $p2s = str_replace($before, $after, $p2s);
        }

        //         // debug
        //         if($oldp2s !== $p2s)
        //             printNice('festival',"{$word}: phones2spellingFixup converted '$oldp2s' to '$p2s'");

        return ($p2s);



        //  NOTE: the phoneme string from festival isn't updated


        // way too many words pronounced 'eh',  fix them up.
        $p2s = str_replace('[a;eh]', '[a;ah]', $p2s);
        $p2s = str_replace('[i;eh]', '[i;ih]', $p2s);
        $p2s = str_replace('[o;eh]', '[o;uh]', $p2s);
        $p2s = str_replace('[u;eh]', '[u;uh]', $p2s);

        // wart from er to ar
        $p2s = str_replace('[ar;er]', '[ar;ar]', $p2s);


        // OR fixup ->  CORN to 'O+R' (not 'AW-R")   // OR is considered two sounds
        $p2s = str_replace('[o;aw].[r;r]', '[o;oh].[r;r]', $p2s);
        $p2s = str_replace('[o;aw].[rr;r]', '[o;oh].[rr;r]', $p2s);
        $p2s = str_replace('[o;aw].[re;r]', '[o_e;oh].[r;r]', $p2s);



        // sort is not [or;er]   but 'worth' is.  need to review this rule
        $p2s = str_replace('[or;er]', '[o;oh][r;r]', $p2s);

        // replace aw.r with ar (part, cart)
        $p2s = str_replace('[a;aw].[r;r]', '[ar;ar]', $p2s);

        $p2s = str_replace('[au;ow]', '[au;aw]', $p2s);           // applaud is /aw/, not /ow/
        $p2s = str_replace('[aw;ow]', '[aw;aw]', $p2s);           // hawk    is /aw/, not /ow/

        // just eliminate the pronunce on the '[r;er]'
        $p2s = str_replace('[i_e;igh]+[r;er]', '[i_e;igh]+[r;r]', $p2s);
        $p2s = str_replace('[i_e;igh]/[r;er]', '[i_e;igh]+[r;r]', $p2s);    // require is not re/qui/re

        return ($p2s);
    }

    //<!--[h;h].[i_e;igh]+[r;er]+[;e]-->







    // typical entry:
    //
    //   ("abandon" nil (((ax) 0) ((b ae n) 1) ((d ax n) 0)))       >>   {'word'='abandon', 'part'='nil', 'phones'='ax/b.ae.n/d.ax.n', 'stress' = '010'}


    function parseEntry($entry)
    {
        $this->word = '';
        $this->part = '';
        $this->phones = '';
        $this->stress = '';

        //echo "starting off with '$entry' <br>";

        $tokCurrent = 0;
        $state = 'idle';
        $phone = '';

        while ($tokCurrent < strlen($entry)) {

            $token = substr($entry, $tokCurrent, 1);    // first character

            //    $mini = substr($entry,$tokCurrent);
            //    echo "loop $tokCurrent, '$mini', word '$this->word', part '$this->part', phones '$this->phones', stress '$this->stress',  and state '$state'  <br>";


            if ($tokCurrent > strlen($entry)) {
                echo "we got lost somehow...<br>";
                return;
            }

            switch ($state) {
                case 'idle':
                    //assert($token=='(',"Expecting '(' in state $state parsing $entry");
                    $state = 'startreadword';
                    break;
                case 'startreadword':
                    //assert($token=='"',"Expecting '\"' in state $state parsing $entry");
                    $state = 'readword';
                    break;
                case 'readword':
                    if ($token == '"')
                        $state = 'startreadpart';
                    else
                        $this->word .= $token;        // build the word, loop in this state
                    break;
                case 'startreadpart':
                    if ($token == ' ')         // haven't found the part yet
                        break;
                    if ($token == ')') {        // this is a morpheme that tom added:  ("swatt>ing")
                        $state = 'alldone';
                        break;
                    }
                    $this->part .= $token;        // build the part, but move to another state that looks for a closing space
                    $state = 'readpart';
                    break;
                case 'readpart':
                    if ($token == ' ') {         // at the end of the part
                        $state = 'word';
                        break;
                    }
                    $this->part .= $token;        // build the part, but move to another state that looks for a closing space
                    break;

                    //  every entry is 3 brackets deep:  (word (syllable ( phones* ) stress)*  )
                    //   ("abandon" nil (((ax) 0) ((b ae n) 1) ((d ax n) 0)))       >>   {'word'='abandon', 'part'='nil', 'phones'='ax/b.ae.n/d.ax.n', 'stress' = '010'}

                case 'word':
                    if ($token == ' ')    //just skip spaces
                        break;

                    if ($token == '(') {
                        $state = 'syllable';     // duck down to handle the next level
                        break;
                    }
                    if ($token == ')') {
                        $state = 'alldone';
                        break;
                    }
                case 'syllable':
                    if ($token == '(') {
                        if (!empty($this->phones))  $this->phones .= '/';   // add separator
                        if (!empty($phone)) {
                            //echo "1emitting {$this->CMUPhoneSet[$phone]['trans']} translated from '$phone'<br>";
                            $this->phones .= $this->CMUPhoneSet[$phone];   // translate and emit
                        }
                        $phone = '';

                        $state = 'phones';      // duck down to handle the next level
                        break;
                    }
                    if ($token == ')') {
                        $state = 'stress';
                        break;
                    }
                    if ($token == ' ')    //just skip spaces
                        break;

                    //assert(false,'should never get here');
                    break;

                case 'stress':
                    if ($token == ' ')    //just skip spaces
                        break;

                    if ($token == ')') {
                        $state = 'word';    // pop back up past syllable (we ate the close bracket)
                        break;
                    }
                    $this->stress .= $token;
                    break;

                case 'phones':
                    if ($token == '(')    //we expect an open bracket, don't check
                        break;

                    if ($token == ')') {
                        if (!empty($phone)) {
                            //echo "2emitting {$this->CMUPhoneSet[$phone]['trans']} translated from '$phone'<br>";
                            //                            if(!empty($this->phones)) $this->phones .= '.';   // add separator
                            if (!empty($this->phones) and $this->phones[strlen($this->phones) - 1] != '/')  $this->phones .= '.';   // add separator
                            $this->phones .= $this->CMUPhoneSet[$phone];   // translate and emit
                        }
                        $phone = '';

                        $state = 'stress';
                        break;
                    }

                    if ($token == ' ') {
                        if (!empty($phone)) {
                            //echo "3emitting {$this->CMUPhoneSet[$phone]['trans']} translated from '$phone'<br>";
                            if (!empty($this->phones) and $this->phones[strlen($this->phones) - 1] != '/')  $this->phones .= '.';   // add separator
                            $this->phones .= $this->CMUPhoneSet[$phone];   // translate and emit
                        }
                        $phone = '';

                        break;
                    }

                    $phone .= $token;
                    break;

                case 'alldone':
                    // almost done.  but we have some phones to fix up

                    // drops
                    $this->phones = str_replace('ax', 'ah', $this->phones);       //  'ax'     map to 'ah'
                    $this->phones = str_replace('ao', 'aa', $this->phones);       //  'aa'     map to 'ao'
                    //  ????               $this->phones = str_replace('z.h','zh',$this->phones);      //  'z.h'    map to 'zh'

                    // adds
                    //                    $this->phones = str_replace('oh.r','or',$this->phones);     //  'ao' 'r'      map to 'or'
                    $this->phones = str_replace('eh.r', 'air', $this->phones);     //  'eh' 'r'      map to 'air'
                    // sometimes the syllable break is funny
                    $this->phones = str_replace('eh/r.', 'air/', $this->phones);     //  'eh' 'r'      map to 'air'

                    $this->phones = str_replace('k.w.', 'kw.', $this->phones);     //  'eh' 'r'      map to 'ar'
                    $this->phones = str_replace('k.x.', 'kx.', $this->phones);     //  'eh' 'r'      map to 'ar'


                    // spelling changes
                    $this->phones = str_replace('uw', 'yu', $this->phones);       //  we use 'jh' instead of 'j'

                    // consolidation
                    $this->phones = str_replace('y.yu', 'yu', $this->phones);       //  we use 'jh' instead of 'j'

                    return;

                default: {
                        echo "arrived in default with state '$state'";
                        return;
                    }
            }

            $tokCurrent++;
        }
        die('should never get here');
    }


    // search function ///////////////////////////



    // Use ? in the search string to represent one unknown letter.
    // Use * as a wild-card i.e. to represent any number of contiguous unknown letters.

    // 1) Enter search string? ?now???ge
    // knowledge
    //
    // 2) Enter search string? ?hy*i*og?
    // physiology
    // physiopathology
    // phytosociology
    //
    // 3) Enter search string? *z*z*z*
    // pizazz
    // pizzazz
    // razzmatazz
    //
    // 4) Enter search string? r*y*y*
    // rhinolaryngology
    // rhythmically
    // rhythmicity
    // royally
    // royalty
    // royalty's

    ///////////////////// strategy
    // check the match for error conditions
    //    eg: two adjacent **

    // scan the target string and the match string
    //   if both-EOF then succeed
    //   if the match is a letter and it matchs, then advance the match AND the target, loop
    //   if the match is a '?' then advance the match AND the target, loop
    //   if the match is a '*' then advance the match, and recursively call for every remaining substring in the target, loop

    //   if any recursive call succeeds, then return success



    // this is a recursive function called by $this->search()
    function compare($wildcard, $candidate, $wPntr = 0, $cPntr = 0, $debug = false)
    {
        //echo "function compare('$wildcard','$candidate',$wPntr,$cPntr)<br>";


        while (strpos($wildcard, '**') !== false)
            $wildcard = str_replace('**', '*', $wildcard);        //watch out for people using '****on by mistake

        //if ($debug) echo "Compare(".substr($wildcard,$wPntr).",".substr($candidate,$cPntr).")<br>";


        if (empty($wildcard)) {
            if ($debug) echo "FAIL - Exhausted Wildcard<br>";
            return (false);
        }

        while (true) {
            if ($debug) echo " - Looping on($wildcard,$candidate,$wPntr,$cPntr) - " . substr($wildcard, $wPntr) . "-" . substr($candidate, $cPntr) . "<br>";

            if (ord(substr($candidate, $cPntr, 1)) == 10) {  // sometimes we see a LF at the end of candidate
                if ($debug) echo " - found a LF and skipping it<br>";
                $cPntr++;
            }

            if ($wPntr == strlen($wildcard) and $cPntr < strlen($candidate)) {
                if ($debug) echo " - FALSE - exhausted wildcard but still have remainder in candidate<br>";
                return (false);
            }
            //   if both-EOF then succeed   // note ABC has strlen 3, but $xPntr can only be 0,1,2 then EOF
            if ($wPntr == strlen($wildcard) and $cPntr == strlen($candidate)) {
                if ($debug) echo " - TRUE - both strings are exhausted<br>";
                return (true);
            }

            switch (substr($wildcard, $wPntr, 1)) {
                    //   if the match is a '?' then advance the match AND the target, loop
                case '?':
                    if ($cPntr == strlen($candidate)) {
                        // we have a ? in the pattern, but have run out of characters in our candidate
                        if ($debug) echo " couldn't match a '?', we either ran out of '$wildcard' or '$candidate'<br>";
                        return (false);
                    }
                    if ($debug) echo " matching a '?', so just advancing both<br>";
                    if ($wPntr < strlen($wildcard))  $wPntr++;
                    if ($cPntr < strlen($candidate)) $cPntr++;
                    break;

                    //   if the match is a '*' then advance the match, and recursively call for every remaining substring in the target, loop
                case '*':
                    if ($debug) echo " - matching a '*', so expect some recursion here...<br>";
                    $wPntr++;
                    $temp = $cPntr;    // don't mess with the real $cPntr, because we have to keep testing in this function
                    if ($wPntr >= strlen($wildcard)) {   // short-circuit for abc*
                        if ($debug) echo " - TRUE - * is last of wildcard<br>";
                        return (true);
                    }
                    while ($temp < strlen($candidate)) {
                        if ($this->compare($wildcard, $candidate, $wPntr, $temp, $debug)) {
                            if ($debug) echo " - TRUE - recursion returned true<br>";
                            return (true);
                        }
                        $temp++;
                    }
                    break;

                    //   if the match is a letter and it matchs, then advance the match AND the target, loop
                default:
                    if ($cPntr == strlen($candidate)) {
                        if ($debug) echo " - FAIL - ran out of candidate while trying to match '", substr($wildcard, $wPntr, 1), "'<br>";
                        return (false);
                    }

                    if (substr($wildcard, $wPntr, 1) === substr($candidate, $cPntr, 1)) {
                        if ($debug) echo " - have matched ", substr($wildcard, $wPntr, 1), ' to ', substr($candidate, $cPntr, 1), ' - GOOD<br>';
                        if ($wPntr < strlen($wildcard))  $wPntr++;
                        if ($cPntr < strlen($candidate)) $cPntr++;
                    } else {
                        if ($debug) echo " - FAIL - cannot match ", substr($wildcard, $wPntr, 1), ' to ', substr($candidate, $cPntr, 1), '<br>';
                        return (false);
                    }
                    break;
            }
        }
        echo "ERROR - should never get here<br>";
        return (false);  // default
    }


    // this function is called by WordArt
    function word2Phone($word)
    {         // returns a phoneString or false if failure

        // first, a very small list of exceptions
        if ($word == 'here')
            return '<[h^h].[e_e^ee]+[r^r]>';
        if ($word == 'mere')
            return '<[m^mh^h].[e_e^ee]+[r^r]>';
        if ($word == 'tremble')        // the 'b' disappears ?!?
            return '<[t^t].[r^r].[e^eh].[m^m]/[b^b][-le^eh+l]>';
        if ($word == 'course')        //
            return '<[c^k].[our^or].[se^s]>';
        if ($word == 'have')        //
            return '<[h^h].[a^ah].[ve^v]>';
        if ($word == 'live')        // live your life, not live-bait
            return '<[l^l].[i^ih].[ve^v]>';
        if ($word == 'hour')
            return '<[h^].[our^our]>';
        if ($word == 'oxygen')
            return '<[o^aw].[x^s].[y^ih].[g^g].[e^eh].[n^n]>';
        if ($word == 'exist')
            return '<[e^eh].[x^s].[i^ih].[s^s].[t^t]>';
        if ($word == 'particle')
            return '<[p^p].[ar^ar].[t^t].[i^ih].[c^k][-le^eh+l]>';
        if ($word == 'around')
            return '<[a^a].[r^r].[ou^ou].[n^n].[d^d]>';


        if (!empty($word)) {
            // $word = strtolower($word);
            $word = preg_replace('/[^a-z]+/i', '', strtolower($word));


            if (isset($this->dictionary[$word])) {
                $aValues = unserialize($this->dictionary[$word]);
                // $this->dictionary[word] = array(phones,p2s,stress,part,failphone,failspell,debug);
                if (empty($aValues[DICT_FAILPHONE])) {    // if failphone is empty, then we were able to translate
                    return ($aValues[DICT_SPELLING]);
                }
            }
        }
        // we don't have anything, but we always return a good phone...
        return ("\[$word;*]");
    }


    /*function testWordisValid(){
        if(!$this->shortDict){    // may not be in dict if we are using shortDict
            assert($this->wordIsValid('hello'),'Should be OK');
            assert(!$this->wordIsValid('helloxxx'),'Should NOT be OK');
            assert($this->wordIsValid('looking'),'Has an affix');
        }
        return(true);
    }
    */

    function wordIsValid($word)
    {    // returns true or false if word is decoded


        if (strlen($word) < 2)  // don't bother with alphabet letters
            return true;

        $retVal = false;    // assume the worst

        if (!empty($word)) {
            $word = preg_replace('/[^a-z]+/i', '', strtolower($word));
            // $word = strtolower($word);

            if (isset($this->dictionary[$word])) {
                $aValues = unserialize($this->dictionary[$word]);
                if (empty($aValues[DICT_FAILPHONE])) {    // if failphone is empty, then we were able to translate
                    $retVal = true;
                }
            }
        }
        return ($retVal);
    }


    // this function is called by PHONICS and BLENDING

    function festivalVerify($wordList)
    {


        $pdbqWords = ',bap,dap,pab,bap,bip,pib,pid,bod,dob,dod,dop,pob,bup,dup,pud,beb,bep,ded,dep,peb,ped';

        $ignore  = 'one,their,what';


        // this part is recursive - if $wordList is an array, then feed every line back
        if (is_array($wordList)) {
            foreach ($wordList as $list) {
                $this->festivalVerify($list);
            }
        }

        // no more arrays
        if (is_string($wordList)) {
            foreach (explode(',', $wordList) as $word) {

                if (strpos($word, '/') > 0) {    // check for can/cad  pairs
                    foreach (explode('/', $word) as $subword) {
                        assert($this->wordIsValid(ltrim($subword)), "Invalid '$subword' in $wordList, rejected by dictionary");
                    }
                } else {
                    if (strpos($pdbqWords, $word) === false) {
                        if (strpos($ignore, $word) === false)    // skip the pbdq and ignore words

                            assert($this->wordIsValid(ltrim($word)), "Invalid '$word' in $wordList");
                    }
                }
            }
        }
    }







    // each call returns one line of CMU dictionary. returns empty string on last.  $n>0 restricts to first n records
    function CMUfgets(int $n = 0): string
    {

        if (empty($this->fileHandle)) {    // only happens the first time we are called

            $dictFile = 'source/cmudict-0.4.out';
            $this->fileHandle = fopen($dictFile, 'r');
            $_SESSION['count'] = $n;
        }

        if (($_SESSION['count']-- == 0) or ($entry = fgets($this->fileHandle)) === false) {   // close up if we get to the end
            fclose($this->fileHandle);
            $this->fileHandle = '';
            return false;
        }
        return ($entry);
    }



    function testgenerateDictionary()
    {
        $this->generateDictionary();
        return true;
    }


    // creates the dictionary if needed, then loads it
    function generateDictionary($n = 0)
    {

        $startTime = time();

        while ($candidate = $this->CMUfgets($n)) {
            $aPhones = $this->parseEntry($candidate);  // $word, $part, $phones and $stress    are loaded
            // printNice($aPhones);


            $p2s = $this->phones2spelling($this->word, $this->phones, $this->stress);  // now have phones->spelling link
            // note: $p2s may be false if we can't convert
            // printNice($p2s, $candidate);

            // fixup for SCHWA and other stuff
            $p2s = $this->phones2spellingFixup($p2s, $this->word, $this->phones, $this->stress);
            // printNice($p2s);


            // write out

            if (empty($p2s['failPhone'])) {
                // just the spelling PLUS an ! if stress on the last syllable
                $result = $p2s['spelling'];
                if (strlen($p2s['stress']) > 1 and substr($p2s['stress'], -1, 1) == '1')
                    $result .= '!';

                $this->dictionary[$p2s['word']] = $result;
                // echo $p2s['word'] . '->' . $this->dictionary[$p2s['word']], '<br>';
            } else {
                $this->dictionaryFailed[$p2s['word']] = $p2s['phones'] . ' / ' . $p2s['spelling'] . ' / failed phone:' . $p2s['failPhone'] . ' / failed spelling:' . $p2s['failSpell'];
            }
        }

        // all done, let's put it out there

        $count = count($this->dictionary);

        $cwd = getcwd();
        $prefix = "<?" . "php\n\n";
        $prefix .= "// This heavily modified deriative was created mechanically from the Festival spelling dictionary\n";
        $prefix .= "// on " . date("F j, Y, g:i a") . "\n\n";
        $prefix .= "// records: $count\n";

        $prefix .= "// '!' at end of record means stress is on the last syllable.\n\n";

        $prefix .= "// https://www.cstr.ed.ac.uk/projects/festival/manual/festival_toc.html \n";
        $prefix .= "// \n";
        $prefix .= "//        The Festival Speech Synthesis System: version 1.4. 0 \n";
        $prefix .= "//        Centre for Speech Technology Research \n";
        $prefix .= "//             University of Edinburgh, UK \n";
        $prefix .= "//              Copyright (c) 1996-1999 \n";
        $prefix .= "//                All Rights Reserved. \n";
        $prefix .= "// \n";
        $prefix .= "// Festival was released under the permissive X11 license.  Much appreciated!\n\n";

        $prefix .= 'global $spellingDictionary;' . "\n";
        $prefix .= '$spellingDictionary = ';
        $postfix = ";\n";


        file_put_contents(
            $cwd . '/source/dictionary.php',
            $prefix . var_export($this->dictionary, true) . $postfix
        );
        file_put_contents(
            $cwd . '/source//dictionaryFailed.php',
            var_export($this->dictionaryFailed, true)
        );

        $elapsed = time() - $startTime;
        printNice("Generated dictionary in $elapsed seconds with $count words");
    }



    /*
        // phase II - fix up the prefixes
        foreach ($morphoWordList as $serializedMorphoWord) {
            $aValues = unserialize($serializedMorphoWord);
            $morphoWord = $aValues[DICT_ENTRY];

            $prefixes = explode('<', $morphoWord);       // get list of prefixes
            $temp = array_pop($prefixes);                   // last value is root(s) plus suffixes
            $suffixes = explode('>', $temp);             // get list of suffixes
            $temp = array_shift($suffixes);                 // first value is root(s)
            $roots = explode('-', $temp);                    // may be two roots (like cat-house)

            // if ($this->explainPostfix) $debug .= "exploded <b>$morphoWord</b>: PREFIX:[" . implode('{{', $prefixes) . '] ' .
            //     'ROOT:[:' . implode('--', $roots)   . '] ' .
            //     'POSTFIX:[' . implode('}}', $suffixes) . ']<br>';

            $spelling = '';
            foreach ($prefixes as $prefix) {
                if (!empty($spelling)) $spelling .= '/';
                $spelling .= "[$prefix;$]";
            }
            foreach ($roots as $root) {

                // there are a number of transformations that we might try on the root
                $newRoot   = '';    // empty
                $dString = "did not find a transform for '$root'";

                // try the natural root
                if (isset($this->dictionary[$root])) {
                    $newRoot   = $root;
                    $dString = "natural root '$root' in dictionary";

                    // drop a silent 'e' - abate for abat>ing
                } elseif (isset($this->dictionary[$root . 'e'])) {
                    $newRoot = $root . 'e';
                    $dString = "added an 'e' to find '$newRoot'";

                    // abhor for abhorr>ed  (try trimming the last letter)
                } elseif (isset($this->dictionary[substr($root, 0, strlen($root) - 1)])) {
                    $newRoot = substr($root, 0, strlen($root) - 1);
                    $dString = "trimmed the last letter to find '$newRoot'";

                    // accessory / for accessor>ies
                } elseif (isset($this->dictionary[$root . 'y'])) {
                    $newRoot = $root . 'y';
                    $dString = "added a 'y' to the root to find '$newRoot'";

                    //ac<compani>ed  has root company
                } elseif (substr($root, -1) == 'i') {
                    if (isset($this->dictionary[substr($root, 0, -1) . 'y']))
                        $newRoot = substr($root, 0, -1) . 'y';
                    $dString = "modified the final 'i' to a 'y' to find '$newRoot'";

                    // ..er becomes ..r - minister => ad<ministr>ate
                } elseif (substr($root, -1) == 'r') {
                    if (isset($this->dictionary[substr($root, 0, -1) . 'er'])) {
                        $newRoot = substr($root, 0, -1) . 'er';
                        $dString = "converted 'r' to 'er' to find '$newRoot'";
                    }
                } //end if the if-elsifsss

                if ($this->explainPostfix) $debug .= "Morpheme: $dString";

                //    // test for a specific word we are interested in
                //if(strpos($morphoWord,'minist') !== false){
                //    echo htmlentities($morphoWord). ' '. $dString . '<br>';
                //}


                $cleanRoot = strtolower(
                    str_replace(
                        '>',
                        '',
                        str_replace(
                            '<',
                            '',
                            str_replace('-', '', $morphoWord)
                        )
                    )
                );  // remove < > - (morpheme markers)


                if (!empty($newRoot)) {
                    if ($this->explainPostfix) $debug .= "root is '$newRoot'<br>";
                    $dictEntry = unserialize($this->dictionary[$newRoot]);
                    if (!empty($spelling))     // add root to prefix
                        $spelling .= '/';
                    $spelling .= $dictEntry[DICT_SPELLING];  // plug in the phones

                } else {
                    if (!$this->shortDict) {
                        $debug .= "Missing dictionary root $root for " . htmlentities($morphoWord) . "<br>";
                    }
                    $spelling = $aValues[DICT_SPELLING];    // default to parsed value
                    // which wipes out the spelling provided by the prefixes
                    $suffixes = array();   // wipe out the suffixes

                    //$debug .= $aValues[DICT_ENTRY],' ',$aValues[DICT_PHONES],' spelling:',$aValues[DICT_SPELLING],' original:',$morphoWord,' fail:',$aValues[DICT_FAILPHONE],'<br> ';
                }
            }
            foreach ($suffixes as $suffix) {
                $spelling .= '/';
                $spelling .= "[$suffix;$]";
            }
            if ($this->explainPostfix) $debug .= "resulting spelling = $spelling<br>";

            // write out the root words
            $aValues[DICT_SPELLING] = $spelling;                      // update the serialized array
            $this->dictionary[$cleanRoot] = serialize($aValues);       // and add it to the dictionary

            if ($this->explainPostfix) $debug .= "and writing '$cleanRoot' with spelling: $spelling<br><br>";
        }

        printNice($aValues);
    }
    */


    function multiSyllableSearch(array $vowels, $testMax = -1)
    {
        require_once('source/dictionary.php');

        $HTML = '';
        $shortVowels = ['a' => '[a^ah]', 'e' => '[e^eh]', 'i' => '[i^ih]', 'o' => '[o^aw]', 'u' => 'u^uh]'];

        $wa = new wordArtAbstract;

        global $spellingDictionary;
        foreach ($spellingDictionary as $word => $spelling) {
            if ($testMax-- == 0)
                break;

            if (!str_contains($spelling, '/'))   // don't bother with single-syllable words
                continue;

            $phones = str_replace('/', '.', $spelling);       // remove the syllable marks
            $phones = str_replace('!', '', $phones);          // remove the stress mark

            $aPhones = explode('.', $phones);
            $fail = false;

            foreach ($aPhones as $s => $phone) {

                // $spelling = $wa->phoneSpelling($phone, false);
                $sound = $wa->phoneSound($phone);
                if (!$wa->is_consonant($sound)) {   // we only look at vowels

                    if (!in_array($phone, $vowels)) {
                        $fail = true;
                        break;  // not interested in this vowel
                    }

                    // ok, this is a vowel we look at
                }
            }

            if ($fail) {   // keep looking
                continue;
            }


            $HTML .= "$word  $spelling<br>";
            // printNice("$word $spelling $sound", $word);
        }
        return $HTML;
    }
}
