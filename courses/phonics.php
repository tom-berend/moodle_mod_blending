<?php


// TODO "'magnet', 'mallet', 'market', 'maggot', and 'mascot' ");
// TODO "words like 'fever' which are not e_e");
// TODO "contrast ear/ih.r  and ear/air  and ear/er (fear/bear/heard) - all are rare - esp: tear/tear");
// TODO "contrast ea/ee, ea/ih, ea/ay  (beach, beard, steak)");

//function rareSpelling_bought_thought(){
//}
//function rareSpelling_mountain_fountain(){
//}
//function rareSpelling_turkey_donkey(){
//}




// IMPORTANT:  the caller assumes a constructor.  in this case we use a method that has the
//                  same name as the class (which is a form of constructor in PHP).

// VIOLATION - I've reconfigured 'o/aw' as 'o/ow'.  it's not phonemically correct, but
//             i believe it is easier to learn this way.




//TODO:  - recognize-the-sound exercises on every new sound

//       - sort out a_e issues (s and c words like face,
//              'r' words like bare, care,
//              root-words

//       - sort out e_e issues  mere uses ih
//              'r' words like bare, care,
//              root-words

//  add harder words (eg: e_eh2)
//  add exceptions   (eg: ea_eeX)

// add suffix drills, eg:    creak/creaked/creaking/creaky

class Phonics
{

    public $clusterWords = array();

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
        'ee' => array('bee,eel,fee,Lee,pee,see,tee,wee,beef,been,beep,beer,bees,beet,deed,deem,deep,
                    feed,feel,feet,geek,heed,heel,jeep,jeer,keel,keen,keep,leek,meek,meet,
                    need,peek,peel,peep,reed,reef,reek,reel,seed,seek,seem,seen,seep,seer,
                    teen,weed,week,weep'),
        'er' => array(),
    );

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



    var $phonics = array(

        ///////////////////////   A-Group Vowel Ending ///////////////////////////
        "ar"         =>
        array(
            "group" => 'A Vowel Endings',
            "words"  => 'bar,char,car,far,jar,mar,par,scar,star,tar,war',
            "words2" => 'arch,ark,arm,art,
                        barb,barf,bark,
                        cart,carve,charm,chart,
                        farce,farm,fart,
                        garb,
                        harm,harp,harsh,
                        lark,
                        marsh,mark,mart,
                        park,parch,part,
                        scarf,shard,shark,spark,
                        tart,
                        ward,warm,
                        yarn,yard'
        ),
        "air"         =>
        array(
            "group" => 'A Vowel Endings',
            "words" => "air,lair,fair,hair,pair,chair,flair,stair,Blair,cairn",
            "words2" => "eclair,affair,repair,impair,despair,fairy,dairy,unfair"
        ),
        "aw"         =>
        array(
            "group" => 'A Vowel Endings',
            "words" => "claw,craw,draw,flaw,gnaw,haw,jaw,law,maw,raw,saw,yaw,
                            thaw,draw,gnaw",
            "words2" => "fawn,pawn,hawk,lawn,crawl,swan,shawl,brawl,brawn,prawn,yawn,Shawn"
        ),

        "ay"         =>
        array(
            "group" => 'A Vowel Endings',
            "words" => "bay,cay,day,fay,gay,hay,jay,lay,may,pay,ray,say,way"
        ),



        //////////   E-Group Vowel Endings   /////////////////////


        "ee"         =>
        array(
            "group" => 'E Vowel Endings',
            "words" => "bee,Cree,Dee,fee,flee,free,glee,hee,knee,lee,pee,see,tee,thee,tree",
            "words2" => 'beech,beef,beet,bleed,breeze,beetle,
                        cheek,creed,cheese,
                        deed,deem,
                        eel,
                        feed,fleece,fleet,freeze,freed,
                        geek,greet,green,greed,
                        heed,heel,jeep,peep,peel,
                        reed,
                        seed,seep,screen,seem,seethe,
                        sneeze,spleen,steel,steep,
                        wheel,week,weed'
        ),

        "ew"         =>
        array(
            "group" => 'E Vowel Endings',
            "words" => ""
        ),

        "ey"         =>
        array(
            "group" => 'E Vowel Endings',
            "words" => ""
        ),



        "ow"         =>
        array(
            "group" => 'O+U+Y Vowel Endings',
            "words" => ""
        ),

        "oy"         =>
        array(
            "group" => 'O+U+Y Vowel Endings',
            "words" => ""
        ),

        "ue"         =>
        array(
            "group" => 'O+U+Y Vowel Endings',
            "words" => ""
        ),

        "y"         =>
        array(
            "group" => 'O+U+Y Vowel Endings',
            "words" => "cry,dry,fly,fry,shy,sky,sly,spry,sty,try,why"
        ),


        ////////////////   A-Group Others   //////////////////////////


        "au"        =>
        array(
            "group" => 'A Others',
            "words" => 'cause,clause,
                        fault,fraud,
                        gaunt,gauze,
                        launch,laud,
                        maul,
                        sauce,
                        taunt,
                        vault'
        ),

        "wa"         =>
        array(
            "group" => 'A Others',
            "words" => 'dwarf,
                        swan,swab,swap,swarm,
                        warm,wash,want,wand,wall,walk,war,watch,wart,
                        wasp,watch,ward,warp,warn'
        ),

        "al"         =>
        array(
            "group" => 'A Others',
            "words" => 'all,alm,
                        bald, ball,balk,balm,
                        call,chalk,calk,calm,
                        fall,
                        hall,halt,
                        mall,malt,
                        pall,palm,
                        qualm,
                        salt,scald,small,stalk,stall,squall,
                        tall,talk,
                        walk,waltz'
        ),

        "igh"         =>
        array(
            "group" => 'E+I Others',
            "words" => "nigh,high,might,light,sight,sigh,slight,fight,flight,fright,
                            right,thigh,wright"
        )



    );



    public function loadClusterWords()
    {
        $views = new ViewComponents();   // eg: ->sound('th')


        $this->clusterWords["Fat Cat Sat"] =
            array(
                "group" => 'test phonics',

                "instruction" => "hello phonics",
                "words" => ["cat,fat,hat"],
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
                "words" => [$this->CVCe["CaCe"], $this->words["bag"]],
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
                "title1" => 'Cake and Grape>s',
                "words1" => "Scott got a cake to split with his
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
                "wordsplus" => array($this->CVCe["CCiCe"], $this->CVCe["CCaCe"]),
            );

        $this->clusterWords["Contrast a, a_e and i, i_e "] =
            array(
                "group" => 'i_e Spellings',
                "review" => true,
                "words" => array($this->CVCe["CiCe"], $this->CVCe["CaCe"], $this->CVC["CiC"], $this->CVC["CaC"]),
                "wordsplus" => array($this->CVCe["CCiCe"], $this->CVCe["CCaCe"], $this->CVC["CiC"], $this->CVC["CaC"]),
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
                "wordsplus" => array($this->CVCe["CoCe"], $this->CVCe["CCoCe"], $this->CVC["CiC"]),
            );

        // reviews for a_e, o_e and a, o,  etc

        $this->clusterWords["Contrast a_e and o_e"] =
            array(
                "group" => 'o_e Spellings',
                //"review"=> true,
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"]),
                "wordsplus" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"]),
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
                "wordsplus" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"], $this->CVCe["CCiCe"]),
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
                "wordsplus" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"], $this->CVC["CoC"], $this->CVC["CaC"]),
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
                "wordsplus" => array(
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

                "wordsplus" => "dame/dare,fame/fare,spade/spare,mate/mare,blame/blare,rate/rare,scale/scare",

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
                "wordsplus" => array(
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
                "wordsplus" => array(
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
                "wordsplus" => array(
                    $this->vowels['ay0'],
                    $this->vowels['ay1'],
                ),
            );


        $this->clusterWords["Introduction"] =
            array(
                "group" => 'Introduction',
                "style" => 'lecture',

                "text" => "<br>
                Theses DECODABLE stories will help bridge the chasm from decoding simple words to reading
                authentic texts.  They are intended for students who have completed the BLENDING exercises.<br><br>

                Hopefully your student can now blend and decode many one-syllable words with common spellings, such as
                'catch', 'tree', and 'bake'.  But real texts also throw up multiple types of multi-syllable words such as
                'baseball','water', and 'baking'.  These stories will help your student handle them too.<br><br>

                Also, these stories have meaning.  The goal of reading is to extract meaning from text,
                so you should question your student about their understanding.",

                "text2" => "<br>
                Our free BLENDING program builds blending and segmenting skills, which are critical to learning to read,
                and helps un-learn guessing habits.  If your student has ANY difficulty with blending, then
                start with BLENDING.<br><br>

                Here's a <a href='http://communityreading.org/wp/60-second-screening/'>simple test</a> of blending skills. If your
                student has ANY trouble with the first two pages of the test, then work through BLENDING before trying these stories.<br><br>

                (If you are not familiar with our training materials, simply press 'Completed' to get to the next set of pages.)

                Enjoy.
                ",



            );



        $this->clusterWords["The Skiff"] =
            array(
                "group" => 'Simple Decodable',
                "pagetype" => 'decodable',
                "image1" => 'skiff1.png',
                "words1" => '{ The Skiff Ride }
            "Let\'s take a ride in my skiff," say>s
            Scott.
            "What\'s a skiff?" asks Ling.
            "Um, it\'s like a ship," say>s Scott,
            "but not so big."
            The kids run to the dock. They can
            swim well, but, to be safe, they slip
            on life vests. Scott and Ling get in
            the skiff.',

                "image2" => 'skiff2.png',
                "words2" => 'Scott steers the skiff. He steers it
            to the west side of the lake. The skiff
            glides in the wind.
            Ling spots lots of fun things.
            "I see ducks by that pine tree!"
            she yells.
            "Is that a fish?" Scott asks.
            "No, that is a crane!" Ling adds.
            She say>s, "Scott, this is so much
            fun!"',


                "image3" => 'lunch1.png',
                "words3" => '{ Lunch Trades }
            Dave checks his lunch bag. "No!"
            he fumes. "It\'s ham. I ate ham all
            week! Will you trade, Ling?"
            "I\'ll trade my hot dog," Ling say>s,
            "but not my chips. Will you trade
            your lunch, Scott?"
            "I will trade," Scott say>s, "but you
            will not like what Mom gave me."',

                "image4" => 'lunch1.png',
                "words4" => '"Why?" asks Ling. "What\'s in your
            bag?"
            "A fish bone, a lump of fat, and a
            wet sock," say>s Scott.
            "No to all of those!" say>s Ling.
            "Ug!" say>s Dave. "No trade!"
            As Ling and Dave trade, Scott
            keeps his bag. He does not tell Ling
            and Dave what he has in his bag. He
            has chips, ham, a bun, and a bunch
            of red grape>s. Scott likes all of the
            things in his bag. He will not trade
            them.',

            );


        $this->clusterWords["Mike's Story"] =
            array(
                "group" => 'Simple Decodable',
                "pagetype" => 'decodable',
                "image1" => 'mike1.png',
                "words1" => '{ Mike\'s Tale }
                The kids sat by a fire.
                "Let\'s all tell tales," said Ling. "Then
                we can vote on which tale is the
                best!"
                "Let me tell mine!" Mike said. "My
                tale will scare you."
                "No!" said Dave, "You can\'t scare
                me!"',

                "image2" => 'mike2.png',
                "words2" => '"Well," said Mike, "we will see!" \
                "There\'s a Grump," Mike said, "that
                makes its home close to this spot. It\'s
                big. It has long fangs. It sleeps when
                the sun is up and wakes when the
                sun sets. The Grump can smell kids. It
                likes to grab them and . . ." \
                Just then, there was a snap. \
                "What was that?" Dave said. \
                "It was just a twig," Ling said. \
                "But what made it snap like that?"
                said Dave.',

                "image3" => 'mike3.png',
                "words3" => 'Dave was scared.
                "EEEEEEEEEEEEEEEE!" he said.
                "It\'s the Grump! Run! Run from
                the Grump!" \
                Dave got up to run, but Ling said,
                "It\'s not the Grump! It\'s just Meg!"',

                "image4" => 'green1.png',
                "words4" => '{ Green Grove Glade }
                Dave and Scott hike to Green
                Grove Glade with their moms and
                dads. \
                They stop at the gate and a man
                say>s, "Moms and dads, rest here
                where you can see your kids as they
                run, jump, and slide." \
                Scott and Dave are glad this is
                a spot for kids. They are glad their
                moms and dads are close if they get
                tired.',

                "image5" => 'green2.png',
                "words5" => 'The kids swing on the swings. They
                slide on the slides. They ride on the
                rides. When they get tired, they get
                their moms and dads and hike back
                to their homes.
                "Was it fun, Scott?" his mom asks
                when they get home.
                Scott nods and smiles.
                "What was it like?" she asks.
                Scott grins and quips, "It was fun,
                Mom! Green Grove Glade is a fun
                spot for kids!"',

            );

        $this->clusterWords["The Gift"] =
            array(
                "group" => 'Simple Decodable',
                "pagetype" => 'decodable',
                "image1" => 'gift1.png',
                "words1" => '{ The Gift }
                    Scott and Meg\'s mom is named
                    Liz. She stops off at Hope\'s Dress
                    Shop. \
                    "Hope," Liz say>s, "I need a doll\'s
                    dress. The dress on Meg\'s doll has a
                    bunch of holes in it." \
                    "Well," say>s Hope, "here\'s a dress.
                    It\'s a doll\'s size, and it\'s on sale."',

                "image2" => 'gift2.png',
                "words2" => '"This is just what I need!" say>s Liz.
                    "It will fit Meg\'s doll, and Meg likes
                    green!" \
                    Hope drops the dress in a bag. Liz
                    hands Hope cash. Hope hands the
                    bag to Liz. \
                    Hope is glad. She has made a
                    sale. Liz is glad, as well. She has a gift
                    to take home to Meg.',

                "image3" => 'sled1.png',
                "words3" => '{ The Sled Ride }
                "I\'ll drive!" said Scott, as he sat on
                the sled. Jade and Meg got on next.
                Dave was the last one on the sled.
                He sat in back. \
                The sled slid off. It went fast.
                "Scott," Jade said, "steer to the left!
                There\'s a big stone there by the-" \
                Smack! The sled hit the stone. The
                kids fell off.',

                "image4" => 'sled2.png',
                "words4" => 'Scott went to check on Jade. /
                "Ug!" Jade said. "I feel like I broke
                all the bones in my leg!" /
                "Hop on the sled," Scott said. "I
                will drag it home." /
                Meg went to check on Dave. /
                Dave said, "I froze my nose!" /
                "Hop on the sled with Jade," said
                Meg. "Scott and I will drag it home."',

            );



        $this->clusterWords["The Boss"] =
            array(
                "group" => 'Simple Decodable',
                "pagetype" => 'decodable',
                "image1" => 'boss1.png',
                "words1" => '{ The Boss }
                "Meg," Scott say>s, "when Mom
                and Dad are on their trip, I will be
                the boss here."
                "You are not the boss of me!" say>s
                Meg.
                "I\'m the boss!" say>s Scott.
                "You are not!" say>s Meg.',

                "image2" => 'boss2.png',
                "words2" => 'Scott glares at Meg. Meg glares
                back at him. Just then Mom steps in
                and taps Scott on the back. "Scott,"
                she say>s, "meet Jen. Jen will be the
                boss till Dad and I get back."
                "Meg\'s boss?" Scott asks.
                "Meg\'s boss and Scott\'s boss," his
                mom say>s.
                "Rats!" say>s Scott. "When will I get
                to be the boss?"',


                "image3" => 'kite1.png',
                "words3" => '{ The King of Kites }
                "What\'s that?" Dave asks. \
                "It\'s a kite I made," say>s Scott. \
                "Can I help you test it?" Dave
                asks. \
                "Yes," say>s Scott. \
                The kids take the kite close to the
                lake to test it. Scott grabs the string.
                Then he runs as fast as he can.',


                "image4" => 'kite2.png',
                "words4" => 'The wind grabs Scott\'s kite. The
                kite zips up. It rides on the wind. It
                shines in the sun. The wind lifts it up
                till it is just a speck. \
                Dave cheers. \
                "Scott," he yells, "you are the man!
                That kite you made is the best kite of
                all time! You are the King of Kites!"',
            );






        $this->clusterWords["Petshop"] =
            array(
                "group" => 'Simple Decodable',
                "pagetype" => 'decodable',
                "image1" => 'petshop1.png',
                "words1" => '{ In the Pet Shop }
                Scott is in a pet shop. He spots
                a chimp in a pen. The chimp hangs
                from a branch. Then he jumps up on
                a big red cube and grins at Scott. \
                Scott sings a tune to the chimp.
                The chimp waves back. Scott likes
                the chimp, and the chimp seems to
                like him!',

                "image2" => 'petshop2.png',
                "words2" => '"Mom," Scott say>s, "this chimp
                is so cute. He got up on his cube
                and waved at me! Can I take him
                home?" /
                "No," say>s his mom. "My home is a
                chimp-free zone." /
                Scott stares at the chimp. His mom
                can see that he is sad, so she tells
                him he can get a fish. /
                Scott is so sad he can\'t take the
                chimp home, but he is glad he gets
                to take a fish home.',

                "image3" => 'cave1.png',
                "words3" => '{ The Cave }
                Scott and Jade are on a hike. \
                Jade spots a cave and peeks in.
                "Are there bats in there?" Scott
                asks. \
                "I can\'t tell," Jade say>s, "but I
                hope so! I like bats!" \
                "Ick!" say>s Scott. "Bats are not
                cute."',


                "image3" => 'cave2.png',
                "words3" => 'Scott and Jade step in the cave. \
                Jade yells, "Bats, where are you?
                Wake up!" \
                Scott say>s, "Let the bats sleep." \
                Just then a bat glides up. It flaps
                its wings. It dips and spins. \
                Jade stares at the bat and smiles.
                Scott ducks and yells, "Hide! A
                bat!"',

            );






    }
}
