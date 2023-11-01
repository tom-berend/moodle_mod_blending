 <?php


class Decodables extends scriptInfrastructure
{

    public $clusterWords = array();

    // the caller looks for this method...
    public function load()
    {



        /////////////  the lessons   //////////////

        $this->loadClusterWords();

        // consonant clusters
        foreach ($this->clusterWords as $key => $value) {
            $this->clusters($key, $value);
        }

    }

    public function clusters($desc, $words)
    {

        $lesson = $this->newLesson(__class__, $desc);
        $lesson->group = $words['group'];

        if (isset($words["showTiles"])) {
            $lesson->showTiles = true;
        }


        $text = "";
        $sideNote1 = "";
        $sideNote2 = "";


        $style = '';
        if (isset($words['style'])) {
            $style = $words['style'];
        }

        if (isset($words['sidenote'])) {
            $sideNote1 = $words['sidenote'];
        }

        switch ($style) {

            case 'lecture': //   (two instruction pages and a wordlistComplete)

                $pageType = 'instructionPage';

                // page 1
                if(!isset($words['text2']))
                    $pageType = 'instructionPage2';
                $page = $this->addPage($pageType, '', '', "Page 1", $words['text']);


                // page 2
                if (isset($words['text2'])) {
                    if(!isset($words['text3']))
                        $pageType = 'instructionPage2';
                    $page = $this->addPage($pageType, '', '', "Page 2", $words['text2']);
                }

                // page 3
                if (isset($words['text3'])) {
                    if(!isset($words['text4']))
                        $pageType = 'instructionPage2';
                    $page = $this->addPage($pageType, '', '', "Page 3", $words['text3']);
                }
                break;



            case 'decodable':
                //addPage($displayType, $layout, $style, $tabname, $dataparm, $data=array(), $note=''){

                if (!isset($words['credit']))  $words['credit']='';
                $colour = 'colour';


                $format = serialize(['colour',[],$words['credit']]);  // default is colour, not B/W.  no phonemes are highlighted


                if (!isset($words['image1']))  $words['image1']='';
                if (!isset($words['image2']))  $words['image2']='';
                if (!isset($words['image3']))  $words['image3']='';
                if (!isset($words['image4']))  $words['image4']='';
                if (!isset($words['image5']))  $words['image5']='';

                if(isset($words['intro'])){   // an intro is always the FIRST page, always has following pages
                    $page = $this->addPage('instructionPage', '', '', "Introduction", $words['intro']);
                }else{
                    $last = isset($words['words2'])?'':'last';  // determines whether a 'completed' button is added
                    $page = $this->addPage('decodableReader1', $words['image1'], $last, "Page 1", $words['words1'],$format);
                }
                if (isset($words['words2'])) {
                    $last = isset($words['words3'])?'':'last';
                    $page = $this->addPage('decodableReader1', $words['image2'], $last, "Page 2", $words['words2'],$format);
                }
                if (isset($words['words3'])) {
                    $last = isset($words['words4'])?'':'last';
                    $page = $this->addPage('decodableReader1', $words['image3'], $last, "Page 3", $words['words3'],$format);
                }
                if (isset($words['words4'])) {
                    $last = isset($words['words5'])?'':'last';
                    $page = $this->addPage('decodableReader1', $words['image4'], $last, "Page 4", $words['words4'],$format);
                }
                if (isset($words['words5'])) {
                    $last = 'last'; // of course it is
                    $page = $this->addPage('decodableReader1', $words['image5'], $last, "Page 5", $words['words5'],$format);
                }

                break;


            default:
                assert(false,'did not expect to get here');


        }
    }

    public function loadClusterWords()
    {

        /////////////////////////////////////////////

        //////////////////////////////////
        /// cat in the hat
        //////////////////////////////////

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

            "text2" =>"<br>
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
            "style" => 'decodable',
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
            "style" => 'decodable',
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
            "style" => 'decodable',
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
            "style" => 'decodable',
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
            "style" => 'decodable',
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







    $this->clusterWords["Damsel in a Dress"] =
    array(
        "group" => 'Decodable Stories',
        "style" => 'decodable',
        "credit" => 'DANIEL ERRICO <a href="https://www.freechildrenstories.com/">FREECHILDRENSTORIES.COM</a>',
        "image1" => "dragon.png",
        "words1" => '{ Dam/sel in a Dress }

    There once live>ed a brave knight who was al/ways save>ing prin/cess>es. One day he rode
    by a tow/er with a prin/cess in/side and a horr/ible dra/gon near/by. The knight charge>ed
    at the dra/gon and drove him off. Vic/tor>i/ous, he burst through the tow/er door and found
    the prin/cess.',

    "words2" => '"I am here to save you!" said the brave knight. \

        "Save me from what?" ask>ed the prin/cess, look>ing ang/ri/ly at her broke>en door. \
        "Why, the horr/ible dra/gon that I chase>ed a/way, of course," said the brave knight. \
        "That dra/gon was my pet and there\'s no/thing horr/ible about him!" she yell>ed.
        "You\'d bet/ter get him back or you\'ll nev/er be a knight a/gain," she said
        (and she meant it).',

    "image3" => "knight.png",
    "words3" => 'The brave knight left right a/way to find the dra/gon that he was
        no long>er al/low>ed to call horr/ible. The dra/gon was al/ready miles a/way
        be/cause dra/gons fly quick>ly af/ter a knight charge>es at them. It took
        the knight days to find the dra/gon who was rest>ing in a cave. ',

    "words4" => '
        The brave knight crept up on the beast as he slept. \

        The dra/gon was have>ing such a won/der/ful dream that fire came shoot>ing out
        of his nose. (You see, dra/gon>s breathe fire when they are scare>ed and ang/ry,
        but al/so when they are ver/y hap/py.) ',

    "words5" => 'The fire made the brave knight\'s arm/or
        ex/treme/ly hot, so he start>ed re/move>ing it un/til he was wear>ing on/ly
        the ragg/ed/y clothes under/neath. He took his arm/or and horse out/side be/fore
        wake>ing the dra/gon.'
    );


    $this->clusterWords["Dam/sel in a Dress II"] =
    array(
        "group" => 'Decodable Stories',
        "style" => 'decodable',
        "credit" => 'DANIEL ERRICO <a href="https://www.freechildrenstories.com/">FREECHILDRENSTORIES.COM</a>',

        "words1" => '"Ex/cuse me," said the knight to the dra/gon. \
            "Mmmrph," said the dra/gon. "I\'ve been chase>ed from my home by a horr/ible knight.
             Leave me a/lone!" \

             "That\'s aw/ful," said the brave knight, re/al/ize>ing that the dra/gon did not
             re/cog/nize him, "but may/be it was just a mis/take. What if the knight is not
             so horr/ible af/ter all?" \

             "He is the most horr/ible knight e/ver!" an/swer>ed the dra/gon. "I hope I nev/er
             see him ag/ain."',

        "words2" => '"If you take me to your home, I will talk to him for you and sort all of
            this out," said the knight. \

            Be/fore they left, the knight snuck out/side, set his arm/or on the horse, and told
            it to ride back to the tow/er. Ride>ing on top of the dra/gon, it did not take long for
            the knight to find the prin/cess>\'s tow/er. \

            "I don\'t see him," said the knight, "where is the horr/ible knight?" \

            "I don\'t know," said the dra/gon.',

        "words3" =>'Af/ter a few short hour>s of look>ing for the knight, they saw a horse who
            came ride>ing up to them, car/ry>ing shine>y arm/or on his back (horse>s are much
            fast>er with/out knights ride>ing on them). \

            "That is the horse and that is the arm/or of the horr/ible knight," said the dra/gon. \

            "Oh no," said the knight, "It look>s like he is gone. A knight does not us/u/al>ly
            leave his horse or take off his arm/or. But what will be/come of his king/dom? The
            towns peo/ple will need a new knight to fight for them."',


        "words4" =>'You could take o/ver for him!" said the dra/gon who was now so hap/py that
            fire shot out of his nose a/gain. \

            So the knight put on the arm/or, and it fit ex/treme>ly well. He hop>ed on the
            horse and rode it per/fect>ly. The dra/gon was very im/press>ed. With the pet
            dra/gon now safe>ly home, the brave knight went in/side to tell the prin/cess
            the good news.',

        "words5" =>'"That\'s fan/tas/tic!" said the prin/cess, "Now you have time to fix
            that door that you broke." \

            As the brave knight fix>ed the tow/er door, the dra/gon watch>ed him and laid
            down for a nap. The dra/gon felt much bet/ter know>ing that de/spite the same
            arm/or, this new knight was not so horr/ible.',

    );

    $this->clusterWords["Ms. Smith’s Class Blog"] =
    array(
        "group" => 'Decodable Stories',
        "pagetype" => 'decodable',
        "credit" => 'Open Source Phonics',

        "title1" =>"Ms. Smith’s Class Blog",
        "words1" =>"Ms. Smith had a class blog for the moms and dads of her
            fifth grade class. This blog was just for them, not for the
            kids. \
            Ms. Smith’s class had been good for most of the year with
            a slump here and there. Today the kids said, “For our end
            of school bash, we want something big! A class raft trip
            would end our fifth grade year with a bang!” \
            Ms. Smith said, “I think a raft trip would be a fan/tas/tic end
            to the year, but I am think>ing that it would cost too much.”
            The kids look>ed glum. But then, Mike said, “What if we can
            make the cash to fund the trip?” \
            Jill said, “Yes, what if we ran a snack shop at school? If
            we sold cold drinks and snacks, I bet we would make a lot
            of cash. We could use the pro/fits to fund the trip.” Now the
            whole class was chat>ing. \
            “Ok, give me some time to think about all of this,” Ms.
            Smith told the class. “It is time for our math les/son.” ",


        "title2" =>"Ms. Smith’s Class Blog (Part 2)",
        "words2" =>" At the end of the day, Ms. Smith pull>ed out her laptop and
            went onto the class blog. She post>ed about the day and
            about how the kids hope>ed to fund a raft trip at the end of
            the year with a school snack shop. She said that she was
            impress>ed by the kids’ bold think>ing. \
            The moms and dads felt grateful that Ms. Smith let them
            know what was hap/pen>ing in class. She was the kind of
            teach>er who con/sult>ed them. Thus, Ms. Smith had their
            trust. \
            One mom, Beth, post>ed, “A raft trip is fun. And to run a
            school snack shop would be just as much fun! But if I were
            a mom of a child who was not in our grade, I would be
            up/set if my child would skip lunch and toss her sand/wich
            out just so she could munch on all the fifth grade snacks!”
            Ms. Smith was glad Beth had pos>ed this as she could see
            that this could be a big prob/lem. \
            One dad, Jeff, post>ed, “What if the snack shop hap/pens
            when lunch is over?” \
            A good plan was made. The next day, Ms. Smith would tell
            the class that she was be/hind their snack shop and raft
            trip plan.",

    );




        $this->clusterWords["Jerry's Box"] =
    array(
        "group" => 'Decodable Stories',
        "style" => 'decodable',
        "credit" => 'DANIEL ERRICO <a href="https://www.freechildrenstories.com/">FREECHILDRENSTORIES.COM</a>',

        "words1" => '{ Jerr/y\'s Box }
            Jerr/y woke up on Mon/day. He grab>ed a box he was keep>ing un/der his bed. \
            When he came down/stairs, his par/ent>s ask>ed him what was in/side.
            "Some/thing real/ly fun," he said, but no/thing more. \

            Jerr/y walk>ed to his bus stop with the box in his hand>s.
            Ever/y/bod/y at the bus stop ask>ed him what was in it.
            "It\'s ver/y im/port/ant," he said, but no/thing more.',

        "words2" => 'When he got to school, the teach>er ask>ed him what was in his box. \

            "It is a se/cret," he said, but no/thing more. At lunch his class/mate>s all crowd>ed
            ar/ound and ask>ed him to o/pen it. "I can\'t - it\'s a gift," he said, but no/thing more. \

            The box sat with him all day, and no one in class could think a/bout any/thing else.
            His teach>er did not let Jerr/y know, but she was cur/i/ous too. She de/cide>ed to send him
            to the prin/ci/pal for dis/turb>ing the class, hope>ing to get an an/swer.',

        "words3" => 'The prin/ci/pal
            ask>ed Jerr/y what he was keep>ing in the box and if it was dan/ger>ous. "It is not for
            you," he said, but no/thing more. \

            Jerr/y went back to class and sat down. The en/tire class was watch>ing as he hand>ed
            the teach>er a note from the prin/ci/pal. "Well, if it is a gift, Jerr/y, I sug/gest you
            de/liv/er it now." Jerr/y turn>ed ar/ound and face>ed the class. He walk>ed down the aisle
            and stop>ed at the desk of Os/car. ',

        "words4" => 'Os/car\'s eyes lit up. No/bod/y paid at/tent/ion to Os/car. No/body talk>ed to Os/car.
            And no/bod/y had ev/er stop>ed at Os/car\'s desk, un/til now. \

            Jerr/y hand>ed him the
            box, with a new note tuck>ed in/side. Os/car read the note. "Dear Os/car, This is
            my box. En/joy. P.S. It work>s bet/ter if you do not o/pen it!" \

            On Tues/day, no/body had more peo/ple at his desk than Os/car.',

    );

    $this->clusterWords["The appointment in Samarra"] =
    array(
        "group" => 'Decodable Stories',
        "style" => 'decodable',
        "credit" => 'William Somerset Maugham',

        "words1" => '{ The Ap/point>ment in Sam/ar/ra }

            \
            The speak>er is Death \
            \
            There was a mer/chant in Bag/dad who sent his ser/vant to mar/ket to buy pro/vis/ion>s
            and in a lit/tle while the ser/vant came back, white and trem/ble>ing, and said,',

        "image2" => "death.jpg",
        "words2" => ' Mas/ter, just now when I was in the mar/ket/place I was jos/tle>ed by a wo/man in the
            crowd and when I turn>ed I saw it was Death that jos/tle>ed me. \
            She look>ed at me and made a threat/en>ing ges/ture,  now, lend me your
            horse, and I will ride a/way from this cit/y and a/void my fate.  \

            I will go to Sam/ar/ra and there Death will not find me.',

        "words3" => 'The merch/ant lent him his horse, and the ser/vant mount>ed it, and he
            dug his spurs in its flank>s and as fast as the horse could gall/op he went. \

            Then the merch/ant went down to the mar/ket/place and he saw me stand>ing in the
            crowd and he came to me and said, Why did you make a threat>ing ges/ture to my
            ser/vant when you saw him this morn/ing? ',

        "words4" => 'That was not a threat/en>ing ges/ture, I said, it was only a start of sur/prise. \

            I was as/ton/ish>ed to see him in Bag/dad, for I had an ap/point/ment with him
            to/night in Sam/ar/ra.'

    );


    $this->clusterWords["Hydrogen"] =
    array(
        "group" => 'Decodable Stories',
        "style" => 'decodable',
        "credit" => 'HYDROGEN - The Essential Element, JOHN RIGDEN',


        "image1" => "hydrogen.jpg",
        "words1" => '{ Hy/dro/gen }
            The sto/ry of hy/dro/gen be/gin>s be/fore there was any one to no/tice.  Long
            before the Earth and its plan/et>ary sib/ling>s ex/ist>ed, be/fore the Sun and the Mil/ky Way
            ex/ist>ed, and e/ven be/fore chem/i/cal el/em/ents like oxy/gen, sod/ium, i/ron, and gold ex/ist>ed,
            the hy/dro/gen a/tom was old, old news.',

            "words2" => 'Ac/cord>ing to cur/rent wis/dom, our un/i/verse be/gan about 15 bill/ion years a/go at a point
            with in/fin/ite den/sity and in/fin/ite tem/per>ate/ure.  That was the be/gin>ing of time, that was
            the or/i/gin of space.  Since then, the or/i/gin>al point has ex/pand>ed in all dir/ect/ion>s to the
            di/men/sion>s of the cur/rent un/i/verse.  As the un/i/verse ex/pand>ed, the cos/mic clock
            tick>ed and the tem/per>ate/ure cool>ed.',

            "words3" => 'By the time the un/i/verse was four min/ute>s old, the ba/sic in/gred/i/ent>s re/quire>ed for all that
            was to fol/low were pre/sent and their ba/sic modes of in/ter/act/ion were es/tab/lish>ed. The stage
            was set for ever/y/thing that fol/low>ed.',

            "words4" => 'Hy/dro/gen is the sim/ple>est of all a/toms.  In its dom/i/nant form, hy/dro/gen con/sist>s of one
            el/ec/tron and one pro/ton, in its rare form, call>ed deu/ter/ium, there are three part/i/cle>s:
        an electron, proton, and a neu/tron.  By con/trast, or/din/ary wa/ter, a sim/ple mol/e/cule, con/sist>s
        of twenty-eight part/i/cle>s: ten el/ec/tron>s, ten pro/ton>s, and eight neu/tron>s.  The wa/ter mol/e/cule
        is ver/y com/pli/cate>ed when com/pare>ed to the hy/dro/gen or deu/ter/ium a/tom>s. \ ',


    );





    if($GLOBALS['debugON']){


        $this->clusterWords["test"] =
        array(
            "group" => 'Decodable Stories',
            "style" => 'decodable',

        "intro" => '',

        );

    }
}



    // this function generates every possible combination of the first, second, and third letters
    public function gen3letters($aFirst, $aSecond, $aThird)
    {
        assertTRUE(is_array($aFirst));
        assertTRUE(is_array($aSecond));
        assertTRUE(is_array($aThird));

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


