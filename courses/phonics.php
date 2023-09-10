<?php
defined('_PHONICS') or die;



todo("'magnet', 'mallet', 'market', 'maggot', and 'mascot' ");
todo("words like 'fever' which are not e_e");
todo("contrast ear/ih.r  and ear/air  and ear/er (fear/bear/heard) - all are rare - esp: tear/tear");
todo("contrast ea/ee, ea/ih, ea/ay  (beach, beard, steak)");

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

class Phonics extends scriptInfrastructure{

  // the caller looks for this method...
    function load(){
        foreach($this->phonics as $key=>$value){
            $this->buildLessons($key,$value);
        }
    }




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
                "words2"=> "eclair,affair,repair,impair,despair,fairy,dairy,unfair"
                 ),
        "aw"         =>
            array(
                "group" => 'A Vowel Endings',
                "words" => "claw,craw,draw,flaw,gnaw,haw,jaw,law,maw,raw,saw,yaw,
                            thaw,draw,gnaw",
                "words2"=> "fawn,pawn,hawk,lawn,crawl,swan,shawl,brawl,brawn,prawn,yawn,Shawn"
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
                "words2"=> 'beech,beef,beet,bleed,breeze,beetle,
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
                "words" =>'dwarf,
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



  function buildLessons($desc,$words){
        trace(__CLASS__,__METHOD__,__FILE__,"desc = '$desc'");

        if(empty($words['words']))  return; // not yet ready

        $lesson = $this->newLesson(__class__, "<sound>$desc</sound>");
        $lesson->group = $words['group'];
        $lesson->showTiles = true;

        $wordList = $words['words'];

        // test the word list
        $festival = festival::singleton();
            foreach(explode(',',$wordList) as $word){
                assertTRUE($festival->wordIsValid(ltrim($word)),"Invalid '$word' in $desc in $wordList");
            }


        $text= "";
        $sideNote1 = "";
        $sideNote2 = "";

        //$writeText = "Practice writing these words.  Turn the screen away, and pronounce words over-accentuating the first
        //                and last sounds.<br><br>
        //                Watch out, the letter '$letter' does not always make an <sound>$sound</sound> sound.
        //                $avoid<br><br>
        //                Warn your student that this is a blending and segmenting exercise, not a rule.";


        //$page = $this->addPage('instructionPage','',    '',   "Intro",   '',     $text, $sideNote);

                                          // layout    style     tabName          dataParm     data
        if(isset($words['stretch']))
            $page = $this->addPage('wordList',    "1col",  'full',   "Stretch",         "normal",   $words['stretch']);

        $page = $this->addPage('wordList',    "1col",  'full',   "Words",         "normal",   $wordList ,   $sideNote1);
        $page = $this->addPage('wordList',    "3col",  'none',   "Scramble",      "scramble",   $wordList);
        if(!empty($words['spinner']))
            $page = $this->addPage('wordSpinner', "1col",  'full',   "Word Spinner",   "normal",  $words['spinner']);

        $page = $this->addPage('wordListTimed',"1col", 'full',   "Test",          "scramble", $wordList);

    }

}
