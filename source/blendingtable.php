<?php


//
//$text= "Practice consonants with the <sound>$sound</sound> sound (as in '$keyword'), focusing on
//        careful pronunciation.<br><br>
//        Stress the <sound>$sound</sound> sound more than you usually would.";
//
//$sideNote1 = "Reinforce that we are learning the '$letter' spelling of $Hsound.<br><br>
//            Always refer to letters
//            by their sound, unless you specifically mean the alphabet symbol.<br><br>
//            Require 100% accuracy.  Errors indicate guessing or using
//            memorized words.";
//
//$sideNote2 = "To practice blending, read across the page.  Then try
//                reading words down the page.";
//
//
//$writeText = "Practice writing these words.  Turn the screen away, and pronounce words over-accentuating the first
//                and last sounds.<br><br>
//                Watch out, the letter '$letter' does not always make an <sound>$sound</sound> sound.
//                $avoid<br><br>
//                Warn your student that this is a blending and segmenting exercise, not a rule.";
//




static $clusterWords =[];       //

// this is a SINGLETON
class BlendingTable
{

    private static $instance = null;

    // this class has stuff used in creating a script, but not for running it.

    var $currentLesson;
    var $scriptsClass;
    var $scripts;
    var $group      = '';     // subtitles

    var $current_script  = 'unknown';       // helps loading a script

    var $systemStuff;


    public $stuffToReview = array(); // used for generating the reviews
    public $Nreview = 0;

    // TODO: replace $this->clusterWords with $clusterWords;
    public $clusterWords = array();

    public $words = [];     // eg:   "bat" => "fat,cat,hat,sat,mat,pat,bat,rat,vat",
    public $CVC = [];   // bigger list of words
    public $oddEndings = [];   // "dge"=>  "dodge, fridge, sludge"




    public $bdp = false; // includ b-d-p exercises ??
    public $bdpText = "This lesson just works on the letters 'b', 'p', and 'd'.<br><br><span style='background-color:yellow;'>Don't spend much
                                time on this lesson-and-test</span>, even if your student struggles.  This drill might be really hard
                                or even impossible until your student's brain reshapes to accommodate new
                                reading skills.<br><br>
                                All emerging readers struggle with 'b', 'p', and 'd', and then it goes away.  This
                                is a discrimination exercise rather than a blending lesson.  It's not important.<br><br>
                                Try it for a minute, then move on.";



    function __construct()
    {
        global $clusterWords;
        if(empty($clusterWords)){
            // this is expensive, so check if the static version is available first
            $this->loadClusterWords();
            $clusterWords = $this->clusterWords;
        }else{
            $this->clusterWords = $clusterWords;
        }

    }




    // this is the list of words that must be memorized
    function memorize_words(): string
    {
        return ('I,you,our,the,was,so,to,no,do,of,too,one,two,he,she,be,are,said,their');
    }


    /*
    function testsomething(){
        // $this->loadClusterWords();
        printNice($this->clusterWords);
        return true;
    }


    function groupTitle($sound)
    {
        $convert = array(
            'ah' => '/ah/ as in Apple',
            'ar' => '/ar/ as in Car',
            'air' => '/air/ as in Hair',
            'aw' => '/aw/ as in Saw',
            'ay' => '/ay/ as in Say',

            'ih' => '/ih/ as in Igloo',
            'igh' => '/igh/ as in High',

            'oh' => '/h/ as in Slow',
            'oo' => '/oo/ as in Book',
            'oy' => '/oy/ as in Boy',
            'ow' => '/ow/ as in Cow',

            'uh' => '/uh/ as in Umbrella',
            'ue' => '/ue/ as in True',

            'eh' => '/eh/ as in Elephant',
            'ee' => '/ee/ as in Free',
            'er' => '/er/ as in Her'
        );

        assertTRUE(isset($convert[$sound]), "Did not find '$sound' in conversion table");
        return ($convert[$sound]);
    }

    function newLesson($script, $lessonName, $prerequisites = array())
    {
        $newLesson = new lesson_prototype($lessonName, $prerequisites);
        $newLesson->tabName = $newLesson->script = $script;
        $newLesson->group   = $this->group;
        $newLesson->lessonKey     = $script . '.' . $lessonName;
        // set up identifiers

        // Only alphanumerics [0-9a-zA-Z], the special characters $-_.+!*'(),
        // may be used unencoded within a URL.  (plus some reserved chars)
        assertTRUE(!isset($this->scriptsClass->lessons[$script][$lessonName]), "Seem to have a duplicate lesson '$lessonName'");

        $scripts = ScriptManager::singleton();
        $scripts->lessons[$newLesson->lessonKey] = $this->currentLesson = $newLesson;        // keep a copy of lesson object
        return ($newLesson);
    }




    // the caller looks for this method...
    public function load()
    {

        if (false) { // test lessons
            // test lesson
            // because we assemble some values at runtime, we can't just define
            // these elements of clusterWords.   But we treat them the same
            // when it comes to rendering them.

            if ($GLOBALS['debugON']) { // lessons should never appear in production
                // but MIGHT if we compile the lessons with debug on

                $lesson = $this->newLesson(__class__, "Affix Test");
                $lesson->group = "Test";

                $words = array("con,de,in,ob", "struct", "ive,ure,ed", "ed");
                $words = array("con,de,in,ob", "spire", "ive,ure,ed", "ed");
                $words = array("un,mis", "hap", "y,en,less", "er,est,ly,ness,s,ing,ed");

                $words = array("", "un", "ease,hap", "y", "ly,er,ness");
                $page = $this->addPage('affixSpinner', "1col", 'full', "Affix Spinner", "normal", $words);
            }
        }

        /////////////  instructions   /////////////

        $lesson = $this->newLesson(__class__, 'Instructions 1');
        $lesson->group = 'Instructions';

        $HTML = '<b>Instructions</b><br><br>
                    Work through each tab.<br><br>
                    THIS page has four tabs at the top
                    (Instructions, Words, Browser, Results),
                    others may have four or five.
                    Click on each one in turn.  To proceed, click on \'Words\' now.<br><br>

                    <img src="./images/assess1.jpg" width="500" />';

        $page = $this->addPage('instructionPage', '', '', "Instructions", $HTML);

        $HTML = 'Usually read words from top to bottom.  If there is a
                    contrast then read across to practice contrast or top to
                    bottom to practice a single sound.  Use the REFRESH
                    button to scramble.  (Click on \'Browser\' now).<br><br>

                    <img src="./images/blending3.jpg" width="500" />';

        $page = $this->addPage('instructionPage', '', '', "Words", $HTML);

        $HTML = "If you are using a PC (not a tablet), put your
                    browser into 'Full Screen Mode'.  For Windows, press F11.  For Mac using
                    Chrome or Firefox, press CMD + SHIFT + F.  For Safari, click the 'stretch'
                    button at the top right corner.<br><br>

                    Try it now.  The same key(s) will exit Full
                    Screen Mode.<br><br>" .

            '<img src="./images/assess4.jpg" width="600" />';

        $page = $this->addPage('instructionPage', '', '', "Browser", $HTML);

        $HTML = 'The last tab is always a test.  Comments are optional.
                    "Advancing" will try another lesson but
                    eventually return to this one.  "Mastered" tells the system not
                    to show this lesson again.  The test itself is less important than
                    giving feedback to your student.<br><br>
                    Click on "Mastered" now to continue.<br><br>

                   <img src="./images/click_mastered.jpg" width="600" />';

        $page = $this->addPage('instructionPage4', '', '', "Result", $HTML);

        $lesson = $this->newLesson(__class__, 'Instructions 2');
        $lesson->group = 'Instructions';

        $HTML = 'Use the \'Word Spinner\' to interactively create words (including
                    nonsense words).  And use it backwards - CALL OUT a word and ask your
                    student to \'spell\' it for segmenting exercise.
                    Usually we only change one letter at a time.<br />


                    <img src="./images/spinner.jpg" width="500" /><br>';

        $page = $this->addPage('instructionPage', '', '', "Word Spinner", $HTML);

        $HTML = 'The last tab is always a test.  Your student must
                    read the words accurately, smoothly, and confidently
                    in less than 10 seconds.  Accuracy is most important.
                    <br><br>
                    Skip directly to Test if your child finds an exercise easy.
                        Race through materials they know, and spend time where they struggle.
                    <br><br>



                    <img src="./images/test.jpg" width="500" /><br>';

        //   function addPage($displayType, $layout, $style, $tabname, $dataparm, $data=array(), $note=''){

        $page = $this->addPage('instructionPage', '', '', "Tests", $HTML);

        $HTML = 'The \'Navigation\' button at the top lets you move to any lesson, and
                    the software will take care of remembering where you left off last lesson.<br><br>
                    OK, that\'s about all you need to know.  15-20 minutes per day, and
                    try not to skip any days.   Hit the \'Mastered\' button on the
                    right to make these instructions go away and start the training.
                    <br><br>

                    <img src="./images/everyday.jpg" width="500" /><br>';

        $page = $this->addPage('instructionPage4', '', '', "Ready to Start", $HTML);

        /////////////  the lessons   //////////////

        $this->loadClusterWords();

        // http://www.allaboutlearningpress.com/how-to-teach-closed-and-open-syllables

        // consonant clusters
        foreach ($this->clusterWords as $key => $value) {
            $this->clusters($key, $value);
        }
    }
*/
    public $CVCe = array(
        "CaCe" => "rate,cane,bane,rate,hate,mate,wade,tame,tape,fade,tape,made,pane,rage,vane,
                            bake,bale,bane,cage,cake,came,dame,daze,date,fade,fame,fate,
                            gale,game,gate,haze,jade,kale,lake,late,male,mane,maze,page,pave,
                            rake,rave,safe,sale,same,save",
        "CCaCe" => "blade,blame,brake,brave,crate,craze,flame,frame,glade,glaze,grate,grave,graze,
                            place,plane,plate,scale,scrape,shale,stale,shade,shake,shame,slate,slave,snake,spade,
                            stage,state,strafe,trade,whale",

        // extra like for 'would you, could you...'
        // remove dice,lice,mice,nice,rice,vice

        "CiCe" => "like,like,like,like,like,
                        bide,bike,bile,bite,dike,dime,dine,dire,dive,fife,file,
                        fine,fire,five,hide,hike,hire,hive,jive,kite,life,like,lime,line,
                        lite,Mike,mile,mime,mine,mire,mite,nice,nine,pike,pine,
                        pipe,ride,rile,ripe,side,sine,site,size,tide,tile,
                        time,tire,vibe,vile,wide,wife,wine,wipe,wire,wise",
        // remove slice,spice,thrice
        "CCiCe" => "bribe,bride,brine,chide,chime,chive,drive,glide,gripe,pride,prize,
                        shine,shire,shine,slide,slime,smile,smite,snide,snipe,
                        spike,spine,spire,spite,stile,tribe,trike,tripe,trite,twine,whine,
                        white,write,shrine,sprite,stride,strife,strike,stripe,strive,
                        thrive",

        // not 'come', it is irregular
        "CoCe" => "bode,bone,bore,code,coke,cone,core,cove,dole,dope,dose,dote,
                        doze,fore,gore,hole,home,hone,hope,hose,hove,joke,lobe,lode,lope,
                        lore,mode,mole,mope,more,mote,node,nope,nose,note,poke,pole,pope,
                        pore,pose,robe,rode,role,rope,rose,rote,rove,sole,sore,tone,
                        tote,vole,vote,woke,wore,wove,yoke,zone",
        "CCoCe" => "broke,choke,chore,chose,clone,close,clove,crone,drone,drove,froze,
                        glove,grope,phone,probe,prone,scone,scope,score,shone,shore,slope,
                        smoke,stole,stone,store,swore,shole,wrote,chrome,throne,stroke,
                        strode,strobe",

        /* missing CuCe */
        "CCuCe" => "cube,cure,cute,dude,duke,dune,dupe,fume,fuse,huge,jute,lube,luge,lure,
                        lute,mule,muse,mute,nude,nuke,puke,pure,rube,rude,rule,rune,ruse,
                        sure,tube,tune,brute,chute,crude,fluke,flute,plume,prude,prune,truce",

        /* missing CeCe  */
        "CCeCe" => "cede,gene,mete,grebe,plebe,scene,swede,theme,these,scheme",
    );

    //TODO: strip the arrays out of this
    public $vowels = array(
        'ah' => array(),
        'aw0' => 'caw,haw,jaw,law,maw,paw,raw,saw,yaw',
        'aw1' => 'bawd,brawl,brawn,caw,chaw,claw,craw,crawl,draw,drawl,drawn,
                                        fawn,gnaw,lawn,pawn,prawn,
                                        shawl,thaw,yawn',
        'all' => 'all,ball,call,fall,gall,hall,mall,pall,tall,wall',
        'alk' => 'balk,talk,walk,halt,malt,salt',

        'ay0' => 'bay,day,gay,hay,jay,lay,may,nay,pay,ray,say,way',
        'ay1' => 'away,bray,clay,dray,flay,fray,gray,okay,play,pray,slay,stay,sway,tray,spray,stray',

        "th" => "bath,goth,hath,math,moth,path,pith,with,than,that,them,then,thin,this,thud,thug,thus",
        //basic sh
        "sh" => "bash,cash,dash,dish,fish,gash,gosh,gush,hash,hush,josh,lash,lush,mash,mesh,mush,
                nosh,posh,rash,rush,sash,wish",
        // two letter beginnings
        "sh2" => "blush,brash,brush,clash,crash,crush,flash,flesh,flush,fresh,plush,trash,slash,slosh,slush,smash,stash",
        // two letter endinges
        "sh3" => "shack,shaft,shank,shelf,shell,shift,ships,shock,shops,shots,shred,shrub,shrug,
                shuck,shunt,shush,shuts,sham,shed,shin,ship,shod,shop,shot,shun,shut",
        // exceptions: bush, push



        'air' => array(),
        'ar' => array(),

        'ear' => 'dear,fear,gear,hear,near,rear,sear',

        'ih' => array(
            'bin',
            'myth'
        ),

        'igh' => array(
            'kite',
            'cried',
            'wild',
            'night',
            'fly',
            'height'
        ),

        "oh" => array(
            'most,corn,chord,cold,forth,hold,mold,pork,fork',
            'note,bone,code,mole,poke,role,stone,sore,pore,core,lore,more,tore,bore,snore,vote',
            'float,bloat,boast,hoax,goat,loan,loaf,boat,road,roast,foam,soar,soap',
            'glow,grow,blow,flow,crow,low,know,slow,row,show,tow,own,snow,know',
            'though,toe,foe,soul,door,floor,pour,court,four,fourth'
        ),

        "ow" => array(
            'howl,cow,how,prow,now,wow,chow,fowl,jowl,prowl,town',
            'out,loud,proud,round,grout,foul',
            'sour,dour,flour,scour'
        ), // hour is an exception

        "oh/ow" => array('hoax/how,know/now,crow/cow,row/prow,four/flour,flow/flower,
                                 flow/fowl,soul/sour,know/now,show/chow,tow/town',),

        "oy" => array(
            'boy,toy,soy,coy,joy,ploy,royal,alloy,loyal,enjoy',
            'oil,boil,coil,oink,roil,soil,toil,foil,broil,foist,void,point'
        ),

        "oh+ow/ow" => array('tow/toy,cow/coy,show/soy,jowl/joy,chow/choy,boat/boil,
                               vote/void,sole/soil,coal/coil,soul/soil',),

        "oo" => array(
            'book',
            'put',
            'could,would,should'
        ),

        'uh' => array(
            'tub',
            'touch',
            'some'
        ),

        "ue" => array(
            'soon',
            'glue',
            'new',
            'tune',

            'super',
            'soup',
            'fruit',
            'do',
            'shoe'
        ),

        'eh' => array(),
        'ee' => array('bee,eel,fee,Lee,pee,see,tee,wee,beef,been,beep,beer,bees,beet,deed,deem,deep,
                    feed,feel,feet,geek,heed,heel,jeep,jeer,keel,keen,keep,leek,meek,meet,
                    need,peek,peel,peep,reed,reef,reek,reel,seed,seek,seem,seen,seep,seer,
                    teen,weed,week,weep'),
        'er' => array(),

    );
    public function contrastTitle($first, $second, $s1, $s2)
    {
        $title = "Contrast '$s1' /$first/ and '$s2' /$second/";
        return ($title);
    }

    /*

    public function clusters($desc, $words)
    {

        $lesson = $this->newLesson(__class__, $desc);
        $lesson->group = $words['group'];

        if (isset($words["showTiles"])) {
            $lesson->showTiles = true;
        }

        if (isset($words['words']))   // decodable text don't have 'words'
            $wordList = $words['words'];
        else
            $wordList = '';

        // test the wordLists
        // test the word lists
        if (isset($words['review']) and $words['review']) {

            $festival = festival::singleton();
            if (isset($words['words'])) {
                $festival->festivalVerify($words['words']);
            }

            if (isset($words['words2'])) {
                $festival->festivalVerify($words['words2']);
            }

            if (isset($words['stretch'])) {
                $festival->festivalVerify($words['stretch']);
            }

            if (isset($words['stretch2'])) {
                $festival->festivalVerify($words['stretch2']);
            }

            if (isset($words['2syl'])) {
                $festival->festivalVerify($words['2syl']);
            }
        }
        $text = "";
        $sideNote1 = "";
        $sideNote2 = "";

        //$writeText = "Practice writing these words.  Turn the screen away, and pronounce words over-accentuating the first
        //                and last sounds.<br><br>
        //                Watch out, the letter '$letter' does not always make an <sound>$sound</sound> sound.
        //                $avoid<br><br>
        //                Warn your student that this is a blending and segmenting exercise, not a rule.";

        //$page = $this->addPage('instructionPage','',    '',   "Intro",   '',     $text, $sideNote);

        // DEBUG DEBUG
        //        if(isset($words['words']))   // harder words
        //            $page = $this->addPage('wordList',    "3col",  'full',   "Debug",         "scramble",   $words['words']);
        //        if(isset($words['words2']))   // harder words
        //            $page = $this->addPage('wordList',    "3col",  'full',   "Debug2",         "scramble",   $words['words2']);
        // DEBUG DEBUG

        $style = '';
        if (isset($words['style'])) {
            $style = $words['style'];
        }

        if (isset($words['sidenote'])) {
            $sideNote1 = $words['sidenote'];
        }

        switch ($style) {

            case 'lecture': //   (two instruction pages and a wordlistComplete)
                $page = $this->addPage('instructionPage', '', '', "Page 1", $words['text']);
                if (isset($words['text2'])) {
                    $page = $this->addPage('instructionPage', '', '', "Page 2", $words['text2']);
                }
                if (isset($words['text3'])) {
                    $page = $this->addPage('instructionPage', '', '', "Page 3", $words['text3']);
                }
                if (isset($words['text4'])) {
                    $page = $this->addPage('instructionPage', '', '', "Page 4", $words['text4']);
                }

                if (isset($words['words'])) {
                    $local = str_replace('+', '&nbsp;+&nbsp;', $words['words']);
                    $page = $this->addPage('wordListComplete', "1col", 'full', "Words", "normal", $local, $sideNote1);
                } else {
                    assertTRUE(false, 'missing wordlist');
                }


                if (isset($words['sidenote2'])) {
                    $sideNote2 = $words['sidenote2'];
                }

                if (isset($words['words2'])) {
                    $local = str_replace('+', '&nbsp;+&nbsp;', $words['words2']);
                    $page = $this->addPage('wordListComplete', "1col", 'full', "Words2", "normal", $local, $sideNote2);
                }

                break;

            case 'decodable':
                //addPage($displayType, $layout, $style, $tabname, $dataparm, $data=array(), $note=''){

                if (!isset($words['credit']))  $words['credit'] = '';
                $colour = 'colour';


                $format = serialize(['colour', [], $words['credit']]);  // default is colour, not B/W.  no phonemes are highlighted


                if (!isset($words['image1']))  $words['image1'] = '';
                if (!isset($words['image2']))  $words['image2'] = '';
                if (!isset($words['image3']))  $words['image3'] = '';
                if (!isset($words['image4']))  $words['image4'] = '';
                if (!isset($words['image5']))  $words['image5'] = '';

                // printNice('xxx',$desc)    ;
                // printNice('xxx',$words)    ;
                $last = isset($words['words2']) ? '' : 'last';  // determines whether a 'completed' button is added
                $page = $this->addPage('decodableReader1', $words['image1'], $last, "Page 1", $words['words1'], $format);

                if (isset($words['words2'])) {
                    $last = isset($words['words3']) ? '' : 'last';
                    $page = $this->addPage('decodableReader1', $words['image2'], $last, "Page 2", $words['words2'], $format);
                }
                if (isset($words['words3'])) {
                    $last = isset($words['words4']) ? '' : 'last';
                    $page = $this->addPage('decodableReader1', $words['image3'], $last, "Page 3", $words['words3'], $format);
                }
                if (isset($words['words4'])) {
                    $last = isset($words['words5']) ? '' : 'last';
                    $page = $this->addPage('decodableReader1', $words['image4'], $last, "Page 4", $words['words4'], $format);
                }
                if (isset($words['words5'])) {
                    $last = 'last'; // of course it is
                    $page = $this->addPage('decodableReader1', $words['image5'], $last, "Page 5", $words['words5'], $format);
                }

                break;


            default: // some basic wordlists
                // layout    style     tabName          dataParm     data

                // layout    style     tabName          dataParm     data
                if (isset($words['instruction'])) {
                    $page = $this->addPage('instructionPage', '', '', "Intro", $words['instruction']);
                }
                if (isset($words['instruction2'])) {
                    $page = $this->addPage('instructionPage', '', '', "Intro2", $words['instruction2']);
                }


                if (isset($words['pronounce'])) {
                    $page = $this->addPage('pronounce', '', '', "Pronounce", $words['pronounce']);
                }

                if (isset($words['contrast'])) {
                    $page = $this->addPage('contrast', '', '', "Contrast", $words['contrast']);
                }

                if (isset($words['stretch'])) {
                    $sideNote = "Read across for contrasts, or down for vowel review. Require clear pronunciation.";
                    if (isset($words['stretchText'])) {
                        $sideNote = $words['stretchText'];
                    }

                    $page = $this->addPage('wordList', "1col", 'full', "Stretch", "normal", $words['stretch'], $sideNote);
                }

                if (isset($words['stretch2'])) {
                    $sideNote = "Read across for contrasts, or down for vowel review. Require clear pronunciation.";
                    $page = $this->addPage('wordList', "1col", 'none', "Stretch", "normal", $words['stretch2'], $sideNote);
                }


                if (!isset($words['review'])) { // don't show wordart if advanced lesson
                    $page = $this->addPage('wordList', "1col", 'full', "Words", "normal", $wordList, $sideNote1);

                    if (is_array($wordList)) // append wordList to review array
                    {
                        $this->stuffToReview = array_merge($this->stuffToReview, $wordList);
                    } else {
                        $this->stuffToReview[] = $wordList;
                    }
                }

                if (isset($words['simpleScramble'])) {
                    $page = $this->addPage('wordList', "3col", 'simple', "Simple", "scramble", $wordList);
                }

                $scrambleSideText = '';
                if (isset($words['scrambleSideText'])) {
                    printNice('words', $words);

                    $scrambleSideText = $words['scrambleSideText'];
                }
                $page = $this->addPage('wordList', "3col", 'none', "Scramble", "scramble", $wordList, $scrambleSideText);

                if (isset($words['words2'])) { // harder words
                    $wordList = $words['words2']; // the test will be with harder words
                    $page = $this->addPage('wordList', "3col", 'none', "Harder", "scramble", $wordList);

                    if (!isset($words['review'])) { // don't show wordart if advanced lesson
                        if (is_array($wordList)) // append wordList to review array
                        {
                            $this->stuffToReview = array_merge($this->stuffToReview, $wordList);
                        } else {
                            $this->stuffToReview[] = $wordList;
                        }
                    }
                }

                if (isset($words['words3'])) { // harder words
                    $wordList = $words['words3']; // the test will be with harder words
                    $page = $this->addPage('wordList', "3col", 'none', "Harder+", "scramble", $wordList);
                }

                if (isset($words['words4'])) { // harder words
                    $wordList = $words['words4']; // the test will be with harder words
                    $page = $this->addPage('wordList', "3col", 'none', "Hardest", "scramble", $wordList);
                }

                if (isset($words['decodable'])) {
                    if (!isset($words['image']))
                        $words['image'] = '';
                    $format = serialize(['colour', []]);  // default is colour, not B/W.  no phonemes are highlighted
                    $page = $this->addPage('decodableReader1', $words['image'], '', "Decodable", $words['decodable'], $format);
                }


                // spinner
                if (!empty($words['spinner'])) {
                    $page = $this->addPage('wordSpinner', "1col", 'full', "Word Spinner", "normal", $words['spinner']);
                }

                if (!empty($words['spinnerE'])) {
                    $page = $this->addPage('wordSpinner', "1col", 'full', "Word Spinner", "E", $words['spinnerE']);
                    $page->plusE = true;
                }

                if (isset($words["2syl"])) {
                    $page = $this->addPage('wordList', "2col", 'simple', "2 Syllable", "scramble", $words["2syl"]);
                }


                //        if(isset($words['endings']))
                //            $page = $this->addPage('wordListMatrixTimed',"1col", 'none',   "Test",          "scramble", $wordList, 'ed,ing');
                //        else
                $page = $this->addPage('wordListTimed', "1col", 'none', "Test", "scramble", $wordList);

                if (!empty($words['Nreview'])) {

                    /////////////////////////////////
                    // second page for review !!
                    /////////////////////////////////

                    if (count($this->stuffToReview) > 3) { // don't start review until 3 strings

                        // we know we have at least 3 lessons in $this->stuffToReview

                        $recent = array_slice($this->stuffToReview, -3, 3); // get the last three
                        $this->Nreview += 1;
                        $lesson = $this->newLesson(__class__, "Review {$this->Nreview}");
                        $lesson->group = $words['group'];

                        $page = $this->addPage('wordList', "3col", 'none', "Recent", "scramble", $recent);

                        if (count($this->stuffToReview) > 6) { // don't start review until 3 strings
                            $recent2 = array_slice($this->stuffToReview, -5, 5); // get the last three
                            $page = $this->addPage('wordList', "3col", 'none', "Earlier", "scramble", $recent2);
                        }

                        $page = $this->addPage('wordList', "3col", 'none', "All", "scramble", $this->stuffToReview);

                        $page = $this->addPage('wordListTimed', "1col", 'none', "Test", "scramble", $recent);
                    }
                }
        }
    }
*/
    public function loadClusterWords()
    {
        $vc = new ViewComponents();   // eg: ->sound('th')

        /////////////////////////////////////////////
        ///// FatCatSat clusters
        /////////////////////////////////////////////

        $this->words = array(
            "bat" => "fat,cat,hat,sat,mat,pat,bat,rat,vat",
            "cap" => "cap,gap,lap,map,rap,sap,tap,zap",
            "bag" => "bag,hag,jag,lag,nag,rag,sag,tag,wag",

            "bit" => "bit,fit,hit,kit,mitt,pit,sit,wit,zit",
            "big" => "big,dig,fig,jig,pig,rig,wig,zig",
            "dip" => "dip,hip,jip,lip,nip,pip,rip,sip,zip",

            "cot" => "cot,dot,got,hot,jot,lot,not,pot,rot,tot",
            /*g+p*/ "bog" => "bog,cog,dog,fog,hog,jog,log,cop,fop,hop,lop,pop,top",

            "but" => "but,cut,gut,hut,jut,mutt,nut,putt,rut,tut",
            /*g+p*/ "bug" => "bug,dug,hug,lug,jug,mug,pug,rug,tug,zug",

            "bet" => "bet,get,jet,let,met,net,pet,set,vet,wet",
            /*g+p*/ "beg" => "beg,keg,leg,Meg,peg,pep,rep",

        );

        // $CVC is a much bigger list of words

        $this->CVC = array(
            "CaC" => "bat,bag,bad,bass,
                        dab,dad,dam,Dan,
                        fab,fad,fan,fat,fax,
                        gab,gag,gap,gas,
                        had,ham,hat,has,
                        jab,jag,jam,jazz,
                        lab,lad,lag,lap,lass,
                        mad,man,map,mass,mat,max,
                        nab,nag,nap,
                        pad,Pam,pan,pass,pat,
                        ram,ran,rap,rat,
                        sad,sag,Sam,sat,
                        tab,tag,tam,tan,tap,
                        van,vat,
                        wad,wag,wax,
                        zap",

            "CiC" => "bib,big,bid,Bill,bin,bit,
                     dib,did,dig,dill,din,dim,diss,dip,
                     fib,fin,fig,fill,fit,fix,fizz,
                     gig,gill,
                     hid,hill,him,hip,his,hiss,hit,
                     jib,jig,Jim,jip,
                     kid,kit,kiss,
                     lid,lip,
                     mid,miff,miss,mitt,mix,
                     nip,nit,
                     pill,pin,pit,pig,
                     rib,rid,riff,rig,rim,rip,
                     sid,sill,sin,sip,sis,sit,six,
                     tiff,Tim,till,tin,tip,
                     wig,will,wit,wiz,
                     yip,
                     zig,zip,zit",

            "CoC" => "Bob,bog,boss,bop,box,
                     dog,doff,doll,don,dot,
                     fob,fog,fop,fox,
                     gob,god,got,
                     hog,hop,hot,
                     job,jog,jot,
                     lob,log,lot,loss,
                     mob,mod,mom,moss,mop,
                     nod,non,not,
                     pod,pop,pot,pox,
                     rob,rod,Ron,rot,
                     sob,sod,sop,
                     tom,toss,top,tot",

            // removed gem,gel (g is soft)
            "CeC" => 'bed,bell,Ben,Bess,bet,beg,
                        den,
                        fed,fen,fez,
                        hell,hen,hex,
                        jet,
                        led,leg,let,less,
                        Meg,men,met,mess,
                        Ned,net,
                        pen,peg,pet,
                        set,sell,sex,
                        ten,Tess,tell,
                        vet,
                        web,wed,well,wet',

            "CuC" => "bud,buff,bug,bum,bun,but,butt,bus,buzz,
                     dub,dud,duff,dug,dun,
                     fun,fuss,fuzz,
                     hub,huff,hull,hug,hut,hum,
                     jug,jut,
                     lug,
                     mud,muff,mug,mum,muss,
                     nub,nun,nut,
                     pub,puff,pug,pun,putt,pup,pus,
                     run,ruff,rug,rut,
                     sub,sum,sun,sup,
                     tub,tug,tut,tux,
                     wuss",
        );

        $this->oddEndings = array(
            "dge" => "badge,budge,bridge,binge,
                            dodge,drudge,dredge,
                            edge,
                            fridge,fudge,fringe,
                            grudge,
                            hedge,hinge,
                            judge,
                            lodge,ledge,lunge,
                            Midge,
                            pudge,pledge,plunge,
                            sludge,
                            range,
                            trudge,
                            wedge,whinge",
            "nce" => "dance,dunce,fence,hence,mince,pence,since,vince,wince,
                            chance,glance,prance,trance,stance,quince,prince",

        );

        //
        //$twoVowels = array(
        //    'a/ah' => 'abstract,advance,atlas,backhand,backlash,backpack,balance,ballast,blackjack,cabbage,canal,canvas,crankshaft,damage,draftsman,flashback,flatland,gallant,grandstand,grassland,halfback,handbag,handstand,hatchback,jackal,jackass,madcap,madman,mammal,palace,rampant,ransack,rascal,salad,salvage,sandal,savage,scandal,stagnant,transplant,vandal,vantage',
        //    'i/ih' => 'acid,addict,advil,africa,aladdin,alice,amid,anglican,angling,animal,antacid,
        //                    antic,anvil,applicant,assassin,assistance,assistant,asthmatic,
        //                    atlantic,attrition,avid,ballistic,balsamic,bandit,bandwidth,
        //                    banish,basil,biblical,blacksmith,brandish,brazil,british,cabin,
        //                    cadillac,candid,cannibal,capital,capitan,captive,catfish,
        //                    charismatic,chitchat,christmas,citric,classic,clinical,
        //                    criminal,diffract,digits,diminish,dipstick,discipline,
        //                    dispatch,distance,distill,distraction,district,dramatic,
        //                    fantastic,finish,flimflam,galactic,gambit,gimmick,granite,
        //                    graphical,halifax,hispanic,imbalance,immigrant,impact,impala,
        //                    incision,indignant,infant,inflict,inhabit,initial,inning,instant,
        //                    instill,instinct,invalid,kidnap,latin,lavish,lipstick,magic,
        //                    militant,milkman,milligram,mishap,miscast,misfit,mishap,
        //                    mishmash,mismatch,misprint,misprint,missal,missile,missing,
        //                    napkin,narrative,panic,pigskin,piranha,plastic,practical,
        //                    principal,quicksand,rabbit,radical,radish,rancid,rapid,
        //                    sandwich,signal,silica,spanish,spinach,spirit,traffic,transcript,
        //                    transit,victim,vigilant,village,whiplash,wingspan',
        //    'o/aw' => 'abolish,absolve,accomplish,admonish,adopt,agnostic,albatross,amazon,apricots,atomic,backdrop,bobcat,bombast,chopsticks,cockpit,combat,comical,contact,contradict,cottage,crackpot,dislodge,dolphin,dominant,flintlocks,frolic,gossip,gotham,hitchcock,hodgepodge,holland,hospital,involve,jackpot,laptop,locksmith,logical,marathon,matchbox,nominal,nonprofit,obstinate,octagon,olive,optical,optimist,ottawa,parabolic,phonics,politics,profit,promise,province,robin,sandbox,scotland,shamrock,shoplifting,snapshot,tomcat,tonic,tonsils,tropical,vagabond,volcanic,',
        //    'aw/aw'=> 'backsaw,blackhawk,crawfish,drawback,drawbridge,goshawk,hacksaw,hawkbill,inlaws,jigsaw,lawman,pawnshop,sawhill,scofflaws,tomahawk,withdraw,withdrawal',
        //    'au/aw'=> 'applaud,applause,assault,audit,autistic,fauna,jaundice,saucepan,sauna,trauma'
        //);



        $this->clusterWords["Instructions"] =
            [
                "group" => 'Instructions',
                "pagetype" => 'instruction',
                "instructionpage" => [

                    'Instructions' => "<b>Instructions</b><br><br>
                    Work through each tab.<br><br>
                    THIS page has four tabs at the top
                    (Instructions, Words, Browser, Results),
                    others may have four or five.
                    Click on each one in turn.  To proceed, click on \'Words\' now.<br><br>

                    <img src='./pix/assess1.jpg' width='500' />",


                    'Words' => "Usually read words from top to bottom.  If there is a
                    contrast then read across to practice contrast or top to
                    bottom to practice a single sound.  Use the REFRESH
                    button to scramble.  (Click on \'Browser\' now).<br><br>

                    <img src='./pix/blending3.jpg' width='500' />",

                    'Browser' => "If you are using a PC (not a tablet), put your
                    browser into 'Full Screen Mode'.  For Windows, press F11.  For Mac using
                    Chrome or Firefox, press CMD + SHIFT + F.  For Safari, click the 'stretch'
                    button at the top right corner.<br><br>

                    Try it now.  The same key(s) will exit Full
                    Screen Mode.<br><br>

                    <img src='./pix/assess4.jpg' width='600' />",


                    'Word Spinner' => 'Use the \'Word Spinner\' to interactively create words (including
                        nonsense words).  And use it backwards - CALL OUT a word and ask your
                        student to \'spell\' it for segmenting exercise.
                        Usually we only change one letter at a time.<br />

                        <img src="./pix/spinner.jpg" width="500" /><br>',


                    'Tests' => 'The last tab is always a test.  Your student must
                        read the words accurately, smoothly, and confidently
                        in less than 10 seconds.  Accuracy is most important.
                        <br><br>
                        Skip directly to Test if your child finds an exercise easy.
                            Race through materials they know, and spend time where they struggle.
                        <br><br>

                        <img src="./pix/test.jpg" width="500" /><br>',


                    'Navigation' => 'The \'Navigation\' button at the top lets you move to any lesson, and
                    the software will take care of remembering where you left off last lesson.<br><br>
                    OK, that\'s about all you need to know.  15-20 minutes per day, and
                        try not to skip any days.   Hit the \'Mastered\' button on the
                        right to make these instructions go away and start the training.
                        <br><br>

                        <img src="./pix/everyday.jpg" width="500" /><br>',


                     'Results' => "The last tab is always a test.  Comments are optional.
                     'Advancing' will try another lesson but
                     eventually return to this one.  'Mastered' tells the system not
                     to show this lesson again.  The test itself is less important than
                     giving feedback to your student.<br><br>
                     Click on 'Mastered' now to continue.<br><br>

                    <img src='./pix/click_mastered.jpg' width='600' />",
                ]
            ];




        $this->clusterWords["Fat Cat Sat"] =
            array(
                "group" => 'Fat Cat Sat',
                "pronounce" => "ah",
                "pronounceSideText" => "We are starting the vowel " . $vc->sound('ah') . "as in Bat.<br><br>
                                 Practice pronouncing it. Make shapes with your mouth, exaggerate, play with it.<br><br>
                                 Find other words that sound like 'bat'.<br><br>
                                 In this course, always refer to letters by their sound.  'Bat' is spelled 'beh-ah-teh'.",

                "words" => [$this->words["bat"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Cap Gap Tap"] =
            array(
                "group" => 'Fat Cat Sat',
                "words" => [$this->words["cap"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Cat + Cap"] =
            array(
                "group" => 'Fat Cat Sat',
                "stretch" => 'cat/cap,rat/rap,mat/map,sat/sap',
                "stretchSideText" => "Contrast the sounds across the page. Ask the student to exaggerate the sounds and feel the difference in their mouth.<br /><br />
                If your student struggles, review words up and down, and then return to contrasts.<br /><br />
                There will be many 'contrast' pages in the following lessons.",

                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bag Nag Tag"] =
            array(
                "group" => 'Fat Cat Sat',
                "words" => [$this->words["bag"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bat + Bag"] =
            array(
                "group" => 'Fat Cat Sat',
                "stretch" => 'bat/bag,hat/hag,nat/nag,rat/rag,sat/sag,tap/tag,zap/zag',
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"]
                ),
                "scrambleSideText" => "If your student struggles, try asking him to read only the last two letters (eg: 'at' and 'ag').",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Cat + Cap + Bag"] =
            array(
                "group" => 'Fat Cat Sat',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"],
                    $this->words["bag"]
                ),
                "scrambleSideText" => "If your student struggles, try asking him to read only the last two letters (eg: 'at', 'ap' and 'ag').",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'g,p,t',
                    ''
                ), // exception list
            );

        if ($this->bdp) {
            $bdq = $this->gen3letters(array('b', 'd', 'p'), array('a'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Cat Words"] =
                array(
                    "group" => 'Fat Cat Sat',
                    "review" => true,
                    "instruction" => $this->bdpText,
                    "scrambleSideText" => "Try these, but don't spend much time on them, and  don't worry if your student doesn't master them.",

                    "words" => array(implode(',', $bdq)),
                );
        }

        $this->clusterWords["All Cat Words"] =
            array(
                "group" => 'Fat Cat Sat',
                "review" => true,
                "words" => [$this->CVC['CaC']],
                "scrambleSideText" => "The next page is 'decodable text'.<br><br>
                        If your student does not recognize words like 'is' and 'the', then they must be taught as sight words.",
                "decodable" => "Sam the cat has a rag hat. \
                A fat rat is at the mat. \
                The bat bag has a gap. \
                Dan is bad.  Dad is mad at Dan.",

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,d,ff,g,k,m,n,p,ss,t,zz',
                    ''
                ), // exception list
                //                "2syl"    => $twoVowels['a/ah']
            );

        $this->clusterWords["Bit Pit Sit"] =
            array(
                "group" => 'Bit Pit Sit',
                "pronounce" => "ih",
                "words" => [$this->words["bit"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'i',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bat + Bit"] =
            array(
                "group" => 'Bit Pit Sit',
                "contrast" => "ah,ih",
                "stretch" => 'bat/bit,cat/kit,fat/fit,pat/pit,mat/mitt,hat/hit',
                "scrambleSideText" => "If your student struggles, try asking him to read only the last two letters (eg: 'at' and 'it').",
                "words" => array(
                    $this->words["bat"],
                    $this->words["bit"]
                ),
            );

        $this->clusterWords["Big Dig Fig"] =
            array(
                "group" => 'Bit Pit Sit',
                "words" => [$this->words["big"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'g,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bit + Big"] =
            array(
                "group" => 'Bit Pit Sit',
                "stretch" => 'bit/big,pit/pig,zit/zig,wit/wig,fit/fig',
                "words" => array(
                    $this->words["bit"],
                    $this->words["big"]
                ),
                "scrambleSideText" => "If your student struggles, try asking him to read only the last two letters (eg: 'it' and 'ig').",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'g,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bag + Big"] =
            array(
                "group" => 'Bit Pit Sit',
                "stretch" => 'bag/big,jag/jig,rag/rig,wag/wig,zag/zig',
                "words" => array(
                    $this->words["bag"],
                    $this->words["big"]
                ),
                "scrambleSideText" => "If your student struggles, try asking him to read only the last two letters (eg: 'ag' and 'ig').",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'g,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bat + Bag + Bit + Big"] =
            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["bit"],
                    $this->words["big"]
                ),
                "scrambleSideText" => "If your student struggles, try asking him to read only the last two letters (eg: 'at', 'ag', 'it', and 'ig').",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'g,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bit + Big + Dip"] =
            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "words" => array(
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('ah', 'ih', 'a', 'i')] =

            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "stretch2" => "bat/bit,bag/big,dab/dib,dam/dim,fab/fib,fan/fin,
                    fat/fit,fax/fix,gag/gig,ham/him,hat/hit,had/hid,
                    jab/jib,jam/jim,lad/lid,lap/lip,nap/nip,pat/pit,
                    ram/rim,rap/rip,rag/rig,sad/sid,sap/sip,sat/sit,
                    tap/tip,tan/tin,wag/wig,zap/zip",
                "words" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CiC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'b,d,ff,g,k,m,n,p,ss,t,zz',
                    ''
                ), // exception list
                //                "2syl"    => $twoVowels['i/ih']
            );

        if ($this->bdp) {
            $bdq = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat and Bit Words"] =
                array(
                    "group" => 'Bit Pit Sit',
                    "review" => true,
                    "instruction" => $this->bdpText,
                    "scrambleSideText" => "Try these, but don't spend much time on them, and  don't worry if your student doesn't master them.",

                    "words" => [$bdq],
                );
        }

        $this->clusterWords["Fat/Cap/Bag + Bit/Big/Dip"] =
            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"]
                ),

                "image" => 'pigrat.jpg',
                "decodable" => "Tim the pig sat, and Zap the rat sat in his lap. \
            Zap the rat bit Tim the pig on his lip, and Tim is mad.  But Tim the pig
            is big and fat, and will sit on Zap the rat, and Zap will be as flat as a hat. ",

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Cot Dot Jot"] =
            array(
                "group" => 'Cot Dot Jot',
                "pronounce" => "aw",
                "words" => [$this->words["cot"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'o',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Cat + Cot"] =
            array(
                "group" => 'Cot Dot Jot',
                "contrast" => "ah,aw",
                "stretch" => 'cat/cot,hat/hot,pat/pot,rat/rot',
                "words" => array(
                    $this->words["bat"],
                    $this->words["cot"]
                ),
            );

        $this->clusterWords["Cot + Cog + Cop"] =
            array(
                "group" => 'Cot Dot Jot',
                "review" => true,
                "words" => array(
                    $this->words["cot"],
                    $this->words["bog"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('ah', 'aw', 'a', 'o')] =

            array(
                "group" => 'Cot Dot Jot',
                "review" => true,
                "stretch2" => "bat/bot,bag/bog,cab/cob,can/con,cat/cot,
                    Dan/Don,fab/fob,fax/fox,
                    gab/gob,hat/hot,hag/hog,
                    jab/job,jag/jog,lag/log,pat/pot,pad/pod,
                    rad/rod,sad/sod,sap/sop,
                    tap/top,tam/Tom",
                "words" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CoC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,d,ff,g,k,m,n,p,ss,t,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Cat + Cot + Bag + Bog"] =
            array(
                "group" => 'Cot Dot Jot',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["cot"],
                    $this->words["bog"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Hit + Hot"] =
            array(
                "group" => 'Cot Dot Jot',
                "contrast" => "ih,aw",
                "stretch" => 'hit/hot,kit/cot,pit/pot,nit/not,lit/lot',
                "words" => array(
                    $this->words["bit"],
                    $this->words["cot"]
                ),
            );

        $this->clusterWords[$this->contrastTitle('ih', 'aw', 'i', 'o')] =

            array(
                "group" => 'Cot Dot Jot',
                "review" => true,
                "stretch2" => "bib/bob,bit/bot,big/bog,
                    din/Don,dig/dog,fib/fob,fix/fox,
                    hit/hot,hip/hop,
                    jib/job,jig/jog,lib/lob,lit/lot,
                    mid/mod,nit/not,pit/pot,
                    rid/rod,rib/rob,Sid/sod,sip/sop,
                    tip/top,Tim/Tom",
                "words" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CoC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,d,ff,g,k,m,n,p,ss,t,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Hit + Hot + Hip + Hop"] =
            array(
                "group" => 'Cot Dot Jot',
                "review" => true,
                "words" => array(
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["cot"],
                    $this->words["bog"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'g,p,t',
                    ''
                ), // exception list
            );

        if ($this->bdp) {
            $bdq = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i', 'o'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat-Bit-Bot Words"] =
                array(
                    "group" => 'Cot Dot Jot',
                    "review" => true,
                    "instruction" => $this->bdpText,
                    "scrambleSideText" => "Try these, but don't spend much time on them, and  don't worry if your student doesn't master them.",

                    "words" => [$bdq],
                );
        }

        $this->clusterWords["Fat/Cap/Bag + Bit/Big/Dip + Cot/Bog/Hop"] =
            array(
                "group" => 'Cot Dot Jot',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"],
                    $this->words["bag"],
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["cot"],
                    $this->words["bog"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'g,p,t',
                    ''
                ), // exception list
            );



        $this->clusterWords["Kit"] =       // a, i, o
            array(
                "group" => 'Cot Dot Jot',
                "pagetype" => 'decodable',

                "format"  => ['B/W', ['a', 'i', 'o']],

                "image1" => 'kit1.png',
                "words1" => "{ Kit } \
                Kit can skip. \
                Kit can flip and flop. \
                Kit can zig and zag. \
                Kit can swim. ",


                "image2" => 'kit2.png',
                "words2" => "{ Kit and Stan } \
            Kit ran and hid. \
            Stan ran and got Kit. \
            Stan ran and hid. \
            Kit ran and got Stan. \
            Tag! Kit won. ",

                "image3" => 'kit3.png',
                "words3" => "{ Kit's Hats } \
            Kit has hats. \
            Kit has big hats. \
            Kit has flat hats. \
            Kit has hip hats.",


                "image4" => 'kit4.png',
                "words4" => "{ Kit's Cats } \
            Kit has cats. \
            Kit's cats ran fast. \
            Kit's cats lap up milk. \
            Kit's cats nap on Kit's lap.",

            );




        //////////////////////////////////
        /// cat in the hat
        //////////////////////////////////

        $this->clusterWords["Ready to Start Reading'"] =
            array(
                "group" => 'The Cat in the Hat',

                "instruction" => "<br>
            Your student now has three vowels (<span class='sound'>ah</span>, <span class='sound'>ih</span>, <span class='sound'>ow</span>).<br><br>
            <img src='images/catinhat.jpeg' height='200' style='float:right;padding:20px' />
            This is the point where I like to
            start reading with a student. Find
            an easy book.  I love Dr Seuss 'The Cat in the Hat', even for teaching adults. It is real reading, and also fun.
            Order a copy now, you will need it in a few days.
            <br><br>
            The next few exercises will prepare for 'The Cat in the Hat' or any similar book.

            These lessons go very fast, don't worry if your student is not perfect.
            We will soon return to the vowel 'uh' and our careful drills.
            Keep working on these drills every day. Lots of important stuff still ahead.<br>",

                "words" => ["back,hack,Jack,lack,Mack,pack,rack,sack,tack,yack,Zack,
                        Dick,hick,kick,Mick,nick,pick,Rick,sick,tick,wick,
                        bock,dock,hock,jock,lock,mock,rock,sock"],   // a-o-i only

                "sidenote" => "The ending '-ck' makes the same sound as '-k'.",


                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'c,k,ck',
                    ''
                ), // exception list
            );

        $noBreakHyphen = '&#8209';

        $this->clusterWords["Exception for 'Ball'"] =
            array(
                "group" => 'The Cat in the Hat',

                "words" => array(
                    $this->vowels['all'],
                    $this->vowels['alk'],
                ),
                "sidenote" => "Words with 'a+L' (usually '{$noBreakHyphen}all' or '{$noBreakHyphen}alk' or '{$noBreakHyphen}alt') make the <sound>aw</sound> sound, which
            is different from the <sound>ah</sound> sound in similar-looking 'bat' / 'cat' words.<br><br>
            These words are very common (ball, walk, salt). <br><br>
            This is the same <sound>aw</sound> as in 'dog', just a different spelling.",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,zz',
                    ''
                ), // exception list
                //                "2syl"    => $twoVowels['a/ah']
            );


        $this->clusterWords["Contrast 'Bat' and 'Ball'"] =
            array(
                "group" => 'The Cat in the Hat',
                // "review" => true,
                "words" => array(
                    $this->CVC['CaC'],
                    $this->vowels['all'],
                    $this->vowels['alk'],
                ),

                "image" => 'ball.jpg',
                "decodable" => "{ Rick Hits a Fan }
                Rick's bat hit the fast ball. \
                Rick did not balk, he did not
                miss the ball.  He hit a
                bunt to the left wall. \
                The ball did nick a tall bald fan on the lip, and the fan
                did fall. \
                Rick's ball is lost, but Rick is calm.",

                "sidenote" => "This lesson will show a small 'Decodable Text' that your
                student can read without guessing.<br><br>

                Words in BLACK need to be memorized.  This lesson has  'he', 'the',
                and 'to'.  There are also some new 4-letter words to try ('lost','calm').<br><br>

                The wordspinner adds 'll','lt', and 'lk'.  Play with them.",



                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,d,ff,g,k,l,ll,lk,lt,m,n,p,ss,t,zz',
                    ''
                ), // exception list
                //                "2syl"    => $twoVowels['a/ah']
            );


        $this->clusterWords["New Sound ".$vc->sound('th')] =
            array(
                "group" => 'The Cat in the Hat',
                "stretch" => 'tat/that,tin/thin,tug/thug,tis/this,bat/bath,got/goth,mat/math,pat/path,pit/pith,wit/with',
                "words" => [$this->vowels['th']],
                "stretchText" => "Here's a new sound - ". $vc->sound('th')." - that we can use both at the front and the back.<br><br>Sometimes the spelling 'th' makes the sound ".$vc->sound('dh')." instead of ".$vc->sound('th').".  Mention it, but don't make a big deal, it shouldn't confuse your student.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["New Spelling '-ay' says ".$vc->sound('ay')] =
            array(
                "group" => 'The Cat in the Hat',
                // "review" => true,
                "words" => [$this->vowels['ay0']],
                "sidenote" => "The spelling 'ay' almost always makes the sound <sound>ay</sound>.<br><br>
                            Words that end in '-ay' (like 'bay') are a different pattern than CVC words (like 'bat'), but confusingly similar.<br><br>
                            Practice them on the word spinner.",
                "words2" => array(
                    $this->CVC['CaC'],
                    $this->vowels['ay0'],
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,d,ff,g,k,ll,m,n,p,ss,t,th,y,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Harder <sound>ay</sound> Words"] =
            array(
                "group" => 'The Cat in the Hat',
                // "review" => true,
                "words" => [$this->vowels['ay1']],
                "sidenote" => "These are harder <sound>ay</sound> words. But since the ending is always the same, your student might be able to handle them.  <br><br>Two-syllable 'Away' and 'Okay' may need some explanation.",
                "words2" => array(
                    $this->CVC['CaC'],
                    $this->vowels['ay0'],
                    $this->vowels['ay1'],
                    $this->vowels['ay0'],
                    $this->vowels['ay1'],
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,d,ff,g,k,ll,m,n,p,ss,t,th,y,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Review for Cat in the Hat"] =
            array(
                "group" => 'The Cat in the Hat',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"],
                    $this->words["bag"],
                    $this->vowels["all"],
                    $this->vowels["alk"],
                    $this->vowels["ay0"],
                    $this->vowels["ay1"],
                    $this->vowels["all"],
                    $this->vowels["ay0"],
                    $this->vowels["ay1"],
                ),
                "scrambleSideText" => "This reviews our spellings for  <sound>ah</sound>, <sound>aw</sound>, and <sound>ay</sound>
                                sounds, which all look similar - 'bat', 'ball', 'bay'.<br><br>
            The Decodable in this lesson has a new black word 'you'.  Point it out.",

                "image" => 'dogball.png',
                "decodable" => "{ Play Ball With a Dog }
                If you play ball in the hall, you may hit
                the clay pot or nick the cat. Or both. Then Mom say stop,
                and grab the ball a/way. \
                It is not fun to play tag with a doll since it can not walk or talk, and you
                will win. \
                You can play ball with this dog, it will not nip or lick
                or walk away. That is big fun.",



                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,d,ff,g,k,l,lk,ll,lt,m,n,p,ss,t,th,th,y,zz',
                    ''
                ), // exception list
            );

        // $this->clusterWords["Ends in '-ear'"] =
        // array(
        //     "group" => 'The Cat in the Hat',
        //     // "review" => true,
        //     "instruction" => "<br>
        //     Words like 'hear' and 'near' are only here because they are in 'The Cat in the Hat'.
        //     Don't spend much time on them.  Feel free to skip this lesson, and just feed the words to your student as you encounter them.<br><br>
        //     And beware, 'bear' and 'pear' are NOT part of this group.<br><br>",
        //         "words" =>  $this->vowels['ear'],
        //     "scrambleSideText" => "'bear' and 'pear' are NOT part of this group.<br><br>  Don't spend much time on this lesson.",
        // );

        $count = count(explode(',', $this->memorize_words()));
        $mwords = str_replace(',', ', ', $this->memorize_words());
        $this->clusterWords["Words to Memorize"] =
            array(
                "group" => 'The Cat in the Hat',
                "review" => true,
                "instruction" => "<br>
            Some words that are SO COMMON that your student must simply memorize them.
            And many cannot be decoded.<br><br>
            <img src='images/catinhat.jpeg' height='200' style='float:right;padding:20px' />
            These $count words are in The Cat in The Hat, but they are also among the most common words in English writing, and
            many are basic vowel patterns:
            $mwords.<br><br>
            Don't spend much time on these words today.  You will see them again and again.
            It's time to start reading, your student is ready.  Just supply words that your student cannot read yet (like 'cold').  Continue working on BLENDING 20 minutes every day.",

                // if you update this list, also update in displaypages->decodableReader()
                "words" => [$this->memorize_words()],
                "scrambleSideText" => "These are common words that your student must memorize (not right away).  It's too much work to decode them.<br><br>
                        'To', 'too', and 'two' should be pointed out.<br><br>
                        'One' and 'two' are not as common as the others, but cannot be decoded.",

                //                "2syl"    => $twoVowels['a/ah']
            );

        /////////////////////////////////////////////////////////////////////

        $this->clusterWords["But Nut Rut"] =
            array(
                "group" => 'Bug Rug Jug',
                "pronounce" => "uh",
                "words" => [$this->words["but"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bat + But"] =
            array(
                "group" => 'Bug Rug Jug',
                "contrast" => "ah,uh",
                "stretch" => 'bat/but,cat/cut,hat/hut,mat/mutt,pat/putt,rat/rut',
                "words" => array(
                    $this->words["bat"],
                    $this->words["but"]
                ),
            );

        $this->clusterWords["Bug Rug Jug"] =
            array(
                "group" => 'Bug Rug Jug',
                "words" => [$this->words["bug"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bug + But"] =
            array(
                "group" => 'Bug Rug Jug',
                "stretch" => 'but/bug,hut/hug,mutt/mug,putt/pug,rut/rug,tut/tug,putt/pup',
                "words" => array(
                    $this->words["bug"],
                    $this->words["but"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bag + Bug"] =
            array(
                "group" => 'Bug Rug Jug',
                "stretch" => 'bag/bug,hag/hug,lag/lug,rag/rug,tag/tug,cap/cup',
                "words" => array(
                    $this->words["bag"],
                    $this->words["bug"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bag + Bug + Bat + But"] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "words" => array(
                    $this->words["bag"],
                    $this->words["bug"],
                    $this->words["bat"],
                    $this->words["but"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('ah', 'uh', 'a', 'u')] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "stretch2" => "bad/bud,bag/bug,
                    cap/cup,cab/cub,cad/cud,cat/cut,
                    Dan/dun,
                    fan/fun,
                    hat/hut,hag/hug,
                    jag/jug,lag/lug,pan/pun,
                    ran/run,rat/rut,rag/rug,
                    Sam/sum,sap/sup,
                    tab/tub,tag/tug",
                "words" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CuC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Big + Bug"] =
            array(
                "group" => 'Bug Rug Jug',
                "contrast" => "ih,uh",
                "stretch" => 'big/bug,dig/dug,rig/rug,pig/pug,jig/jug',
                "words" => array(
                    $this->words["big"],
                    $this->words["bug"]
                ),
            );

        $this->clusterWords[$this->contrastTitle('ih', 'uh', 'i', 'u')] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "stretch2" => "bid/bud,bit/but,big/bug,
                    dig/dug,din/dun,
                    fin/fun,fizz/fuzz,
                    hit/hut,
                    jig/jug,
                    mid/mud,miff/muff,miss/muss,
                    nib/nub,nit/nut,
                    pit/putt,
                    riff/ruff,rig/rug,rib/rub,
                    sin/sun,sip/sup",
                "words" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CuC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Big + Bug + Bit + But"] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "words" => array(
                    $this->words["big"],
                    $this->words["bug"],
                    $this->words["bit"],
                    $this->words["dip"],
                    $this->words["but"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bog + Bug"] =
            array(
                "group" => 'Bug Rug Jug',
                "contrast" => "aw,uh",
                "stretch" => 'bog/bug,dog/dug,hog/hug,log/lug,pop/pup,cop/cup',
                "words" => array(
                    $this->words["bog"],
                    $this->words["bug"]
                ),
            );

        $this->clusterWords[$this->contrastTitle('ow', 'uh', 'o', 'u')] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "stretch2" => "bog/bug,bot/but,boss/bus,
                    dog/dug,Don/dun,
                    fozz/fuzz,
                    hog/hug,hot/hut,
                    jog/jug,jot/jut,
                    mod/mud,mom/mum,moss/muss,
                    non/nun,not/nut,
                    pot/putt,
                    rob/rub,Ron/run,rot/rut,
                    sob/sub,sop/sup,
                    top/tut",
                "words" => array(
                    $this->CVC["CoC"],
                    $this->CVC["CuC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bog + Bug + Cot + Cut"] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "words" => array(
                    $this->words["cot"],
                    $this->words["bug"],
                    $this->words["but"],
                    $this->words["bug"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        if ($this->bdp) {
            $bdq = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i', 'o', 'u'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat-Bit-Bot-But Words"] =
                array(
                    "group" => 'Bug Rug Jug',
                    "review" => true,
                    "instruction" => $this->bdpText,
                    "scrambleSideText" => "Try these, but don't spend much time on them, and  don't worry if your student doesn't master them.",

                    "words" => [$bdq],
                );
        }

        $this->clusterWords["Fat/Cap/Bag + Bit/Big/Dip + Cot/Bog/Hop + But/Bug/Pup"] =
            array(
                "group" => 'Bug Rug Jug',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"],
                    $this->words["bag"],
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["cot"],
                    $this->words["bog"],
                    $this->words["but"],
                    $this->words["bug"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );





        $this->clusterWords["Bet Get Jet"] =
            array(
                "group" => 'Bet Get Jet',
                "pronounce" => "eh",
                "words" => [$this->words["bet"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'e',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bat + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "ah,eh",
                "words" => array(
                    $this->words["bat"],
                    $this->words["bet"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Beg Leg Keg"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => [$this->words["beg"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bet + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bag + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bag"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('ah', 'eh', 'a', 'e')] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "stretch2" => "bat/bet,bag/beg,dab/Deb,Dan/den,fan/fen,fad/fed,
                    lad/led,lag/leg,lass/less,man/men,mat/met,mass/mess,
                    pan/pen,pat/pet,sat/set,tan/ten,vat/vet,wad/wed",
                "words" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bat + Bet + Bat + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bit + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "ih,eh",
                "words" => array(
                    $this->words["bit"],
                    $this->words["bet"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Big + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'p,g',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('ih', 'eh', 'i', 'e')] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "stretch2" => "bid/bed,bill/bell,bin/Ben,bit/bet,big/beg,
                   din/den,fin/fen,hill/hell,lid/led,miss/mess,
                   pin/pen,pig/peg,pit/pet,sit/set,sill/sell,
                   tin/ten,till/tell,will/well,wit/wet",
                "words" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bit + Bet + Big + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "words" => array(
                    $this->words["bit"],
                    $this->words["beg"],
                    $this->words["dip"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Fat/Cap/Bag + Bit/Big/Dip + Bet/Beg/Pep"] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["bit"],
                    $this->words["beg"],
                    $this->words["dip"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Got + Get"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "aw,eh",
                "words" => array(
                    $this->words["cot"],
                    $this->words["bet"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bog + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bog"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'p,g',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('aw', 'eh', 'o', 'e')] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "stretch2" => "boss/Bess,bog/beg,Don/den,jot/jet,log/leg,lot/let,
                    loss/less,moss/mess,nod/Ned,not/net,pot/pet,toss/Tess",
                "words" => array(
                    $this->CVC["CoC"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["Got + Get + Bog + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "words" => array(
                    $this->words["cot"],
                    $this->words["bog"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["Fat/Cap/Bag + Bit/Big/Dip + Bet/Beg/Pep + Cot/Bog/Hop"] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["bit"],
                    $this->words["beg"],
                    $this->words["dip"],
                    $this->words["cot"],
                    $this->words["bog"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        $this->clusterWords["But + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "uh,eh",
                "words" => array(
                    $this->words["but"],
                    $this->words["bet"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    't',
                    ''
                ), // exception list
            );

        $this->clusterWords["Bug + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bug"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'p,g',
                    ''
                ), // exception list
            );

        $this->clusterWords[$this->contrastTitle('uh', 'eh', 'u', 'e')] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "stretch2" => "bud/bed,bug/beg,bun/Ben,but/bet,fun/fen,hull/hell,
                    Hun/hen,jut/jet,lug/leg,muss/mess,nut/net,pun/pen",
                "words" => array(
                    $this->CVC["CuC"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        $this->clusterWords["But + Bet + Bug + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "review" => true,
                "words" => array(
                    $this->words["but"],
                    $this->words["bug"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list
            );

        if ($this->bdp) {
            $bdq = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i', 'o', 'u', 'e'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat-Bit-Bot-But-Bet Words"] =
                array(
                    "group" => 'Bet Get Jet',
                    "review" => true,
                    "instruction" => $this->bdpText,
                    "scrambleSideText" => "Try these, but don't spend much time on them, and  don't worry if your student doesn't master them.",

                    "words" => [$bdq],
                );
        }

        $this->clusterWords["Grand Review"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => $this->words,
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );


        // /////////////////////////////////
        // // Junie B Jones
        // /////////////////////////////////


        $this->clusterWords["Ready for Harder Books'"] =
            array(
                "group" => 'Junie B Jones',

                "instruction" => "<br>
            Your student now has the five 'short' vowels (<sound>ah</sound>,<sound>ih</sound>,<sound>ow</sound>,<sound>uh</sound>,<sound>eh</sound>).<br><br>
            <img src='images/junie.png' height='200' style='float:right;padding:20px' />
            Hopefully you have been reading 'Cat in the Hat' or similar.  Now it's
            time for harder books.<br><br>

            I recommend the 'Junie B Jones' books for both boys and girls, and for
            all ages including adults.  They are well-written, funny, and subversive.
            Order a few of them now. This group of lessons will prepare you.<br><br>

            Keep working on drills in this program.  At least 20 minutes a day,
            more if you are working through a decodable text.",


                "words" => "back,hack,Jack,lack,Mack,pack,rack,sack,tack,yack,Zack,
                        Dick,hick,kick,Mick,nick,pick,Rick,sick,tick,wick,
                        bock,dock,hock,jock,lock,mock,rock,sock",   // a-o-i only

                "sidenote" => " The ending '-ck' makes the same sound as '-k'.<br><br>
                            There is an important idea here.  'k' and 'ck' are two
                            different spellings for the sound <sound>k</sound>.<br><br>
                            It is wrong to say 'letters make a sound', this is an
                            example of a 'spelling makes a sound' mapping",

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'c,k,ck',
                    ''
                ), // exception list
            );



        //     $this->clusterWords["New Spelling 'ck' of <sound>k</sound>"] =
        //     array(
        //         "group" => 'Junie B Jones',
        //     "words" => "back,hack,Jack,lack,Mack,pack,rack,sack,tack,yack,Zack,
        //                 Dick,hick,kick,Mick,nick,pick,Rick,sick,tick,wick,
        //                 bock,dock,hock,jock,lock,mock,rock,sock",   // a-o-i only

        //     "sidenote" => " The ending '-ck' makes the same sound as '-k'.<br><br>
        //                     There is an important idea here.  'k' and 'ck' are two
        //                     different spellings for the sound <sound>k</sound>.<br><br>
        //                     It is wrong to say 'letters make a sound', this is an
        //                     example of a 'spelling makes a sound' mapping",

        //     "spinner" => array('b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
        //         'a,e,i,o,u',
        //         'c,k,ck',
        //         ''), // exception list
        // );



        $this->clusterWords["New Sound <sound>sh</sound>"] =
            array(
                "group" => 'Junie B Jones',

                "words" => $this->vowels['sh'],
                // "words2" => $this->vowels['sh2'],
                "sidenote" => "Here's a new sound - <sound>sh</sound> that we can use both at the front and the back, just like <sound>th</sound>.<br><br>
                            The WordSpinner has both 'sh' and 'th', make sure to contrast them.",
                "image" => 'bandaid.png',
                "decodable" => "{ Nash had a Rash }
                Nash had a rash from a gash that he got in the bath, and it did not pass.  \
                He did not wish to rot into mush, so he did grab cash from his stash, and did a fast dash to the doc.  \
                The doc cut his skin and did tack a mesh on his rash and set it with a sash.  \
                Then Nash did pass cash to the doc with his thanks. ",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,e',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,sh,th,zz',
                    ''
                ), // exception list
            );




        $this->clusterWords["<sound>sh</sound> with consonant clusters"] =
            array(
                "group" => 'Junie B Jones',
                "review" => true,
                "words" => $this->vowels['sh'],
                "words2" => $this->vowels['sh2'],
                "scrambleSideText" => "This is just a warmup - we are about to spring TWO leading consonants on your student.",
                "words3" => array($this->vowels['sh'], $this->vowels['sh2']),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,e',
                    'b,d,ff,g,k,l,ll,m,n,p,sh,ss,t,th,sh,th,zz',
                    ''
                ), // exception list
            );



        $this->clusterWords["Seth"] =
            array(
                "group" => 'Junie B Jones',
                "pagetype" => 'decodable',
                "image1" => 'sethbed.png',
                "words1" => '{ Seth }
                        This is Seth Smith.
                        Seth is ten. \
                    Seth must get in bed at ten. \
                    Seth can jump on his bed,
                    but not past ten. \
                    Seth can stomp and romp
                    and stand on his hands, but
                    not past ten. \
                    Seth\'s dad gets mad if Seth is
                    not in bed at ten.',
                "image2" => 'sethmom.png',
                "words2" => '{ Seth\'s Mom }
                    This is Pat. Pat is Seth\'s mom. \
                    Pat can fix things, with quick hands. \
                    Pat can scrub, plan, and think. \
                    Pat can run fast. Pat is fit and trim. \
                    Pat can sing songs, and dad will drum on a tin pan.  Seth
                    will hit on his big drum, and sing. ',
                "image3" => 'sethdad.png',
                "words3" => '{ Seth\'s Dad }
                    This is Ted. Ted is Seth\'s dad. \
                    Ted is strong. Ted can chop big logs with
                    his ax. Ted will lift his big ax in his
                    hands and chop. \
                    Ted can lift big stumps.  Ted can lift stumps as big as a man, and bring them with him. \
                    Ted can crush tin cans with his hands, and stuff them in a big bag.',
                "image4" => 'salshop.png',
                "words4" => '{ Sal\'s Fish Shop }
                Pat and Seth went in Sal\'s Fish
                Shop. Sal\'s Fish Shop is best. \
                Sal has fresh fish.   Sal has fresh shrimp.
                Sal has crabs.  Sal has clams.  Sal has squid. \
                Pat can pick fish.  Pat got fish and shrimp, and Sal did pack them in a bag.',
                "image5" => 'sethlunch.png',
                "words5" => '{ Lunch }
                Seth had lunch with his mom
                and dad. \
                Pat had shrimp and chips. \
                Ted had shrimp, fish, and
                chips. \
                Seth had ham and chips. \
                Munch, munch. Crunch, crunch. Yum, yum. ',
            );

        $this->clusterWords["Seth II"] =
            array(
                "group" => 'Junie B Jones',
                "pagetype" => 'decodable',

                "image1" => 'sethbird.png',
                "words1" => '{ Seth\'s Finch }
                That is Seth\'s pet finch, Chip. \
                Chip can flap his wings.
                Chip can munch on ants and bugs.
                Chip can sing. \
                Chip can land on Seth\'s hand. That finch is fun!',
                "image2" => 'sethbird2.png',
                "words2" => '{ Lost Finch }
                Seth\'s pet finch, Chip, is lost. \
                Seth can\'t spot him.
                Pat can\'t spot him.
                Ted can\'t spot him. \
                Chip is not on Seth\'s bed.
                Chip is not on Seth\'s desk. \
                Then, at last, Pat spots Chip.
                Chip hid in Pat\'s hat and
                slept.',
                "image3" => 'sethsled.png',
                "words3" => '{ Seth\'s Sled }
                Seth\'s sled went fast. Seth held on. \
                Seth hit bumps but did not stop.
                Seth hit slush but did not stop. \
                Then Seth\'s sled hit mud.
                Splash! \
                Seth got mud on his sled.
                Seth got mud on his pants.
                Seth got mud on his hat.',
                "image3" => 'quints.png',
                "words3" => '{ Meg\'s Tots }
                This is Meg.  Meg is Pat\'s best pal. \
                Pat has 1 lad, Seth. \
                Meg has 5 tots, Tom, Tim,
                Max, Sam, and Wes.
                Meg has quints!',

                "image4" => 'quints2.png',
                "words4" => 'Pat and Ted help Meg. \
                Pat sets Tim and Tom on
                Seth\'s rug.
                Ted sets Sam on Seth\'s quilt.
                Pat sets Max on Seth\'s bed. \
                Ted helps Wes stand up on
                Seth\'s desk.',

                "image5" => 'hashmilk.png',
                "words5" => '{ Hash and Milk }
                Pat and Ted had lunch with
                Meg\'s tots. \
                Max got hash on his chin.
                Wes got hash on his bib. \
                Tim\'s milk is on Tom.
                Then Tom got milk on Tim. \
                Sam got milk on Pat and Ted.',

            );



        $this->clusterWords["New Sound <sound>ee</sound>"] =
            array(
                "group" => 'Junie B Jones',
                "words" => $this->vowels['ee'],

                "sidenote" => "The spelling 'ee' always makes the <sound>ee</sound> sound.<br><br>  Some phonics programs treat <sound>eer</sound> as
                    a separate sound ('beer', 'deer'), but we do not.  It is easier to teach <sound>ee</sound> + <sound>r</sound>.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,ee',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );



        $this->clusterWords["Scott and Lee"] =
            array(
                "group" => 'Junie B Jones',
                "pagetype" => 'decodable',

                // "format"  => ['B/W',['th','ch']],

                "image1" => 'scottlee1.png',
                "words1" => "{ Scott and Lee }
                    This is Scott Green. Scott is ten. \
                    Scott's dad keeps a pig in a pen.
                    Scott's mom keeps three hens.
                    Scott keeps a sheep. \
                    Lee the Sheep is Scott's pet.
                    Scott feeds Lee and rubs him on the
                    back. \
                    Lee is a sweet sheep.",
                "image2" => 'scottlee2.png',
                "words2" => "{ Red Ants }
                        Lee the Sheep had a bad week
                        last week. Red ants bit him on his legs
                        and feet. \
                        Lee can feel the ants that seek to feed on his feet and skin. \
                        Scott had to sweep the ants
                        with his hand to get rid of them.",
                "image3" => 'scottlee3.png',
                "words3" => "Scott was mad at the ants. \
                    \"Ants,\" he said, \"Lee is a sweet
                    sheep. Feel free to munch on plants
                    and weeds, but not on Lee!\" \
                    One of the ants said, \"We feel
                    bad. We will not munch on Lee. We
                    will munch on plants and weeds.\"",
                "image4" => 'scottlee4.png',
                "words4" => "{ The Bees }
            The red ants left. But then the
            bees got Lee! The bees stung Lee on
            his cheek and on his feet. \
            Scott ran up to help Lee. Then he
            went and had a chat with the bees.",

                "image5" => 'scottlee5.png',
                "words5" => "\"Bees,\" said Scott, \"why sting Lee
            the Sheep? He is a sweet sheep.\" \
            One bee said, \"Bees will be bees.\" \
            One bee said, \"I must be me.\" \
            Then Scott got mad. He said,
            \"Sting the pig. Sting the hens! Sting
            the cat. Sting the dog. But let Lee
            be!\" And the bees let Lee be.",

            );





        /////////////////////////////////////////////
        ///// consonant clusters
        /////////////////////////////////////////////

        // c & k CVC (cup,kit)
        // ck endings (pick,lock)
        // suffix clusters (ct, ft, lb, lf, lm, lp, lt, mp, nd, nt, pt, sk, sp, st)
        // suffix plus 's' (bs, cks, ds, ff, gs, lls, ms, ns, ps, ts)
        // suffix clusters plus 's' (cts, fts, lbs, ...)
        // suffix digraphs (ng, nk, sh, x, ngz, nks)
        // prefix clusters (bl, cl, fl, gl, pl, sc, sk, sl, sm, sn, sp, st, sw, tw, spl)
        // both prefix and suffix clusters
        // r-controlled prefixes (br, cr, dr, fr, gr, pr, scr, spr, str, shr, tr)
        // digraphs (qu, th, wh, squ, thr)
        // digraphs (ch, tch)
        // (ge,dge)
        // two-syllable words

        // c & k CVC (cup,kit)
        $this->clusterWords["c and k CVC"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => "cab,cad,cam,can,cap,cat,
                         kid,kiss,kit,
                         cob,cod,cog,con,cop,cot,
                         cub,cud,cuff,cull,cup,cut,cuss,
                         keg,ken",
            );


        //        $this->clusterWords["ck Endings + -ed,-ing"]      =
        //            array(
        //                "group" => 'Consonant Clusters',
        //                "endings" => true,
        //                "words" => "hack,lack,pack,rack,sack,
        //                         deck,
        //                         kick,pick,tick,
        //                         dock,lock,mock,rock,
        //                         duck,tuck"
        //                 );

        // suffix clusters (ct, ft, lb, lf, lm, lp, lt, mp, nd, nt, pt, sk, sp, st) + bt,mp,xt,tz
        $this->clusterWords["Suffix Clusters"] =
            array(
                "group" => 'Consonant Clusters',
                // bad                "stretch" => "lump/plump,lamp/clamp,tuck/stuck,lend/blend,camp/scamp,
                // bad                            link/blink,win/twin,lash/splash,wept/swept",
                "words" => "band,camp,cask,calf,
                        damp,daft,fact,
                        gash,gasp,hack,half,hand,haft,
                        jamb,lamp,
                        land,lamb,lank,
                        mask,pant,pact,
                        ramp,raft,rasp,
                        task,tact,

                        belt,bend,bent,cent,celt,deft,dent,desk,
                        felt,fend,heft,help,kelp,kept,
                        lent,
                        meld,mend,melt,pent,rent,sent,
                        tend,text,vent,welk,went,wept,

			last,mast,past,best,vast,best,jest,zest,gist,lost,bust,
			dust,fast,test,nest,pest,rest,west,gust,fist,wist,just,

                        bilk,dint,disk,film,gift,gilt,
                        kiln,limb,lint,lisp,milk,pimp,
                        ritz,sift,tilt,
                        vint,wimp,wind,wisp,

                        bomb,dock,dolf,fond,font,
                        hock,jock,mock,pomp,romp,sock,

                        buck,bulk,bump,bunt,busk,cult,cusp,
                        duck,duct,dumb,dump,fund,gulf,
                        hulk,hump,hunt,jump,luck,lump,
                        muck,musk,numb,puck,pulp,punt,rump,
                        sump,tusk",

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,nt,pt,sk,sp,st',
                    ''
                ), // exception list

            );

        // suffix plus 's' (bs, cks, ds, ff, gs, lls, ms, ns, ps, ts)
        $this->clusterWords["Suffix Clusters with 's'"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => "bands,camps,casks,casts,facts,
                        gasps,hacks,hands,lamps,lands,lambs,masks,masts,pants,pact,
                        ramps,rafts,rasps,tasks,

                        belts,bends,cents,debts,dents,desks,
                        gents,helps,jests,melds,mends,melts,nests,pests,rents,rests,
                        tests,texts,vents,welks,

                        dints,disks,films,fists,gifts,gilts,gives,
                        kilns,limbs,lisps,pimps,
                        sifts,tilts,
                        wimps,winds,wisps,

                        bombs,docks,fonts,
                        hocks,jocks,mocks,romps,socks,

                        bucks,bulks,bumps,bunts,busts,busks,cults,
                        ducks,ducts,dumps,dusts,funds,gulfs,gusts,
                        hulks,humps,hunts,jumps,lucks,lumps,
                        mucks,musks,numbs,pulps,punts,rumps,
                        tusks",
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'cks,cts,fts,lfs,lks,lp,mbs,mps,nds,nts,pts,sks,sps,sts',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        $this->clusterWords["Suffix Digraphs (ng, nk, sh)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => "bang,bing,bong,ding,dung,fang,gang,gong,hang,hung,
                        king,long,mung,ping,pong,rang,ring,rung,sang,sing,song,sung,
                        wend,wing,zing,

                        bank,bonk,bunk,dank,dunk,fink,funk,junk,link,mink,
                        pink,punk,rank,rink,sank,sink,sunk,tank,yank,zink,

                        bash,cash,dash,dish,dosh,fish,gash,gosh,hash,hush,Josh,lash,lush,
                        mash,mesh,mush,posh,rash,rush,wish",
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ng,nk,sh',
                    ''
                ), // exception list
            );

        $this->clusterWords["Suffix Digraphs (ngs, nks)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => "bangs,bongs,dings,fangs,gangs,hangs,
                        kings,pings,rings,rungs,sings,songs,wings,

                        banks,bonks,bunks,dunks,finks,junks,links,minks,monks,
                        punks,ranks,rinks,sinks,tanks,yanks",
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ngs,nks,shs',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        $this->clusterWords["Prefix Clusters (sh shr)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => array("sham,shed,shin,ship,shod,shop,shot,shun,shut,shack,shaft,shall,shank,shelf,shell,shift,shock,shops,shred,shrub,shrug,shuck,shush"),

                "spinner" => array(
                    'sh,shr', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,ft,g,k,ll,m,n,nt,p,ss,t,th,zz',
                    ''
                ), // exception list

            );

        // prefix clusters (bl, cl, fl, gl, pl, sc, sk, sl, sm, sn, sp, st, sw, tw, spl)
        $this->clusterWords["Prefix Clusters (bl, cl, fl, ...)"] =
            array(
                "group" => 'Consonant Clusters',
                "stretch" => "lap/flap,lap/clap,fat/flat,fan/flan,
                                pan/plan,pan/span,
                                Sam/scam,Sam/swam,sap/slap,
                                tab/stab,
                                fit/flit,sin/skin,kin/skin,sit/skit,pill/spill,pit/spit,
                                lip/slip,lip/clip,
                                bog/blog,fog/flog,sob/slob,
                                pot/plot,cop/clop,cog/clog,sop/slop,
                                lug/slug,cuff/scuff,tub/stub,sun/stun,
                                bed/bled,fed/fled,led/sled,well/swell,
                                lock/block,lash/flash,lick/slick",
                "words" => array(
                    "clap,clam,clan,
                        flab,flap,flat,flax,
                        glad,glam,glass,
                        plan,
                        scab,slab,slam,slap,swam,stab,snap,snag,span,spat,scat",

                    "blip,clip,click,flip,flit,glib,glad,
                        quit,quiz,skin,skip,snip,slit,slip,slim,spin,stick,
                        spit,split,swig,swim,twin",

                    "bled,fled,Greg,shed,sled,
                        glen,pled,stem,swell",

                    "blog,blob,blot,clog,clot,flog,flop,
                        plod,plop,plot,scot,slog,slot,smog,snob,snot,spot,
                        stop",

                    "club,plug,plus,plum,snug,smug,slum,scum,stub,stud,snub"
                ),

                "spinner" => array(
                    'bl,cl,fl,gl,pl,sc,sk,sl,sm,sn,sp,st,sw,tw,spl', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,ll,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

            );

        // both prefix and suffix clusters
        $this->clusterWords["both prefix and suffix clusters"] =
            array(
                "group" => 'Consonant Clusters',

                "words" => array(
                    "black,blank,bland,blast,clash,clasp,flank,flash,gland,plank,plant,scalp,scamp,slang,smack,snack,stamp,stand,stash,swank,swath,twang,splash",
                    "blunt,blush,clump,clung,flung,flunk,pluck,plumb,plump,plush,skunk,slump,slush,stump,stung,stuck,swung",
                    "blimp,clink,flick,flint,glint,glitz,pling,slick,slink,stick,splint",
                    "block,blond,click,clock,flock,smock,stock,thong",
                    "blest,cleft,slept,smelt,speck,spent,swept,blend"
                ),
                "spinner" => array(
                    'bl,cl,fl,gl,pl,sc,sk,sl,sm,sn,sp,st,sw,tw,spl', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,ng,nk,nt,pt,sh,sk,sp,st',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        // warm-up for r-controlled prefixes

        $this->clusterWords["Warm up for r-Controlled prefixes"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => "rag,ram,ran,rap,rat,red,ref,rep,rev,rib,rid,
                            rig,rim,rip,rob,rod,rot,rub,rug,rum,run,rut",
            );

        // r-controlled prefixes (br, cr, dr, fr, gr, pr, scr, spr, str, tr)

        $this->clusterWords["r-Controlled prefixes(br, cr, fr, ...)"] =
            array(
                "group" => 'Consonant Clusters',
                "stretch" => "rat/brat,ring/bring,rust/crust,rip/trip,rust/trust,rash/crash,
                            rub/scrub,rink/drink,rip/strip,rug/drug,rap/strap,rush/brush,
                            rip/grip,rag/brag,rim/brim,ramp/cramp,ring/string",
                "words" => array(
                    "brag,brad,brat,bran,
                        crab,cram,crap,crank,crack,
                        drag,drab,drat,
                        grab,gram,
                        prank,
                        sprat,sprig,scrub,scrim,scruff,
                        track,trap,tram,tramp",

                    "brim,bring,
                        crib,drip,frill,grip,grim,grin,
                        prim,prick,
                        spring,sprint,strip,strip,string,spritz,
                        trim,trip",

                    "bred,fret,prep,press,shred,trek,crept,crest,
                        dress,Brent,trend,cress",

                    "crop,drop,frock,grog,
                        prod,prof,prom,strong,trot,trod,broth,dross,frost,
                        froth,front,frond,prompt",

                    "brush,crud,crush,drug,drum,
                        grub,grunt,sprung,shrug,truck,trust,truss,trump,
                        scrub,brunch,crumb,drunk",
                ),

                "spinner" => array(
                    'b,br,d,dr,f,fr,g,gr,p,pr,sc,scr,sp,spr,st,str,sh,shr,t,th,tr', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,ng,nk,nt,pt,sh,sk,sp,st',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        // digraphs (qu, th, wh, squ, thr)
        $this->clusterWords["Digraphs (qu, th, wh, squ, thr)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => "broth,cloth,froth,
                            quit,quick,quack,quill,quilt,quip,quad,
                            math,moth,
                            smith,square,squint,
                            that,then,three,thick,think,this,thrush,thrift,thank,thump,thin,
                            whack,whiff,whim,whip,with,whish,whizz",

            );

        //  Digraphs (ch, tch, th, sh in suffix)
        $this->clusterWords["Digraphs (ch, tch, th, sh in suffix)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => array(
                    "branch,catch,patch,latch,match,ranch,snatch,path,math,ash,rash,
                                        bash,hash,cash,mash,splash,trash,slash,crash",
                    "clinch,ditch,filch,glitch,grinch,hitch,itch,pinch,pitch,linch,
                                        rich,switch,witch,which,winch,pith,stitch,fifth,sixth,dish,
                                        phish,swish,fish,Trish,wish",
                    "conch,notch,moth,sloth,troth,cloth,nosh,posh,splosh,broth,josh,slosh,froth",
                    "bench,fetch,quench,stretch,trench,sketch,depth,mesh,flesh,fresh,tech",
                    "bunch,clutch,crutch,crunch,hunch,lunch,punch,much,such,rush,mush,
                                        flush,crush,blush,hush,plush",
                ),

                "spinner" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ch,tch,th,sh',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        // digraphs (ch, th, thr, sh, shr in prefix
        $this->clusterWords["Digraphs (ch, th, thr, sh, shr in prefix)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => array(
                    "chum,clinch,chunk,chin,check,catch,chop,chest,
                                        chat,chill,chink,chip,chap,chick,chock,chuck",
                    "think,thing,thin,thump,them,throb,thrill,thrift",
                    "shit,shod,shot,shop,sham,shut,ship,shin,shush,
                                            shrub,shrunk,shrimp,shrill,shrink,shrug",
                ),
                "spinner" => array(
                    'ch,th,thr,sh,shr', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,ng,nk,nt,pt,sh,sk,sp,st',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        // 'silent-letter' prefix clusters (kn
        $this->clusterWords["Silent Prefix Clusters (kn-, gn-, wr- ...)"] =
            array(
                "group" => 'Consonant Clusters',
                "instruction" => "This lesson has some tricky words that start with 'silent'
                            letters like kn-, gn-, and wr-.<br><br>
                            It is incorrect to say that these letters are silent, because letters are
                            built up into spellings and those spellings are not silent.  The 'k' in
                            'knot' is not silent, but rather the <spelling>kn</spelling> spelling in 'knot' is pronounced
                            <sound>n</sound>.  This idea
                            is explored in the 'Phonics' course.",
                "words" => array(
                    "knot,knit,knack,knave,knee,knew,knight,knob,knock,know",
                    "gnat,gnaw,gnash,gnarl,gnome,gnu",
                    "wrack,wrap,wring,writ,wretch,wren,wrest,wrung"
                ),
                "spinner" => array(
                    'n,kn,gn,r,wr', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,ng,nk,nt,pt,sh,sk,sp,st',
                    ''
                ), // exception list
            );

        ///////////////////////////
        //// the a_e pattern
        ///////////////////////////

        $this->clusterWords["a_e spelling of /ay/"] =
            array(
                "group" => 'a_e Spellings',
                "pronounce" => "ay",
                "stretchText" => "Read across for contrasts, or down for vowel review. Require clear pronunciation.<br><br>
                            It is old-fashioned and incorrect to say \"The green 'Magic E' changes the earlier vowel to say its name.\"<br><br>
                            But 'Magic E' is powerful teaching tool, and I use it anyhow.",
                "stretch" => "rat/rate,can/cane,ban/bane,rat/rate,hat/hate,mat/mate,
                                tam/tame,tap/tape,fad/fade,tap/tape,mad/made,pan/pane,rag/rage,van/vane",
                "words" => [$this->CVCe["CaCe"]],
            );

        $this->clusterWords["a_e spelling of /ay/ (spinner)"] =
            array(
                "group" => 'a_e Spellings',
                // "words" =>  $this->CVCe["CaCe"],
                "words" => array($this->CVCe["CaCe"], $this->words["bag"]),
                "spinnerE" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["a_e spelling of /ay/ (harder)"] =
            array(
                "group" => 'a_e Spellings',
                "words" => array($this->CVCe["CCaCe"]),
                "spinnerE" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'a',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["a_e spelling of /ay/ (mixed)"] =
            array(
                "group" => 'a_e Spellings',
                "words" => array($this->CVCe["CCaCe"], $this->CVCe["CaCe"], $this->words["bag"]),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );


        $this->clusterWords["decodable a_e"] =
            array(
                "group" => 'a_e Spellings',
                "pagetype" => 'decodable',
                "image1" => 'scottjade1.png',
                "words1" => "{ Cake and Grape>s }
                Scott got a cake to split with his
                pal Jade. Jade got a bunch of red
                grape>s to split with Scott. \
                Scott went to Jade's and gave
                Jade the cake. Jade gave Scott the
                grape>s. Then the kid>s sat and ate.
                Jade ate all of Scott's cake. Scott
                ate all of Jade's grape>s.",

                "image2" => 'funsand.png',
                "words2" => "{ Fun in the Sand }
                Scott is with Jade and Dave. The
                kids dig in the sand. They shape the
                sand. They make a sand man. \
                A big wave hit>s. The kid>s can't
                save their sand man from the wave.
                The sand man get>s wet. He slump>s.
                He sags. He drip>s. \
                The sand man is a mess. But the
                kid>s are not sad. They run and splash
                in the wave>s.",

                "image3" => 'skates.png',
                "words3" => '{ Skate>s }
                Jade got skate>s when she was
                six. Scott just got his last week. He
                crave>s to get up on his skate>s. \
                "Is this safe?" Scott ask>s. "What if
                I trip and get a scrape? What if I hit
                a tree? What if I see a snake?" \
                "It is safe!" say>s Jade. "Just skate." \
                Jade helps Scott skate. Scott slip>s
                and trip>s. Then he gets the hang of it.
                "Jade," he yell>s, "it\'s fun to skate!',

                "image4" => 'bakecake1.png',
                "words4" => '{ Scott Bake>s a Cake }
                Scott\'s mom bake>s cake>s with
                Meg. \
                "Scott," she say>s, "you can help us
                with this cake, it will be a game." \
                Scott shrugs. "Well," he say>s, "if
                you will take my help, I will help." \
                "It will be fun," say>s his mom. "You
                can crack the egg>s. \
                Scott crack>s three egg>s and
                drop>s them in the dish. "',

                "image5" => 'bakecake2.png',
                "words5" => 'Scott ask>s if he can mix up the
                egg>s. Then he ask>s if he can add in
                the cake mix. \
                "Well," his mom say>s, "if you add
                the cake mix, then Meg gets to frost
                the cake." \
                "Can I help Meg frost it?" Scott
                ask>s.  Mom and Meg smile. \
                Meg say>s, "See, Scott. It\'s fun to
                bake a cake!"',


            );



        //                "words" =>  $this->CVCe["CaCe"].','.$this->CVC["CaC"]
        /////////////////////////

        $this->clusterWords["i_e spelling of /igh/"] =
            array(
                "group" => 'i_e Spellings',
                "pronounce" => "igh",
                "stretch" => "bid/bide,bit/bite,dim/dime,din/dine,fin/fine,hid/hide,kit/kite,lit/lite,min/mine,
                                mit/mite,pin/pine,pip/pipe,rip/ripe,sit/site,Tim/time,tin/tine",
                "words" => [$this->CVCe["CiCe"]],
            );

        $this->clusterWords["i_e spelling of /igh/ (spinner)"] =
            array(
                "group" => 'i_e Spellings',
                // "words" =>  $this->CVCe["CiCe"],
                "words" => array($this->CVCe["CiCe"], $this->words["big"]),
                "spinnerE" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'i',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["i_e spelling of /igh/ (harder)"] =
            array(
                "group" => 'i_e Spellings',
                "words" => array($this->CVCe["CCiCe"]),
                "spinnerE" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'i',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["i_e spelling of /igh/ (mixed)"] =
            array(
                "group" => 'i_e Spellings',
                "words" => array($this->CVCe["CCiCe"], $this->CVCe["CiCe"], $this->CVC["CiC"]),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'i',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        // reviews for a_e, i_e and a, i

        $this->clusterWords["Contrast a_e and i_e"] =
            array(
                "group" => 'i_e Spellings',
                //"review"=> true,
                "words" => array($this->CVCe["CiCe"], $this->CVCe["CaCe"]),
                "words2" => array($this->CVCe["CCiCe"], $this->CVCe["CCaCe"]),
            );

        $this->clusterWords["Contrast a, a_e and i, i_e "] =
            array(
                "group" => 'i_e Spellings',
                "review" => true,
                "words" => array($this->CVCe["CiCe"], $this->CVCe["CaCe"], $this->CVC["CiC"], $this->CVC["CaC"]),
                "words2" => array($this->CVCe["CCiCe"], $this->CVCe["CCaCe"], $this->CVC["CiC"], $this->CVC["CaC"]),
                "Nreview" => true,
            );

        /////////////////////////


        $this->clusterWords["A Hike with Scott"] =
            array(
                "group" => 'i_e Spellings',
                "pagetype" => 'decodable',
                "image1" => 'hike1.png',
                "words1" => '{ A Fine Hike }
            Scott is on a hike with Clive and
            Clive\'s dad. They hike three miles up
            a big hill. \
            They can see a fine mist rise as they hike to their camp site. \
            At the top of the hill, Clive\'s dad
            say>s, "This is the spot we will camp." He
            drops his pack on the grass. Scott
            and Clive help him set up the tent.',

                "image2" => 'hike2.png',
                "words2" => 'At five, Scott and Clive hike to
            the lake to fish. They get five fish! \
            At dusk, the kids hike back to
            camp. Clive\'s dad makes a fire. The
            kids munch on hot dogs. \
            At nine, they get in their tent.
            They are all tired. They smile as they
            sleep. They all had a fine time.',


                "image3" => 'bike.png',
                "words3" => '{ The Bike Ride }
            Scott\'s sis, Meg, likes to ride a
            bike. One time, Meg went on a bike ride
            with Scott. Meg\'s tire hit a rock and
            she fell off the bike. \
            Meg was brave. She did not yell.
            She did not sob. She got back on the
            bike. Then she said, "Let\'s ride!" \
            "Meg," Scott said, "I am glad my
            sis is so brave!" \
            That made Meg smile with pride!',

                "image4" => 'plane.png',
                "words4" => '{ The Plane Ride }
            Scott\'s dad rents a plane. He asks
            Scott and Meg to ride with him in the
            plane. The kids smile and nod. \
            The kids get in the plane. They
            click on their belts. Then their dad
            takes off. The plane picks up speed.
            By the time it gets to the end of the
            strip, it lifts up.',

                "image5" => 'plane2.png',
                "words5" => 'The kids can see lots of things
            from the plane. \
            "That\'s Big Lake!" say>s Scott. "But
            it\'s not so big from up here, is it? It
            seems like it\'s just a frog pond!" \
            "What\'s that?" Meg asks.
            "That\'s a truck," say>s Scott.
            "A truck?" say>s Meg. "But it\'s the
            size of a dot!" \
            Scott and Meg smile. It\'s fun to
            ride in a plane.',

            );





        $this->clusterWords["o_e spelling of /oh/"] =
            array(
                "group" => 'o_e Spellings',
                "pronounce" => "oh",
                "stretch" => "cod/code,con/cone,cop/cope,dot/dote,hop/hope,lob/lobe,
                                mod/mode,nod/node,not/note,pop/pope,rob/robe,rod/rode,
                                tot/tote",
                "words" => [$this->CVCe["CoCe"]],
            );

        $this->clusterWords["o_e spelling of /oh/ er)"] =
            array(
                "group" => 'o_e Spellings',
                // "words" =>  $this->CVCe["CoCe"],
                "words" => array($this->CVCe["CoCe"], $this->words["bog"]),
                "spinnerE" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'o',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["o_e spelling of /oh/ (harder)"] =
            array(
                "group" => 'o_e Spellings',
                "words" => array($this->CVCe["CCoCe"]),
                "spinnerE" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'o',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["o_e spelling of /oh/ (mixed)"] =
            array(
                "group" => 'o_e Spellings',
                "words" => array($this->CVCe["CCoCe"], $this->CVCe["CoCe"]),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'o',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast o_e /h/ and o /aw/"] =
            array(
                "group" => 'o_e Spellings',
                //"review"=> true,
                "words" => array($this->CVCe["CoCe"], $this->CVC["CoC"]),
                "words2" => array($this->CVCe["CoCe"], $this->CVCe["CCoCe"], $this->CVC["CiC"]),
            );

        // reviews for a_e, o_e and a, o,  etc

        $this->clusterWords["Contrast a_e and o_e"] =
            array(
                "group" => 'o_e Spellings',
                //"review"=> true,
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"]),
                "words2" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"]),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,o',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a_e, i_e and o_e"] =
            array(
                "group" => 'o_e Spellings',
                //"review"=> true,
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"], $this->CVCe["CiCe"]),
                "words2" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"], $this->CVCe["CCiCe"]),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a, a_e and o, o_e "] =
            array(
                "group" => 'o_e Spellings',
                //"review"=> true,
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"], $this->CVC["CoC"], $this->CVC["CaC"]),
                "words2" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"], $this->CVC["CoC"], $this->CVC["CaC"]),
            );

        $this->clusterWords["Contrast a, a_e, i, i_e, and o, o_e "] =
            array(
                "group" => 'o_e Spellings',
                "review" => true,
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CaCe"],
                    $this->CVCe["CiCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CaC"],
                    $this->CVC["CiC"]
                ),
                "words2" => array(
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCaCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CaC"],
                    $this->CVC["CiC"]
                ),
                "Nreview" => true,
            );


        $this->clusterWords["Scott's Snack Stand"] =
            array(
                "group" => 'o_e Spellings',
                "pagetype" => 'decodable',
                "image1" => 'snack1.png',
                "words1" => '{ Scott\'s Snack Stand }
            Scott has a snack stand. Last
            week, he rode his bike to a shop to
            get nuts to sell at his stand. He got
            three big bags of nuts. The nuts cost
            him a lot of cash. \
            Scott slid the bags in his tote bag.
            Then he rode home. \
            When he got home, he got his
            mom to help him make hot spice
            nuts on the stove top.',

                "image2" => 'snack1.png',
                "words2" => 'Then Scott set up his stand.
            "Hot spice nuts!" he said. "Get
            a bag of hot spice nuts! Just one
            buck!" \
             A kid came by and got a bag of
            nuts. Then a man got a bag. Then
            the man\'s wife got a bag. He made
            back the five he had spent on nuts,
            plus ten more in cash!',

            );



        /////////////////////////

        $this->clusterWords["u_e spelling of /ue/"] =
            array(
                "group" => 'u_e Spellings',
                "pronounce" => "ue",
                "words" => [$this->CVCe["CCuCe"]], // hard ones right away
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'u',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast u_e /ue/ and u /uh/"] =
            array(
                "group" => 'u_e Spellings',
                "words" => array(
                    $this->CVCe["CCuCe"],
                    $this->CVC["CuC"]
                ),
            );

        $this->clusterWords["Contrast i_e and u_e"] =
            array(
                "group" => 'u_e Spellings',
                //"review"=> true,
                "words" => array(
                    $this->CVCe["CCuCe"],
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCiCe"]
                ),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,u',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast i_e, i, u_e, and u"] =
            array(
                "group" => 'u_e Spellings',
                "words" => array(
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVC["CiC"],
                    $this->CVC["CuC"]
                ),
            );

        $this->clusterWords["Contrast o_e and u_e"] =
            array(
                "group" => 'u_e Spellings',
                //"review"=> true,
                "words" => array(
                    $this->CVCe["CCuCe"],
                    $this->CVCe["CoCe"],
                    $this->CVCe["CCoCe"]
                ),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast o_e, o, u_e, and u"] =
            array(
                "group" => 'u_e Spellings',
                "review" => true,
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CuC"]
                ),
            );

        $this->clusterWords["Contrast a, a_e, i, i_e, o, o_e, and u, u_e spellings"] =
            array(
                "group" => 'u_e Spellings',
                "review" => true,
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CaCe"],
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCaCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $this->CVC["CiC"]
                ),
                "Nreview" => true,
            );

        //////////////////////////////

        $this->clusterWords["e_e spelling of /ee/"] =
            array(
                "group" => 'e_e Spellings',
                "pronounce" => "ee",
                "words" => [$this->CVCe["CCeCe"]], // only the hard ones
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'e',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast e_e /ee/ and e /eh/"] =
            array(
                "group" => 'e_e Spellings',
                "words" => array(
                    $this->CVCe["CCeCe"],
                    $this->CVC["CeC"]
                ),
            );

        $this->clusterWords["Contrast a_e and e_e"] =
            array(
                "group" => 'e_e Spellings',
                //"review"=> true,
                "words" => array(
                    $this->CVCe["CCeCe"],
                    $this->CVCe["CaCe"],
                    $this->CVCe["CCaCe"]
                ),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast i_e, i, e_e, and e"] =
            array(
                "group" => 'e_e Spellings',
                "words" => array(
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCeCe"],
                    $this->CVC["CiC"],
                    $this->CVC["CeC"]
                ),
            );

        $this->clusterWords["Contrast o_e, o, e_e, and e"] =
            array(
                "group" => 'e_e Spellings',
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCeCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CeC"]
                ),
            );

        $this->clusterWords["Review all ?_e spellings"] =
            array(
                "group" => 'e_e Spellings',
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CaCe"],
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCaCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVCe["CCeCe"]
                ),
                "spinnerE" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,c,d,f,g,k,l,m,n,p,s,t,z',
                    ''
                ), // exception list
            );

        $this->clusterWords["Review all long and short spellings"] =
            array(
                "group" => 'e_e Spellings',
                "review" => true,
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CaCe"],
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCaCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCeCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CaC"],
                    $this->CVC["CuC"],
                    $this->CVC["CeC"],
                    $this->CVC["CiC"]
                ),
                "Nreview" => true,
            );

        // (dge)
        $this->clusterWords["Digraphs (dge,nge)"] =
            array(
                "instruction" => "Words that end with '-dge' or -'nge' have a silent-e at the end,
                                    but that 'e' does NOT change the sound of the
                                    previous vowel.<br><br>
                                    Compare the sounds on the word-pairs in the
                                    Stretch page.

                                    Often if there are two letters between the vowel and
                                    the silent-e, these words do not follow the a_e pattern.",
                "group" => 'More Digraphs',
                "stretch" => 'base/badge,bide/binge,bride/bridge,dome/dodge,file/fridge,
                                    fuse/fudge,jute/judge,lobe/lodge,Pete/pledge,plume/plunge',
                "words" => $this->oddEndings["dge"],
                //                "spinner" => array('bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw',         // prefix, vowels, suffix for spinner
                //                                 'a,e,i,o,u',
                //                                 'dge',
                //                                 '')  // exception list
            );

        // (nce)
        $this->clusterWords["Digraphs (nce)"] =
            array(
                "group" => 'More Digraphs',
                "stretch" => "dane/dance,dune/dunce,mine/mince,sine/since,vine/vince,wine/wince,
                                glade/glance,trade/trance,state/stance,pride/prince",
                "words" => $this->oddEndings["nce"],
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'dge,nge,nce',
                    ''
                ), // exception list
                "Nreview" => true,
            );

        $this->clusterWords["Review Digraphs and ?_e"] =
            array(
                "group" => 'More Digraphs',
                "review" => true,
                "words" => array( /* $this->CVC['nce'].','.$this->CVC['nce'],*/
                    $this->CVCe["CaCe"] . ',' .
                        $this->CVCe["CiCe"] . ',' .
                        $this->CVCe["CoCe"] . ',' .
                        $this->CVCe["CCaCe"] . ',' .
                        $this->CVCe["CCiCe"] . ',' .
                        $this->CVCe["CCoCe"] . ',' .
                        $this->CVCe["CCuCe"] . ',' .
                        $this->CVCe["CCeCe"]
                ),
                "Nreview" => true,
            );

        //////////////////////////////////
        //  open and closed syllables
        //////////////////////////////////

        // these are all correctly represented in festival
        $compounds = "backpack,bathrobe,bathtub,chopstick,classmate,clockwise,grandstand,
              handcuff,matchstick,pancake,penknife,postman,ringworm,sandbag,
              something,spaceship,sunshine,tightrope";

        /*
$this->clusterWords["Compounds"]  =
array(
"group" => 'Introduction to Phonics',
"style"     =>  "lecture",
"instruction"   =>  "<strong>WHAT IS PHONICS?</strong><br><br>
those sounds.<br><br>",
"text"      =>  "<br>
<b>Letters:</b> There are 26 letters.  Letters make spellings and spellings make sounds.
Now can you explain why 'letters' and 'spellings' are not the same?
",
"text2"      =>  "<br>
<b>Letters:</b> There are 26 letters.  Letters make spellings and spellings make sounds.
Now can you explain why 'letters' and 'spellings' are not the same?
",

"words"     =>  $compounds,
"words2"     => $compounds,
);

 */

        //        $this->clusterWords["-ple Endings"] =
        //        $this->clusterWords["-cle Endings"] =
        //        $this->clusterWords["-gle Endings"] =

        //        $this->clusterWords["-ble Endings"] =
        //            array(
        //                "group" => 'Open and Closed Syllables',
        //                "stretch" => 'able/amble,bible/babble,bugle/bumble,cable/cobble,fable/fumble,gable/gobble,noble/nibble,ruble/rubble,sable/shamble,table/tumble',
        //                "words" => 'bible,bugle,cable,fable,gable,noble,ruble,sable,table,
        //                            babble,bubble,dabble,fumble,gamble,hobble,mumble,nibble,
        //                            nimble,pebble,rabble,rubble,treble,tumble'
        //                );

        //'rabbit,napkin'
        $closed = 'bathmat,bucket,cactus,catfish,rabbit,napkin,button,
                candid,canvas,canyon,cutlet,combat,
                damsel,dismal,fossil,
                goblet,goblin,gospel,
                helmet,hidden,jackal,
                kelvin,magnet,mammal,mantis,mascot,
                magnet,mascot,mishap,
                napkin,nutmeg,nostril,
                packet,pallid,person,picnic,pommel,pocket,puppet,publish,
                rancid,rocket,
                sunset,
                tandem,tennis,tonsil,ticket';

        //        //'tiger,pilot'
        //        $open  = 'pilot,cubic,broken,legal,omit,silent,cable';
        //
        //        $this->clusterWords["Closed Syllables"] =
        //            array(
        //                "group" => 'Open and Closed Syllables',
        //                "words" => $closed
        //                );

        //        $this->clusterWords["Open Syllables"] =
        //            array(
        //                "group" => 'Open and Closed Syllables',
        //                "words" => 'tiger,pilot'
        //                );

        ///////////////////////////////////////////////////////////////////////////
        ///////////////////////////                ////////////////////////////////
        ///////////////////////////    phonics     ////////////////////////////////
        ///////////////////////////                ////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////

        $this->clusterWords["Introduction to Phonics"] =
            array(
                "group" => 'Introduction to Phonics',
                "pagetype" => "lecture",
                "showTiles" => true,
                "text" => "<strong>WHAT IS PHONICS?</strong><br><br>
                                We use about 40 sounds for speaking English.
                                Since we only have 26 letters, we can't simply use a letter
                                of the alphabet for each sound.
                                <br><br>
                                Here's a sound that you can't find in the alphabet:  <sound>oy</sound>
                                and it has two common spellings:<br>

                                <img src='./images/soil_boy.jpg' width='500' />
                                <br><br>
                                'Phonics' is about mapping sounds to spellings that represent
                                those sounds.<br><br>",
                "text2" => "<br>
                                <b>Letters:</b> There are 26 letters.  Letters make spellings and spellings make sounds.
                                 We shouldn't say \"this letter makes a sound\", because only spellings make sounds.
                                 <br><br>

                                <b>Spellings:</b> There are about 180 common spellings, and
                                spellings make sounds. The simplest spellings
                                have one letter like 'a' in <spelling>a</spelling> which makes the
                                <sound>ah</sound> sound
                                in 'cat'.<br><br>

                                People get confused because the letter 'a' seems the same as
                                the spelling <spelling>a</spelling> which
                                makes the <sound>ah</sound> sound.  But that doesn't work for
                                the spelling <spelling>oi</spelling> which
                                makes the <sound>oy</sound> in 'soil'.<br><br>
                                <img src='./images/cat_soil.jpg' width='500' /><br>
                                Now can you explain why 'letters' and 'spellings' are not the same?
                                ",

                "text3" => "<br>
                                In this program we will show sounds like this: <sound>oi</sound> and
                                spellings like this: <spelling>oy</spelling> and <spelling>oi</spelling>.
                                <br><br>
                                We simplify the colours when we show a word-drawing.  You
                                are familiar with word-drawings if you have taken the Blending
                                program.<br>
                                <img src='./images/soil.jpg' width='500' />
                                ",

                "text4" => "<br>

                                Here is the PURPOSE of the 'PHONICS' program:<br><br>

                                <b>There are only a few sounds.<br>
                                But probably more than you think.<br>
                                You must be able to recognize them.</b><br><br>

                                Sounds have multiple spellings, and many common words have
                                uncommon spellings.  It is confusing, and we can't
                                help that.  The purpose
                                of this program is to visit the sounds and build awareness
                                of them.<br><br>
                                ",

                "words" => "soil,treat,feel,bat,rot,fight,book,bowl,howl",
                "sidenote" => "Identify the SOUND and the SPELLING of the vowel in each word.",
            );

        $this->clusterWords["Five <spelling>?_e</spelling> Spellings"] =
            array(
                "group" => 'Introduction to Phonics',
                "pagetype" => "lecture",
                "showTiles" => true,
                "text" => "<br>
                                There are five spellings that have a trailing 'e', and we link
                                the spelling together with a red bar.  You
                                have already mastered reading these spellings, .<br><br>
                                <img src='./images/gate_kite.jpg' width='400' /><br><br>",

                "text2" => "<br>
                                It is NOT correct to say that 'e' is silent in
                                'gate' or 'note'.   Nor is it correct to say that 'e' in the
                                spelling <spelling>a_e</spelling> of 'gate'
                                modifies the sound of the
                                <spelling>a</spelling>.  The correct way to think is that the spelling
                                <spelling>a</spelling> makes the sound <sound>ah</sound> and the
                                spelling <spelling>a_e</spelling> makes the sound <sound>ay</sound>.
                                <br><br>
                                For this program, let's pretend that there are NO silent
                                letters in English.  The 'k' in
                                'knife' is not silent, but rather the spelling
                                <spelling>kn</spelling> makes the sound <sound>n</sound>.  You
                                will see that this approach simplifies English spelling
                                and make it much more regular.
                                ",

                "words" => array(
                    $this->CVCe['CaCe'],
                    $this->CVCe['CiCe'],
                    $this->CVCe['CoCe'],
                    $this->CVCe['CCuCe'],
                    $this->CVCe['CCeCe']
                ),
                "sidenote" => "Identify the SOUND and the SPELLING of the vowel in each word.",
            );

        $this->clusterWords["What is a Vowel?"] =
            array(
                "group" => 'Introduction to Phonics',
                "pagetype" => "lecture",
                "showTiles" => true,
                "text" => "<br>
                                What is a vowel?
                                <br><br>
                                The answer might surprise you.
                                Before you turn to the next page, try to figure out
                                how many vowels there are.
                                <br><br>
                                The five spellings
                                <spelling>a</spelling>,
                                <spelling>e</spelling>,
                                <spelling>i</spelling>,
                                <spelling>o</spelling>,
                                <spelling>u</spelling>, and sometimes
                                <spelling>y</spelling>?
                                <br><br>
                                The five short-vowel sounds
                                <sound>ah</sound>,
                                <sound>eh</sound>,
                                <sound>ih</sound>,
                                <sound>aw</sound>,
                                <sound>uh</sound>, and five long-vowel sounds
                                <sound>ay</sound>,
                                <sound>ee</sound>,
                                <sound>igh</sound>,
                                <sound>oh</sound>,
                                <sound>ue</sound>?
                                <br><br>
                                Or something else?  What do you think a vowel is?",

                "text2" => "<br>
                                A vowel is simply a sound that you can 'sing' with
                                your mouth open for a few seconds.
                                <br><br>
                                For example, 'pet' has three
                                sounds <sound>p</sound> <sound>eh</sound> <sound>t</sound>,
                                but you can only sing the <sound>eh</sound>.
                                You can only hold the <sound>m</sound> in 'mat' or the
                                <sound>n</sound>' in 'nod'
                                if you close your mouth.
                                <br><br>
                                On the 'WORDS' tab are a few words.  Try to 'sing'
                                their sounds and
                                confirm that vowels are different from other sounds.",

                "text3" => "<br>
                                If you play with sounds for a few minutes, you will see that
                                you can hold <sound>r</sound>, <sound>wh</sound>,
                                <sound>v</sound> and <sound>j</sound>.  Those are sometimes called
                                semi-vowels and behave like vowels in many ways.  Try them.
                                <br><br>
                                So again, how many vowels are there?  We are going to
                                teach the 16 vowels across the top of this page, but there is no
                                clear answer.  Depending on who is counting and why, there
                                are between 14 and 20 vowels in English, plus the semi-vowels.

                                ",

                "words" => "soil,treat,feel,bat,rot,fight,book,bowl,howl,note,goat,bit,bug",

                "sidenote" => "Identify the part of the word that you can 'sing' with your
                                mouth open.  It is the vowel.",
            );

        $this->clusterWords["The 16 Vowels"] =
            array(
                "group" => 'Introduction to Phonics',
                "pagetype" => "lecture",
                "showTiles" => true,
                "text" => "<br>
                                The next tab of this lesson lists 16 vowels we will study in this
                                program, with an example word below.   We would like
                                you and your tutor to memorize the sounds of the vowels (don't bother
                                remembering the example words).
                                <br><br>
                                The sound <sound>ah</sound> makes an 'ahh'
                                sound like 'cat'.  We want you to see <sound>ah</sound>
                                and say 'ahh'.
                                <br><br>
                                You can practice them on tab 3 without the example words.  Then
                                visit the Words page and make sure you know them.
                                ",

                "text2" => "<br>
                                <img src='./images/16v_plus.jpg' /><br>
                                ",

                "text3" => "<br>
                                <img src='./images/16v.jpg' /><br>
                                  ",

                "words" => "soil,treat,feel,bat,rot,fight,book,bowl,howl,note,goat,bit,bug,
                                hair,car,her",

                "sidenote" => "Read the word AND the vowel sound, for example: 'CAR' - <sound>ar</sound><br>
                                'BUG' - <sound>uh</sound>
                                <br> and then find them in the vowel list at the top of the page.",
            );

        $this->clusterWords["'r'-Controlled Vowels"] =
            array(
                "group" => 'Introduction to Phonics',
                "pagetype" => 'lecture',
                "showTiles" => true,
                "text" => "<br>
                                You may notice that some vowels include the letter 'r', such as
                                <sound>air</sound>, <sound>ar</sound>, and <sound>er</sound>.  That
                                is because they are a single sound, you can 'sing' them with the
                                'r' and you cannot split them up.<br><br>

                                Some lists of phoneme vowels include <sound>ire</sound>, <sound>ore</sound>,
                                <sound>ear</sound>, and other r-sounds.  We leave them out because they can
                                usually be split into two sounds.  Consider 'fire', do
                                you say it in one syllable or two?  If you say it as one syllable,
                                can you still sing the <sound>igh</sound> and then add
                                the <sound>r</sound>?<br><br>
                                Now try that with 'fair' or 'farm' or 'fort'.  Not as easy.",

                "words" => "fire/file,tire/tile,wire/wipe,mire/mile,dire/dime,spire/spike,hire/hide",

                "sidenote" => "In the 'ire' words, the vowel is <sound>igh</sound>, and the 'r' is
                                a separate sound.  You can see that by comparing them to the
                                second word in the pair.   Check them out.<br><br>

                                Think about the <spelling>ire</spelling> words like 'fire', do you pronounce
                                them with ONE syllable or TWO?  Try it both ways.",

                "words2" => "dame/dare,fame/fare,spade/spare,mate/mare,blame/blare,rate/rare,scale/scare",

                "sidenote2" => "Compare the 'a_e' words (vowel is <sound>ay</sound>)
                                to the <sound>air</sound> words (vowel is <sound>air</sound>). The 'r' controls
                                the spelling and changes the sound. <br><br>",

            );

        ///////////////////////   StR #13  /oh/   o_e as in note,
        //                                        oa  as in goat
        //                                        oe  as in toe
        //       other: o (most), ow (grow), ough (though), ou (soul), oo (door)

        $this->clusterWords["<sound>oh</sound>"] =
            array(
                "group" => '<sound>oh</sound> <sound>ow</sound> <sound>oo</sound> <sound>oy</sound>',
                "showTiles" => true,
                "instruction" => "<br>
                        The sound <sound>oh</sound> has four common spellings:<br>
                        <ul>
                        <li><spelling>o_e</spelling> as in 'note'</li>
                        <li><spelling>o</spelling> as in 'most'</li>
                        <li><spelling>oa</spelling> as in 'goat'</li>
                        <li><spelling>ow</spelling> as in 'grow'</li>
                        </ul><br>
                        It also has less-common spellings, including:<br>
                        <ul>
                        <li><spelling>oe</spelling> as in 'toe'</li>
                        <li><spelling>ough</spelling> as in 'though'</li>
                        <li><spelling>ou</spelling> as in 'soul'</li>
                        <li><spelling>oo</spelling> as in 'door'</li>
                        </ul><br>
                        Note: we treat <sound>or</sound> as <sound>oh</sound>+<sound>r</sound>
                        as in  pore, pour, more, door, snore, store.
                        ",
                "words" => $this->vowels['oh'],
            );

        ///////////////////////   StR #14  /ow/   ow as in cow,
        //                                        ou  as in loud
        //                               other: ought (drought)

        $this->clusterWords["<sound>ow</sound>"] =
            array(
                "group" => '<sound>oh</sound> <sound>ow</sound> <sound>oo</sound> <sound>oy</sound>',
                "showTiles" => true,
                "instruction" => "<br>
                        The sound <sound>ow</sound> has two common spellings:<br>
                        <ul>
                        <li><spelling>ow</spelling> as in 'cow'</li>
                        <li><spelling>ou</spelling> as in 'loud'</li>
                        </ul><br>
                        It also has a less-common spellings:<br>
                        <ul>
                        <li><spelling>ough</spelling> as in 'drought'</li>
                        <li><spelling>o</spelling> as in 'sour'</li>
                        </ul><br>
                        Note:  Some phonics programs treat <sound>our</sound> as a sound, and we don't.
                        We think 'hour' and 'sour' are multi-syllable
                        words. There are only a half-dozen them, and there is no harm if
                        you consider them to share the <spelling>ou</spelling> of 'loud'.  We
                        think looks like this: <br>
                        <img src='./images/sour-dour.jpg' /><br><br>
                        ",
                "words" => $this->vowels['ow'],
            );

        $this->clusterWords["Contrast <sound>ow</sound>"] =
            array(
                "group" => '<sound>oh</sound> <sound>ow</sound> <sound>oo</sound> <sound>oy</sound>',
                "showTiles" => true,
                "stretch" => $this->vowels['oh/ow'],
                "stretchText" => "There is no rule or logic to some of these
                                    spellings.  All that is required is for your
                                    student to be aware of them.",
                "words" => array(
                    implode(',', $this->vowels['oh']),
                    implode(',', $this->vowels['ow']),
                ),
            );

        ///////////////////////   StR #37  /oy/   oy as in boy,
        //                                        oi as in coin

        $this->clusterWords["<sound>oy</sound>"] =
            array(
                "group" => '<sound>oh</sound> <sound>ow</sound> <sound>oo</sound> <sound>oy</sound>',
                "showTiles" => true,
                "instruction" => "<br>
                        The sound <sound>oy</sound> has two common spellings:<br>
                        <ul>
                        <li><spelling>oy</spelling> as in 'boy'</li>
                        <li><spelling>oi</spelling> as in 'coin'</li>
                        </ul><br>
                        It also has some rare spellings which we will ignore:<br>
                        <ul>
                        <li><spelling>aw</spelling> as in 'lawyer'</li>
                        <li><spelling>uoy</spelling> as in 'buoy'</li>
                        </ul><br>
                        ",
                "words" => $this->vowels['oy'],
            );

        $this->clusterWords["Contrast <sound>oy</sound>"] =
            array(
                "group" => '<sound>oh</sound> <sound>ow</sound> <sound>oo</sound> <sound>oy</sound>',
                "showTiles" => true,
                "stretch" => $this->vowels['oh+ow/ow'],
                "stretchText" => "Be very strict about sounding these pairs out.  Make
                                    sure your student hears and announces the differences.",
                "words" => array(
                    implode(',', $this->vowels['oh']),
                    implode(',', $this->vowels['ow']),
                    implode(',', $this->vowels['oy']),
                ),
            );

        ///////////////////////////////////////////////////
        ///////////////////////////////////////////////////
        ///////////////////////////////////////////////////

        $this->clusterWords["<sound>aw</sound>"] =
            array(
                "group" => '<sound>aw</sound> <sound>ay</sound> <sound>air</sound> <sound>ar</sound>',
                "showTiles" => true,
                "instruction" => "<br>
                        The sound <sound>aw</sound> has four common spellings:<br>
                        <ul>
                        <li><spelling>o</spelling> as in 'dog'</li>
                        <li><spelling>aw</spelling> as in 'law'</li>
                        <li><spelling>a</spelling> as in 'swan'</li>
                        <li><spelling>au</spelling> as in 'launch'</li>
                        </ul><br>
                        It also has some infrequent spellings:<br>
                        <ul>
                        <li><spelling>augh</spelling> as in 'caught'</li>
                        <li><spelling>ough</spelling> as in 'ought'</li>
                        <li><spelling>oa</spelling> as in 'broad'</li>
                        </ul><br>
                        And only one word with this spelling: <spelling>awe</spelling> as in 'Awe'.
                        ",
                "words" => $this->vowels['aw0'],
                "words2" => array(
                    $this->vowels['aw0'],
                    $this->vowels['aw1'],
                ),
            );

        $this->clusterWords["<sound>aw</sound> with -all"] =
            array(
                "group" => '<sound>aw</sound> <sound>ay</sound> <sound>air</sound> <sound>ar</sound>',
                "showTiles" => true,
                "words" => array(
                    $this->vowels['all'],
                    $this->vowels['alk']
                ),
                "words2" => array(
                    $this->vowels['aw0'],
                    $this->vowels['aw1'],
                    $this->vowels['all'],
                    $this->vowels['alk'],
                ),
            );

        $this->clusterWords["<sound>ay</sound>"] =
            array(
                "group" => '<sound>aw</sound> <sound>ay</sound> <sound>air</sound> <sound>ar</sound>',
                "showTiles" => true,
                "instruction" => "<br>
                         The sound <sound>ay</sound> has three common spellings:<br>
                         <ul>
                         <li><spelling>a_e</spelling> as in 'cake'</li>
                         <li><spelling>ay</spelling> as in 'pay'</li>
                         <li><spelling>ai</spelling> as in 'rain'</li>
                         </ul><br>
                         It also has some infrequent spellings:<br>
                         <ul>
                         <li><spelling>ea</spelling> as in 'steak'</li>
                         <li><spelling>ey</spelling> as in 'they'</li>
                         <li><spelling>aigh</spelling> as in 'straight'</li>
                         <li><spelling>eigh</spelling> as in 'eight'</li>
                         </ul><br>
                         ",
                "words" => $this->vowels['ay0'],
                "words2" => array(
                    $this->vowels['ay0'],
                    $this->vowels['ay1'],
                ),
            );

        //     $this->clusterWords["alphabet I"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'a,b,c,d,e,f',
        //             "scrambleSideText" => "This is an exercise to NAME the letters (like the Alphabet song).<br><br>
        //                     You can also practice the MAIN sound that each letter makes (eg: 'a' make the <sound>ah</sound> sound).",
        //             "words2" => 'g,h,i,j,k,l',
        //             "words3" => 'm,n,o,p',
        //             "words4" => 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p',
        //         );

        //     $this->clusterWords["ALPHABET I "] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'A,B,C,D,E,F',
        //             "scrambleSideText" => "This is an exercise to NAME the letters (like the Alphabet song).<br><br>
        //                     You can also practice the MAIN sound that each letter makes (eg: 'a' make the <sound>ah</sound> sound).",
        //             "words2" => 'G,H,I,J,K,L',
        //             "words3" => 'M,N,O,P',
        //             "words4" => 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P',
        //         );

        //     $this->clusterWords["alphabet II"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P',
        //             "scrambleSideText" => "This is an exercise to NAME the letters (like the Alphabet song).<br><br>
        //                     You can also practice the MOST COMMON sound that each letter makes (eg: 'a' make the <sound>ah</sound> sound).",
        //             "words2" => 'q,r,s,t,u,Q,R,S,T,U',
        //             "words3" => 'v,w,x,y,z,V,W,X,Y,Z',
        //             "words4" => 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z',
        //         );

        //     $this->clusterWords["at,ag,ap"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'at,ag',
        //             "words2" => 'at,ag,ap',
        //             "words3" => 'at,ag,ap,ak',
        //             "words4" => 'at,ag,ap,ak,ad',
        //         );

        //     $this->clusterWords["it,ig,ip"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'it,ig',
        //             "words2" => 'it,ig,ip',
        //             "words3" => 'it,ig,ip,ik',
        //             "words4" => 'it,ig,ip,ik,id',
        //         );

        //     $this->clusterWords["at,it"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'at,it',
        //             "words2" => 'at,it,ag,ig',
        //             "words3" => 'at,it,ag,ig,ap,ip',
        //             "words4" => 'at,it,ag,ig,ap,ip,ak,ik',
        //         );

        //     $this->clusterWords["ot,og,op"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'ot,og',
        //             "words2" => 'ot,og,op',
        //             "words3" => 'ot,og,op,od',
        //             "words4" => 'ot,og,op,od,on',
        //         );

        //     $this->clusterWords["at,ot"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'at,ot',
        //             "words2" => 'at,ot,ag,og',
        //             "words3" => 'at,ot,ag,og,ap,op',
        //             "words4" => 'at,ot,ag,og,ap,op,an,on',
        //         );

        //     $this->clusterWords["it,ot"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'it,ot',
        //             "words2" => 'it,ot,ig,og',
        //             "words4" => 'it,ot,ig,og,ip,op',
        //         );

        //     $this->clusterWords["at,it,ot"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "review" => true,
        //             "words" => 'at,it,ot',
        //             "words2" => 'at,it,ot,ag,ig,og',
        //             "words4" => 'at,it,ot,ag,ig,og,ap,ip,op',
        //         );




        //     $this->clusterWords["Aesop - Belling the Cat"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "style" => 'decodable',
        //             "image1" => 'belling.png',
        //             "words1" => '{ Bell>ing the Cat }
        //     The Mice call>ed a meet>ing to make a plan to get free from their en/em/y, the Cat. \
        //     They wish>ed to dis<cov>er some way to know when she was, so they would have time to run away. \
        //     Something had to be done, for the Cat\'s claws gave the mice the creeps. ',

        //             "image2" => 'belling.png',
        //             "words2" => 'They talk>ed and made man/y plan>s, but their best plan was still not ver/y good. \
        //     At last a very small Mouse got up and said:
        //     "I have a plan that I know will be good." \

        //     "All we have to do is to hang a bell on the Cat\'s neck.
        //     When we hear the bell ring>ing, we will know that she is close."',

        //             "image3" => 'belling.png',
        //             "words3" => 'All the Mice cheer>ed. This was a very good plan. \
        //      But an wise Mouse rose and said:
        //      "I will say that the plan of the small Mouse is very good. But let me ask: Who will bell the Cat?"',

        //             "image4" => 'belling.png',
        //             "words4" => ' { Lesson } \
        //             It is one thing to say that some/thing should be done,
        //             but quite an/other thing to do it.',

        //         );


        //     $this->clusterWords["Maxxi the Dog"] =
        //         array(
        //             "group" => 'For Douglas',
        //             "style" => 'decodable',
        //             "image1" => 'maxxi1.png',
        //             "words1" => '{ Maxxi has a Bath }
        //         Maxxi the dog smell>s bad. His fur is thick
        //         and matt>ed, and his muddy paws must be wash>ed. \
        //         I think he was play>ing
        //         with a duck in the slime at the pond.  Or may/be with the geese in
        //         the park.  Or, may/be he sniff>ed out a skunk. ',


        //             "image2" => 'maxxi2.png',
        //             "words2" => '

        //     Doug/las threw him in the bath.  They thrash>ed.  They strugg/le>d.
        //     Maxxi kick>ed and scratch>ed and bark>ed.  Doug/las did not
        //     give up. \
        //      "Stop barking, you silly dog" Doug/las said. "Let me wash you." \
        //     Then Maxxi re<lax>ed and let Doug/las wash him.  ',

        //             "image3" => 'maxxi1.png',
        //             "words3" => '

        //     Af/ter the bath, Maxxi shook him/self.  What a mess that made! \

        //     There was wa/ter on the floor, wa/ter on the wall>s, wa/ter every/where. Doug/las
        //     ran to get a mop.',


        //             "image4" => 'maxxi4.jpg',
        //             "words4" => '

        //     "And his teeth need to be brush>ed, too." Mom said. "Go brush his teeth." \

        //     So, Doug/las held Maxxi and brush>ed his teeth.  Maxxi had a nice smile.  \

        //     "Maxxi,"
        //     he said, "You smell ver/y nice now, and your teeth are good.  But you are a lot of work." ',

        //         );
    }



    // this function generates every possible combination of the first, second, and third letters
    public function gen3letters(array $aFirst, array $aSecond, array $aThird)
    {

        $result = array();
        foreach ($aFirst as $f) {
            foreach ($aSecond as $s) {
                foreach ($aThird as $t) {
                    $result[] = $f . $s . $t;
                }
            }
        }
        return ($result);
    }
}
