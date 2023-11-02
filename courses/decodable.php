<?php

namespace Blending;

class Decodable
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

    public function loadClusterWords()
    {

        /////////////////////////////////////////////

        //////////////////////////////////
        /// cat in the hat
        //////////////////////////////////


        $this->clusterWords["Damsel in a Dress"] =
            array(
                "group" => 'Decodable Stories',
                "pagetype" => 'decodable',


                "instructions" => "
            Theses DECODABLE stories will help bridge the chasm from decoding simple words to reading
            authentic texts.  They are intended for students who have completed the BLENDING and PHONICS exercises.<br><br>

            These stories have meaning.  The goal of reading is to extract meaning from text,
            so you should question your student about their understanding.<br><br>

            Here's a <a href='http://communityreading.org/wp/60-second-screening/'>simple test</a> of blending skills. If your
            student has ANY trouble with the first two pages of the test, then work through BLENDING before trying these stories.<br><br>

            (If you are not familiar with our training materials, simply press 'Completed' to get to the next set of pages.)",






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
                "pagetype" => 'decodable',
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

                "words3" => 'Af/ter a few short hour>s of look>ing for the knight, they saw a horse who
            came ride>ing up to them, car/ry>ing shine>y arm/or on his back (horse>s are much
            fast>er with/out knights ride>ing on them). \

            "That is the horse and that is the arm/or of the horr/ible knight," said the dra/gon. \

            "Oh no," said the knight, "It look>s like he is gone. A knight does not us/u/al>ly
            leave his horse or take off his arm/or. But what will be/come of his king/dom? The
            towns peo/ple will need a new knight to fight for them."',


                "words4" => 'You could take o/ver for him!" said the dra/gon who was now so hap/py that
            fire shot out of his nose a/gain. \

            So the knight put on the arm/or, and it fit ex/treme>ly well. He hop>ed on the
            horse and rode it per/fect>ly. The dra/gon was very im/press>ed. With the pet
            dra/gon now safe>ly home, the brave knight went in/side to tell the prin/cess
            the good news.',

                "words5" => '"That\'s fan/tas/tic!" said the prin/cess, "Now you have time to fix
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

                "title1" => "Ms. Smith’s Class Blog",
                "words1" => "Ms. Smith had a class blog for the moms and dads of her
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


                "title2" => "Ms. Smith’s Class Blog (Part 2)",
                "words2" => " At the end of the day, Ms. Smith pull>ed out her laptop and
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
                "pagetype" => 'decodable',
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
                "pagetype" => 'decodable',
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
    }

}

