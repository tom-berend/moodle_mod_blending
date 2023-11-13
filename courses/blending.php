<?php

namespace Blending;




// https://www.opensourcephonics.org/first-vowel-team-stories/

///// 80-word decodables
// https://www.freereading.net/wiki/Illustrated_Decodable_fiction_passages.html
// https://www.freereading.net/wiki/Illustrated_Decodable_non-fiction_passages.html
// decoding key...
// https://www.freereading.net/wiki/Decodable_letter_combination_passages.html


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





class Blending extends LessonAbstract
{

    var $currentLesson;
    var $scriptsClass;
    var $scripts;
    var $group      = '';     // subtitles

    var $current_script  = 'unknown';       // helps loading a script


    public $stuffToReview = array(); // used for generating the reviews
    public $Nreview = 0;

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



    // this is sort of like a singleton - the $clusterWords array only gets created once per transaction
    function __construct()
    {
        global $clusterWords;
        if (empty($clusterWords)) {
            $this->loadClusterWords();   // this is expensive, so check if the static version is available first
            $clusterWords = $this->clusterWords;
        } else {
            $this->clusterWords = $clusterWords;
        }
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


    public $vowels = array(
        'ah' => array(),
        'aw0' => 'caw,haw,jaw,law,maw,paw,raw,saw,yaw',
        'aw1' => 'bawd,brawl,brawn,caw,chaw,claw,craw,crawl,draw,drawl,drawn,
                                            fawn,gnaw,lawn,pawn,prawn,
                                            shawl,thaw,yawn',
        'all' => 'all,alm,
                        bald, ball,balk,balm,
                        call,chalk,calk,calm,
                        fall,
                        hall,halt,
                        mall,malt,
                        pall,palm,
                        salt,scald,small,stalk,stall,
                        tall,talk,
                        walk,waltz',     // qualm,squall,


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

        "ee" => "bee,eel,fee,pee,see,tee,wee,beef,been,beep,beer,beet,deed,deem,deep,deer,
                feed,feel,feet,flee,free,geek,glee,heed,heel,jeep,jeer,keel,keen,keep,knee,
                leek,meek,meet,need,peek,peel,peep,peer,reed,reef,reek,reel,seed,seek,seem,
                seen,seep,teen,tree,veer,weed,week,weep",

    );



    // // the caller looks for this method...
    // public function load()
    // {

    //     if (false) { // test lessons
    //         // test lesson
    //         // because we assemble some values at runtime, we can't just define
    //         // these elements of clusterWords.   But we treat them the same
    //         // when it comes to rendering them.

    //         if ($GLOBALS['debugON']) { // lessons should never appear in production
    //             // but MIGHT if we compile the lessons with debug on

    //             $lesson = $this->newLesson(__class__, "Affix Test");
    //             $lesson->group = "Test";

    //             $words = array("con,de,in,ob", "struct", "ive,ure,ed", "ed");
    //             $words = array("con,de,in,ob", "spire", "ive,ure,ed", "ed");
    //             $words = array("un,mis", "hap", "y,en,less", "er,est,ly,ness,s,ing,ed");

    //             $words = array("", "un", "ease,hap", "y", "ly,er,ness");
    //             $page = $this->addPage('affixSpinner', "1col", 'full', "Affix Spinner", "normal", $words);
    //         }
    //     }

    //     /////////////  instructions   /////////////

    //     $lesson = $this->newLesson(__class__, 'Instructions 1');
    //     $lesson->group = 'Instructions';

    //     $HTML = '<b>Instructions</b><br><br>
    //                 Work through each tab.<br><br>
    //                 THIS page has four tabs at the top
    //                 (Instructions, Words, Browser, Results),
    //                 others may have four or five.
    //                 Click on each one in turn.  To proceed, click on \'Words\' now.<br><br>

    //                 <img src="./images/assess1.jpg" width="500" />';

    //     $page = $this->addPage('instructionPage', '', '', "Instructions", $HTML);

    //     $HTML = 'Usually read words from top to bottom.  If there is a
    //                 contrast then read across to practice contrast or top to
    //                 bottom to practice a single sound.  Use the REFRESH
    //                 button to scramble.  (Click on \'Browser\' now).<br><br>

    //                 <img src="./images/blending3.jpg" width="500" />';

    //     $page = $this->addPage('instructionPage', '', '', "Words", $HTML);

    //     $HTML = "If you are using a PC (not a tablet), put your
    //                 browser into 'Full Screen Mode'.  For Windows, press F11.  For Mac using
    //                 Chrome or Firefox, press CMD + SHIFT + F.  For Safari, click the 'stretch'
    //                 button at the top right corner.<br><br>

    //                 Try it now.  The same key(s) will exit Full
    //                 Screen Mode.<br><br>" .

    //         '<img src="./images/assess4.jpg" width="600" />';

    //     $page = $this->addPage('instructionPage', '', '', "Browser", $HTML);

    //     $HTML = 'The last tab is always a test.  Comments are optional.
    //                 "Advancing" will try another lesson but
    //                 eventually return to this one.  "Mastered" tells the system not
    //                 to show this lesson again.  The test itself is less important than
    //                 giving feedback to your student.<br><br>
    //                 Click on "Mastered" now to continue.<br><br>

    //                <img src="./images/click_mastered.jpg" width="600" />';

    //     $page = $this->addPage('instructionPage4', '', '', "Result", $HTML);

    //     $lesson = $this->newLesson(__class__, 'Instructions 2');
    //     $lesson->group = 'Instructions';

    //     $HTML = 'Use the \'Word Spinner\' to interactively create words (including
    //                 nonsense words).  And use it backwards - CALL OUT a word and ask your
    //                 student to \'spell\' it for segmenting exercise.
    //                 Usually we only change one letter at a time.<br />


    //                 <img src="./images/spinner.jpg" width="500" /><br>';

    //     $page = $this->addPage('instructionPage', '', '', "Word Spinner", $HTML);

    //     $HTML = 'The last tab is always a test.  Your student must
    //                 read the words accurately, smoothly, and confidently
    //                 in less than 10 seconds.  Accuracy is most important.
    //                 <br><br>
    //                 Skip directly to Test if your child finds an exercise easy.
    //                     Race through materials they know, and spend time where they struggle.
    //                 <br><br>



    //                 <img src="./images/test.jpg" width="500" /><br>';

    //     //   function addPage($displayType, $layout, $style, $tabname, $dataparm, $data=array(), $note=''){

    //     $page = $this->addPage('instructionPage', '', '', "Tests", $HTML);

    //     $HTML = 'The \'Navigation\' button at the top lets you move to any lesson, and
    //                 the software will take care of remembering where you left off last lesson.<br><br>
    //                 OK, that\'s about all you need to know.  15-20 minutes per day, and
    //                 try not to skip any days.   Hit the \'Mastered\' button on the
    //                 right to make these instructions go away and start the training.
    //                 <br><br>

    //                 <img src="./images/everyday.jpg" width="500" /><br>';

    //     $page = $this->addPage('instructionPage4', '', '', "Ready to Start", $HTML);

    //     /////////////  the lessons   //////////////

    //     $this->loadClusterWords();

    //     // http://www.allaboutlearningpress.com/how-to-teach-closed-and-open-syllables

    //     // consonant clusters
    //     foreach ($this->clusterWords as $key => $value) {
    //         $this->clusters($key, $value);
    //     }
    // }


    public $multi = array(
        'ah' => "Alabama,Adam,Alan,catnap,banana,canvas,Japan,Kansas,Canada,sandal,salad,mammal,rascal,bantam,Batman,caravan,Dallas,cabana",
    );

    public function contrastTitle($first, $second, $s1, $s2)
    {
        $title = "Contrast '$s1' /$first/ and '$s2' /$second/";
        return ($title);
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

    public function loadClusterWords()
    {
        $views = new ViewComponents();

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


        $catCK = "back,hack,lack,pack,rack,sack,tack,yack,Zack";
        $kitCK = "Dick,hick,lick,Mick,nick,pick,Rick,sick,tick,wick";

        $aiSH = "bash,cash,dash,gash,hash,lash,mash,rash,sham,shack,
                  dish,fish,wish,shin,ship,shash";
        $aioSH = $aiSH . ",bosh,cosh,dosh,gosh,Josh,mosh,nosh,posh,shod,shop,shot";
        $aiouSH = $aioSH . ",bush,gush,hush,lush,mush,rush,shun,shrub,shrug,shop,shot";

        $aioCK = "back,hack,Jack,lack,Mack,pack,rack,sack,tack,yack,Zack,
                Dick,hick,kick,Mick,nick,pick,Rick,sick,tick,wick,
                bock,dock,hock,jock,lock,mock,rock,sock";

        $aiouCK = $aioCK . ",buck,duck,luck,muck,puck,ruck,suck,tuck,yuck";
        $aioueCK = $aiouCK . ",beck,deck,heck,neck,peck";

        $aiWH = "wham,whim,whiz,which,whiff,whip";

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
                     jib,jig,Jim,jip,jock,
                     kid,kit,kiss,
                     lid,lip,
                     mid,miff,miss,mitt,mix,
                     nip,nit,
                     pill,pin,pit,pig,pick,
                     rib,rid,riff,rig,rim,rip,
                     sid,sill,sin,sip,sis,sit,six,
                     tiff,Tim,till,tin,tip,
                     wig,will,wit,wiz,
                     yip,
                     zig,zip,zit",

            // include 'ock' words
            "CoC" => "Bob,bog,boss,bop,box,bock,
                     dog,doff,doll,don,dot,dock,
                     fob,fog,fop,fox,
                     gob,god,got,
                     hog,hop,hot,hock,
                     job,jog,jot,
                     lob,log,lot,loss,lock,
                     mob,mod,mom,moss,mop,mock,
                     nod,non,not,
                     pod,pop,pot,pox,pock,
                     rob,rod,Ron,rot,rock,
                     sob,sod,sop,sock,
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
        //    'o/aw' => "abolish,absolve,accomplish,admonish,adopt,agnostic,albatross,amazon,apricots,atomic,
        //                     backdrop,bobcat,bombast,chopsticks,cockpit,combat,comical,contact,contradict,
        //                     cottage,crackpot,dislodge,dolphin,dominant,flintlocks,frolic,gossip,gotham,hitchcock,hodgepodge,holland,hospital,involve,jackpot,laptop,locksmith,logical,marathon,matchbox,nominal,nonprofit,obstinate,octagon,olive,optical,optimist,ottawa,parabolic,phonics,politics,profit,promise,province,robin,sandbox,scotland,shamrock,shoplifting,snapshot,tomcat,tonic,tonsils,tropical,vagabond,volcanic,",
        //    'aw/aw'=> 'backsaw,blackhawk,crawfish,drawback,drawbridge,goshawk,hacksaw,hawkbill,inlaws,jigsaw,lawman,pawnshop,sawhill,scofflaws,tomahawk,withdraw,withdrawal',
        //    'au/aw'=> 'applaud,applause,assault,audit,autistic,fauna,jaundice,saucepan,sauna,trauma'
        //);



        // $this->clusterWords["Instructions"] =
        //     [
        //         "group" => 'Instructions',
        //         "pagetype" => 'instruction',
        //         "instructionpage" => [

        //             'Instructions' => "<b>Instructions</b><br><br>
        //             Work through each tab.<br><br>
        //             THIS page has four tabs at the top
        //             (Instructions, Words, Browser, Results),
        //             others may have four or five.
        //             Click on each one in turn.  To proceed, click on \'Words\' now.<br><br>

        //             <img src='./pix/assess1.jpg' width='500' />",


        //             'Words' => "Usually read words from top to bottom.  If there is a
        //             contrast then read across to practice contrast or top to
        //             bottom to practice a single sound.  Use the REFRESH
        //             button to scramble.  (Click on \'Browser\' now).<br><br>

        //             <img src='./pix/blending3.jpg' width='500' />",

        //             'Browser' => "If you are using a PC (not a tablet), put your
        //             browser into 'Full Screen Mode'.  For Windows, press F11.  For Mac using
        //             Chrome or Firefox, press CMD + SHIFT + F.  For Safari, click the 'stretch'
        //             button at the top right corner.<br><br>

        //             Try it now.  The same key(s) will exit Full
        //             Screen Mode.<br><br>

        //             <img src='./pix/assess4.jpg' width='600' />",


        //             'Word Spinner' => 'Use the \'Word Spinner\' to interactively create words (including
        //                 nonsense words).  And use it backwards - CALL OUT a word and ask your
        //                 student to \'spell\' it for segmenting exercise.
        //                 Usually we only change one letter at a time.<br />

        //                 <img src="./pix/spinner.jpg" width="500" /><br>',


        //             'Tests' => 'The last tab is always a test.  Your student must
        //                 read the words accurately, smoothly, and confidently
        //                 in less than 10 seconds.  Accuracy is most important.
        //                 <br><br>
        //                 Skip directly to Test if your child finds an exercise easy.
        //                     Race through materials they know, and spend time where they struggle.
        //                 <br><br>

        //                 <img src="./pix/test.jpg" width="500" /><br>',


        //             'Navigation' => 'The \'Navigation\' button at the top lets you move to any lesson, and
        //             the software will take care of remembering where you left off last lesson.<br><br>
        //             OK, that\'s about all you need to know.  15-20 minutes per day, and
        //                 try not to skip any days.   Hit the \'Mastered\' button on the
        //                 right to make these instructions go away and start the training.
        //                 <br><br>

        //                 <img src="./pix/everyday.jpg" width="500" /><br>',


        //             'Results' => "The last tab is always a test.  Comments are optional.
        //              'Advancing' will try another lesson but
        //              eventually return to this one.  'Mastered' tells the system not
        //              to show this lesson again.  The test itself is less important than
        //              giving feedback to your student.<br><br>
        //              Click on 'Mastered' now to continue.<br><br>

        //             <img src='./pix/click_mastered.jpg' width='600' />",
        //         ]
        //     ];


        // set the minimum version
        $this->minimumVersion = '1.0.0';


        $this->clusterWords["Fat Cat Sat"] =


            array(
                "group" => 'Fat Cat Sat',
                // https://tfcs.baruch.cuny.edu/content-and-function-words/
                "sentences" => [
                    'Bill jump>ed the gap.^Bill jump>ed in the gap.',
                    'Pam move>ed the cat.^Bill move>ed to the cat.',
                    'Bob hit the drum.^Bob will hit the drum',
                    'Where is a nut shop?^What is a nut shop?',
                    'Is she a doc/tor.^She is a doc/tor.',
                    'The dog has a stick,^The dog has my stick.',

                ],

                "pronounce" => "ah",
                "pronounceSideText" => "We are starting the vowel %% sound('ah') %% as in Bat.

                Practice pronouncing it. Make shapes with your mouth, exaggerate, play with saying it.

                Find other words that sound like 'bat'.

                In this course, always refer to letters by their common sound.  'Bat' is spelled 'beh-ah-teh'.",

                "words" => [$this->words["bat"]],
                "sidenote" => "Have your student read the words aloud, clearly pronouncing each one.  Focus on accuracy
                               first, then speed.  Do not accept any drifting such as 'hat' drifting towards 'het', 'hut' or 'hit'.<br><br>

                                Point out that every word has one vowel (in red). We are working on the vowel %% sound('ah') %%. <br><br>

                                These lesson focus on vowels, because students
                                usually know the sounds of the consonants.  If your student
                                struggles with consonants then consider making some flashcards.<br><br>

                                Use this tab as preparation, a chance to look these words over.  The next tab is a 'Scramble', which will exercise
                                these words.<br><br>

                                After you finish the lesson, consider asking your student to WRITE these words.
                                You call them aloud, but peeking is ok.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    't',
                    '',
                ), // exception list
                "spinnertext" => "Key out a word like ‘bat’ and you will see how this works.<br><br>
                                  The Wordspinner creates both real and nonsense words.  Practice blending by having
                                  your student read the word.  Practice segmenting by calling a word and having your
                                  student key it out.<br><br>

                                  Stick to the ‘short vowel’ pronunciations.  A very small number of CVC words in English
                                  are irregular, for example ‘son’ is usually pronounced like ‘sun’.",

                "testtext" => "Your student should be able to read this list <b>accurately in</b> 10 seconds
                or less.  That indicates they are processing with automaticity, 'without thinking'.  Use the timer to challenge them.<br><br>
                When they succeed, mark the lesson as mastered, and move to the next lesson. Not ready
                yet? Mark as in-progress to record you were here.  Use the 'refresh'
                to give your student lots of chances.<br><br>
                Don't get stuck or frustrated.  It's important that your students master every skill, but
                you will see these words again.
                ",


                // "title1" => "Bad Boss",
                // "image1" => "drillbit.png",
                // "words1" => "Rick is my boss at Galatic Atomic.  He is a big/wig in our shop.  He nitpick>s and at/tack>s staff for small slip>s. \
                //             Tom is an ap/pli/cant for a job.  He will as/sist Rick. \
                //             Tom had a mis/hap with a drillbit.  He miss>ed the dis/tance. It was too long and hit a rock. Tom did not have the skill to stop.
                //             The drill got hot and the bit snap>ed in an in/stant.  Tom had to go to the hos/pit/al for stitch>s. \
                //             Rick the Boss had a fit.  He was bal/lis/tic. He did not as/sist Tom.  He ad/mon/ish>ed Tom.  He dismiss>ed Tom.  Tom lost his job. \
                //             Rick is a dipstick. ",


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
                "stretchText" => "Contrast the sounds across the page. Ask the student to exaggerate the sounds and feel the difference in their mouth.<br /><br />
                If your student struggles, review words up and down, and then return to contrasts.<br /><br />
                There will be many 'contrast' pages in the following lessons.",

                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"]
                ),

                "sidenote" => "Difficulty here is often a surprise to
                              both the student and the tutor.  <br><br>
                              If the student
                              struggles, simplify by asking only for the
                              endings 'at' and 'ap' on this tab and the following
                              scramble tab, then adding the prefix letters
                              later.",


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
                "scrambleSideNote" => "If your student struggles, try asking him to read only the last two letters (eg: 'at' and 'ag').",
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
                "scrambleSideNote" => "If your student struggles, try asking him to read only the last two letters (eg: 'at', 'ap' and 'ag').",
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
                    "scrambleSideNote" => "Try these, but don't spend much time on them,
                    and  don't worry if your student doesn't master them.<br><br>
                    The principle behind BLENDING is 'overlearning to mastery ', training the phonological
                    circuits. But the b,d,p letters probably cannot be learned that way since
                    they depend more on visual processing circuits that will take longer to train.",

                    "words" => array(implode(',', $bdq)),
                );
        }


        $this->clusterWords["Cat with -ck"] =
            array(
                "group" => 'Fat Cat Sat',
                "words" => [$catCK],
                "sidenote" => "Phonics describes the mapping between spellings and sounds.  Until now, we have
                worked with single-letter spellings and very simple mappings between spelling and sound.<br><br>

                But English has many sounds with spellings of two or more letters, many spellings that make the same sound,
                and many spellings that can make more than one sound .<br><br>

                It is important for your student to grasp this concept, so you must be clear when you talk about
                spellings and sounds.  Try to explain the following sentence, and why the words in our list
                have four letters but only three sounds.<br><br>

                <b>The spelling %% spelling('ck') %% makes the same sound %% sound('k') %% as the spelling %% spelling('k') %%",

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'k,ck,g,p,t',
                    ''
                ), // exception list


            );

        $this->clusterWords["All Cat Words"] =
            array(
                "group" => 'Fat Cat Sat',
                "review" => true,
                "words" => [
                    $this->CVC['CaC'],
                    $this->CVC['CaC'],
                    $catCK
                ],

                "title1" => "Sam the Cat",
                "image1" => "raghat.png",  // stable diffusion !!
                "words1" => "Sam the bad cat has a rag hat. \
                            A fat rat is at the mat. \
                            The bat bag has a gap. ",
                "note1" => "We haven't practiced 'the' or 'is' yet.<br><br>
                            Try the 'Decode Level' buttons, see what they do.<br><br>
                            'Bad' may cause your student to make letter-reversals.",

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,d,ck,ff,g,k,m,n,p,ss,t,zz',
                    ''
                ), // exception list
                //                "2syl"    => $twoVowels['a/ah']
            );




        $this->clusterWords["Bit Pit Sit"] =
            array(
                "group" => 'Bit Pit Sit',
                "pronounce" => "ih",
                "pronounceSideText" => "We are starting the second vowel %% sound('ih') %%as in Bit.<br><br>
                                 Practice pronouncing it. Make shapes with your mouth, exaggerate, play with it.<br><br>
                                 Find other words that sound like 'bit'.<br><br>
                                 In this course, always refer to letters by their sound.  'Bit' is spelled 'beh-ih-teh'.",



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
                "scrambleSideNote" => "If your student struggles, try asking him to read only the last two letters (eg: 'at' and 'it').",
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
                "scrambleSideNote" => "If your student struggles, try asking him to read only the last two letters (eg: 'it' and 'ig').",
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
                "scrambleSideNote" => "If your student struggles, try asking him to read only the last two letters (eg: 'ag' and 'ig').",
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
                "scrambleSideNote" => "If your student struggles, try asking him to read only the last two letters (eg: 'at', 'ag', 'it', and 'ig').",
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


        $this->clusterWords['All Bat and Bit'] =
            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "stretch" => "bat/bit,bag/big,dab/dib,dam/dim,fab/fib,fan/fin,
                    fat/fit,fax/fix,gag/gig,ham/him,hat/hit,had/hid,
                    jab/jib,jam/jim,lad/lid,lap/lip,nap/nip,pat/pit,
                    ram/rim,rap/rip,rag/rig,sad/sid,sap/sip,sat/sit,
                    tap/tip,tan/tin,wag/wig,zap/zip",
                "words" => array(
                    $this->CVC["CaC"],      // twice as many shorts as -ck
                    $this->CVC["CiC"],
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $catCK,
                    $kitCK
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,zz',
                    ''
                ), // exception list

                "title1" => 'Tim the Pig',
                "image1" => 'pigrat.jpg',
                "words1" => "Tim the pig sat, and Zap the rat sat in his lap. \
                        Zap the rat bit Tim the pig on his lip, and Tim is mad.  But Tim the pig
                        is big and fat, and will sit on Zap the rat, and Zap will be as flat as a hat. ",
                "note1" => "A few words here we don't know yet, like 'on' and 'but'.  Point them out.<br><br>
                            Also point out how many words are FUNCTION words - typically almost half
                            of the words in most text.  Most function words can be decoded normally,
                            although they will be quickly memorized.<br><br>
                            Your student must be 100% accurate with function words.  Watch carefully."


            );

        $this->clusterWords['sh- and -sh and wh-'] =
            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "words" => [$aiSH],

                "sidenote" => "The spelling " . $views->spelling('sh') . " makes a single sound %% sound('sh') %%, which is different from %% sound('s') %%.<br><br>
                It can be used both at the beginning and end of a word. ",

                "wordsplus" => [$aiSH, $aiSH, $aiWH],
                "sidenoteplus" => "This list also contains 'wh-' words.<br><br>
                                  The spelling " . $views->spelling('wh') . " makes a single sound %% sound('wh') %%.",

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,sh,t,v,w,wh,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'ck,b,d,ff,g,k,m,n,p,sh,ss,t,zz',
                    ''
                ), // exception list
                "spinnertext" => "Make a point of contrasting 's' and 'sh', and 'w' and 'wh'.",

            );


        $this->clusterWords['Bat and Bit with -sh'] =
            array(
                "group" => 'Bit Pit Sit',
                "review" => true,
                "words" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $catCK,
                    $kitCK,
                    $aiSH
                ),

                "title1" => "Black Tick",
                "image1" => "whack.png",
                "words1" => "Zack, Jack, and Mack sat in a shack. A van went click clack, click clack
            as it did pass on the track. \\

            Jack sat on a sack and cut up the
            snack that he had hid in his pack. Mack sat on a mat that was in a stack on a rack. \\

            Zack felt a smack on his back. \\

            Whack!  It was not a trick.  Jack did smack a black tick on Zack's back.  It was a wham! The tick
            had not bit Zack so he will not be sick.  Zack was glad for the whack.",

                "note1" => "The words in green ovals are 'function words' that
                            cannot be decoded and must be memorized.<br><br>
                            The words 'on' and 'not' use the vowel %% sound('aw') %% which has not yet been taught.<br><br>
                            Explain the exclaimation mark and how to emphasize when reading.<br><br>
                            After working through this page, try it again with 'Plain' decoding.",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,sh,t,v,w,wh,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'ck,b,d,ff,g,k,m,n,p,sh,ss,t,zz',
                    ''
                ), // exception list

            );

        if ($this->bdp) {
            $bdq = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat and Bit Words"] =
                array(
                    "group" => 'Bit Pit Sit',
                    "review" => true,
                    "instruction" => $this->bdpText,
                    "scrambleSideNote" => "Try these, but don't spend much time on them,
                    and  don't worry if your student doesn't master them.<br><br>
                    The principle behind BLENDING is 'overlearning to mastery ', training the phonological
                    circuits. But the b,d,p letters probably cannot be learned that way since
                    they depend more on visual processing circuits that will take longer to train.",

                    "words" => [$bdq],
                );
        }

        $this->clusterWords["Cot Dot Jot"] =
            array(
                "group" => 'Cot Dot Jot',
                "pronounce" => "aw",
                "pronounceSideText" => "We are starting the third vowel %% sound('aw') %% as in Bot.<br><br>
                                 Practice pronouncing it. Make shapes with your mouth, exaggerate, play with it.<br><br>
                                 Find other words that sound like 'bot'.<br><br>
                                 In this course, always refer to letters by their sound.  'Bot' is spelled 'beh-aw-teh'.",



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
                    "scrambleSideNote" => "Try these, but don't spend much time on them,
                    and  don't worry if your student doesn't master them.<br><br>
                    The principle behind BLENDING is 'overlearning to mastery ', training the phonological
                    circuits. But the b,d,p letters probably cannot be learned that way since
                    they depend more on visual processing circuits that will take longer to train.",

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

                "title1" => 'Kit',
                "image1" => 'kit1.png',
                "words1" => "Kit can skip. \
                Kit can flip and flop. \
                Kit can zig and zag. \
                Kit can swim. ",

                "title2" => "Kit and Stan",
                "image2" => 'kit2.png',
                "words2" => "Kit ran and hid. \
                Stan ran and got Kit. \
                Stan ran and hid. \
                Kit ran and got Stan. \
                Tag! Kit won. ",

                "title3" => "Kit's Hats",
                "image3" => 'kit3.png',
                "words3" => "Kit has hats. \
                Kit has big hats. \
                Kit has flat hats. \
                Kit has hip hats.",


                "title4" => "Kit's Cats",
                "image4" => 'kit4.png',
                "words4" => "Kit has cats. \
                Kit's cats ran fast. \
                Kit's cats lap up milk. \
                Kit's cats nap on Kit's lap.",

                "title" => "Kit's Pants",
                "image" => 'kit6.png',
                "words" => "Kit had pink pants. \
                         Kit's pants got lost at camp. \
                         Kit's mom got mad at Kit. \
                         Kit's mom can't stand lost pants.",



            );




        //////////////////////////////////
        /// cat in the hat
        //////////////////////////////////
        $wa = new WordArtAbstract();
        $mwords = implode(',', $wa->memorize_words);
        $count = count($wa->memorize_words);


        $this->clusterWords["Exception for 'Ball'"] =

            array(
                "group" => 'The Cat in The Hat',

                "instruction" => "<br>
                Your student now has three vowels (%% sound('ah') %%,%% sound('ih') %%  and %% sound('ow') %%.  Wonderful!!

                    It is urgent to start reading real books with your student. Find
                    an easy book and have it ready. The next dozen lessons will help you prepare. <br><br>

            <figure style='float:right;border:solid 20px white;'>
            <img src='pix/catinhat.jpeg' height='200px' alt='The Cat in The Hat' />
            <figcaption style='line-height:10px;'><span style='font-size:12px;'>Copyright: Random House</span></figcaption>
          </figure>

            I love Dr Seuss's 'The Cat in The Hat', even for teaching adults.
            It is real reading, and also fun.  Click on the image below to see Page 1 of 'The Cat in The Hat'.
            This should give you an idea of how complex the text should be for your student's first book.
            <br><br>

            <figure style='float:right;border:solid 20px white;'>
            <a href='pix/catinhat2.jpg' target='_blank'><img src='pix/catinhat2.jpg' height='200px' alt='The Cat in The Hat' /></a>
            <figcaption style='line-height:10px;'><span style='font-size:12px;'>Copyright: Random House</span></figcaption>
            </figure>

            There are several patterns on that page that your
            student does not yet know.  These next 10 lessons will cover some of them very quickly.<br><br>

            We will soon return to the vowel %% sound('uh') %% and our careful over-learning drills.",




                "group" => 'The Cat in The Hat',

                "stretch" => 'cat/call,bat/ball,mat/mall,tap/tall,fat/fall,hat/hall,sap/salt,tag/talk,map/malt,hag/halt,wag/walk',

                "words" => array(
                    $this->vowels['all'],
                ),
                "wordsplus" => array(
                    $this->vowels['all'],
                    $this->CVC['CaC'],
                    $catCK
                ),

                //  (usually '{$noBreakHyphen}all' or '{$noBreakHyphen}alk' or '{$noBreakHyphen}alt')
                "stretchText" => "Words with 'a+L' make
                the %% sound('aw') %% sound, which
                is different from the %% sound('ah') %% in similar-looking 'bat' / 'cat' words.<br><br>
                These words are very common (ball, walk, salt). <br><br>
                This is the same %% sound('aw') %% sound as in 'dog', which is why we give it the magenta color.
                ",

                "image1" => 'ball.jpg',
                "title1" => "Rick Hits a Fan",
                "words1" => "Rick's bat hit the fast ball. \
                Rick did not balk, he did not
                miss the ball.  He hit a
                bunt to the left wall. \
                The ball did nick a tall bald fan on the lip, and the fan
                did fall. \
                Rick's ball is lost, but Rick is calm.",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'ck,g,k,ll,lk,m,n,p,ss,st,t,zz',
                    ''
                ), // exception list
                "spinnertext" => "The spinner adds 'll','lt', and 'lk'.  Play with them.",

                //                "2syl"    => $twoVowels['a/ah']
            );


        $this->clusterWords["Ready for Harder Books'"] =
            array(
                "group" => 'The Cat in The Hat',

                $words = $aioCK,

                "sidenote" => " The ending '-ck' makes the same sound as '-k'.<br><br>
                            There is an important idea here.  'k' and 'ck' are two
                            different spellings for the sound <sound>k</sound>.<br><br>
                            It is wrong to say 'a letter make a sound', more correct to say
                            that 'a spelling makes a sound'.  This example shows that two
                            spellings cand make the same sound",

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'c,k,ck',
                    ''
                ), // exception list

            );

        $this->clusterWords["Function Words"] =
            array(
                "group" => 'The Cat in The Hat',

                "sidenote" => "<br>
                'Function Words' are the tiny words that provide structure to sentences.
                Many of them cannot be decoded, so your student must memorize them.
                We mark these words in these green circles.<br><br>

                Many of these $count words (refresh for more) are in The Cat in The Hat,<br><br>

                Watch like a hawk, because
                many older failed readers ignore these words and get them wrong, mangling the meaning
                of sentences.  Function words must ALWAYS be read correctly.<br><br>

                Don't spend much time on these words today, you will see them again and again.",

                // if you update this list, also update in displaypages->decodableReader()
                "words" => [$mwords],
                "scrambleSideNote" => "These are common words that your student must memorize (not right away).  It's too much work to decode them.<br><br>
                           'To', 'too', and 'two' should be pointed out.<br><br>
                           'One' and 'two' are not as common as the others, but cannot be decoded (and are needed in 'Cat in The Hat').",
                //                "2syl"    => $twoVowels['a/ah']
            );




        // $this->clusterWords["Multi-Syllable'"] =
        //     [
        //         "group" => 'The Cat in The Hat',

        //         "layout" => '2col',     // words are too long for 3-col
        //         "words" => [
        //             $this->multi['ah'],
        //         ],
        //         "sidenote" => "These are hard words with open syllables, but maybe your student can read them.  Try, but don't spend much time on this lesson.<br><br>
        //                          The syllable marks make them easier to read, you may want to flip back and forth to show how these words are built up.",

        //         "testtext" => "Try once, and then mark as mastered.<br><br>It is more important to start the next vowel.",
        //     ];



        $this->clusterWords["New Sound 'th' "] =
            array(
                "group" => 'The Cat in The Hat',
                "stretch" => 'tat/that,tin/thin,tug/thug,tis/this,bat/bath,got/goth,mat/math,pat/path,pit/pith,wit/with',
                "words" => [$this->vowels['th']],
                "stretchText" => "Here's a new sound %% sound('th') %% that we can use both at the front and the back.<br><br>
                        Sometimes the spelling %% spelling('th') %% makes the sound %% sound('dh') %% instead of %% sound('th') %%, as in 'other.
                        Mention it, but don't make a big deal, it shouldn't confuse your student.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

                "title1"  => "Goth Moth",
                "image1" => 'gothmoth.png',
                "words1" => "A big moth with a black goth hat had a bath with math on the wall. \
                            The moth with the goth hat did math in the bath. \
                            Then a small pink moth did spray the big moth in the bath.  What gall!",

            );


        $this->clusterWords["New Sound 'sh'"] =
            array(
                "group" => 'The Cat in The Hat',

                "words" => [$this->vowels['sh']],
                "wordsplus" => $this->vowels['sh2'],
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



        $this->clusterWords["New Spelling '-ay' says <sound>ay</sound>"] =
            array(
                "group" => 'The Cat in The Hat',
                // "review" => true,
                "words" => [$this->vowels['ay0']],
                "sidenote" => "The spelling 'ay' almost always makes the sound <sound>ay</sound>.<br><br>
                            Words that end in '-ay' (like 'bay') are a different pattern than CVC words (like 'bat'), but confusingly similar.<br><br>
                            Practice them on the word spinner.",
                // "words2" => array(
                //     $this->CVC['CaC'],
                //     $this->vowels['ay0'],
                // ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'ay',
                    '',
                    ''
                ), // exception list
            );

        $this->clusterWords["Harder <sound>ay</sound> Words"] =
            array(
                "group" => 'The Cat in The Hat',
                // "review" => true,
                // "words" => [$this->vowels['ay1']],
                "sidenote" => "These are harder <sound>ay</sound> words. But since the ending is always the same, your student might be able to handle them.  <br><br>Two-syllable 'Away' and 'Okay' may need some explanation.",
                "words" => array(
                    $this->CVC['CaC'],
                    $this->vowels['ay0'],       // repeating gives more examples from ay0 and ay1
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

                "title1" => "The Clay Tray",
                "image1" => "claytray.jpeg",  // stable diffusion - wow!
                "words1" => "Can Ray and Fay stay this day?  I want to play and Mom says okay. \\
                        We play with clay.  We  cast a tray from clay, then we spray,
                        and stray clay falls away.
                        We dis/play the tray.  But we can/not pay for the clay so we may not  have the tray. \\
                        The day is hot, the wind is calm.  Ray and Fay must go away with no delay.",

            );



        $this->clusterWords["New Sound <sound>ee</sound>"] =
            array(
                "group" => 'The Cat in The Hat',
                "words" => [$this->vowels['ee']],

                "sidenote" => "The spelling %% spelling('ee') %% **always** makes the %% sound('ee') %% sound, so
                            we are going to paint it green to make it obvious.  Point that out to your
                            student.<br><br>
                    Some phonics programs treat " . $views->spelling('eer') . " as
                    a separate sound ('beer', 'deer'), but we do not.  It is easier to teach
                    %% sound('ee') %% plus %% sound('r') %%.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,ee',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
                // "format"  => ['B/W',['th','ch']],

                "title1" => "Scott and Lee",
                "image1" => 'scottlee1.png',
                "note1" => "There are words in this story with the %% sound('eh') %% sound that your student has not yet seen,
                                like 'hen', 'red', 'let', and 'get'.  Point them out and help with them.",
                "words1" => " This is Scott Green. Scott is six. \
                    Scott's dad keeps a hog.
                    Scott's mom keeps three cats.
                    Scott keeps a sheep. \\
                    Lee the Sheep is Scott's pet.
                    Scott feeds Lee and rubs him on the
                    back. \\
                    Lee is a sweet sheep.",

                "title2" => "Red Ants",
                "image2" => 'scottlee2.png',
                "words2" => "Lee the Sheep had a bad week
                        last week. Red ants bit him on his legs
                        and feet. \
                        Lee can feel the ants that seek to feed on his feet and skin. \
                        Scott had to sweep the ants
                        with his hand to get rid of them.",

                "title3" => 'Ants Feel Bad',
                "image3" => 'scottlee3.png',
                "words3" => "Scott was mad at the ants. \
                    \"Ants,\" he said, \"Lee is a sweet
                    sheep. Feel free to munch on plants
                    and weeds, but not on Lee!\" \
                    One of the ants said, \"We feel
                    bad. We will not munch on Lee. We
                    will munch on plants and weeds.\"",

                "title4" => 'Bees',
                "image4" => 'scottlee4.png',
                "words4" => "The red ants left. But then the
                    bees got Lee! The bees stung Lee on
                    his cheek and on his feet. \
                    Scott ran up to help Lee. Then he
                    went and had a chat with the bees.",

                "title5" => 'Let Lee Be',
                "image5" => 'scottlee5.png',
                "words5" => "\"Bees,\" said Scott, \"why sting Lee
                    the Sheep? He is a sweet sheep.\" \
                    One bee said, \"Bees will be bees.\" \
                    One bee said, \"I must be me.\" \
                    Then Scott got mad. He said,
                    \"Sting the pig. Sting the hens! Sting
                    the cat. Sting the dog. But let Lee be!\" \
                    And the bees let Lee be.",

            );







        $this->clusterWords["Suffix '+ed''"] =
            array(
                "group" => 'The Cat in The Hat',

                "words" => ["
flay>ed,
play>ed,
pray>ed,
stay>ed,
sway>ed,
splay>ed,
spray>ed,
stray>ed,
call>ed,
stall>ed,
stalk>ed,
walk>ed,
talk>ed,
halt>ed,
salt>ed,
pray>ed,
halt>ed,
thaw>ed,
brawl>ed,
claw>ed,
yawn>ed,
"],   // a-o-i plus all-alt-alk, only
            );


        $this->clusterWords["Review for Cat in the Hat"] =
            array(
                "group" => 'The Cat in The Hat',
                "review" => true,
                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"],
                    $this->words["bag"],
                    $this->vowels["all"],
                    $this->vowels["ay0"],
                    $this->vowels["ay1"],
                    $this->vowels["all"],
                    $this->vowels["ay0"],
                    $this->vowels["ay1"],

                    "scrambleSideNote" => "This reviews our spellings for  <sound>ah</sound>, <sound>aw</sound>, and <sound>ay</sound>
                                sounds, which all look similar - 'bat', 'ball', 'bay'.<br><br>
            The Decodable in this lesson has a new black word 'you'.  Point it out.",
                    "spinner" => array(
                        'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,th,v,w,z', // prefix, vowels, suffix for spinner
                        'a,i,o',
                        'b,d,ff,g,k,l,lk,ll,lt,m,n,p,ss,t,th,th,y,zz',
                    ),
                )
            );

        $this->clusterWords["Play Ball"] =
            array(
                "group" => 'The Cat in The Hat',
                "pagetype" => 'decodable',

                "image1" => 'dogball.png',
                "title1" => 'Play Ball With a Dog',
                "words1" => "If you play ball in the hall, you may hit
                the clay pot or nick the cat. Or both. Then Mom say stop,
                and grab the ball. \
                It is not fun to play tag with a doll since it can not walk or talk, and you
                will win. \
                You can play ball with this dog, it will not nip or lick
                or walk. That is big fun.",
            );





        // $this->clusterWords["Ends in '-ear'"] =
        // array(
        //     "group" => 'The Cat in The Hat',
        //     // "review" => true,
        //     "instruction" => "<br>
        //     Words like 'hear' and 'near' are only here because they are in 'The Cat in The Hat'.
        //     Don't spend much time on them.  Feel free to skip this lesson, and just feed the words to your student as you encounter them.<br><br>
        //     And beware, 'bear' and 'pear' are NOT part of this group.<br><br>",
        //         "words" =>  $this->vowels['ear'],
        //     "scrambleSideNote" => "'bear' and 'pear' are NOT part of this group.<br><br>  Don't spend much time on this lesson.",
        // );


        /////////////////////////////////////////////////////////////////////

        $this->clusterWords["But Nut Rut"] =
            array(
                "group" => 'Bug Rug Jug',
                "pronounce" => "uh",
                "pronounceSideText" => "We are starting the fourth vowel %% sound('uh') %% as in But.<br><br>
                Practice pronouncing it. Make shapes with your mouth, exaggerate, play with it.<br><br>
                Find other words that sound like 'but'.<br><br>
                In this course, always refer to letters by their sound.  'But' is spelled 'beh-uh-teh'.",


                "words" => [$this->words["but"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    't',
                    ''
                ), // exception list

                //"image1" => 'dogball.png',
                "title1" => 'Frog Facts',
                "words1" => "A frog can swim, and it can be on land. It has
                    skin that is slick. \
                    A frog will sit on a log to rest. If a frog wants a
                    bug, it sits still, and when a bug lands next to it,
                    the frog snaps the bug up. \
                    Then it can jump off for a swim. Frogs jump
                    well, and they swim well. \
                    A frog has eggs, as hens do. Frog eggs are not
                    as big as hen eggs.",
                "note1" => "There are words here that students have not
                    yet practiced.  Let them try, then help them.<br><br>
                    Check for comprehension. For example, ask your student to explain
                    how a frog catches a bug.",

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

                //"image1" => 'dogball.png',
                "title1" => 'Insects',
                "words1" => "This plan/et has a lot of insects on it. Insects (or
                    bugs) are of/ten pests and can at/tack plant>ed
                    crops, animals, and us, as well. \
                    The cricket is an insect. It is big and black, and
                    it can jump as fast as a frog. In fact it must, for it
                    is of/ten hunt>ed by frogs. \
                    The ant is not as big as the cricket. Ants are
                    strong, and they dig long, twist>ed tun/nels that
                    con/nect well.",
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

                "title1" => "Which Animals Are Good Pets?",
                "words1" => "Lots of animals can be good pets. \
                Dogs are fun pets for kids. Dogs must have a
                spot to run and sniff. They will nap in bed with
                you if Mom and Dad let them. \
                Cats are fun pets too. They are swift as they
                hunt. They nap a lot. Mom and Dad can/not stop
                cats from nap>ing on the beds. Cats tend to do
                just what they want to do. \
                A rabbit can be a good pet. They are soft and
                fluff>y and can be kept in a small spot. They
                snack on plants. If you bring scraps of plants or
                a carrot to your rabbit, he will be glad. \
                Some kids have rats, and they can be a lot of
                fun. \
                Ants can be fun as well. They can be kept in a
                small plastic box with sand. They will not nap in
                bed with you. But you can look at them as they
                dig their tunnels in the sand. \
                And then there are animals who are not
                good pets. Not a lot of kids have elks or
                skunks for pets.",

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
                "title1" => "Tim's Pig",
                "words1" => "Tim play>ed in the grass by the shack where he
                    lock>ed his pigs. \
                    A man snuck up to the shack and got in.
                    Tim jumped up and ran at him. \
                    The man put one of the pigs in a bag and was
                    back on the grass with one big jump. Then he
                    ran off, and he was fast! \
                    Tim ran at him. He swung a stick to snag
                    the bag. The man ran off with not a thing in his hands,
                    and Tim had his pig back."

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
                    "scrambleSideNote" => "Try these, but don't spend much time on them, and  don't worry if your student doesn't master them.",

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
                "pronounceSideText" => "We are starting the fifth vowel %% sound('eh') %% as in Bet.<br><br>
                Practice pronouncing it. Make shapes with your mouth, exaggerate, play with it.<br><br>
                Find other words that sound like 'bet'.<br><br>
                In this course, always refer to letters by their sound.  'Bet' is spelled 'beh-eh-teh'.",


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

                "title1" => "Wren in a Nest",
                "words1" => "The wren rest>ed in her nest with her eggs. She
                    had a good nest of twigs and grass set in mud. \
                    The wren sat over her eggs all day. The nest
                    was a soft and snug spot to be. \
                    But then the wind hit the top of the elm, and the
                    sun set. It got dim, and the wren felt a chill
                    sweep over her. \
                    Still the wren sat on her eggs. At last, she felt
                    an egg jump! And in not long at all she had a
                    chick.",
                "note1" => "'Wren' is a hard word.  Spend a moment explaining the spelling."
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

                "title1" => "A Dog’s Wish",
                "words1" => "Jed was at the plant stand. The man at the
                    stand hand>ed Jed a nut. \
                    \"Plant this nut,\" he said. \"A big red dog will
                    spring up. Then it will ask you to bring it a
                    drink.\" \
                    Jeb plant>ed the nut in a box full of sand. The
                    next day, a big red dog sat next to the box.
                    \"Can I get you a drink?\" Jeb said. \
                    \"Yes,\" said the big red dog. \"And put a big hunk
                    of ham in it too!” \
                    “That will not be good.” Jeb said. \
                    “To me it will be good,” said the big red dog. He
                    lick>ed his chops, and then he lick>ed Jed.",
                "notes1" => "Explain the use of quotation marks to denote speech, and the convention
                    that we start a new paragraph each time the speaker changes. ",
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

                "title1" => "Tim Had Mumps",
                "words1" => "Tim got mumps. He was hot. He felt sick. His
                    neck felt big and hot. He had to rest in bed. \
                    Grand/dad sat by the bed. “Drink this milk,” said
                    Grand/dad. “It will help.” \
                    Tim drank the milk. \
                    Tim was upset. “What can we do?” \
                    Grand/dad said, “The mumps are not fun. But
                    just rest. Rest will help.” \
                    The next day, Tim still felt rot/ten. He rest>ed with
                    a snug blan/ket. It was dull. \
                    “Can I get up?” Tim said to Grand/dad. \
                    “Not yet,” Grand/dad said. “I had mumps,” said
                    Grand/dad. “I was sev/en.” \
                    “You were not sev/en,” said Tim. Grand/dad at
                    sev/en? Too odd. Tim grin>ed. \
                    Then he slept. \
                    “Still sick?” said Grand/dad as Tim got up. \
                    “Yes,” said Tim. The bed was damp. He let
                    Grand/dad fix up the bed. Then he got back in. \
                    Tim rest>ed and rest>ed. And then he got up
                    strong. “Yes!” he said, jump>ing from the bed. “I
                    got rid of the mumps.” ",

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


                "title1" => "Crops",
                "words1" => "If a big plot of land has a lot of plants in it, and
                they were plant>ed by men, the plants are said
                to be a crop. \
                Lots of plants can be crops, such as: plums,
                figs, car/rots, mel/ons, and catnip. Cot/ton is a
                crop, as well. \
                You have to get crops wet of/ten, and not let
                bugs or pests kill them. Frost can kill crops as
                well, but you can/not stop frost. You just have to
                trust good luck to vis/it your crop.",
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

                "title1" => "What Is an Atlas?",
                "image1" => "atlas.jpg",
                "words1" => "An atlas is a set of maps. It is helpful if you are
                    on a trip and you end up lost. Of/ten, if you do
                    not want to admit that you are lost, you will not
                    stop to ask for help. \
                    With a good atlas, you can get back on the best
                    track. An atlas of a big land will have a lot of
                    maps in it. \
                    If you want, you can get an atlas of the planet!",
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

                "title1" => 'Sandbox',
                "image1" => 'sandbox.png',
                "words1" =>
                "It is fun to be in the sandbox.
                    You can pack sand in a cup or bucket.
                    You can tip the sand from the cup or bucket
                    and get a block of sand. \\
                    Wet sand is best for this. Sand blocks are
                    strong when the sand is damp.
                    If the sand is not wet or damp, you can still
                    dump the sand in the cup or bucket.
                    You will get a hill of sand. Will it be a big hill? Or
                    will the sand spill and be flat?
                    Wet or not, a sandbox is lots of fun.",
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

                "title1" => 'Have a Picnic!',
                // "image1" => 'sandbox.png',
                "words1" => "In the spring, if the sun is out, a pic/nic is a good
                    bet for a fun thing to do. Pick a spot on the
                    grass, and fling a big blanket to sit on. \
                    Fill a bas/ket with muf/fins, nap/kins, and plas/tic
                    cups. If the pic/nic bas/ket has flaps on it, it will
                    stop in/sects that want to jump in. \
                    A pic/nic next to a pond can be splen/did. You
                    can toss scraps to the ducks and then go for a
                    swim.",
                "note1" => "Lots of two-syllable words here.  Point them out.<br><br>
                    And ask comprehension questions!"
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
                "words" => [
                    $this->words["but"],
                    $this->words["bug"],
                    $this->words["bet"],
                    $this->words["beg"]
                ],
                "wordsplus" => [
                    $this->CVC["CuC"],
                    $this->CVC["CeC"]
                ],

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'g,p,t',
                    ''
                ), // exception list


            );



        $this->clusterWords["Seth"] =
            array(
                "group" => 'Bet Get Jet',
                "pagetype" => 'decodable',
                "title1" => "Seth",
                "image1" => 'sethbed.png',
                "words1" => 'This is Seth Smith.
                        Seth is ten. \
                    Seth must get in bed at ten. \
                    Seth can jump on his bed,
                    but not past ten. \
                    Seth can stomp and romp
                    and stand on his hands, but
                    not past ten. \
                    Seth\'s dad gets mad if Seth is
                    not in bed at ten.',

                "title2" =>  "Seth's Mom",
                "image2" => 'sethmom.png',
                "words2" => 'This is Pat. Pat is Seth\'s mom. \
                    Pat can fix things, with quick hands. \
                    Pat can scrub, plan, and think. \
                    Pat can run fast. Pat is fit and trim. \
                    Pat can sing songs, and dad will drum on a tin pan.  Seth
                    will hit on his big drum, and sing. ',

                "title3" => "Seth's Dad",
                "image3" => 'sethdad.png',
                "words3" => 'This is Ted. Ted is Seth\'s dad. \
                    Ted is strong. Ted can chop big logs with
                    his ax. Ted will lift his big ax in his
                    hands and chop. \
                    Ted can lift big stumps.  Ted can lift stumps as big as a man, and bring them with him. \
                    Ted can crush tin cans with his hands, and stuff them in a big bag.',

                "title4" => "Sal's Fish Shop",
                "image4" => 'salshop.png',
                "words4" => 'Pat and Seth went in Sal\'s Fish
                Shop. Sal\'s Fish Shop is best. \
                Sal has fresh fish.   Sal has fresh shrimp.
                Sal has crabs.  Sal has clams.  Sal has squid. \
                Pat can pick fish.  Pat got fish and shrimp, and Sal did pack them in a bag.',

                "image5" => 'sethlunch.png',
                "title5" => 'Lunch',
                "words5" => 'Seth had lunch with his mom
                and dad. \
                Pat had shrimp and chips. \
                Ted had shrimp, fish, and
                chips. \
                Seth had ham and chips. \
                Munch, munch. Crunch, crunch. Yum, yum. ',
            );


        $this->clusterWords["Seth II"] =
            array(
                "group" => 'Bet Get Jet',

                "title1" => "Seth\'s Finch",
                "image1" => 'sethbird.png',
                "words1" => 'That is Seth\'s pet finch, Chip. \
                Chip can flap his wings.
                Chip can munch on ants and bugs.
                Chip can sing. \
                Chip can land on Seth\'s hand. That finch is fun!',

                "title2" => "Lost Finch",
                "image2" => 'sethbird2.png',
                "words2" => 'Seth\'s pet finch, Chip, is lost. \
                Seth can\'t spot him.
                Pat can\'t spot him.
                Ted can\'t spot him. \
                Chip is not on Seth\'s bed.
                Chip is not on Seth\'s desk. \
                Then, at last, Pat spots Chip.
                Chip hid in Pat\'s hat and
                slept.',

                "title3" => "Seth\'s Sled",
                "image3" => 'sethsled.png',
                "words3" => 'Seth\'s sled went fast. Seth held on. \
                Seth hit bumps but did not stop.
                Seth hit slush but did not stop. \
                Then Seth\'s sled hit mud.
                Splash! \
                Seth got mud on his sled.
                Seth got mud on his pants.
                Seth got mud on his hat.',

                "title4" => "Meg's Tots",
                "image4" => 'quints.png',
                "words4" => 'This is Meg.  Meg is Pat\'s best pal. \
                Pat has 1 lad, Seth. \
                Meg has 5 tots, Tom, Tim,
                Max, Sam, and Wes.
                Meg has quints!',


                "image5" => 'quints2.png',
                "words5" => 'Pat and Ted help Meg. \
                Pat sets Tim and Tom on
                Seth\'s rug.
                Ted sets Sam on Seth\'s quilt.
                Pat sets Max on Seth\'s bed. \
                Ted helps Wes stand up on
                Seth\'s desk.',

                "title6" => "Hash and Milk",
                "image6" => 'hashmilk.png',
                "words6" => 'Pat and Ted had lunch with
                Meg\'s tots. \
                Max got hash on his chin.
                Wes got hash on his bib. \
                Tim\'s milk is on Tom.
                Then Tom got milk on Tim. \
                Sam got milk on Pat and Ted.',

            );



        $fiveSounds = '';
        $views = new Views();
        foreach (['ah', 'ih', 'ow', 'uh', 'eh'] as $sound)
            $fiveSounds .= (empty($fiveSounds) ? '' : '&nbsp;&nbsp;') . "%% sound('$sound') %%";

        $this->clusterWords["Grand Review"] =
            array(
                "group" => 'Bet Get Jet',

                "instruction" => "Congratulations.  This is the last lesson, you and your student have reached the end of the
                    BLENDING module.<br><br>
                    Your student now has the five 'short' vowels $fiveSounds.<br><br>
                    <img src='pix/junie.png' height='200' style='float:right;padding:20px' />
                    Hopefully you have been reading 'Cat in the Hat' or similar.  It is now time to
                    move on to harder grade-2 chapter books.<br><br>

                    I recommend the 'Junie B. Jones' books for both boys and girls, and for
                    all ages including adults.  They are well-written, funny, and subversive.  Boys
                    will also enjoy the 'Secret Agent Jack Stalwart' series.<br><br>

                    Older students and adults are usually impatient to start harder, 'useful' books,
                    but that is always a mistake.  They will only get frustrated and make no further progress. <br><br>

                    Consider continuing tutoring with the PHONICS module, perhaps 15 minutes of drills each day
                    followed by 45 minutes of reading.  If that is too much, the reading is more important.",


                "words" => [
                    $this->CVC['CaC'],
                    $this->CVC['CiC'],
                    $this->CVC['CoC'],
                    $this->CVC['CuC'],
                    $this->CVC['CeC'],
                    $catCK . ',' . $kitCK,
                    $aiouSH,
                    $aioueCK,
                ],

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,sh,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,d,ff,g,k,m,n,p,sh,ss,t,th,zz',
                    ''
                ), // exception list

                "title1" => "The Angry King",
                "words1" => "
                   The king, clad in velvet and mink, was vex>ed,
                   cross, and angry.  \
                   He flung his big met/al cup at the map of his
                   lands and the lands of the next king over. \
                   “Bring me my can/nons,” he said at last. “I must
                   grab the land>s of the nit/wit king who has held
                    the hills and rocks west of us for too long.” \
                   The men were glum. They did not want to tell
                   the king a bad thing. \
                   “What is the prob/lem, you milk/sops?” the king
                   yell>ed. \
                   One man bit his lip. One man said, “King, this
                   task will be too big for us.” \
                   “Why, you timid rab/bit>s?” the king yell>ed. \
                   One man sum/mon>ed the pluck to tell the king
                   the bad thing. He said, “King, you have just one
                   can/non. And that one can/non is stuck in the
                   mud.“",

                "testtext" => "The most important thing now is to start reading authentic books.  Your student
                        is behind and desperately needs textbooks and lessons, but there is no shortcut
                        to strong reading.",


            );


        //     );




        // /////////////////////////////////
        // // Ready for Harder Books
        // /////////////////////////////////




        //     $this->clusterWords["New Spelling 'ck' of <sound>k</sound>"] =
        //     array(
        //         "group" => 'Ready for Harder Books',
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





        // $this->clusterWords["'sh' with consonant clusters"] =
        //     array(
        //         "group" => 'Ready for Harder Books',
        //         "review" => true,
        //         "words" => [$this->vowels['sh']],
        //         "wordsplus" => [$this->vowels['sh'],$this->vowels['sh2']],
        //         "scrambleSideNote" => "This is just a warmup - we are about to spring TWO leading consonants on your student.",
        //         // "words3" => array($this->vowels['sh'], $this->vowels['sh2']),
        //         "spinner" => array(
        //             'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
        //             'a,i,o,u,e',
        //             'b,d,ff,g,k,l,ll,m,n,p,sh,ss,t,th,sh,th,zz',
        //             ''
        //         ), // exception list
        //     );






        /////////////////////////////////////////////
        ///// consonant clusters
        /////////////////////////////////////////////


        $suffixClusters =  "band,camp,cask,calf,
                    damp,daft,fact,
                    gash,gasp,half,hand,haft,
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

                    bomb,dolf,fond,font,
                    pomp,romp,

                    bulk,bump,bunt,busk,cult,cusp,
                    duct,dumb,dump,fund,gulf,
                    hulk,hump,hunt,jump,lump,
                    musk,numb,pulp,punt,rump,
                    sump,tusk";

        $suffixDigraphs =  "bash,bush,cash,dash,dish,fish,gash,gosh,gush,hash,hush,Josh,
                lash,lush,mash,mesh,mush,nosh,posh,push,rash,rush,sash,tush,wish,

                batch,belch,bench,bitch,bunch,catch,conch,ditch,fetch,filch,
                finch,gulch,hatch,hitch,hunch,hutch,latch,lunch,match,mulch,munch,
                notch,patch,pinch,pitch,punch,ranch,retch,watch,welch,
                winch,witch,zilch,

                hack,lack,pack,rack,sack,deck,kick,pick,tick,dock,lock,mock,rock,duck,tuck,
                hock,jock,mock,buck,duck,luck,muck,puck,much,rich,such";

        $prefixClusters = "clap,clam,clan,
                    flab,flap,flat,flax,
                    glad,glam,glass,
                    plan,
                    scab,slab,slam,slap,swam,stab,snap,snag,span,spat,scat,

                blip,clip,click,flip,flit,glib,glad,
                    quit,quiz,skin,skip,snip,slit,slip,slim,spin,stick,
                    spit,split,swig,swim,twin,

                bled,fled,Greg,shed,sled,
                    glen,pled,stem,swell,

                blog,blob,blot,clog,clot,flog,flop,
                    plod,plop,plot,scot,slog,slot,smog,snob,snot,spot,
                    stop,

                club,plug,plus,plum,snug,smug,slum,scum,stub,stud,snub";


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

        // // c & k CVC (cup,kit)
        // $this->clusterWords["c and k CVC"] =
        //     array(
        //         "group" => 'Consonant Clusters',
        //         "words" => ["cab,cad,cam,can,cap,cat,
        //                 cob,cod,cog,con,cop,cot,
        //                 cub,cud,cuff,cull,cup,cut,cuss",

        //                 "kid,kiss,kit,kip,kin,kill,keg,ken"],

        //     );


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

        $this->clusterWords["Suffix Clusters"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => [$suffixClusters],

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,nt,pt,sk,sp,st',
                    ''
                ), // exception list

            );


        $this->clusterWords["Suffix Digraphs (ck, sh, ch, tch)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => [$suffixDigraphs],

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'sh, ch, tch, ph',
                    ''
                ), // exception list
            );



        $this->clusterWords["Suffix Clusters and Digraphs"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => [$suffixClusters, $suffixDigraphs],

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ck,ct,ft,lf,lk,lp,mb,mp,nd,nt,pt,sk,sp,st',
                    ''
                ), // exception list

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
                "words" => [$prefixClusters],

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


        // both prefix and suffix clusters
        $this->clusterWords["Very Short Stories"] =
            [
                "group" => 'Consonant Clusters',
                "pagetype" => 'decodable',

                // convert ” to \"   and   ’ to \'

                "title1" => 'Trap',
                "image1" => 'trap.png',
                "words1" => "“It's a trap!“ Gil said. He put his hand up to stop
                    Zed. They were on a track that ran up a hill.
                    Gil had spot>ed flat grass, past the next bend.
                    “It’s just grass,” Zed said. “We can step on it.”
                    But Gil got a rock. He flung it on the grass. The
                    rock fell in a pit. The grass had mask>ed the pit.
                    It was a trap!",
                "note1" => "Point out the conjunction “It's“ and explain that it is short for “It is“.",

                "title2" => "Hunt",
                "image2" => "earlystart.png",
                "words2" => "Dan was in his tent at camp. He had a
                        cramp in his leg. He sat up to rub it. The rest of
                        the men in the camp slept. The
                        wind hit the tent with a hiss. Dan kick>ed the flap>s shut,
                        then there was a hush. Dan got up. \
                        He drank from his cup. He had eggs and a bit of
                        ham. The sun crept up. It lit the hills. Dan was
                        glad. The rest of the men got up, and the elk
                        hunt was on.",


            ];





        // suffix plus 's' (bs, cks, ds, ff, gs, lls, ms, ns, ps, ts)
        $this->clusterWords["Suffix Clusters with 's'"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => ["bands,camps,casks,casts,facts,
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
                        tusks"],
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'cks,cts,fts,lfs,lks,lp,mbs,mps,nds,nts,pts,sks,sps,sts',
                    ''
                ), // exception list
                "Nreview" => true,
            );



        $this->clusterWords["Suffix Digraphs (ngs, nks)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => ["bangs,bongs,dings,fangs,gangs,hangs,
                        kings,pings,rings,rungs,sings,songs,wings,
                        banks,bonks,bunks,dunks,finks,junks,links,minks,monks,
                        punks,ranks,rinks,sinks,tanks,yanks"],
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ngs,nks,shs',
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
    }
}
