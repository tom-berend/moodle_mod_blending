<?php namespace Blending;


//https://howtospell.co.uk/y-to-i-spelling-rule


// 'compounds' needs dictionary cleanup


class Spelling extends LessonAbstract
{

    var $clusterWords = array();


    // these are two-syllable compounds using ONLY cvc or cvce style words
    //      all have been tested in the festival dictionary.
    var $compounds =  "backpack,bathrobe,bathtub,chopstick,classmate,clockwise,grandstand,
                handcuff,matchstick,pancake,postman,ringworm,sandbag,
                something,spaceship,sunshine,tightrope,doghouse";



    //
    //// multi-syllable compounds like 'ham-ber/ger'
    //var $compounds2 = "hamburger,dinosaur,basketball,dishwasher,strawberry,
    //                   octopus,telephone,microwave,grasshopper,watermelon,
    //                   butterfly,fingerprint,
    //                   hamburger,helicopter";

    // these have been verified to be in the dictionary
    var $compounds2 = "obedient,celebration,librarian,impossible,invisible,
                    appreciate,apologize,supermarket,television,
                    elevator,escalator,thermometer,historical,motorcycle,
                    misunderstand,
                    alphabetical,mathematical,disorganization,disagreeable,electricity,
                    cafeteria,unforgettable,university,
                    cooperation,communication,imagination,apologetic,misunderstanding,immediately,
                    basketball,bicycle,blueberry,library,umbrella,principal,privacy,
                    policeman,policewoman,envelope,telephone,
                    computer,dangerous,grandmother,
                    grandfather,grasshopper,lemonade,tricycle,fingernail,butterfly,slippery,
                    vitamin";


    // two-syllable words that thave been tested in the festival dictionary
    var $compounds3 =  "anybody,airplane,birthday,armpit,
                backpack,bathrobe,bathtub,blindfold,boathouse,baseball,bedroom,
                chopstick,classmate,clockwise,classroom,cupcake,
                doorknob,dollhouse,doghouse,
                fireman,football,flashlight,
                grandstand,
                handcuff,homework,
                matchstick,
                necklace,
                pancake,penknife,postman,popcorn,peanut,playground,
                ringworm,railroad,redhead,
                sandbag,something,spaceship,sunshine,skateboard,something,snowstorm,
                tightrope,toothbrush,teapot";



    // these have NOT been verified to be in the dictionary
    var $compounds4 = "anybody,obedient,celebration,librarian,discovery,impossible,invisible,
                    appreciate,apologize,supermarket,television,temperature,
                    calculator,elevator,escalator,thermometer,historical,motorcycle,
                    misunderstand
                    alphabetical,mathematical,disorganization,disagreeable,unquestionable,electricity,
                    cafeteria,unforgettable,vocabulary,veterinarian,university,congratulations,
                    cooperation,communication,imagination,apologetic,elementary,misunderstanding,immediately,
                    basketball,bicycle,blueberry,library,umbrella,principal,privacy,
                    piggybank,policeman,envelope,telephone,screwdriver,beautiful,
                    computer,dangerous,grandmother,
                    grandfather,grasshopper,lemonade,tricycle,fingernail,butterfly,slippery,
                    vitamin,government";


    // verbs, can add 'ing'              // these have NOT been verified to be in the dictionary
    var $compounds5 = "admit,avoid,abhor,carol,decay,enter,embed,fasten,forbid,gather,hammer,incur,
                    label,listen,permit,program,propel,recur,suggest,travel,total,unplug";

    // some that don't work:  blackboard,

    var $doublingSimple = "bat,jam,map,rap,tan,flap,clip,flip,plan,sled,flop,spot,
                    pin,sip,tip,run,wet,
                    jog,pot,rot,bet,gel,pet,wed,top,jot";

    // clusters at front make it harder
    var $doublingSimpleII = "flap,clip,flip,plan,sled,flop,spot,flit,knit,plug";

    // two consonants at the end
    var $notDoublingI = "raft,act,rent,sell,spell,vent,land,call,walk,band,pitch";

    // digraph vowels
    var $notDoublingII = "sail,steam,beat,row,gnaw,stow,hurt,kneel,fix,wax";

    // two-syllable words (ingnore stress rule for now)
    var $doublingComplex = "propel,travel,cancel,spiral,total,label,level,model,compel";
    var $doublingComplexNot = "open,orbit,order,permit,quiver,season";

    var $yToI        = "army,pry,fry,study,supply,try,marry,vary,worry,apply,bury,
                    carry,comply,modify,notify,deny,envy";

    var $notYToI     = "pray,toy,obey,prey,survey,deploy,key,okay,outlay,pay,play,relay,stay,spray,annoy,deploy";

    var $suffixedWords = "rented,haunted";

    var $twoSyllable =  "protest,duckling,sleepy,safety,liquid,
                            season,flower,trainer,funnel,baseball,minus,unit,
                            current,dozen,retreat,
                            greater,custard,mammal,
                            lonely,mayor,figure,planet,
                            survive,percent,eclipse,
                            insect,compass,radar,system,
                            pollen,symbol,pupil,convex,
                            matching,disease,easy,central,cancer,
                            stitches,factor,amount,larva,
                            sequence,treatment,primate,
                            fossil,salad,fraction,
                            hundred,concert,awful,account,crystal,
                            revolve,fewer,cyclone,immune,reptile,instinct,
                            subtract,connect,friction,complete,
                            control,humid,motion,product,voyage,fungus,
                            kidney,success,thousand,
                            potato,rabbit,cartoon,hamster,pencil,candy,slipper,
                            window,monkey,rocket,ketchup,glasses,spider,
                            zebra,doctor,magnet,garden,number,napkin,pocket,
                            zipper,winter,pumpkin,jacket";

    // words that end in -ble, -fle, -ple, etc.
    var $bleWords  = "bible,cable,noble,table,hobble,wobble,nibble,scribble,bubble,
                    wobble,double,trouble,scrabble,
                    circle,uncle,article,particle,cubicle,
                    vehicle,miracle,obstacle,rankle,
                    cackle,crackle,shackle,tackle,sparkle,heckle,fickle,
                    pickle,tickle,sprinkle,twinkle,wrinkle,buckle,
                    chuckle,cradle,ladle,bridle,sidle,doodle,noodle,poodle,
                    needle,paddle,meddle,peddle,fiddle,middle,riddle,cuddle,
                    huddle,muddle,puddle,candle,handle,dwindle,swindle,bundle,
                    hurdle,dawdle,waddle,bugle,angle,haggle,giggle,wiggle,
                    juggle,smuggle,snuggle,struggle,gurgle,wriggle,trifle,stifle,
                    baffle,raffle,muffle,ruffle,scuffle,shuffle,truffle,table,apple";


    // note the difference between reptile and tickle
    // some words don't split right, like grumble and gamble.


    var $drop_the_e =       // drop 'e' for 'ing' affix
    "bike,owe,cave,cure,dive,drive,leave,phone,price,probe,love,
                        raise,range,quake,love,lose,case,urge,wake,ache,bake,fake,stare";

    var $drop_the_e_able =       // drop 'e' for 'able' affix
    "bike,cure,dive,drive,probe,love,
                        lose,wake,bake,fake";

    var $drop_the_e_final =  array(       // drop 'e' for 'ing' affix
        "biking,owing,caging,caving,curing,diving,driving,leaving,phoning,pricing,probing,
                        raising,ranging,quaking,loving,losing,making,urging,waking,rising,aching,baking,faking,staring",
        "cured,poked,caged,caved,cured,phoned,priced,probed,
                        quaked,loved,urged,ached,baked,faked,stared"
    );


    var $notDrop_the_e =   // don't drop 'e'
    "be,flee,see,knee,agree";

    var $w_ion = "act,port,note,dict,edit,elate,
                medal,delete,verse,donate,ignite,vacate,connect";

    var $w_ous  = "copy,glory,envy,ruin,nerve,rigor,pore,fame,humor,riot,joy,vary,fury,fibre,vapor";

    var $w_ist = "race,elite,real,art,tour,cycle,nude,ego,harp,violin,arson,pure,ideal,
                active,union,novel,mural,absurd,extreme,future";

    var $w_ize = "style,ion,real,lion,idol,equal,oxide,legal,penal,immune,mobile,vapor,
                    vocal,item,polar,italic";


    // useful words "act,add,bark,leak,mail,pull,pump,raft,push,rent,rain"





    // the caller looks for this method...
    function load()
    {


        $arrow = '&#8594;';        //HTML arrow character




        /////////////  instructions   /////////////

        $lesson = $this->newLesson(__class__, 'Instructions 1');
        $lesson->group = 'Instructions';

        $HTML = '<b>Instructions</b><br><br>
                    Work through each tab. The first tab will often contain a lesson, with
                    examples on the remaining tabs.<br><br>
                    THIS page has five tabs at the top
                    (Tabs, Explore, Blending, Browser,Completed),
                    others may have four or five.
                    Click on each one in turn.  To proceed, click on \'Explore\' now.<br><br>

                    <img src="./images/assess1.jpg" width="500" />';

        $page = $this->addPage('instructionPage', '',    '',   "Tabs",   $HTML);


        $HTML = 'This program is not about drills, instead we hope you will
                    \'explore\' these lessons with your student, and that both of you will find
                    them interesting.<br><br>

                    You will read the lessons to your student,  and lead a discussion of each lesson.
                    Much of the material may be unfamiliar to you, even if you are
                    a skilled reader.  Don\'t worry.  (Click on \'Blending\' now).<br><br>';

        $page = $this->addPage('instructionPage', '',    '',   "Explore",   $HTML);

        $HTML = "If your student struggles with decoding, then start
                    with the BLENDING program first.  Your student
                    should be able to read these words quickly, smoothly, accurately, and confidently (perhaps
                    after trying a few practice words).<br>" .

            '<img src="./images/cent.jpg" width="600"/>';

        $page = $this->addPage('instructionPage', '',    '',   "Blending",   $HTML);



        $HTML = "If you are using a PC (not a tablet), put your
                    browser into 'Full Screen Mode'.  For Windows, press F11.  For Mac using
                    Chrome or Firefox, press CMD + SHIFT + F.  For Safari, click the 'stretch'
                    button at top right corner.<br><br>

                    Try it now.  The same key(s) will exit Full
                    Screen Mode.<br><br>" .

            '<img src="./images/assess4.jpg" width="600" />';

        $page = $this->addPage('instructionPage', '',    '',   "Browser",   $HTML);


        $HTML = 'The last tab has a control to move you to the next lesson.
                    Click on "Completed" now to continue.<br><br>
                     Comments are optional,
                     and will be seen by the developers.  We would appreciate your feedback and suggestions for improvement.';

        $page = $this->addPage('instructionPage2', '',    '',   "Completed",   $HTML);




        //////////////////  demo pages

        //$lesson = $this->newLesson(__class__, 'demo');
        //$lesson->group = 'demo';
        //
        //$words = "bat,jam,map,rap,tan,
        //            pin,sip,tip,
        //            jog,pot,rot,bet,gel,pet,wed,top,jot";
        //$page = $this->addPage('wordListMatrixTriple',    "1col",  'none',   "WLM-Triple",       "normal",   $words,  'ed');
        //$page = $this->addPage('wordListMatrixScramble',  "1col", 'none',    "WLM-Scramble",     'ed,ing',   $words,  'sidebar message goes here');
        //
        //
        //
        //$ml = new matrixLesson();
        //    $ml->bases = 'test';
        //    $ml->text = "<p>See what you can do with 'test'.</p><br>
        //        <p>Why aren't 'greatest' or 'intestine' valid constructions
        //        from the base 'test'?</p>";
        //    $ml->hints = 'de<b>test</b>,con<b>test</b>,pro<b>test</b>,at<b>test</b>,<b>test</b>ament,<b>test</b>ify,pro<b>test</b>ant,<b>test</b>able,re<b>test</b>';
        //    $ml->addMatrixLesson('demo','testMatrix');
        //

        ////////////////////////////////////////////////////////


        $this->clusterWords = [];

        // set the minimum version
        $this->minimumVersion = '1.0.0';


        $this->clusterWords["Introduction"] = array(
            "group"     =>  "Basics",
            "style"     =>  "lecture",
            "text"      =>  "SPELLING is about multi-syllable words.  The most interesting way
                                 to create words in English is by adding bits of meaning together.<br><br>

                                We build many long words by starting with a shorter
                                word and then adding beginnings and endings.  You already know
                                the standard endings, like these:<br /><br />

                                <img src='images/jumped.jpg' width='400' /><br /><br />

                                'Sounding out' these words is too much work.
                                You must chop off the beginnings and endings to find the inside word.
                                ",

            "spinner"   =>  array(
                "You already know these common endings.  Click on the
                                      'spinner' below to create longer words that will not surprise you.",
                "", "walk,jump,shout", "er,ing,ed,s", ""
            ),

            "text2"      => "We create long words by starting with a base or compound, and
                                then adding prefixes and suffixes.
                                <br /><br />

                                <img src='images/accounting.jpg' width='350' />",

            "spinner2"   =>  array(
                "If we tried to sounbd out 'impartiality' using phonics, we would have to
                                       decode this:<br>
                                       <img src='images/impartiality.jpg' width='450' />
                                       <br><br>
                                       That's too hard.  Instead, click on the spinner to see how this word is built up
                                       from bits of meaning.  If you are 'partial' then you prefer a part. If
                                       you are 'impartial' then you don't.",
                "im", "part", "ial", "ity"
            ),


            "words"     =>  "dis+ap+point+ment,
                                 un+for+give+able,
                                 geo+graph+ic+al,
                                 heart+break+ing,
                                 ef+fort+less+ly,
                                 mis+under+stand+ing,
                                 help+less+ness,
                                 micro+organ+ism+s,
                                 un+en+force+able,
                                 re+strict+ion+s",
            "sidebar"   =>  "Read these words.  They become easy when you see how they are built."
        );



        $this->clusterWords["Affixes"] = array(
            "group"     =>  "Basics",
            "style"     =>  "lecture",
            "text"      =>  "A 'simple' word like <b>'press'</b> is
                                called a base, and the words that are built using it will carry its meaning.<br><br>
                                So, we 'press' (squeeze, from the Latin <i>pressare</i>), but
                                we also express, impress, compress, repress,
                                depress, oppress, and suppress.  Can you see how these words carry the
                                meaning of <b>'press'</b>?.<br><br>
                                We can also add endings like '-ive' or '-ed' or '-ing',
                                making hundreds of different 'press' words. Beginnings and endings are
                                called 'affixes'.  There are only a few
                                dozen common affixes, and you already know them.",

            "spinner"   =>  array(
                "We know the verb 'to press'. Click on the buttons to build other 'press'
                                      words.  Can you see how they somehow carry the meaning of 'press'.  Notice
                                      how many words we build with just these few affixes.",
                "im,ex,sup,re,de,com", "press", "ion,ed,ing,ive", ""
            ),

            "text2"      => "Did you have trouble with the '-ion', as in 'depression'?
                                When we sound it out, the 'ss' moves to the last syllable (some
                                teachers split syllables between 'ss' as if there were two sounds).  And it
                                changes from an /s/ to an /sh/ sound.<br><br>

                                <img src='images/depression.jpg' width='450' /><br><br>

                                But looking at meaning, 'ss' remains with 'press', of course.<br><br>

                                We will see this again and again.  The way we spell words is according to MEANING,
                                and not the way we PRONOUNCE them.  This course will
                                focus on MEANING.",

            "words"     =>  "im+press+ive,de+press+ion,op+press+er+s,
                                 com+press+able,ir+re+press+ive+ly,ex+press+ion+s,
                                 de+press+ure+ize,un+im+press+ed,anti+de+press+ive+s,
                                 un+re+press+able,ex+press+ion+less+ly,im+press+ion+ist+ic,
                                 ir+re+press+ible,sup+press+ant+s,in+ex+press+ive,ex+press+ive+ness",
            "sidebar"   =>  "Can you sound out these 'press' words?  Can you guess out what they mean?
                                Can you think of more 'press' words?<br><br> Click on 'Refresh' for more."
        );



        $this->clusterWords["Bound Bases"] = array(
            "group"     =>  "Basics",
            "style"     =>  "lecture",


            "text"     =>  "Many bases are not words by themselves.
                                For example, 'hap' (from Old Norse <i>happ</i>) means 'luck'
                                but we haven't used that word since Shakespere's time.
                                Modern words like 'happy', 'mishap', 'perhaps', and 'happen' are built
                                from 'hap'.  Can you see the 'luck' in them?<br><br>
                                The next tab has some words that use 'rupt' which means to break or to burst.
                                Can you see the meaning of 'break' in each word?",

            "spinner"   =>  array(
                "We don't use <b>'rupt'</b> by itself, but we can make about 20 fairly common words by
                                      combining it with just the affixes below.  Try it.  Did any of the words surprise you?
                                      Can you see the idea of 'breaking' in these words?",
                "e,dis,inter,cor", "rupt", "er,ion,ed,ing", ""
            ),

            "text2"     =>  "The rules of connecting bases and affixes govern how
                                words are spelled. Bases and affixes build the meaning of a word, often in
                                surprising ways. The study of how words are formed is called 'morphology'.<br><br>

                                The most surprising thing about English spelling is how regular it is.  There
                                are a few simple rules of spelling, and we are going to explore them together.<br><br>

                                In the meantime, can you read the 'rupt' words on the following tab?",

            "words"     =>  "rupt+ure,e+rupt+ion,ab+rupt+ness,bank+rupt+cy,bank+rupt+ed,cor+rupt+ing,cor+rupt+ion,
                                ir+rupt+ion+s,bank+rupt+ing,cor+rupt+ible,cor+rupt+ion+s,dis+rupt+ion+s,
                                inter+rupt+ed,dis+rupt+ive+ly,inter+rupt+ing,inter+rupt+ion,in+cor+rupt+ible,
                                inter+rupt+ion+s",
            "sidebar"   =>  "Can you see the 'breaking' meaning in these 'rupt' words?<br><br>Can you
                                now understand 'uninterruptedly' or 'incorruptibility'?"
        );





        $this->clusterWords["Compounds"] = array(
            "group"     =>  "Basics",
            "style"     =>  "phonics",
            "text"      =>  "We can join two bases to create a 'compound' word,
                                    for example joining 'space' and 'ship' into 'spaceship',
                                    or 'hair' and 'cut' into 'haircut'.<br><br>
                                    You may have noticed 'bank+rupt' in the previous lesson,
                                    it is a sort-of compound word, even though the base 'rupt'
                                    is not a stand-alone word.
                                    <br><br>
                                    If we can identify the bases in a compound, then we can usually
                                    sound them out using the phonics tools we learned in the BLENDING program.",
            "words"     =>  $this->compounds,

            "text2"      =>  "Compounds almost always split on 'illegal'
                                    consonant clusters.  For example 'sand/bag' splits on 'd/b'
                                    and we never use db inside a word.  We never use 'h/s', 'p/s',
                                    't/m', 'n/c', etc.  Look back on the list and see if you
                                    can find other illegal clusters.<br><br>
                                    Some compounds DO split on legal clusters.  'Tightrope' could
                                    legitimately be decoded 'tigh+trope', but your clue is
                                    that 'tigh' isn't a word so you must use 'tight'. Some VERY RARE words like  'clamprod'
                                    and 'seathorn' create real words both ways, but only one way makes sense.
                                    ",

            "words2"     =>  $this->compounds3,
            "sidebar"   =>  "Can you split these words into individual bases?  Hit Refresh
                                for more words."
        );




        $this->clusterWords["Multi-syllables"] = array(
            "group"     =>  "Basics",
            "style"     =>  "phonics",
            "text"      =>  "In the BLENDING drills, we saw that one-syllable words always
                                    have a vowel sound, which we show in red.  The rule
                                    is that <b>every syllable has a vowel sound</b>.<br><br>
                                 It is often not clear on which letter a syllable starts and ends in normal speech,
                                  and it is not really important.  But
                                 we do need to be able to count syllables.  Try counting syllables by
                                 tapping your fingers as if you were
                                 counting on them.",
            "text2"     => "We need to count, but we also need to identify which syllable has the STRESS. In
                                a multi-syllable word, there is usually one syllable that is
                                louder and longer.
                                <br><br>
                                Consder 'prefer', we say it as 'preFER'.  It sounds wrong when we say it the other way
                                (try it).  'Travelling' stresses the first and last syllable.<br><br>
                                 If you over-emphasize the stress, it usually becomes more obvious.
                                Try saying 'obvious' and shouting the stress - 'OBvious', 'obVIous', 'obviOUS'.
                                Usually only one sounds right.",
            "words"     =>  $this->compounds2,
            "words2"    =>  $this->compounds4,
            "sidebar"   =>  "Count the syllables in these words, and identify the ones with STRESS.<br><br>  Instructor: Read them aloud to your
                                student, pronouncing them carefully, and ask your student to tap out the
                                number of syllables.  Hit refresh, there are lots of words."
        );


        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////
        //////  start of affixrules ///
        ///////////////////////////////


        $this->clusterWords["Dropping Final-e"] = array(
            "group"     =>  "Affix Connectors",
            "style"     =>  "spellingRule",
            "text"      =>  "Here's our first spelling rule, called 'no-E'.  Use it when adding
                                a suffix beginning with a vowel
                                to a word ending with an 'e', for example
                                'bike + ing and trouble+ing'.<br><br>

                                For final-e: :
                                <b>Drop the 'e' before adding the affix.</b><br><br>

                                There are four or five exceptions, which we will look at very shortly.  For now,
                                use the final-e rule for all suffixes that start
                                with vowels like '-able', '-ed', '-ite', '-ous', and '-ure'.
                                Look at the examples in the next tabs, and then spell the words in the next lesson.",
            "twords"    =>  $this->drop_the_e,
            "triples"   => 'ing',

            "spinner1"   => array("", "", "hope,vote,type,change,bake,probe", "ed,ing,er", "", 'style' => 'suffix'),
            "spinner2"   => array(
                "'-ous' has the general sense \"possessing, full of\".",
                "", "fame,nerve,grieve,pore", "ous", "", 'style' => 'suffix'
            ),
            "spinner3"    => array(
                "'-ure' indicates an action or condition.  You can combine affixes.
                                            How would you spell advent+ure+ous?  From the affixes, what do you
                                            think it means?",
                "", "create,please,literate,compose", "ure", "", 'style' => 'suffix'
            ),

            "2ndtext1"    =>  'Learn to \'announce\' a spelling by reading it aloud in a special
                                way.  Here is how to announce the \'no-e\' rule:<br /><br />
                                <b><span style="font-size:24px;">dive + ing &nbsp;
                                <span style="font-size:50%;">rewritten</span>
                                &nbsp; div<img src="images/sep-drop-e.PNG" height="30" />ing &nbsp;
                                <span style="font-size:50%;">produces</span>&nbsp;
                                diving</span></b>

                                <br />
                                <span style="font-size:16px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;is read aloud for spelling:
                                </span> <br />
                                <span style="font-size:24px;">
                                    <b>d-i-v-e</b>
                                    <i>plus</i>
                                    <b>i-n-g</b>
                                    <i>rewritten</i>
                                    <b>d-i-v- [\'no e\']</b>
                                    <i>(pause)</i>
                                    <b>i-n-g</b><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;<i>produces</i> <b>d-i-v</b> (pause) <b>i-n-g</b>
                                </span>
                                <br  /><br />

                                Learn to spell aloud this way.  There are only a few connectors and a handful of rules
                                for applying them, and learning them will help you become an
                                excellent speller.',

            "2ndtext2"    =>  'The \'do-nothing\' connector is a special case.  Leave clear pauses but you
                                    don\'t have to call it out or repeat the production step:<br /><br />
                                <span style="font-size:24px;">
                                    jump + ing &nbsp;
                                    <span style="font-size:50%;">rewritten</span>&nbsp; jump<img src="images/sep-none.PNG" height="30" />ing &nbsp;
                                    <span style="font-size:50%;">produces</span>&nbsp; jumping
                                </span>
                                <br />
                                <span style="font-size:16px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;is spelled aloud:
                                </span> <br />
                                <span style="font-size:24px;">
                                    <b>j-u-m-p</b> <i>plus</i> <b>i-n-g</b> <i>rewritten</i> <b>j-u-m-p</b> <i>(pause)</i> <b>i-n-g</b><br />
                                </span>

                                <br  /><br />
                                Practice \'announcing\' the spellings on the next tabs.',



            "twords2"    =>  $this->drop_the_e,
            "triples2"   => 'ing,ed',

            "words"     =>  $this->drop_the_e,
            "sidebar"   =>  "How would you spell these words if you added '-ing'?",
            "words2"     =>  $this->drop_the_e_final,   // this is already an array
            "sidebar2"   =>  "Figure out the base for these words, and then announce their spellings."
        );


        $this->clusterWords["Dropping Final-e Exceptions"] = array(
            "group"     =>  "Affix Connectors",
            "style"     =>  "spellingRule",

            "text"      =>  "We mentioned that there were exceptions to the no-E rule.  The three spinners
                                in the next tab present four exceptions to the rule, a few old-fashioned spellings,
                                and three words that you must simply memorize.<br><br>
                                The next lesson has exercises.",

            "twords"    =>  $this->drop_the_e_able,
            "triples"   => 'able',

            "spinner1"   => array(
                "Two quick exceptions. Words ending in 'ge' keep the 'e' before 'able' to
                                      keep the soft 'ge' sound.
                                      Words ending in 'ce' also keep the 'e' to keep the soft 'c' /s/ sound",
                "", "change,manage,marriage,knowledge,notice,replace,service", "able", "", 'style' => 'suffix'
            ),

            "spinner2"   => array(
                "Two more exceptions.  Words that end in 'oe' and 'ye' keep the final 'e'
                                      for affixes that don't start with 'e'.  And words
                                      with final syllabic 'e' (where we pronounce the 'e') keep their final e,
                                      such as 'be', 'see', 'agree', 'recipe', 'acne', 'epitome' and 'apostrophe'.",
                "", "be,agree,pee,canoe,dye,toe,eye", "ing", "", 'style' => 'suffix'
            ),

            "spinner3"   => array(
                "You will sometimes see older spellings for 'likeable', 'loveable', 'moveable', 'liveable',
                                      'saleable', 'hireable'.  They aren't wrong,
                                      but they are fading away in favor of 'likable', etc. <br><br>
                                      Here are three exceptions that you simply must learn:",
                "", "due,whole,true", "ly", "", 'style' => 'suffix'
            ),


            "twords2"    =>  $this->drop_the_e,
            "triples2"   => 'ing,ed',

            "words"     =>  $this->drop_the_e,
            "sidebar"   =>  "How would you spell these words if you added '-ing'?",
            "words2"     =>  $this->drop_the_e_final,   // this is already an array
            "sidebar2"   =>  "Figure out the base and then announce the spelling of these words."
        );





        //   <div class="MMlist"><p>jump + ing &nbsp;<span style="font-size:30%;">rewritten</span>&nbsp; jump<img src="images/sep-none.PNG" height="30">ing &nbsp;<span style="font-size:30%;">produces</span>&nbsp; jumping</p></div>


        ////////////////////////////////////
        //////// doubling rules, -s/-es, and revuew
        ////////////////////////////////////

        // give an example of how to call these words

        $this->clusterWords["Doubling Rule"] = array(
            "group"     =>  "Affix Connectors",
            "style"     =>  "spellingRule",
            "text"      =>  "An affix starting
                                with a vowel ('-ed', '-ing', '-est') to a one-syllable word ending in
                                consonant-vowel-consonant (CVC) requires us
                                to DOUBLE the final consonant.  'bet' and 'chip', but not 'huff' or 'heat'<br><br>
                                Clip+ing is 'clipping' and 'stop' is 'stopping',
                                but fill+ing is 'filling and boat+ing is 'boating'.  <br><br>
                                If we double an 'M' then the rule is called 'double-M'.  We have several rules
                                such as 'double-B', 'double-G', and 'double-F'.
                                Explore the spinners on the next tabs, and the rewrite/production rules on
                                the next lesson.",
            "twords"    =>  $this->doublingSimple,
            "triples"   => 'ing',

            "spinner1"   => array(
                "Consonant-vowel-consonant (CVC) words must double their ending.
                                        Double-letter vowels do NOT double.  And two final consonants do NOT
                                       double.  Only the CVC pattern doubles.<br><br>
                                       A 'qu' is considered a consonant so
                                         'squat' is CVC (squ-a-t).",
                "", "sail,beat,stow,hurt,hang,quit", "ed,ing,er"
            ),
            "spinner2"    => array(
                "There is an exception for words that end in 'x', 'w', or 'y'
                                      (box, wax, snow, gnaw, play).
                                      You will recognize 'aw', 'ow', and 'ay' as vowels from your phonics lessons so
                                      it's really only the 'x' ending that is the exception.",
                "", "map,flip,plan,sled,fix,row,play", "ed,ing,er", ""
            ),
            "spinner3"   => array(
                "Here are two interesting words to explore.  Before you click, tell
                                      what the base of 'staring' and 'starring' is (and why).",
                "", "star,stare", "ed,ing,er"
            ),

            "twords2"    =>  array($this->doublingSimple, $this->notDoublingI),
            "triples2"   => 'ing',

            // only uses front-cluster CVC
            "twords3"    =>  array($this->doublingSimpleII, $this->notDoublingII),
            "triples3"   => 'ing,ed',

            "words"     =>  array($this->doublingSimpleII, $this->notDoublingI, $this->notDoublingII),
            "sidebar"   =>  "Announce the spelling of these words with the ending '-ing' or '-ed'."
        );


        $this->clusterWords["Doubling Rule II"] = array(
            "group"     =>  "Affix Connectors",
            "style"     =>  "lecture",
            "text"      =>  "The doubling-rule for multi-syllable words is simple:
                                Double if the stress is on the final syllable of the base.  But the
                                trick is that you have to pronounce the whole word first.<br><br>
                                'preFER' plus 'ing' becomes 'preFERRing' with double-R, but
                                'preFER' plus 'ence' does not.  Say these words aloud to understand
                                why: 'preferring', 'preference'.<br><br>
                                Identify the stress on
                                the final merged word before you decide on the rule for the base.<br><br>

                                <img src='images/preference.jpg' width='70%' /><br><br>


                                Announce and explain the rewrite/production rules for words on
                                the next three tabs.",

            "spinner"   => array(
                "Say the final word aloud to identify the stress,
                                      and only double the 'r' if the stress is on the last syllable.",
                "in,re,de", "fer", "ed,ing,ence,al", ""
            ),

            "words"    =>  $this->compounds5,
            "sidebar"   =>  "Announce
                                the rewrite/production rules for adding '-ing' and explain why for each word.
                                Hit REFRESH for new words."
        );


        // flammable uses both drop-e and doubling
        // still need lay+ed

        $this->clusterWords["Convert Y to I"] = array(
            "group"     =>  "Affix Connectors",
            "style"     =>  "spellingRule",
            "text"      =>  "This rule is called 'Y to I'.  Use it for words that end in consonant+'Y'
                                  ('envy' but not 'obey'). And use it only if the suffix does
                                 NOT start with 'i' (not '+ing', '+ible', '+ist', etc).<br><br>

                                 If BOTH conditions are met, then convert the 'Y' to 'I' before adding the
                                 suffix.  For example, try+ed is 'tried' (both conditions so 'Y to I') but try+ing is 'trying'
                                 ('-ing' start with 'i') and pray+ed is 'prayed' (not consonant+'Y').
                                <br><br>
                                Explore the spinners in the next three tabs, then announce the rewrite/production
                                rules for words in the next lesson..",
            "twords"    =>  array($this->yToI),
            "triples"   => 'ed',

            "spinner1"   => array(
                "These endings requires 'Y to I', EXCEPT '+ing'.  Announce some of the
                                      rewrite rules",
                "", "steady,ready,pretty,tidy,coy", "er,est,ly,ing", ""
            ),
            "spinner2"   => array(
                "We don't convert final vowel+y because it is always a
                                        vowel spelling ('ay', 'ey', etc). But here are TWO '-ay' words that
                                        don't follow this rule:   gay/gaily,and day/daily.<br><br>
                                      'Pay/paid, say/said, lay/laid, and slay/slain are NOT exceptions.
                                      They are merely
                                      past-participles like 'have/had' and 'go/gone'.  You must not use Y-to-I because
                                      you will end up with pay+ed -> paied.
                                      <span style='background-color:yellow'>These spinners are WRONG.</span>",
                "", "pay,lay,say", "ed", "", 'style' => 'suffix'
            ),
            "spinner3"    => array(
                "You have to apply the rules step-by-step as you build the final word.",
                "", "ease,hap", "y", "ly,er,ness"
            ),

            "twords2"    =>  array($this->yToI, $this->notYToI),
            "triples2"   => 'ed',

            "twords3"    =>  array($this->yToI),
            "triples3"   => 'ed,ing',

            "words"     =>  array(
                $this->yToI,
                $this->notYToI
            ),
            "sidebar"   =>  "How would you spell these words if you added '-ing'?  Announce
                                the rewrite/production rules and explain why you used a rule.
                                Would they be the same for adding '-ed'? Hit REFRESH for new words."
        );



        $this->clusterWords["Review"] = array(
            "group"     =>  "Affix Connectors",
            "style"     =>  "review",
            "text"      =>  "This is a review of the affixing rules so far.
                                <ul>
                                    <li>(Nothing)</li>
                                    <li>No-E</li>
                                    <li>Double-Letter</li>
                                    <li>Y to I</li>
                                </ul>
                                Announce the words on the following tabs.",

            "twords"     =>  array(
                $this->drop_the_e,
                $this->doublingComplex,
                $this->doublingComplexNot,
                $this->doublingSimpleII,
                $this->notDoublingII,
                $this->doublingComplex,
                $this->doublingComplexNot,
                $this->yToI,
                $this->notYToI
            ),
            "triples"   => 'ing,ed',
            "sidetext"  =>  "Announce the spelling and explain why you used each rule.
                                Hit REFRESH for new words."
        );


        //////////////////////////////////////////////////////////////////////////////////////////////////////////


        $this->clusterWords["con-"] = array(
            "group"     =>  "Basics",
            "style"     =>  "lecture",
            "text"      =>  "The prefix 'con-' means 'together' or 'with'.  The verb
                                'conflict' joins 'con-' with 'flict' (from the Latin <i>fligere</i>)
                                meaning 'to strike'.  Can you see how a conflict describes
                                people together, striking each other?<br><br>
                                Sometimes we use 'co-', 'com-', 'col-' or 'cor-' because we find
                                it easier to say; we say 'correct' and 'collect' became /m/ or /n/ doesn't work (try it).
                                But the meaning is the same.<br><br>",

            "spinner"   =>  array(
                "For each word, try to think of a different prefix from 'con', for example for con+sult there
                                       is re+sult, in+sult, and as-sult.   Can you see a similar idea in those three words?  The base <b>'sult'</b> comes
                                       from the Latin <i>selere</i> meaning 'to leap'. The base <b>'sent'</b> comes
                                       from the Latin <i>sentire</i> meaning 'to feel'.",
                "con", "sult,sent,tempt,tain,sume", "", "", 'style' => 'prefix'
            ),


            "text2"     =>  "Here is the history of
                                two wonderful 'con-' words:<br><br>
                                The base 'spire' is from the Latin <i>spirare</i> meaning
                                'to breathe' (respire, inspire, perspire, expire, and spirit).  So to
                                'conspire' means 'to breathe together'. Isn't that a perfect
                                definition of a conspiracy?<br><br>
                                The base 'pane' means 'bread' (pantry, pancake, do you know how to say 'bread'
                                in French or Italian?)  So a 'companion' is
                                literally someone with whom you share bread with.",

            "words"     =>  "connect,conspire,contact,concede,costar,concern,
                                compete,compound,compress,companion,correct,
                                corrupt,correspond,combine,compare,coincidence,cohesive,
                                communicate,collect,compatible,concur,collide",
            "sidebar"   =>  "Can you see the 'together' in these words?
                                Do any of these surprise you?  Hit refresh for more words."
        );


        $this->clusterWords["Morphology vs Phonics"] = array(
            "group"     =>  "Basics",
            "style"     =>  "lecture",

            "text"       => "But what about just 'sounding out' words using the rules of phonics and
                                the six 'open' and 'closed' syllable types that Orton-Gillingham teaches?<br><br>
                                It turns out that we can ONLY do that for a few multi-syllable words, mostly
                                words borrowed from non-Latin languages.<br><br>
                                <img src='images/potato.jpg' width='100%' />",

            "text2"      => "To see the problems, consider the base 'sign' and a few words
                                that are built on it:<br>
                                <img src='images/design.jpg' width='50%' />
                                <div style='font-size:80%;'><ul>
                                <li>'Sign' is CVC but sounds /igh/ ('sine') and not /ih/ ('sin') as expected.</li>
                                <li>The /s/ changes to /z/ when we modify 'sign' to 'de+sign'.</li>
                                <li>People often pronounce the 'de+' /ih/ vowel as /ee/ ('dee-zine').</li>
                                <li>The vowel sound /igh/ changes to /ih/ when we modify to 'sign+al.</li>
                                <li>The 'gn' spelling of /n/ splits into two sounds /g/ + /n/.</li>
                                <li>The vowel /ih/ in 'de+' changes to /eh/ in 'de+sign+ate'.</li>
                                <li>'+nate+ion' drops final 'e', somehow shifts 't' to retain /ay/ sound.</li>
                                <li>The stressed syllable moves from 2nd in 'design' to 1st in 'designate'.</li>
                                </ul></div><br>
                                These words will defeat us if we map their spellings to sounds
                                using phonics and then try to recognize them.",

            "spinner2"   =>  array(
                "It's not just complex multi-syllable words.  Even 'walk+ing' changes.
                                      Say aloud the words 'walk' and 'walking' and see how the first syllable changes. <br><br>
                                       The way to read and understand a multi-syllable word is
                                by starting with the base 'sign' and adding affixes.
                                But see how easy reading becomes after we recognize the 'sign' in
                                'designation'. Click below to build it.<br><br>",
                "de", "sign", "ate", "ion"
            ),


            "text3"      => "Look at the first two vowels change in 'photograph' and
                                'photographer', and how their syllables break.
                                You can't sound them from their spelling and you can't decode them
                                from left to right.<br><br>
                                 English spelling usually gives us
                                the meaning of longer words, not their pronunciation.
                                And we can only pronounce multi-syllable words
                                correctly if we know spoken English.<br><br>
                                <img src='images/photographer.jpg' width='50%' />",



            "words"     =>  $this->compounds2,
            "sidebar"   =>  "Can you see the bases in these words?  Can you name
                                some related words that hint at their meaning?  Hit Refresh
                                for more words."
        );



        /*
        "Morphology vs Phonics" => array(
                "group"     =>  "Basics",
                "style"     =>  "lecture",
                "text"     => "We can also add affixes to compound words.  Usually the affixes go
                                to the beginning or end ('hair+cuts+s), but not always.  A 'barber'
                                is the guy who trims your 'barb' (from the Latin <i>barba</i> meaning
                                'beard'), so a 'barbershop' is built 'barb+er+shop<br><br>
                                WRONG: <i>Barba</i> is also the source of 'barbarian', likely because of their handsome beards,
                                and barbed wire.",

                "text2"    => "But as words get built up, phonics becomes harder.  We move the syllable breaks
                                away from the meaning of the words.   Phonics and spelling give us different
                                ways at looking at a word.<br>
                                <img src='images/barbershop.jpg' width='70%' />",
                            ),

*/




        $this->clusterWords["Etymology"] = array(
            "group"     =>  "Basics",
            "style"     =>  "lecture",
            "text"     =>  "We are the 'Community Reading Project'.  You now know the meaning of
                                'com-'.  'Unity' is from the Latin <i>unio</i> and means 'one', and so perhaps 'com+unity' means
                                'together as one'. That makes sense.<br><br>

                                But where did 'one' come from?  Take a minute
                                to watch
                                <a href='https://www.youtube.com/watch?v=0mbuwZK0lr8 target='_blank'>this video by the amazing Gina Cooke.<br><br>
                                <img src='images/unity.jpg' width='70%' /></a>",

            "text2"     =>  "Our words carry history, they are
                                like fossils that tell us stories about the past.
                                'Etymology' is the study of the origin of words and the way in which their
                                meanings have changed throughout history.  <br><br>
                                Check out this remarkable website.  It should be your starting point if
                                you want to know the history of a word.
                                <a href='http://www.etymonline.com/index.php target='_blank'>Etymonline.com<br>
                                <img src='images/etymonline.jpg' width='70%' /></a>",

            "text3"     =>  "Words are 'related' if they share a common history and a common meaning.
                                 'Particle', 'partner', and 'depart' share the common root 'part', and also share the
                                 meaning of 'divide or portion'.  'Rampart' looks like a 'part' word, but
                                 does not have the shared meaning.<br><br>
                                 For spelling purposes, words are 'related' if they can be
                                 mapped with the rules of spelling.<br>
                                <img src='images/sign.png' width='70%' /></a>",

            "text4"     =>  "Not all word histories are true.<br><br>  For example
                                a 'barb+er' is the guy who trims your 'barb' (from Latin <i>barba</i> meaning
                                'beard') in a 'barb+er+shop'.  This makes immediate sense if you know some French ('barbe') or
                                Italian ('barba'), but we use the Old English word 'beard'.<br><br>
                                'Barbarians' got their name because they had dirty, unkept beards,
                                and didn't go to barbershops.  They ate meat cooked on 'barbeques' using their
                                hands.<br><br>
                                Check <a href='http://www.etymonline.com/index.php target='_blank'>Etymonline.com</a>
                                and decide if this is a true story.<br>
                                <img src='images/barbershop.jpg' width='50%' />",

            "words"     =>  "react / actual,
                                 innate / nature,
                                 depress / pressure,
                                 outgrow / growing,
                                 destruct / structure,
                                 define / finite,
                                 mishap / happen,
                                 adjust / justice,
                                 affirm / firmly",

            "sidebar"   =>  "Can you see the relationship in these words?  Do any of them surprise you?"
        );



        ////////////////////////////////////////
        ///////////   -s and -es ///////////////
        ////////////////////////////////////////

        $this->clusterWords["'-s' and '-es' plurals"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "lecture",
            "text"      =>  "When we spell the plural form, for some words we add 's' and
                                for others 'es'.  Consider the following words,
                                can you see the rule?
                                <br /><br /><table width='100%'>
                                <tr><th style='color:blue;font-weight:bold;'>'+s' WORDS</th><th style='color:blue;font-weight:bold;'>'+es' WORDS</th></tr>
                                <tr><td>CAT+S $arrow CATS     </td><td>COACH+ES $arrow COACHES </td></tr>
                                <tr><td>DOG+S $arrow DOGS     </td><td>GLASS+ES $arrow GLASSES </td></tr>
                                <tr><td>BOOK+S $arrow BOOKS   </td><td>TAX+ES   $arrow TAXES   </td></tr>
                                <tr><td>STAR+S $arrow STARS   </td><td>DISH+ES  $arrow DISHES  </td></tr>
                                <tr><td>NIGHT+S $arrow NIGHTS   </td><td>FIZZ+ES  $arrow FIZZES  </td></tr>
                                </table><br />
                                Hint: Announce the spellings (eg: 'cat' plus 's' produces 'cats') and listen.  Do you
                                see the reason now?  Exactly.  If we add a syllable then we must use
                                'ES' because every syllable needs a vowel.  Otherwise 'S' is the default.",

            "text2"      => "Sometimes we add an '+s' or '+es' to a word that already ends in 'e'.  We could make
                                up rules for this but we don't need to.  The syllable rule works
                                and the joining rules get rid of the extra 'e' if necessary.<br><br>" .
                '<b><span style="font-size:24px;">dish + es &nbsp;<span style="font-size:50%;">rewritten</span>&nbsp; dish<img src="images/sep-none.PNG" height="30" />es &nbsp;<span style="font-size:50%;">produces</span>&nbsp; dishes</span></b><br>
                                 <b><span style="font-size:24px;">rage + es &nbsp;<span style="font-size:50%;">rewritten</span>&nbsp; rag<img src="images/sep-drop-e.PNG" height="30" />es &nbsp;<span style="font-size:50%;">produces</span>&nbsp; rages</span></b><br>
                                 <b><span style="font-size:24px;">fake + s &nbsp;<span style="font-size:50%;">rewritten</span>&nbsp; fake<img src="images/sep-none.PNG" height="30" />s &nbsp;<span style="font-size:50%;">produces</span>&nbsp; fakes</span></b><br>
                                 <b><span style="font-size:24px;">knee + s &nbsp;<span style="font-size:50%;">rewritten</span>&nbsp; knee<img src="images/sep-none.PNG" height="30" />s &nbsp;<span style="font-size:50%;">produces</span>&nbsp; knees</span></b>' .
                "<br><br>",

            "text3"      => "But there is one more very important rule:  If we change the base
                                in ANY way, then we need to use '+es'. We use this rule with words that end in a
                                'Y' that is not part of a vowel digraph, such as 'ay', 'ey', etc).<br><br>" .

                '<b><span style="font-size:24px;">pony + es &nbsp;<span style="font-size:50%;">rewritten</span>&nbsp; pon<img src="images/sep-y-i.PNG" height="30" />es &nbsp;<span style="font-size:50%;">produces</span>&nbsp; ponies</span></b><br>' .

                "<br><br>Forget any rule you learned about 'drop Y and add IES'.  Doing that doesn't respect the
                                meaning of the base - we can't change 'pony' into 'pon'.  This will become clearer when we
                                talk about how words carry meaning.<br><br>
                                The 'any change' rule also takes care of words with final 'f' or 'fe' that
                                change, for example 'knife' $arrow 'knives' and 'half' $arrow 'halves'.",


            "words"     =>  array(
                'dog',        // +s
                'baby',        // +es
                'cake',       // drop e + es
                'pony'
            ),      // y->i + es
            "sidebar"  =>   "Explain why each of these words require '+s' or '+es', and then announce their spelling."
        );




        $this->clusterWords["+ure"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "lecture",
            "text"      => "The suffix '-ure' is common in English, it usually means an
                                act or process.  For example, if the verb is 'press', then the act or process
                                is 'pressure'.  If the adjective is 'moist' then the process is 'moisture'.<br><br>
                                On the last page are some words with 'ure' endings.  Read them and identify
                                what they are modifying, and maybe discuss the interesting ones.<br><br>
                                Finally, not all words that end in '-ure' have this meaning, for example
                                'vulture' and 'future'.",

            "spinner"   =>  array(
                "Can you see how 'feat' (from the Latin for 'accomplishment') becomes 'feature'?
                                      Or how 'fail' becomes 'failure'?  Notice how 'seize' seems to change
                                      spelling (losing an 'e').  So does 'please' and it also changes sounds.  We'll talk about both
                                      those ideas very soon.",
                "", "feat,fail,press,please,seize", "ure", 'style' => 'suffix'
            ),

            "text2"     =>  "Is  <b>'conjure</b> built from conj+ure or con+jure?<br><br>

                                Sometimes we can just look for other words that share the same
                                building blocks. It's hard to think of any 'conj'
                                words, and there seem to be other 'jure' words (injure, perjure, abjure) that
                                share the idea of something related to a 'jury'.<br><br>
                                The best and most interesting way is to look up the history of a word.
                                Word history is called  'etymology', and here's an amazing
                                website that should be your first stop: <a href='http://etymonline.com' target='_blank'>Etymonline.com</a>.<br><br>
                                Take a minute and look up the history of 'conjure'.
                                ",

            "words"     =>  "moisture,exposure,pressure,pleasure,adventure,scripture,sculpture,
                                erasure,fixture,seizure,departure,signature,furniture,structure,
                                procedure,culture,architecture,puncture,enclosure,creature,posture,denture,
                                gesture,mixture,legislature",
            "sidebar"   =>  "Do any of these surprise you?  Hit refresh for more words."
        );




        //                "words"     =>  array('she,knee,recipe,acne,catastrophe,apostrophe,resume,acme,
        //                                      anemone,simile,cliche,vertice,hyperbole,epitome,refugee,employee'),


        //'-ion' is a common ending that makes a word into a noun,
        // condition, or action.
        //
        // <br /><br /><table width='100%'>
        // <tr><td>DICT + ION   </td><td>ACT + ION  </td></tr>
        // <tr><td>VERSE + ION </td><td>MIS + ION</td></tr>
        // </table><br />








        $this->clusterWords["-ion"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "spellingRule",
            "text"      =>  "The '-ion' suffix turns verbs and adjectives into nouns.
                                If we can [verb] (for example 'ignite') something then we
                                can often refer to the the [verb]+ion as a [noun], for example 'ignition').
                                <br><br>
                                This isn't important to remember, because you already know these words.  The
                                important thing is to notice that the suffix is a separate piece of the word.
                                <br><br>
                                The list on the following page contains words that are not bases, because
                                we want to focus on the '-ion' suffix.  For example, 'ignite'
                                is <b>ign</b>+<b>ite</b>.",

            "spinner1"   => array("", "", "hope,write,type,change", "ed,ing,er", ""),
            "spinner2"   => array("", "", "lay,pay", "ed", ""),
            "spinner3"    => array("", "un", "ease,hap", "y", "ly,er,ness"),

            "text2"     =>  "There is no '-tion' suffix (even though you will find it in
                                dictionaries).  The 't' is always part of
                                the base.  'Action' is <b>act</b>ion not <b>ac</b>tion because <b>act</b> has
                                meaning and <b>ac</b> does not.
                                <br /><br />
                                <b>Act</b> gives us '<b>act</b>ing', '<b>act</b>ive', 'ex<b>act</b>',
                                and re<b>act</b>ion. Similarly we can find families of words for
                                <b>mot</b>ion, <b>quest</b>ion, e<b>rupt</b>ion, <b>edit</b>ion, and <b>port</b>ion.
                                <br><br>
                                There is also no '-ation' or '-ition' suffixes, they are always <b>ate</b>+<b>ion</b>
                                or <b>ite</b>+<b>ion</b> using the drop-final-E rule.",


            "twords"    =>  $this->w_ion,
            "triples"   => 'ion',

            "words"     =>  $this->w_ion,
            "sidebar"   =>  "How would you spell these words if you added '-ion'?",
        );



        $this->clusterWords["-ous"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "spellingRule",
            "text"      =>  "The '-ous' ending can change a verb or noun into an adjective (a word that describes
                                a noun).  For example, if you acquire fame [noun], then you become fame+<b>ous</b> -> famous [adjective].
                                <br><br>
                                This isn't important to remember, because you already know these words.  The
                                important thing is to notice that the suffix is a separate piece of the word.
                                <br><br>
                                Read the words on the next page and discuss how they are formed.",

            "spinner1"   => array("", "", "hope,write,type,change", "ed,ing,er", ""),
            "spinner2"   => array("", "", "lay,pay", "ed", ""),
            "spinner3"    => array("", "un", "ease,hap", "y", "ly,er,ness"),

            "text2"     =>  "There is also an '-ious' suffix.  It's each to get confused, because
                                some words like 'glory' and 'harmony' convert y-to-i and add '-ous'.  They
                                look like '-ious' but they are not.  <br><br>
                                But other words
                                really need the extra 'i', and you can't always tell by listening
                                to their pronunciation.   Consider 'ambitious', 'officious','litigious'.<br><br>",

            "twords"    =>  $this->w_ous,
            "triples"   => 'ous',

            "words"     =>  $this->w_ous,
            "sidebar"   =>  "How would you spell these words if you added '-ous'?",
        );

        //                                For example, 'ambitious' looks to root from 'ambit' from the
        //                                Latin <i>ambire</i \"to go round, to go about\".  An ambitious Roman would go around
        //                                canvassing to be elected.  But 'ambit' isn't the root, it is 'amb' and the full
        //                                construction is



        $this->clusterWords["-ist"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "spellingRule",
            "text"      =>  "The '-ist' suffix is added to words to denote someone who does
                                something, for example <b>art</b>+<b>ist</b>, <b>violin</b>+<b>ist</b>
                                <br><br>
                                This isn't important to remember, because you already know these words.  The
                                important thing is to notice that the suffix is a separate piece of the word.
                                <br><br>
                                Read the words on the next page and discuss how they are formed.",
            "twords"    =>  $this->w_ist,
            "triples"   => 'ist',

            "spinner1"   => array("", "", "hope,write,type,change", "ed,ing,er", ""),
            "spinner2"   => array("", "", "lay,pay", "ed", ""),
            "spinner3"    => array("", "un", "ease,hap", "y", "ly,er,ness"),

            "words"     =>  $this->w_ist,
            "sidebar"   =>  "How would you spell these words if you added '-ist'?",
        );

        $this->clusterWords["-ize"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "spellingRule",
            "text"      =>  "The '-ize' ending can change a noun into a verb.
                                For example, if you make someone act civil, then you have
                                'civilized' him.  If you put give him energy, then you have
                                'energized' him.
                                <br><br>
                                This isn't important to remember, because you already know these words.  The
                                important thing is to notice that the suffix is a separate piece of the word.
                                <br><br>
                                Read the words on the next page and discuss how they are formed.",
            "twords"    =>  $this->w_ize,
            "triples"   => 'ize',

            "spinner1"   => array("", "", "hope,write,type,change", "ed,ing,er", ""),
            "spinner2"   => array("", "", "lay,pay", "ed", ""),
            "spinner3"    => array("", "un", "ease,hap", "y", "ly,er,ness"),


            "words"     =>  $this->w_ize,
            "sidebar"   =>  "How would you spell these words if you added '-ize'?",
        );




        $this->clusterWords["Meaning"] = array(
            "group"     =>  "Common Affixes",
            "style"     =>  "lecture",
            "text"      =>  "We have learned some basic rules for joining bases and affixes.
                                But these lessons aren't just about
                                learning rules, it's about something much more important.<br><br>

                                Here is the secret of reading and spelling - that we encode MEANING into
                                our words by linking bases and affixes together.  This is magic, because now we can
                                understand words that we have never heard before,
                                read and write words that we have never seen, and
                                even spell them correctly.<br><br>

                                You are going to become a word scientist.  Every word carries a history
                                lesson and often a science or social-studies lesson as well, and
                                you are going to learn how to study them.",

            // just do ONE - maybe PEN
            // drill not connected to lesson

            "text2"     => "Look at this:
                                <br><table width='100%'>
                                <tr><td>FORM     </td><td></td><td>FORM + AL   </td><td>FORM + AL + IZE</td></tr>
                                <tr><td>PEN      </td><td></td><td>PEN + AL    </td><td>PEN + AL + IZE</td></tr>
                                </table><br />

                                'Pen' is used in the sense of a cage, not a pencil.
                                If you like, you can add '-ing' or '-ed' to those '+ize' words.<br><br>
                                This is how we build up both words and meanings at the same time.
                                A informal performance still must follow a formula,
                                 and a penalty box is used to pen
                                the penalized player.<br><br>

                                We can 'reform' someone (back into shape), or 'dispense' (release or give out)
                                something.  And a period of European history was called the
                                'Reformation' and we can guess that it had something to do with 'reform'.  It was
                                because the
                                Catholic church was giving out too many 'dispensations' and now we
                                can guess what that
                                was.  Do you see the power of this tool?",

            "words"     =>  "dis+ap+point+ment,
                                 un+for+give+able,
                                 geo+graph+ic+al,
                                 heart+break+ing,
                                 ef+fort+less+ly,
                                 mis+under+stand+ing,
                                 help+less+ness,
                                 micro+organ+ism+s,
                                 un+en+force+able,
                                 re+strict+ion+s",
            "sidebar"   =>  "Read these words.  They become easy when you see how they are built."
        );



        // add video


        //        "-ble endings" => array(
        //                "group"     =>  "Lessons",
        //                "text"      =>  "There are words that end in 'ble', 'cle', or 'kle', and they are a bit
        //                                tricky.  The vowel is clear, but the sounds don't seem in the right order.",
        //                "words"     =>  $this->bleWords,
        //                "scramble"  =>  true
        //                            )





        ////////////////////////////////////
        //////// teach word matrix
        ////////////////////////////////////


        $this->processClusterWords();


        /*
$cumulative[] = $this->twoSyllable;

        $lesson = $this->newLesson(__class__, "Syllables");
        $lesson->group = "Basics";
        $text = "Syllables <b>always</b> have a vowel sound, which we usually show in red.<br><br>
                Longer words may have multiple syllables";

        $page = $this->addPage('instructionPage','',    '',   "Intro",   $text);

        $page = $this->addPage('wordList', "1col",  'full',   "Words",     "normal",   $this->twoSyllable);
        $page = $this->addPage('wordList', "2col",  'simple', "Scramble",   "normal",   $this->twoSyllable);

        $optional = "";
        $page = $this->addPage('instructionPage3', '', '',   "Result",   $optional);
        $page->controlPositionOverride = 'aside';


$cumulative[] = $this->bleWords;


        $lesson = $this->newLesson(__class__, "ble / cle / kle");
        $lesson->group = "Basics";
        $text = "There are words that end in 'ble', 'cle', or 'kle', and they are a bit
                tricky.  The vowel is clear, but the sounds don't seem in the right order.";

        $page = $this->addPage('instructionPage','',    '',   "Intro",   $text);

        $page = $this->addPage('wordList', "1col",  'full',   "Words",     "normal",   $this->bleWords);
        $page = $this->addPage('wordList', "2col",  'simple', "Scramble",   "normal",   $this->bleWords);
        $page = $this->addPage('wordList', "3col",  'none',   "Review",   "normal",    $cumulative);

*/





        $ml = new matrixLesson();
        $ml->bases = 'port';

        $ml->video = "The following lessons are going to use the 'Word Matrix' tool
                        to explore how words are created and spelled.  Follow the link below to
                        watch a
                        short video on how to use the Word Matrix for the base 'Port'.<br><br>
                        <iframe width='560' height='315' src='https://www.youtube.com/embed/6KMx4fkRkzY' frameborder='0' allowfullscreen></iframe>";

        $ml->text = "<p>'Port' comes from the Latin <i>portare</i> 'to carry'.  The
                harbor of a city was the center of commerce - where things were 'imported' and 'exported'.</p><br>
                <p>A 'reporter' would announce that ships had arrived, which would be an
                'opportune' time to do business, so it was 'important' to get down there.
                'Porters' would 'transport' goods around. Goods would be 'portioned' out 'proportionally'.</p>";
        $ml->hints = '<b>port</b>+ion,im+<b>port</b>,ex+<b>port</b>+er,re+<b>port</b>+er,
                            de+<b>port</b>+ation,com+<b>port</b>,sup+<b>port</b>+ing,
                            pro+<b>port</b>+ional,op+<b>port</b>+unity,
                            dispro<b>port</b>ionatly';
        $ml->addMatrixLesson('Simple Matrices', 'port');


        $ml = new matrixLesson();
        $ml->bases = 'pack';
        $ml->text = "<p>There are many obvious words that can be built on 'pack',
                    like 'unpack', 'repack', 'unrepack'
                    'packer', 'package', and so on.  Give them a try.</p>
                    <p>There are also many compounds, like 'jetpack'
                    and 'sixpack'.</p>";
        $ml->hints = '<b>pack</b>+age,<b>pack</b>+et,re+<b>pack</b>+er,un+<b>pack</b>,pre+<b>pack</b>,<b>pack</b>+agable';
        $ml->addMatrixLesson('Simple Matrices', 'pack');


        $ml = new matrixLesson();
        $ml->bases = 'test';
        $ml->text = "<p>See what you can do with 'test'.</p><br />
                <p>Why aren't 'greatest' or 'intestine' valid constructions
                from the base 'test'?</p>";
        $ml->hints = 'de<b>test</b>,con<b>test</b>,pro<b>test</b>,at<b>test</b>,<b>test</b>ament,<b>test</b>ify,pro<b>test</b>ant,<b>test</b>able,re<b>test</b>';
        $ml->addMatrixLesson('Simple Matrices', 'test');



        $ml = new matrixLesson();
        $ml->bases = "spect";
        $ml->text = "<p>The spelling of 'suspect' and 'suspicious' seems
                    tricky, because they come from two different roots.</p><br />
                    <p>'Suspect'  comes from su+spect, 'su' (or 'sub') meaning 'up to'
                    and 'spect' from Latin <i>specere</i> 'to look at'.
                    Roughly, 'suspect' means 'to look at secretly'.  We also find 'spect' in 'inspect, 'respect',
                    and 'prospect'.</p><br />
                    <p>'Suspicious' comes from 'su+spice, with 'spice' from
                    Latin <i>suspicere</i> 'to look up at'.  There are lots
                    of 'spic' words like 'conspicuous' and 'despicable'.</p>";

        $ml->hints = 'in<b>spect</b>,re<b>spect</b>,a<b>spect</b>,pro<b>spect</b>,
                            <b>spect</b>ator,
                            re<b>spect</b>ive,
                            re<b>spect</b>able,
                            su<b>spect</b>ing,
                            retro<b>spect</b>ively,
                            un<b>spect</b>acular,
                            circum<b>spect</b>ion,
                            re<b>spect</b>ability,
                            retro<b>spect</b>ives';
        $ml->addMatrixLesson('Simple Matrices', 'spect');


        $ml = new matrixLesson();
        $ml->bases = 'tract';
        $ml->text = "<p>The Latin verb <i>trahere</i> means to pull or to draw, and we
                                    have shortened it to <b>tract</b>.
                                 Contract ('make smaller', not the agreement) is built from 'Com-' (together, as in common, commit, communicate)
                                 plus <b>tract</b>, metaphorically to make a bargain.</p><br>
                        <p>As you build the matrix, provide a reason why each word might have a 'pull' in its meaning.</p>";
        $ml->hints = 'de<b>tract</b>,con<b>tract</b>,ex<b>tract</b>,<b>tract</b>ion,re<b>tract</b>,sub<b>tract</b>,at<b>tract</b>,<b>tract</b>or,abs<b>tract</b>';
        $ml->addMatrixLesson('Simple Matrices', 'tract');


        //$ml = new matrixLesson();
        //    $ml->bases = 'cuss';
        //    $ml->text = "<p>To 'cuss' means to use bad language, but
        //        it really comes from the Latin <i>quatere</i>
        //        \"to strike, shake\".</p><br>
        //        Can you find the some of the 'shaking' words built with 'cuss'</p><br>";
        //
        //    $ml->challenge = 'dis+<b>cuss</b>+ion,con+<b>cuss</b>+ion,ex+<b>cus</b>+ed,<b>cuss</b>+ed+ness,per+<b>cuss</b>+ionists,unfo+<b>cuss</b>+ed,reper+<b>cuss</b>+ion,undis+<b>cuss</b>+able';
        //    $ml->addMatrixLesson('Simple Matrices','cuss');


        ////////////////////////////////////////////////////////////////////////////

        /*

        $ml = new matrixLesson();
            $ml->text = "<p></p";
            $ml->bases = "duce/duct";
            $ml->addMatrixLesson('Cognates and Twins','duce/duct');

*/
    }

    function processClusterWords()
    {
        $cumulative = array();

        foreach ($this->clusterWords as $key => $cluster) {
            $lesson = $this->newLesson(__class__, $key);
            $lesson->group = $cluster['group'];

            // phonics
            if (isset($cluster['style']) and $cluster['style'] == 'phonics') {
                $cumulative[] = $cluster['words'];
                $page = $this->addPage('instructionPage', '',  '',   "Intro",   $cluster['text']);
                $page = $this->addPage('wordList', "1col",  'full',      "Words",     "normal",   $cluster['words']);

                if (isset($cluster['text2'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Page 2", $cluster['text2']);
                }

                //$page = $this->addPage('wordList', "1col",  'simple',     "Scramble",   "normal",  $cluster['words']);
                $page = $this->addPage('wordListComplete2', "1col",  'none',   "Exercise",     "normal",   $cluster['words2'], $cluster['sidebar']);
                continue;
            }


            // spelling rule
            if (isset($cluster['style']) and $cluster['style'] == 'spellingRule') {
                $page = $this->addPage('instructionPage', '',  '',   "Intro",   $cluster['text']);


                if (isset($cluster['spinner1'])) {
                    $style = '';
                    if (isset($cluster['spinner1']['style'])) {
                        $style = $cluster['spinner1']['style'];
                        unset($cluster['spinner1']['style']);
                    }
                    $page = $this->addPage('affixSpinner', "1col",    $style,   "Spinner 1",   "normal",  $cluster['spinner1']);
                }
                if (isset($cluster['spinner2'])) {
                    $style = '';
                    if (isset($cluster['spinner2']['style'])) {
                        $style = $cluster['spinner2']['style'];
                        unset($cluster['spinner2']['style']);
                    }
                    $page = $this->addPage('affixSpinner', "1col",    $style,   "Spinner 2",   "normal",  $cluster['spinner2']);
                }
                if (isset($cluster['spinner3'])) {
                    $style = '';
                    if (isset($cluster['spinner3']['style'])) {
                        $style = $cluster['spinner3']['style'];
                        unset($cluster['spinner3']['style']);
                    }
                    $page = $this->addPage('affixSpinnerLast', "1col", $style,   "Spinner 3",   "normal",  $cluster['spinner3']);
                }

                $lesson = $this->newLesson(__class__, $key . ' (2)');
                $lesson->group = $cluster['group'];


                // 2nd page text
                if (isset($cluster['2ndtext1'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Text 1", $cluster['2ndtext1']);
                }
                if (isset($cluster['2ndtext2'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Text 2", $cluster['2ndtext2']);
                }



                $page = $this->addPage('wordListMatrixTriple', "1col", 'none',  "{$cluster['triples']} Ending",       "normal",  $cluster['twords'],  $cluster['triples']);

                // triples 2
                if (isset($cluster['text2'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Page 2", $cluster['text2']);
                }
                if (isset($cluster['triples2'])) {
                    $page = $this->addPage('wordListMatrixTriple', "1col", 'none',  "Practice",       "normal",  $cluster['twords2'],  $cluster['triples2']);
                }

                // triples 3
                if (isset($cluster['text3'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Page 3", $cluster['text3']);
                }
                if (isset($cluster['triples3'])) {
                    $page = $this->addPage('wordListMatrixTriple', "1col", 'none',  "-ing, -ed",       "normal",  $cluster['twords3'],  $cluster['triples3']);
                }

                if (isset($cluster['words2'])) {      // two sets of word exercises
                    $words = str_replace('+', '&nbsp;+&nbsp;', $cluster['words2']);
                    $page = $this->addPage('wordListComplete2', "1col",  'none',   "Practice II",     "normal",   $cluster['words2'], $cluster['sidebar2']);
                    continue;
                } else {                              // just one set of word exercises
                    $words = str_replace('+', '&nbsp;+&nbsp;', $cluster['words']);
                    $page = $this->addPage('wordListComplete2', "1col",  'none',   "Exercises",     "normal",   $cluster['words'], $cluster['sidebar']);
                    continue;
                }
            }

            // lecture  (two instruction pages and a wordlistComplete)
            if (isset($cluster['style']) and $cluster['style'] == 'lecture') {
                $page = $this->addPage('instructionPage', '',  '',   "Text",   $cluster['text']);

                $style = '';
                if (isset($cluster['spinner'])) {
                    if (isset($cluster['spinner']['style'])) {
                        $style = $cluster['spinner']['style'];
                        unset($cluster['spinner']['style']);
                    }
                    $page = $this->addPage('affixSpinner', "1col",  $style,   "Spinner",   "normal",  $cluster['spinner']);
                }

                if (isset($cluster['text2'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Text2",   $cluster['text2']);
                }

                $style = '';
                if (isset($cluster['spinner2'])) {
                    if (isset($cluster['spinner2']['style'])) {
                        $style = $cluster['spinner2']['style'];
                        unset($cluster['spinner2']['style']);
                    }
                    $page = $this->addPage('affixSpinner', "1col",  $style,   "Spinner2",   "normal",  $cluster['spinner2']);
                }

                if (isset($cluster['text3'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Text3",   $cluster['text3']);
                }

                if (isset($cluster['text4'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Text4",   $cluster['text4']);
                }

                $words = str_replace('+', '&nbsp;+&nbsp;', $cluster['words']);
                $sidebar = isset($cluster['sidebar']) ? $cluster['sidebar'] : '';
                $page = $this->addPage('wordListComplete2', "1col",  'none',   "Words",     "normal",   $words, $sidebar);
                continue;
            }


            // review  (two-column base+ending  a wordlistComplete)
            if (isset($cluster['style']) and $cluster['style'] == 'review') {
                $page = $this->addPage('instructionPage', '',  '',   "Intro",   $cluster['text']);
                if (isset($cluster['text2'])) {
                    $page = $this->addPage('instructionPage', '',  '',   "Intro2",   $cluster['text2']);
                }
                $page = $this->addPage('wordListMatrixScramble',  "1col", 'none',  "{$cluster['triples']} Practice",   $cluster['triples'],  $cluster['twords'],   $cluster['sidetext']);
                //$words = str_replace('+','&nbsp;+&nbsp;',$cluster['twords']);
                //$page = $this->addPage('wordListComplete', "1col",  'none',   "Words",     "normal",   $words, $cluster['sidebar']);
                continue;
            }

            $page = $this->addPage('instructionPage', '',  '',   "Intro",   $cluster['text']);
            if (isset($cluster['text2'])) {
                $page = $this->addPage('instructionPage', '',  '',   "Intro2",   $cluster['text2']);
            }

            if (isset($cluster['words'])) {
                $page = $this->addPage('wordList', "1col",  'full',   "Words",     "normal",   $cluster['words']);
                if (isset($cluster['scramble']))
                    $page = $this->addPage('wordList', "2col",  'simple', "Scramble",   "normal",  $cluster['words']);
            }


            if (isset($cluster['cumulative'])) {
                if (count($cumulative) < 1)   // only put the compete up for the first page
                    $page = $this->addPage('wordListComplete', "2col",  'none',   "Practice",   "normal",  $cluster['words']);
                else
                    $page = $this->addPage('wordList', "2col",  'none',   "Practice",   "normal",  $cluster['words']);

                if (count($cumulative) > 1) {
                    $page = $this->addPage('wordListComplete', "2col", 'none',   "Review",   "normal",    $cumulative);
                }
            }
        }
    }
}


class matrixLesson extends scriptInfrastructure
{
    var $video;
    var $text;
    var $bases;
    var $hints;
    var $challenge;
    var $wordLists = array();
    var $baseMsg = '';

    function addMatrixLesson($group, $title)
    {
        // the lesson name HAS to be 'Spelling' !!
        $lesson = $this->newLesson('Spelling', $title);
        $lesson->group = $group;

        if (!empty($this->video))
            $page = $this->addPage('matrixIntro',  "",     "",   "Video",   $this->video);

        if (!empty($this->text))
            $page = $this->addPage('matrixIntro',  "",     "",   "Lesson",   $this->text);

        if (!empty($this->bases))
            $page = $this->addPage('matrixShow',   "",     "",   "Matrix",   $this->bases, '', $this->baseMsg);

        if (!empty($this->hints))
            $page = $this->addPage('matrixWordList', "1col", "none",   "Hints",  $this->hints);

        if (!empty($this->challenge))
            $page = $this->addPage('matrixWordList', "1col", "none",   "Challenge",  $this->challenge, '', "Which of these words is really built on {$this->bases},
                                                                        and which should not be on this list?");

        foreach ($this->wordLists as $wordList) {
            $page = $this->addPage('matrixWordList', "1col", 'none',   $wordList[0], $wordList[1], '', $wordList[2]);
        }

        $page = $this->addPage('matrixFinal',  "",     "",   "Complete", '');
    }


    function addWordList($title, $words, $text = '')
    {
        $this->wordLists[] = array($title, $words, $text);
    }
}
