<?php

namespace Blending;


global $clusterWords;
$clusterWords = [];


$GLOBALS['allCourses'] = ['blending', 'phonics', 'decodable', 'spelling'];     // used for sanity checks?
// there should be matching files eg:  ./courses/blending.php
// TODO just interrogate the directory to find the courses available



// this is the parent of all lessons in the courses directory
class LessonAbstract
{

    public $minimumVersion = '9.9.99';   // will stop any lesson that doesn't define minimum version

    public $clusterWords = [];

    public $words = array(
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

    public $catCK = "back,hack,lack,pack,rack,sack,tack,yack,Zack";
    public $kitCK = "Dick,hick,lick,Mick,nick,pick,Rick,sick,tick,wick";

    public $aiSH = "bash,cash,dash,gash,hash,lash,mash,rash,sham,shack,
              dish,fish,wish,shin,ship,shash";

    public   $aioCK = "back,hack,Jack,lack,Mack,pack,rack,sack,tack,yack,Zack,
              Dick,hick,kick,Mick,nick,pick,Rick,sick,tick,wick,
              bock,dock,hock,jock,lock,mock,rock,sock";


    public $aiWH = "wham,whim,whiz,which,whiff,whip";

    public $aioSH;
    public $aiouSH;    // $CVC is a much bigger list of words
    public $aiouCK;
    public $aioueCK;

    public $CVC = array(
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

    public $oddEndings = array(
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


        "ee" => "bee,eel,fee,pee,see,tee,wee,beef,been,beep,beer,beet,deed,deem,deep,deer,
                    feed,feel,feet,flee,free,geek,glee,heed,heel,jeep,jeer,keel,keen,keep,knee,
                    leek,meek,meet,need,peek,peel,peep,peer,reed,reef,reek,reel,seed,seek,seem,
                    seen,seep,teen,tree,veer,weed,week,weep",


        'er' => array(),
    );

    // $temp = 'bran,plan,span,clan,gran,scan';        // very short list, but add to this lesson



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

        // BLENDING has some progressive word sets
        $this->aioSH =  $this->aiSH . ",bosh,cosh,dosh,gosh,Josh,mosh,nosh,posh,shod,shop,shot";
        $this->aiouSH = $this->aioSH . ",bush,gush,hush,lush,mush,rush,shun,shrub,shrug,shop,shot";
        $this->aiouCK = $this->aioCK . ",buck,duck,luck,muck,puck,ruck,suck,tuck,yuck";
        $this->aioueCK = $this->aiouCK . ",beck,deck,heck,neck,peck";
    }

    function loadClusterWords()
    {
        assertTrue(false, 'Did you mean instantiate LessonAbstract?');  // don't
    }

    // this function generates every possible combination of the first, second, and third letters
    public function gen3letters($aFirst, $aSecond, $aThird)
    {
        assertTRUE(is_array($aFirst));
        assertTRUE(is_array($aSecond));
        assertTRUE(is_array($aThird));

        $result = '';
        foreach ($aFirst as $f) {
            foreach ($aSecond as $s) {
                foreach ($aThird as $t) {
                    $result .= (empty($result)?'':',').$f . $s . $t;
                }
            }
        }
        return ($result);
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



}
