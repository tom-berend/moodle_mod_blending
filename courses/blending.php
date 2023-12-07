<?php

namespace Blending;

// https://www.freereading.net/wiki/Illustrated_Decodable_fiction_passages.html
// https://www.freereading.net/wiki/Illustrated_Decodable_non-fiction_passages.html
// https://www.freereading.net/wiki/Passages_to_practice_advanced_phonics_skills,_fluency,_and_comprehension.html




$writeText = "Have your student practice handwriting these words.  Turn the screen away, and pronounce words over-accentuating the first and last sounds.";






class Blending extends LessonAbstract
{


    public $bdp = true; // includ b-d-p exercises ??
    public $bdpText = "Try these, but don't spend more than a minute on them, and  don't worry if your student doesn't master them.
                        The principle behind BLENDING is 'overlearning to mastery', training the phonological circuits.
                        But the b,d,p letters cannot be learned that way since \
                        they depend on visual processing circuits that will take longer to train.";








    public $multi = array(
        'ah' => "Alabama,Adam,Alan,catnap,banana,canvas,Japan,Kansas,Canada,sandal,salad,mammal,rascal,bantam,Batman,caravan,Dallas,cabana",
    );

    public function contrastTitle($first, $second, $s1, $s2)
    {
        $title = "Contrast '$s1' /$first/ and '$s2' /$second/";
        return ($title);
    }


    public function loadClusterWords()
    {
        $views = new ViewComponents();

        /////////////////////////////////////////////
        ///// FatCatSat clusters
        /////////////////////////////////////////////



        // set the minimum version
        $this->minimumVersion = '1.0.0';

        $this->clusterWords["Fat Cat Sat"] =

            array(
                "group" => 'Fat Cat Sat',


                "pronounce" => "ah",
                "pronounceSideText" => "We are starting the vowel %% sound('ah') %% as in Bat.  \
                            In this course, always refer to letters by their common sound.

                            Practice pronouncing %% sound('ah') %%. Make shapes with your mouth, exaggerate, play with saying it.

                            Find other words that sound like 'bat'.

                            Work through each of the tabs, and don't hesitate to backtrack. The last tab will be a test. ",

                "words" => [$this->words["bat"]],

                "scrambleSideNote" => "Read down the columns.  This exercise overloads memory and forces \
                                        the student to develop auditory decoding skills.",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    't',
                    '',
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

                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"]
                ),

                "sidenote" => "Difficulty here is often a surprise to both the student and the tutor. \
                            This core skill, if missing, will prevent your student from becoming a skilled reader.

                              If the student struggles, simplify by asking only for the endings **'-at'** and **'-ap'** \
                              on this tab and the following scramble tab, then adding the prefix letters later.",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'p,t',
                    ''
                ), // exception list
                "spinnertext" => "Don't be surprised if your student changes the first sound when you change the third letter.  That will go away quickly with practice.",


                "testNote" => "Your student **MUST** read this list with perfect accuracy in under 10 seconds.  Take a break, \
                            come back to it tomorrow.  Practice it with just **-ap** and **-at**. But don't go on until this is mastered."

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
            $bdp = $this->gen3letters(array('b', 'd', 'p'), array('a'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Cat Words"] =
                array(
                    "group" => 'Fat Cat Sat',
                    "sidenote" => $this->bdpText,
                    "words" => [$bdp],
                );
        }


        $this->clusterWords["All Cat Words"] =
            array(
                "group" => 'Fat Cat Sat',
                "words" => [
                    $this->CVC['CaC'],
                    $this->CVC['CaC'],
                ],

                "title1" => "Sam the Cat",
                "image1" => "raghat.png",  // stable diffusion !!
                "words1" => "Sam the bad cat has a rag hat. \
                            A fat rat sat at the mat. \
                            Sam the cat can bat the rat. \
                            Zap! The rat is sad and mad. \
                            Sam is a bad cat. ",
                "note1" => "**'The'** cannot be decoded, and must  be memorized. Point it out.  There are only about 20 \
                            words like that, we will see them soon.

                            Try the 'Decode Level' buttons, see what they do.  'Non-content words provide \
                            the connection structure around words that have meaning.

                            Every story in this course has some meaning.  Ask your student 'What is going on?' \
                            What happened? What will happen next?",

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
                "pronounceSideText" => "We are starting the second vowel %% sound('ih') %%as in Bit.",



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
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    't',
                    ''
                ), // exception list

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
                    $this->catCK,
                    $this->kitCK
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
                        Zap the rat bit Tim the pig on his lip.  Nip on the lip!  Tim is mad.   \
                        Tim the pig is big and fat, and will sit on Zap the rat, and Zap will be as flat as a hat. ",
                "note1" => "A few words here we might not know yet, like 'on'.
                            Point out how many words are non-content words - typically almost half \
                            of the words in most text.  Most non-content words can be decoded normally, \
                            although they will be quickly memorized.  Your student must be 100% accurate with function words.  Watch carefully.

                            Every story in this course has some meaning.  Ask your student 'What is going on?' \
                            What happened? What will happen next?",
            );

        // $this->clusterWords['sh- and -sh and wh-'] =
        //     array(
        //         "group" => 'Bit Pit Sit',
        //         "words" => [$this->aiSH],

        //         "sidenote" => "The spelling %% spelling('sh') %% makes a single sound %% sound('sh') %%, which is different from %% sound('s') %%.<br><br>
        //         It can be used both at the beginning and end of a word. ",

        //         "wordsplus" => [$this->aiSH, $this->aiSH, $this->aiWH],
        //         "sidenoteplus" => "This list also contains 'wh-' words.<br><br>
        //                           The spelling %% spelling('wh') %% makes a single sound %% sound('wh') %%.",

        //         "spinner" => array(
        //             'b,c,d,f,g,h,j,k,l,m,n,p,r,s,sh,t,v,w,wh,z', // prefix, vowels, suffix for spinner
        //             'a,i',
        //             'ck,b,d,ff,g,k,m,n,p,sh,ss,t,zz',
        //             ''
        //         ), // exception list
        //         "spinnertext" => "Make a point of contrasting 's' and 'sh', and 'w' and 'wh'.",

        //     );


        $this->clusterWords["Bat and Bit with -ck"] =
            array(
                "group" => 'Bit Pit Sit',
                "words" => [$this->catCK, $this->kitCK],
                "sidenote" => "English has many sounds with spellings of two or more letters, and many spellings that make the same sound. \
            Spelling %% spelling('ck') %% makes the same %% sound('k') %% sound as %% spelling('c') %% in 'cat' and 'kit', but it can be put \
            at the end of many words.

            We introduce it here because it is very common, and we will soon use it in decodable stories",

                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $this->catCK,
                    $this->kitCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'k,ck,g,p,t',
                    ''
                ), // exception list


                "title1" => "Black Tick",
                "image1" => "whack.png",
                "credit1" => ['Quack Attack', 'Lisa Webber', 'FreeReading.Org', 'https://www.freereading.net/w/images/a/a3/QuackAttack.pdf', 'CC BY-NC-SA', '3.0', 'Adapted from'],
                "words1" => "Zack and Jack sat in a van. The van did click clack, click clack
            as it did pass a track. \\

            Jack sat on a sack and did snip and stab the
            snack that he had hid in his pack. Zack sat on a mat that was in a stack on a rack. \\

            Zack felt a smack on his back. \\

            Whack!  It was not a trick.  Jack did smack a black tick on Bill's back.  Jack hit Bill with a slap!
            The tick had not bit Bill so he will not be sick.  Bill was glad for the whack.",

                "note1" => "The words in green ovals are 'non-content words' that \
                            cannot be decoded and must be memorized. The words in pink ovals (in the *'non-content'* tab)  can be decoded.

                            Both require 100% accuracy.  Your student may not be attending carefully to these words, watch like a hawk!

                            'Felt' uses the %% sound('eh') %%.  The non-content words 'for', 'on', and 'not' are decodable but use vowels we have not yet seen.  Help your student with these.

                            Explain the exclaimation mark and how to emphasize when reading. After working through this page, try it again with other decoding options.",

            );

        if ($this->bdp) {
            $bdp = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat and Bit Words"] =
                array(
                    "group" => 'Bit Pit Sit',
                    "sidenote" => $this->bdpText,
                    "words" => [$bdp],
                );
        }

        $this->clusterWords["Cot Dot Jot"] =
            array(
                "group" => 'Cot Dot Jot',
                "pronounce" => "aw",
                "pronounceSideText" => "We are starting the third vowel %% sound('aw') %% as in Bot.",

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
                "title1" => 'Kit',
                "image1" => 'kit1.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],
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

                "title6" => "Kit's Pants",
                "image6" => 'kit6.png',
                "words6" => "Kit had pink pants. \
                         Kit's pants got lost at camp. \
                         Kit's mom got mad at Kit. \
                         Kit's mom can't stand lost pants.",

            );

        if ($this->bdp) {
            $bdp = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i', 'o'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat-Bit-Bot Words"] =
                array(
                    "group" => 'Cot Dot Jot',
                    "sidenote" => $this->bdpText,
                    "words" => [$bdp],
                );
        }

        $this->clusterWords["Fat/Cap/Bag + Bit/Big/Dip + Cot/Bog/Hop"] =
            array(
                "group" => 'Cot Dot Jot',
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





        //////////////////////////////////
        /// cat in the hat
        //////////////////////////////////
        $wa = new WordArtAbstract();
        $mwords = implode(',', $wa->memorize_words);
        $count = count($wa->memorize_words);


        $this->clusterWords["Spelling '-ck'"] =

            array(
                "group" => 'Get Ready for Books',

                "instruction" => "### Get Ready for Books

                Your student now has three vowels (%% sound('ah') %%,%% sound('ih') %%  and %% sound('ow') %%.  Wonderful!! \

                   It is time to start reading connected text, and we will start presenting decodable pages as part of our drills.

                    It is also important to start reading *real books*, even if they have many words your student cannot yet read. Find \
                    an easy book and have it ready. By the end of this section, your student will be ready for a first book.

            We will soon return to the vowel %% sound('uh') %% and our careful over-learning drills.",

                "group" => 'Get Ready for Books',

                $words = $this->aioCK,

                "sidenote" => " The ending %% spelling('ck') %% makes the same sound as %% spelling('c') %% or %% spelling('k') %%.  You can use it with all vowels we have studied.

                            There is an important idea here.  Spellings %% spelling('c') %%, %% spelling('k') %% and %% spelling('ck') %% are three \
                            different spellings for the sound %% sound('k') %%.

                            It is wrong to say 'a letter make a sound', more correct to say \
                            that 'a spelling makes a sound'.  This example shows that several \
                            spellings cand make the same sound",

                "words" => [$this->catCK],

                "wordsplus" => [$this->aioCK],
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'c,k,ck',
                    ''
                ), // exception list

            );


        $this->clusterWords["Exception for 'Ball'"] =

            array(

                "group" => 'Get Ready for Books',

                "stretch" => 'cat/call,bat/ball,mat/mall,tap/tall,fat/fall,hat/hall,sap/salt,tag/talk,map/malt,hag/halt,wag/walk',

                "words" => array(
                    $this->vowels['all'],
                ),
                "wordsplus" => array(
                    $this->vowels['all'],
                    $this->CVC['CaC'],
                ),

                //  (usually '{$noBreakHyphen}all' or '{$noBreakHyphen}alk' or '{$noBreakHyphen}alt')
                "stretchText" => "Words with 'a+L' make the %% sound('aw') %% sound, which \
                is different from the %% sound('ah') %% in similar-looking 'bat' / 'cat' words.

                These words are very common (ball, walk, salt).

                This is the same %% sound('aw') %% sound as in 'dog'.  Yikes, both spellings %% spelling('a') %%  \
                and %% spelling('o') %% can make the %% sound('aw') %% sound.  That's tricky, and why we use the \
                magenta color.  Explain it to your student.",

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
                    'ck,g,k,ll,lk,lt,m,n,p,ss,st,t,zz',
                    ''
                ), // exception list

                //                "2syl"    => $twoVowels['a/ah']
            );



        $this->clusterWords["Spelling '-ay' "] =
            array(
                "group" => 'Get Ready for Books',
                "sidenote" => "The spelling %%spelling('ay')%% almost always makes the sound %%sound('ay')%% as in 'bay'.

                This is our first two-letter vowel.  Words that end in %%spelling('ay')%% are different pattern from CVC words (like 'bat'), but confusingly similar.

                Practice them on the word spinner.",
                "words" => [$this->vowels['ay0']],
                "wordsplus" => [$this->vowels['ay0'], $this->vowels['ay1']],

                "plusSideNote" => "These are harder %%sound('ay')%% words. But since the ending is always the same, your student might be able to handle them. \
                 If not, don't worry, and don't spend much time here.
                 We will rigourously drill consonant clusters after we master the five short vowels.",


                // Two-syllable 'Away' and 'Okay' may need some explanation.",

                "spinnertext" => "*Careful!* When you use vowel %%spelling('ay')%% you must replace the final letter with a blank.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,o,i,ay',
                    'b,d,ff,g,k,ll,m,n,p,ss,t,th,y,zz,@',  // @ is a blank
                    ''
                ), // exception list

                "title1" => "The Clay Tray",
                "image1" => "claytray.jpeg",  // stable diffusion - wow!
                "words1" => "Can Ray and Fay stay this day?  I want to play and Mom says okay. \\
                    We play with clay.  We  cast a tray from clay, then we spray,
                    and stray clay falls away.
                    We dis/play the tray.  But we can/not pay for the clay so we may not  have the tray. \\
                    The day is hot, the wind is calm.  Ray and Fay must go away with no delay.",

                'testNote' => "Don't spend much time on this test, just one or two tries.  We haven't worked on consonant clusters yet, and your student may struggle.  Keep moving forward.",
            );





        $this->clusterWords["Review New Vowels"] =
            array(
                "group" => 'Get Ready for Books',

                "sidenote" => "We have new spellings %%spelling('a+l')%%, %%spelling('ay')%%, and %%spelling('ck')%%.  Let's review.",
                "words" => array(
                    $this->CVC['CaC'],
                    $this->catCK,
                    $this->vowels['all'],
                    $this->vowels['ay0'],
                    $this->vowels['ay1'],
                ),

                "wordsplus" => array(
                    $this->CVC['CaC'],
                    $this->CVC['CiC'],
                    $this->CVC['CoC'],
                    $this->catCK,
                    $this->aioCK,
                    $this->vowels['all'],
                    $this->vowels['all'],
                    $this->vowels['ay0'],
                    // $this->vowels['ay1'],
                ),

                "spinnertext" => "*Careful!* When you use vowel %%spelling('ay')%% you must replace the final letter with a blank.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,o,i,ay',
                    'b,d,ff,g,k,ll,m,n,p,ss,t,th,y,zz,@',  // @ is a blank
                    ''
                ), // exception list
                "image1" => 'dogball.png',
                "title1" => 'Play Ball With a Dog',
                "words1" => "If you play ball in the hall, you may hit
                    the clay pot or nick the cat. Or both. Then Mom say stop,
                    and will grab the ball. \
                    It is not fun to play tag with a doll since it can not walk or talk, and you
                    will win. \
                    You can play ball with this dog, it will not nip or lick
                    or walk. That is big fun.",

                'testNote' => "Don't spend much time on this test, just one or two tries.  We are not 'overlearning to automaticity' in this group of lessons, just quickly exploring some ideas.  Keep moving forward.",
            );


        $w1 =  'play,play>s,play>ing,play>ed';
        $w2 =  'play,play>s,play>ing,play>ed,call,call>s,call>ing,call>ed,slay,slay>s,slay>ed,slay>ing,sing,sing>s,sing>ed,sing>ing';


        $this->clusterWords["-ing and-ed"] =
            array(
                "group" => 'Get Ready for Books',
                "layout" => 'affixes',
                'affixtext' => "
                ### Verbs

                Verbs decribe actions that can happen in the past, present, or future. \
                There are four basic verb tenses, the base verb and three endings %% spelling('-s') %%, %% spelling('-ing') %%, and %% spelling('-ed') %%.

                Have your student read the *FIRST* and *THIRD* columns.  The interpretation of the second column will become clear soon. (hint: \
                these are do-nothing connectors.)",

                'words' => [$w1],

                'wordsplus' => [$w2],
                'plusSideNote' => "Look for `Wax` verbs, remember that we don't count w, x, or y as consonants",
            );

        $w3 = 'dip>ing,dip>ed,nag>ing,nag>ed,jog>ing,jog>ed,rot>ing,rot>ed,sip>ing,sip>ed';
        $w4 = $w3 . ',tip>ing,tip>ed,pop>ing,pop>ed,bag>ing,bag>ed,zip>ed,zip>ing,wax>ing,wax>ed';


        $this->clusterWords["-ing and-ed continued"] =
            array(
                "group" => 'Get Ready for Books',
                "layout" => 'affixes',


                'affixtext' => "
                ### Verbs II

                When a verb ends in a consonant, sometimes that consonant is doubled before adding the %% spelling('-ing') %%, and %% spelling('-ed') %% ending. Use this \
                rule for the CVC words we have been practicing.

               > Double ONLY if the word ends in 1 vowel + 1 consonant.  Don’t count w, x, or y as a consonant.

               These are the 'doubling-final' connectors.

               There is a slightly different rule for words with 2 or more syllables (double ONLY if the word ends in 1 vowel + 1 consonant AND the final syllable is stressed)",

                'words' => [$w3],
                'wordsplus' => [$w2, $w4],
            );



        $this->clusterWords["Function Words"] =
            array(
                "group" => 'Get Ready for Books',

                "sidenote" => "*Function Words* are the tiny words that provide structure to sentences. \
                About 20 of them cannot be decoded and must be memorized.  Many of these words are in The Cat in The Hat.  \
                We mark these words in these green circles.

                Watch for these words like a hawk, because
                many older failed readers ignore these words and get them wrong, mangling the meaning
                of sentences.  Function words must ALWAYS be read correctly.

                Don't spend much time on these words today, you will see them again and again.",

                "words" => [$mwords],
                "scrambleSideNote" => "These are common words that your student must memorize (not right away).  It's too much work to decode them.

                           'To', 'too', and 'two' should be pointed out.

                           'One' and 'two' are not as common as the others, but cannot be decoded (and are needed in 'Cat in The Hat').",
                //                "2syl"    => $twoVowels['a/ah']


                // https://tfcs.baruch.cuny.edu/content-and-function-words/
                "sentences" => [
                    "Kids hit drums.",
                    "The kids hit drums.",
                    "The kids hit the drums.",
                    "The kids will hit the drums.",
                    "The kids will be hit>ing the drums.",
                    "The kids have been hit>ing the drums.",
                    "The kids will have been hit>ing the drums.",
                ],
                "sentencesText" => "Have your student read each line and explain the *DIFFERENCE* in meanings.  It's ok if \
                                    they need to backtrack and correct himself.  Point how how the non-content words modify verbs.

                                    Point out that 'Kids', 'hit' 'drums' are the only content words.

                                    Non-content words must be read accurately to understanding a text.  They are often ignored by stronger \
                                    readers who process them effortlessly and accurately.  Your student must pay careful attention.",



                "title1" => 'Run>ing in a Sack',
                "credit1" => ['Oludeleadewalephotography', '', 'Wikimedia', 'https://commons.wikimedia.org/wiki/File:Sack_race_3.jpg', 'CC BY-SA', '4.0', 'Image:'],
                "image1" => "Sack_race_3.jpg",
                "words1" => 'I was want>ing to talk to you, but you are far a/way so I am jot>ing this to you. \
                            Last week, I was play>ing with my two pals, the three of us were skip>ing and hop>ing. \
                            We grab>ed some bags and had a sack run. I was hop>ing fast>est and win>ing until I trip>ed, tip>ed, and land>ed on my butt. \
                            Then we had a pic/nic with fish stick>s, and we were grin>ing as we talk>ed and munch>ed.',

            );









        $this->clusterWords["New Sound 'th' "] =
            array(
                "group" => 'Get Ready for Books',
                "stretch" => 'tat/that,tin/thin,tug/thug,tis/this,bat/bath,got/goth,mat/math,pat/path,pit/pith,wit/with',
                "words" => [$this->vowels['th']],
                "stretchText" => "Here's a new sound %% sound('th') %% that we can use both at the front and the back.

                        Sometimes the spelling %% spelling('th') %% makes the sound %% sound('dh') %% instead of %% sound('th') %%, as in 'other. \
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

        $this->clusterWords["Suffix '+ed''"] =
            array(
                "group" => 'Get Ready for Books',

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


        $this->clusterWords["New Sound 'sh'"] =
            array(
                "group" => 'Get Ready for Books',

                "words" => [$this->vowels['sh']],
                "wordsplus" => [
                    $this->vowels['sh'],
                    $this->vowels['sh2'],
                    $this->vowels['sh3'],
                ],
                "sidenote" => "Here's a new sound - %%sound('sh')%% that we can use both at the front and the back, just like %%sound('th')%%.
                            The WordSpinner has both 'sh' and 'th', make sure to contrast them.",


                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,e',
                    'b,d,ff,g,k,l,ll,m,n,p,ss,t,sh,th,zz',
                    ''
                ), // exception list

                "title1" => 'Nash had a Rash',
                "image1" => 'bandaid.png',
                "words1" => "Nash had a rash on his shin from a gash that he got in the bath, and it did not pass.  \
                            He did not wish to rot, so he grab>ed cash from his stash, and did a fast dash to the doc.  \
                            The doc shut the gash on his shin with a brush, then gave him a shot.  \
                            Then Nash pass>ed cash to the doc with his thanks. ",

            );





        $this->clusterWords["'ee' spelling of ee"] =
            array(
                "group" => 'Get Ready for Books',
                "words" => [$this->vowels['ee']],
                "wordsplus" => [$this->vowels['ee'], $this->vowels['ee2']],

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,ee',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list

                "sidenote" => "Rules mostly don't work in Phonics so we avoid them. But there is one reliable rule that you can point out to your student.

                             > The spelling %% spelling('ee') %% **always** makes the %% sound('ee') %% sound.

                             We are going to paint this spelling green to make it obvious.

                            LOTS of spellings make the sound %% sound('ee') %%, and this isn't even the most common (the spelling %% spelling('y') %% in 'baby' wins). \
                            But whenever you see the %% spelling('ee') %%, point out that it always makes the sound %% sound('ee') %%.",


                "title1" => "Scott and Lee",
                "image1" => 'scottlee1.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],
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








        $this->clusterWords["Review for Ready for Books"] =
            array(
                "group" => 'Get Ready for Books',
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
                ),

                "scrambleSideNote" => "This reviews our spellings for  <sound>ah</sound>, <sound>aw</sound>, and <sound>ay</sound> \
                                sounds, which all look similar - 'bat', 'ball', 'bay'.

                                The Decodable in this lesson has a new black word 'you'.  Point it out.",
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,d,ff,g,k,l,lk,ll,lt,m,n,p,ss,t,th,th,y,zz',
                ),

            );




        // $this->clusterWords["Ends in '-ear'"] =
        // array(
        //     "group" => 'The Cat in The Hat',
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
                "pronounceSideText" => "We are starting the fourth vowel %% sound('uh') %% as in But.",

                "words" => [$this->words["but"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    't',
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
                "wordsplus" => array(
                    $this->CVC["CuC"],
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
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
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CuC"],
                    $this->catCK,
                    $this->rugCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list


                "title1" => 'Frog Facts',
                "image1" => 'frogs.png',
                "credit1" => ['Frog Facts', 'https://www.freereading.net/w/images/f/fb/Decodable_nonfiction_1.pdf', 'FreeReading.Net', 'https://www.freereading.net', 'CC BY-NC-SA', '3.0', 'Adapted from'],

                "words1" => "A frog can swim, and it can be on land. It has
                    skin that is slick. \
                    A frog will sit on a log. If a frog wish>es to grab a
                    bug, it sits still, and when a bug lands next to it,
                    the frog snaps the bug up. \
                    Then it can jump off for a swim. Frogs can jump
                    well, and they swim well. ",

                "note1" => "'Well' is an %% sound('eh') %% word that students have not \
                    yet practiced.  Let them try, then help them if necessary.

                    Check for comprehension. For example, ask your student to act out \
                    how a frog catches a bug, or describe the catch from the fly's point of view.",
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
                "wordsplus" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CuC"],
                    $this->kitCK,
                    $this->rugCK,
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

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
                "stretch" => "bid/bud,bit/but,big/bug,
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
                "wordsplus" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CuC"],
                    $this->kitCK,
                    $this->rugCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );


        $this->clusterWords["Bot + Bug"] =
            array(
                "group" => 'Bug Rug Jug',
                "contrast" => "aw,uh",
                "stretch" => 'bog/bug,dog/dug,hog/hug,log/lug,pop/pup,cop/cup',
                "words" => array(
                    $this->words["bog"],
                    $this->words["bug"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

            );

        $this->clusterWords[$this->contrastTitle('ow', 'uh', 'o', 'u')] =
            array(
                "group" => 'Bug Rug Jug',
                "stretch" => "bog/bug,bot/but,boss/bus,
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
                "wordsplus" => array(
                    $this->CVC["CoC"],
                    $this->CVC["CuC"],
                    $this->botCK,
                    $this->rugCK,
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

            );

        $this->clusterWords["Bog + Bug + Cot + Cut"] =
            array(
                "group" => 'Bug Rug Jug',
                "words" => array(
                    $this->words["cot"],
                    $this->words["bug"],
                    $this->words["but"],
                    $this->words["bug"]
                ),
                "wordsplus" => array(
                    $this->CVC["CoC"],
                    $this->CVC["CuC"]
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );

        if ($this->bdp) {
            $bdp = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i', 'o', 'u'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Bat-Bit-Bot-But Words"] =
                array(
                    "group" => 'Bug Rug Jug',
                    "sidenote" => $this->bdpText,
                    "words" => [$bdp],
                );
        }

        $this->clusterWords["Four Vowel Review"] =
            array(
                "group" => 'Bug Rug Jug',
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
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $this->CVC["CoC"],
                    $this->CVC["CuC"],
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

            );





        $this->clusterWords["Bet Get Jet"] =
            array(
                "group" => 'Bet Get Jet',
                "pronounce" => "eh",
                "pronounceSideText" => "We are starting the fifth vowel %% sound('eh') %% as in Bet.",
                "words" => [$this->words["bet"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'e',
                    't',
                    ''
                ), // exception list
            );



        $this->clusterWords["Bet + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "stretch" => 'beg/bet,let/leg,met/Meg,pet/peg',
                "words" => array(
                    $this->words["beg"],
                    $this->words["bet"]
                ),
                "wordsplus" => array(
                    $this->CVC["CeC"],
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i,o,u,e',
                    'b,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
            );


        $this->clusterWords["Bat + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "ah,eh",
                "stretch" => "bat/bet,bag/beg,dab/Deb,Dan/den,fan/fen,fad/fed,
                    lad/led,lag/leg,lass/less,man/men,mat/met,mass/mess,
                    pan/pen,pat/pet,sat/set,tan/ten,vat/vet,wad/wed",
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["bet"],
                    $this->words["beg"]
                ),
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CeC"],
                    $this->catCK,
                    $this->betCK,
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
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



        $this->clusterWords["Bit + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "ih,eh",
                "stretch" => "bid/bed,bill/bell,bin/Ben,bit/bet,big/beg,
                   din/den,fin/fen,hill/hell,lid/led,miss/mess,
                   pin/pen,pig/peg,pit/pet,sit/set,sill/sell,
                   tin/ten,till/tell,will/well,wit/wet",
                "words" => array(
                    $this->words["bit"],
                    $this->words["bet"]
                ),
                "wordsplus" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CeC"],
                    $this->kitCK,
                    $this->betCK,
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    't',
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




        $this->clusterWords["Bat + Bit + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],

                    $this->words["bet"],  // extra e words
                    $this->words["beg"],
                    $this->words["bet"],
                    $this->words["beg"],
                ),
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $this->catCK,
                    $this->kitCK,

                    $this->CVC["CeC"],  // extra e words
                    $this->betCK,
                    $this->CVC["CeC"],
                    $this->betCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

                "title1" => "Tim Had Mumps",
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],
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


        $this->clusterWords["Bog + Beg"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "aw,uh",
                "stretch" => "boss/Bess,bog/beg,Don/den,jot/jet,log/leg,lot/let,
                    loss/less,moss/mess,nod/Ned,not/net,pot/pet,toss/Tess",

                "words" => array(
                    $this->words["bog"],
                    $this->words["beg"]
                ),
                "wordsplus" => array(
                    $this->CVC["CoC"],
                    $this->CVC["CeC"],
                    $this->botCK,
                    $this->betCK,
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
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



        $this->clusterWords["Bat + Bot + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["cot"],
                    $this->words["bog"],

                    $this->words["bet"],  // extra e words
                    $this->words["beg"],
                    $this->words["bet"],
                    $this->words["beg"],
                ),
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CoC"],
                    $this->CVC["CeC"],
                    $this->catCK,
                    $this->botCK,

                    $this->CVC["CeC"],  // extra e words
                    $this->betCK,
                    $this->CVC["CeC"],
                    $this->betCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
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



        $this->clusterWords["Bit + Bot + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["cot"],
                    $this->words["bog"],

                    $this->words["bet"],  // extra e words
                    $this->words["beg"],
                    $this->words["bet"],
                    $this->words["beg"],
                ),
                "wordsplus" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CoC"],
                    $this->CVC["CeC"],
                    $this->kitCK,
                    $this->botCK,
                    $this->betCK,

                    $this->CVC["CeC"],  // extra e words
                    $this->betCK,
                    $this->CVC["CeC"],
                    $this->betCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
                "title1" => 'Insects',
                "image1" => 'insects.png',
                "words1" => "This plan/et has a lot of in/sects on it. Insects (or
                                        bugs) are of/ten pests and can at/tack plant>ed
                                        crops, animals, and us, as well. \
                                        The cricket is an insect. It is big and black, and
                                        it can jump as fast as a frog. In fact it must, for it
                                        is of/ten hunt>ed by frogs. \
                                        The ant is not as big as the cricket. Ants are
                                        strong, and they dig long, twist>ed tun/nels that
                                        con/nect well.",
            );


        $this->clusterWords["Bat + Bit + Bot + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bat"],
                    $this->words["cap"],
                    $this->words["bag"],
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["cot"],
                    $this->words["bog"],

                    $this->words["bet"],  // extra e words
                    $this->words["beg"],
                    $this->words["bet"],
                    $this->words["beg"],
                ),
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CiC"],
                    $this->CVC["CoC"],
                    $this->CVC["CeC"],
                    $this->catCK,
                    $this->kitCK,
                    $this->botCK,
                    $this->betCK,

                    $this->CVC["CeC"],  // extra e words
                    $this->betCK,
                    $this->CVC["CeC"],
                    $this->betCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
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






        $this->clusterWords["But + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "contrast" => "uh,eh",
                "stretch" => "bud/bed,bug/beg,bun/Ben,but/bet,fun/fen,hull/hell,
                    Hun/hen,jut/jet,lug/leg,muss/mess,nut/net,pun/pen",

                "words" => array(
                    $this->words["but"],
                    $this->words["bet"]
                ),
                "words" => array(
                    $this->CVC["CuC"],
                    $this->CVC["CeC"],
                    $this->rugCK,
                    $this->betCK,
                ),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list

                "title1" => "Which Are Good Pets?",
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

        //     "title1" => 'Have a Picnic!',
        //     // "image1" => 'sandbox.png',
        //     "words1" => "In the spring, if the sun is out, a pic/nic is a good
        //         bet for a fun thing to do. Pick a spot on the
        //         grass, and fling a big blanket to sit on. \
        //         Fill a bas/ket with muf/fins, nap/kins, and plas/tic
        //         cups. If the pic/nic bas/ket has flaps on it, it will
        //         stop in/sects that want to jump in. \
        //         A pic/nic next to a pond can be splen/did. You
        //         can toss scraps to the ducks and then go for a
        //         swim.",
        //     "note1" => "Lots of two-syllable words here.  Point them out.<br><br>
        //         And ask comprehension questions!"
        // );



        $this->clusterWords["Bat + But + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bat"],
                    $this->words["bag"],
                    $this->words["cap"],
                    $this->words["but"],
                    $this->words["bug"],

                    $this->words["bet"],  // extra e words
                    $this->words["beg"],
                    $this->words["bet"],
                    $this->words["beg"],
                ),
                "wordsplus" => array(
                    $this->CVC["CaC"],
                    $this->CVC["CuC"],
                    $this->CVC["CeC"],
                    $this->catCK,
                    $this->rugCK,

                    $this->CVC["CeC"],  // extra e words
                    $this->betCK,
                    $this->CVC["CeC"],
                    $this->betCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list


                "title1" => "Seth",
                "image1" => 'sethbed.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],

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



        $this->clusterWords["Bit + But + Bet"] =
            array(
                "group" => 'Bet Get Jet',
                "words" => array(
                    $this->words["bit"],
                    $this->words["big"],
                    $this->words["dip"],
                    $this->words["but"],
                    $this->words["bug"],

                    $this->words["bet"],  // extra e words
                    $this->words["beg"],
                    $this->words["bet"],
                    $this->words["beg"],
                ),
                "wordsplus" => array(
                    $this->CVC["CiC"],
                    $this->CVC["CuC"],
                    $this->CVC["CeC"],
                    $this->kitCK,
                    $this->rugCK,
                    $this->betCK,

                    $this->CVC["CeC"],  // extra e words
                    $this->betCK,
                    $this->CVC["CeC"],
                    $this->betCK,
                ),

                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,ck,d,ff,g,k,m,n,p,ss,t,th,zz',
                    ''
                ), // exception list
                "title1" => "Seth's Finch",
                "image1" => 'sethbird.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],

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







        if ($this->bdp) {
            $bdp = $this->gen3letters(array('b', 'd', 'p'), array('a', 'i', 'o', 'u', 'e'), array('b', 'd', 'p'));
            $this->clusterWords["b-d-p for Five Vowels"] =
                array(
                    "group" => 'Bet Get Jet',
                    "sidenote" => $this->bdpText,
                    "words" => [$bdp],
                );
        }




        $fiveSounds = '';
        $views = new Views();
        foreach (['ah', 'ih', 'ow', 'uh', 'eh'] as $sound)
            $fiveSounds .= (empty($fiveSounds) ? '' : '&nbsp;&nbsp;') . "%% sound('$sound') %%";

        $this->clusterWords["Five Short Vowels"] =
            array(
                "group" => 'Bet Get Jet',

                "sidenote" => "Congratulations.  Your student now has the five 'short' vowels $fiveSounds.

                    <img src='pix/junie.png' height='200' style='float:right;padding:20px' />

                    Hopefully you have been reading 'Cat in the Hat' or similar.  It is now time to move on to harder grade-2 chapter books.

                    I recommend the 'Junie B. Jones' books for both boys and girls, and for \
                    all ages including adults.  They are well-written, funny, and subversive.  Older boys \
                    will also enjoy the 'Secret Agent Jack Stalwart' series.

                    Older students and adults are usually impatient to start harder, 'useful' books, \
                    but that is always a mistake.  They will only get frustrated and make no further progress.",



                "words" => [
                    $this->CVC['CaC'],
                    $this->CVC['CiC'],
                    $this->CVC['CoC'],
                    $this->CVC['CuC'],
                    $this->CVC['CeC'],
                    $this->catCK . ',' . $this->kitCK,
                    $this->aiouSH,
                    $this->aioueCK,
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

                "testNote" => "The most important thing now is to start reading authentic books.  Your student
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

        $SHprefixes = "sham,shed,shin,ship,shod,shop,shot,shun,shut,shack,shaft,shall,shank,shelf,shell,shift,shock,shops,shred,shrub,shrug,shuck,shush";

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

        $this->clusterWords["Suffix Clusters (mp, lf, sk)"] =
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
                "wordsplus" => [$suffixClusters, $suffixDigraphs],

                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'sh, ch, tch, ph',
                    ''
                ), // exception list
            );




        $this->clusterWords["Prefix Clusters (sh shr)"] =
            array(
                "group" => 'Consonant Clusters',
                "stretch" => "Sam/sham,sin/shin,sip/ship,sop/shop,sod/shod,sun/shun,sell/shell,sack/shack,sock/shock,suck/shuck",
                "words" => [$SHprefixes],

                "spinner" => array(
                    's,sh,shr', // prefix, vowels, suffix for spinner
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
                "wordsplus" => [$prefixClusters, $SHprefixes],

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

                // five sparate string so equally distributed vowels
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


                "title1" => 'Trap',
                "image1" => 'trap.png',
                "credit1" => ['The Trap', 'Lisa Webber', 'FreeReading.Net', 'https://www.freereading.net/w/images/5/57/Decodable_Fiction_1.pdf', 'CC BY-NC-SA', '3.0'],
                "words1" => "“It's a trap!“ Gil said. He put his hand up to stop,
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


            );





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
            );



        $nksWords = "bangs,fangs,gangs,hangs,banks,ranks,yanks,tanks,
            dings,kings,pings,rings,links,sings,wings,finks,minks,rinks,sinks,
            bongs,songs,bonks,monks,
            rungs,bunks,dunks,junks,punks";

        $nksPlusWords = "blanks,cranks,clanks,flanks,franks,planks,shanks,spanks,swanks,thanks,
                            blinks,clinks,drinks,stinks,thinks,flings,things,stings,swings,
                            clonks,plonks,prongs,thongs,wrongs,
                            chunks,clunks,drunks,flunks,plunks,skunks,trunks";

        $this->clusterWords["Suffix Digraphs (ngs, nks)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => [$nksWords],
                "wordsplus" => [$nksWords, $nksPlusWords],
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'ng,ngs,nk,nks,',
                    ''
                ), // exception list
            );


        // r-controlled prefixes (br, cr, dr, fr, gr, pr, scr, spr, str, tr)

        $this->clusterWords["r-Controlled prefixes(br, cr, fr, ...)"] =
            array(
                "group" => 'Consonant Clusters',
                // "stretch" => "rat/brat,ring/bring,rust/crust,rip/trip,rust/trust,rash/crash,
                //             rub/scrub,rink/drink,rip/strip,rug/drug,rap/strap,rush/brush,
                //             rip/grip,rag/brag,rim/brim,ramp/cramp,ring/string",
                "stretch" => "rat/brat,rip/trip,rag/brag,ram/cram,rap/trap,rib/crib,rim/brim,rip/trip,rod/prod,rot/trot
                                rub/drub,red/bled,rep/prep",
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
                    'b,ck,ct,d,ft,lf,lk,lp,m,mb,mp,n,nd,ng,nk,nt,p,pt,s,sh,sk,sp,st,t',
                    ''
                ), // exception list
            );

        // digraphs (qu, th, wh, squ, thr)
        $this->clusterWords["Digraphs (qu, th, wh, squ, thr)"] =
            array(
                "group" => 'Consonant Clusters',
                "words" => ["broth,cloth,froth,
                            quit,quick,quack,quill,quilt,quip,quad,
                            math,moth,
                            smith,square,squint,
                            that,then,three,thick,think,this,thrush,thrift,thank,thump,thin,
                            whack,whiff,whim,whip,with,whish,whizz"],

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
            );


        // 'silent-letter' prefix clusters (kn
        $this->clusterWords["Silent Prefix Clusters (kn-, gn-, wr- ...)"] =
            array(
                "group" => 'Consonant Clusters',
                "sidenote" => "This lesson has some tricky words that start with digraphs like kn-, gn-, and wr-.

                It is incorrect to say the first letter in these words is silent.  Letters are sometimes the same as \
                            spellings but not always.  The 'k' in 'knot' is not silent, but rather part of the %%spelling('kn')%% spelling and that spelling is pronounced %%sound('n')%%.

                            These ideas are developed in a phonics course.",
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






        ////////////////////////////
        ////////////////////////////
        ////////////////////////////
        ////////////////////////////



        ///////////////////////////
        //// the a_e pattern
        ///////////////////////////

        $this->clusterWords["a_e spelling of /ay/"] =
            array(
                "group" => 'Silent-e Spellings',
                "pronounce" => "ay",
                "pronounceSideText" => "We are starting the five 'magic-e' vowels.  This lesson will attack \
                                words like 'made', 'lame', 'nape', and 'gate'.",

                "stretchText" => "
                        %%spelling('a_e')%% is not \
                        the most common spelling of the sound  %%sound('ay')%% (it is the %%spelling('y')%% as in 'baby') \
                        but it causes the most difficulty.

                        It is old-fashioned and incorrect to say \"The green 'Magic E' changes the earlier vowel to say its name.\"
                        But 'Magic E' is powerful teaching tool, and I use it anyhow.",



                "stretch" => "rat/rate,can/cane,ban/bane,rat/rate,hat/hate,mat/mate,
                                tam/tame,tap/tape,fad/fade,tap/tape,mad/made,pan/pane,rag/rage,van/vane",
                "words" => [$this->CVCe["CaCe"]],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'be,ce,de,ge,ke,le,me,ne,pe,se,te',
                    ''
                ), // exception list
            );

            $this->clusterWords["a_e spelling of /ay/ (mixed)"] =
            array(
                "group" => 'Silent-e Spellings',

                "words" => [
                    $this->CVCe["CaCe"],
                    $this->CVCe["CaCe"],
                    $this->CVC["CiC"],
                ],
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'a',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );


        $temp = 'bran,plan,span,clan,gran,scan';        // very short list, but add to this lesson

        $this->clusterWords["a_e spelling of /ay/ (harder)"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CCaCe"]),
                "wordsplus" => array(
                    $temp,
                    $this->CVCe["CaCe"],
                    $this->CVCe["CCaCe"],
                    $this->CVC["CaC"],
                ),
                "spinner" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'a',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
                "title1" => "Cake and Grape>s",
                "image1" => 'scottjade1.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],

                "words1" => "Scott got a cake to split with his
                pal Jade. Jade got a bunch of red
                grape>s to split with Scott. \
                Scott went to Jade's and gave
                Jade the cake. Jade gave Scott the
                grape>s. Then the kid>s sat and ate.
                Jade ate all of Scott's cake. Scott
                ate all of Jade's grape>s.",

                "title2" => "Fun in the Sand",
                "image2" => 'funsand.png',
                "words2" => "Scott is with Jade and Dave. The
                kids dig in the sand. They shape the
                sand. They make a sand man. \
                A big wave hit>s. The kid>s can't
                save their sand man from the wave.
                The sand man get>s wet. He slump>s.
                He sags. He drip>s. \
                The sand man is a mess. But the
                kid>s are not sad. They run and splash
                in the wave>s.",

                "title3" => 'Skate>s',
                "image3" => 'skates.png',
                "words3" => "Jade got skate>s when she was
                six. Scott just got his last week. He
                crave>s to get up on his skate>s. \
                \"Is this safe?\" Scott ask>s. \"What if
                I trip and get a scrape? What if I hit
                a tree? What if I see a snake?\" \
                \"It is safe!\" say>s Jade. \"Just skate.\" \
                Jade helps Scott skate. Scott slip>s
                and trip>s. Then he gets the hang of it.
                \"Jade,\" he yell>s, \"it\'s fun to skate!\"",

                "title4" => 'Scott Bake>s',
                "image4" => 'bakecake1.png',
                "words4" => "Scott\'s mom bake>s cake>s with Meg. \
                \"Scott,\" she say>s, \"you can help us
                with this cake, it will be a game.\" \
                Scott shrugs. \"Well,\" he say>s, \"if
                you will take my help, I will help.\" \
                \"It will be fun,\" say>s his mom. \"You
                can crack the egg>s. \
                Scott crack>s three egg>s and
                drop>s them in the dish.",

                "image5" => 'bakecake2.png',
                "words5" => "Scott ask>s if he can mix up the
                egg>s. Then he ask>s if he can add in
                the cake mix. \
                \"Well,\" his mom say>s, \"if you add
                the cake mix, then Meg gets to frost
                the cake.\" \
                \"Can I help Meg frost it?\" Scott
                ask>s.  Mom and Meg smile. \
                Meg say>s, \"See, Scott. It\'s fun to
                bake a cake!\"",
            );


        /////////////////////////

        $this->clusterWords["i_e spelling of /igh/"] =
            array(
                "group" => 'Silent-e Spellings',
                "pronounce" => "igh",
                "stretch" => "bid/bide,bit/bite,dim/dime,din/dine,fin/fine,hid/hide,kit/kite,lit/lite,min/mine,
                                mit/mite,pin/pine,pip/pipe,rip/ripe,sit/site,Tim/time,tin/tine",
                "words" => [$this->CVCe["CiCe"]],
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'be,ce,de,ge,ke,le,me,ne,pe,se,te',
                    ''
                ), // exception list

            );

            $this->clusterWords["i_e spelling of /igh/ (mixed)"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => [
                    $this->CVCe["CiCe"],
                    $this->CVCe["CiCe"],
                    $this->CVC["CiC"],
                ],
                "spinner" => array(
                    'b,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,i',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list

            );


        $this->clusterWords["i_e spelling of /igh/ (harder)"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CCiCe"]),
                "wordsplus" => array($this->CVCe["CiCe"], $this->CVCe["CCiCe"],$this->CVC["CiC"]),
                "spinner" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'a,i',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );



        $temp = 'chin,skin,grin,thin,spin';        // very short list, but add to this lesson

        $this->clusterWords["i_e spelling of /igh/ (mixed)"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CiCe"], $this->CVC["CiC"]),
                "wordsplus" => array($temp, $this->CVCe["CCiCe"], $this->CVCe["CiCe"], $this->CVC["CiC"]),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        // reviews for a_e, i_e and a, i

        $this->clusterWords["Contrast a_e and i_e"] =
            array(
                "group" => 'Silent-e Spellings',
                "stretch" => "lake/like,bake/bike,dame/dime,wade/wide,pane/pine,vane/vine,mate/mite,tame/time,male/mile,mane/mine",
                "words" => array($this->CVCe["CiCe"], $this->CVCe["CaCe"]),
                "wordsplus" => array($this->CVCe["CiCe"], $this->CVCe["CaCe"], $this->CVCe["CCiCe"], $this->CVCe["CCaCe"], $this->CVC["CaC"], $this->CVC["CiC"]),

                "title1" => "A Fine Hike",
                "image1" => 'hike1.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],
                "words1" => 'Scott is on a hike with Clive and
            Clive\'s dad. They hike three mile>s up
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
            kids dine on hot dogs. \
            At nine, they get in their tent.
            They are all tired. They smile as they
            sleep. They all had a fine time.',

                "title3" => "The Bike Ride",
                "image3" => 'bike.png',
                "words3" => 'Scott\'s sis, Meg, likes to ride a
            bike. One time, Meg went on a bike ride
            with Scott. Meg\'s tire hit a rock and
            she fell off the bike. \
            Meg was brave. She did not yell.
            She did not sob. She got back on the
            bike. Then she said, "Let\'s ride!" \
            "Meg," Scott said, "I am glad my
            sis is so brave!" \
            That made Meg smile with pride!',

                "title4" => 'The Plane Ride',
                "image4" => 'plane.png',
                "words4" => 'Scott\'s dad rents a plane. He asks
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
                "group" => 'Silent-e Spellings',
                "pronounce" => "oh",
                "stretch" => "cod/code,con/cone,cop/cope,dot/dote,hop/hope,lob/lobe,
                                mod/mode,nod/node,not/note,pop/pope,rob/robe,rod/rode,
                                tot/tote",
                "words" => [$this->CVCe["CoCe"]],
                "wordsplus" => array($this->CVCe["CoCe"], $this->CVC["CoC"]),
                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,th,v,w,z', // prefix, vowels, suffix for spinner
                    'o',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list

            );


        $this->clusterWords["o_e spelling of /oh/ (harder)"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CCoCe"]),
                "wordsplus" => array($this->CVCe["CaCe"], $this->CVCe["CCaCe"],$this->CVC["CoC"]),
                "spinner" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'o',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["o_e spelling of /oh/ (mixed)"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CCoCe"], $this->CVCe["CoCe"]),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'o',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list


                "title3" => 'The Sled Ride',
                "image3" => 'sled1.png',
                "words3" => "“I’ll drive!” said Scott, as he sat on
                the sled. Jade and Meg got on next.
                Dave was the last one on the sled.
                He sat in back. \
                The sled slid off. It went fast.
                “Scott,” Jade said, “steer to the left!
                There’s a big stone there by the—”
                Smack! The sled hit the stone. The
                kids fell off.",

                "image4" => 'sled2.png',
                "words4" => "“I’ll drive!” said Scott, as he sat on
                Scott went to check on Jade. \
                “Ug!” Jade said. “I feel like I broke
                all the bones in my leg!” \
                “Hop on the sled,” Scott said. “I
                will drag it home.” \
                Meg went to check on Dave. \
                Dave said, “I froze my nose!” \
                “Hop on the sled with Jade,” said
                Meg. “Scott and I will drag it home.”",


            );


        $this->clusterWords["Contrast a_e and o_e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"]),
                "wordsplus" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"]),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,o',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a_e, i_e and o_e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"], $this->CVCe["CiCe"]),
                "wordsplus" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"], $this->CVCe["CCiCe"]),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a, a_e and o, o_e "] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array($this->CVCe["CoCe"], $this->CVCe["CaCe"], $this->CVC["CoC"], $this->CVC["CaC"]),
                "wordsplus" => array($this->CVCe["CCoCe"], $this->CVCe["CCaCe"], $this->CVC["CoC"], $this->CVC["CaC"]),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a, a_e, i, i_e, and o, o_e "] =
            array(
                "group" => 'Silent-e Spellings',
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

                "spinner" => array(
                    'bl,br,cl,cr,dr,fl,fr,gl,gr,pr,sc,scr,sk,sn,spl,spr,st,str,tr,tw', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list

                "title1" => 'Scott\'s Snack Stand',
                "image1" => 'snack1.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],
                "words1" => 'Scott has a snack stand. Last
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
                "group" => 'Silent-e Spellings',
                "pronounce" => "ue",
                "words" => array($this->CVCe["CCuCe"]), // hard ones right away

                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'u',
                    'be,ce,de,fe,ge,ke,le,me,ne,pe,se,te,ze',
                    ''
                ), // exception list
            );

            $this->clusterWords["u_e spelling of /ue/ (harder)"] =
            array(
                "group" => 'Silent-e Spellings',
                "pronounce" => "ue",
                "words" => array($this->CVCe["CCuCe"]), // hard ones right away
                "wordsplus" => array($this->CVCe["CCuCe"], $this->CVC["CuC"]),

                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );


        $this->clusterWords["Contrast i_e and u_e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CCuCe"],
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCiCe"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast i_e, i, u_e, and u"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVC["CiC"],
                    $this->CVC["CuC"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast o_e and u_e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CCuCe"],
                    $this->CVCe["CoCe"],
                    $this->CVCe["CCoCe"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'be,ce,de,fe,ge,ke,le,me,ne,pe,se,te,ze',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast o_e, o, u_e, and u"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCuCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CuC"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a, a_e, i, i_e, o, o_e, and u, u_e spellings"] =
            array(
                "group" => 'Silent-e Spellings',
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
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        //////////////////////////////

        $this->clusterWords["e_e spelling of /ee/"] =
            array(
                "group" => 'Silent-e Spellings',
                "pronounce" => "ee",
                "words" => [$this->CVCe["CCeCe"]], // only the hard ones
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'e',
                    'be,ce,de,fe,ge,ke,le,me,ne,pe,se,te,ze',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast e_e /ee/ and e /eh/"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CCeCe"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'e',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast a_e and e_e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CCeCe"],
                    $this->CVCe["CaCe"],
                    $this->CVCe["CCaCe"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast i_e, i, e_e, and e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CiCe"],
                    $this->CVCe["CCiCe"],
                    $this->CVCe["CCeCe"],
                    $this->CVC["CiC"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list
            );

        $this->clusterWords["Contrast o_e, o, e_e, and e"] =
            array(
                "group" => 'Silent-e Spellings',
                "words" => array(
                    $this->CVCe["CoCe"],
                    $this->CVCe["CCoCe"],
                    $this->CVCe["CCeCe"],
                    $this->CVC["CoC"],
                    $this->CVC["CeC"]
                ),
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list


                "title1" => 'The Gift',
                "image1" => 'gift1.png',
                "credit1" => ['Core Knowledge Foundation', '', '', 'https://www.coreknowledge.org/', 'CC BY-NC-SA', '4.0'],
                "words1" => "Scott and Meg’s mom is name>ed
            Liz. She stops off at Hope’s Dress  Shop. \
            “Hope,” Liz says, “I need a doll’s
            dress. The dress on Meg’s doll has a
            bunch of holes in it.” \
            “Well,” says Hope, “here’s a dress.
            It’s a doll’s size, and it’s on sale.” ",


                "image2" => 'gift2.png',
                "words2" => "“This is just what I need!” says Liz.
            “It will fit Meg’s doll, and Meg likes green!” \
            Hope drops the dress in a bag. Liz
            hands Hope cash. Hope hands the
            bag to Liz. \
            Hope is glad. She has made a
            sale. Liz is glad, as well. She has a gift
            to take home to Meg.",

            );

        $this->clusterWords["Review all ?_e spellings"] =
            array(
                "group" => 'Silent-e Spellings',
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
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'be,ce,de,fe,ge,ke,le,me,ne,pe,se,te,ze',
                    ''
                ), // exception list
            );


            $w1 = '';
            foreach (['tape','bake','save','hike','mine','time','pose','rule','prune','wire'] as $w){
                $w1 .= (empty($w1)?'':',')."$w>ing,$w>er,$w>ed";    // all with endings
            }
            $w2 = '';
            foreach (['bat','tap','slim','rip','kiss','fit','log','mix'] as $w){
                $w2 .= (empty($w2)?'':',')."$w>ing,$w>er,$w>ed";    // all with endings
            }




            $this->clusterWords["e-ending -ing and-ed"] =
            array(
                "group" => 'Silent-e Spellings',
                "layout" => 'affixes',

                'affixtext' => "When a word ends in -e and the suffix begins with a vowel, you usually drop the -e before adding the suffix. This is the 'no-e' rule",

                'words' => [$w1],
            );


            $this->clusterWords["e-ending -ing and-ed continued"] =
            array(
                "group" => 'Silent-e Spellings',
                "layout" => 'affixes',
                "stretch" => 'bat>ing/bate>ing,tap>ing/tape>ing,slim>ing/slime>ing,rip>er/ripe>er,kit>ing/kite>ing,fat>ed/fate>ed,hat>er/hate>er,tub>ing/tube>ing',
                "stretchText" => "There is a BIG difference between staring and starring, but we haven't covered the %%spelling('ar')%% vowel yet..",

                'affixtext' => "When you see a double consonant, maybe it was the 'double-final' and maybe it was 'do-nothing'.  The 'Drop-e' rule only follows single consonants.",

                'words' => [$w1,$w2],
                'sidenote' => "Ask you student which rule to use -  'drop-e', 'double', or 'do-nothing'.  Why?",
            );



        $this->clusterWords["Review all long and short spellings"] =
            array(
                "group" => 'Silent-e Spellings',
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
                "spinner" => array(
                    'b,bl,br,cl,cr,d,dr,f,fl,fr,g,gl,gr,h,k,l,m,n,p,pr,r,s,sc,scr,sk,sn,spl,spr,st,str,t,tr,tw,v', // prefix, vowels, suffix for spinner
                    'a,e,i,o,u',
                    'b,be,ce,d,de,g,ge,k,ke,l,le,m,me,n,ne,p,pe,s,se,t,te',
                    ''
                ), // exception list


                "title1" => 'Bell>ing the Cat',
                "image1" => 'belling.png',
                "words1" => 'The Mice call>ed a meet>ing to make a plan to get free from their en/em/y, the Cat. \
                    They wish>ed to find some way to know where she was, so they would have time to run away. \
                    Something had to be done, for the Cat\'s claws gave the mice the creeps. \
                    They talk>ed and made man/y plan>s, but their best plan was still not ver/y good. ',

                "words2" => '
                    At last a very small Mouse got up and said:
                    "I have a plan that I know will be good." \

                    "All we have to do is to hang a bell on the Cat\'s neck.
                    When we hear the bell ring>ing, we will know that she is close." \

                    All the Mice cheer>ed. This was a very good plan. \

                    But an wise Mouse rose and said:
                    "I will say that the plan of the small Mouse is very good. But let me ask: Who will bell the Cat?"',

                "title3" => 'Lesson',
                "image3" => 'belling.png',
                "words3" => 'It is one thing to say that some/thing should be done, but quite an/other thing to do it.',


            );
    }
}



/*
               "title1" => "Glass",
                "image1" => "glass.png",
                "credit1" => ['Making Glass', 'Lisa Webber', 'FreeReading.Net', 'https://www.freereading.net/w/images/7/70/MakingGlass.pdf', 'CC BY-NC-SA', '3.0', 'Adapted from'],

                "words1" => "Did you know that we get glass from sand? Sand is melt>ed and then chill>ed. It is turn>ed
                into glass. The cooling is why you can see through glass. You can tell how
                thin glass is by how well you see through it is. Thin glass is clear>er. \

            There is col/or>ed glass too. You can see through it, but not as much as nor/mal glass. Col/or>ed glass
            is made by add>ing col/or. \
            There is blue, green, and brown glass. Sand is soft. It smell>s like earth. If you touch glass, it
            is hard. If you sniff it, it has no smell. It is hard to think that glass was once sand.",

*/