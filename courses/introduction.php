<?php

namespace Blending;

class Introduction extends LessonAbstract
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
        $views = new ViewComponents();   // eg: ->sound('th')

        // set the minimum version
        $this->minimumVersion = '1.0.0';



        // >     *BLENDING* guides a tutor-led intensive intervention for older students and adults reading at \
        // grade-1 or -2 level.  It develops *Phonological Awareness* using *CVC* words.

        // Our notation is non-standard, but easier for tutors and parents.  The IPA for  %% sound('uh') %% is %% sound('ə')%% \
        // which is a bit scary.


        $this->clusterWords["Bat + But"] =
            array(
                "group" => 'Introduction',
                "contrast" => "ah,uh",
                "pronounceSideText" => "## Introduction

            This is a sample lesson from *BLENDING*. It reviews how to use the course.  The real lessons will not have as many \
            annoying notes on the side.

            Work through each of the tabs.  The final tab contains a link to the next lesson in this introduction.*.

            ***

            Struggling students often lack phonological awareness; they manage with consonants but have difficulty with sounds of vowels.

            At the point *this* lesson is presented, we have already introduced and practiced the \
            sound %% sound('ah') %% and %% sound('uh') %% separately, This lesson contrasts  the two sounds. \
            You should always refer to these vowels by their sounds and not their spellings.

            ***

            Have your student make shapes with their mouth, exaggerate, play with saying them. \
              It's important that they over-pronounce to build a clear auditory distinction.

            Work through each of the tabs, and don't hesitate to backtrack.",


                "stretch" => 'bat/but,cat/cut,hat/hut,mat/mutt,pat/putt,rat/rut',
                "stretchText" => "This is a 'contrast' page for  %% sound('ah') %% and %% sound('uh') %%.",


                "words" => array(
                    $this->words["bat"],
                    $this->words["but"]
                ),

                "sidenote" => "These are mostly the same words as in the contrast page.  Have your student read the words aloud, clearly pronouncing each one.

               Focus on *accuracy* first, then *speed*.  Do not accept any drifting such as 'hat' drifting towards 'het', 'hut' or 'hit'.

              Your student will notice that each word has a vowel.  In English each syllable has a vowel, but that won't be obvious until we see some multi-syllable words.
               Don't spend too much time on this tab \
               because the colors are distracting; the next tab has no colors.

               Older students usually know the sounds of the consonants.  If your student struggles with consonants \
               then consider making some flashcards.  Or simply keep working here.  You are the tutor, bend this course to your student's needs.

               Use the **Refresh** button to keep your student from memorizing as they practice.",

                "scrambleSideNote" => "Have your student read down the three columns. Focus on *accuracy*, then *speed*.
                  Use the **Refresh** button to keep your students from memorizing.

                  This page overloads the student's memory, forcing them to abandon memorized words and develop blending skills.

                   This is not 'reading' of course.  But it is necessary to drill until your student has \
                   developed phonological skills.

                   Towards the end of a tutoring session, consider asking your student to WRITE these words \
                   as practice for segmenting and handwriting. \
                   You can call them aloud from this screen. Don't be too strict, handwriting is a difficult task \
                   in the beginning. ",


                // // https://tfcs.baruch.cuny.edu/content-and-function-words/
                // "sentences" => [
                //     "Kids hit drums.",
                //     "The kids hit drums.",
                //     "The kids hit the drums.",
                //     "The kids will hit the drums.",
                //     "The kids will be hit>ing the drums.",
                //     "The kids have been hit>ing the drums.",
                //     "The kids will have been hit>ing the drums.",
                // ],
                // "sentencetext" => "Have your student read each line and explain the *DIFFERENCE* in meanings.  It's ok if \
                //                     they need to backtrack and correct himself.  Point how how the non-content words modify verbs.

                //                     Point out that 'Kids', 'hit' 'drums' are the only content words.

                //                     Non-content words must be read accurately to understanding a text.  They are often ignored by stronger \
                //                     readers who process them effortlessly and accurately.  Your student must pay careful attention.",



                "spinner" => array(
                    'b,c,d,f,g,h,j,k,l,m,n,p,r,s,t,v,w,z', // prefix, vowels, suffix for spinner
                    'a,u',
                    'b,d,ff,g,k,ll,m,n,p,s,t,y,zz',
                    '',
                ), // exception list
                "spinnertext" => "Key out a word like ‘bat’ and you will see how this tool works.

                                  The Wordspinner creates both real and nonsense words.  Use nonsense words freely even \
                                  though your student didn't see them in the previous tabs.

                                  Practice blending by having \
                                  your student read the word.  Practice segmenting by calling a word and having your \
                                  student key it out.  **Both** skills must be mastered.

                                  Stick to the ‘short vowel’ pronunciations.  A very small number of CVC words in English \
                                  are irregular, for example ‘son’ is usually pronounced like ‘sun’, here it should be \
                                  pronounced like 'son/ic' or 'son/net'.",


                "title1" => 'Frog Facts',
                "image1" => 'frogs.png',
                "credit1" => ['Frog Facts', 'https://www.freereading.net/w/images/f/fb/Decodable_nonfiction_1.pdf', 'FreeReading.Net', 'https://www.freereading.net', 'CC BY-NC-SA', '3.0', 'Adapted from'],

                "words1" => "A frog can swim, and it can be on land. It has
                skin that is slick. \
                A frog will sit on a log. If a frog wish>es to grab a
                bug, it sits still, and when a bug lands next to it,
                the frog snaps the bug up. \
                Then it can jump off for a swim. Frogs jump
                well, and they swim well. ",

                "note1" => "Try out the **Decode Level** buttons to see what they do. The markup becomes evident as you move through the course.

                    The rule for decodable texts is **NO GUESSING**.  That's not always possible. \
                    This text has the word 'well' with an %% sound('eh') %% that your student will not have \
                    practiced, plus the affixed word 'wish+es'.  Let them try, then help them if necessary.

                    Every story in this course has some meaning, however slight.  Check for comprehension. For example, ask your student to act out \
                    how a frog catches a bug, or describe the catch from the fly's point of view.

                    Until your student masters the third vowel, there are only a few decodable stories.  By the \
                    fifth vowel, almost every lesson has a decodable story.",

                "testNote" => "Your student should be able to read this list **accurately** in 10 seconds \
                    or less.  That accuracy and speed indicates they are processing with automaticity, 'without thinking'.

                    Use the timer to challenge them.  When they succeed, mark the lesson as mastered, and move to the next \
                    lesson. Not ready yet?  Use the 'refresh' to give your student lots of chances, or flip back \
                    to the Spinner. Done for the day? \
                    Mark the lesson as 'In Progress' and return tomorrow.

                    Don't get stuck or frustrated.  It's important that your students master every skill, but \
                    you will see these words again. Use the Navigation button to try something harder, then return \
                    tomorrow.

                    Clicking *Mastered* moves you to the next lesson.  If you navigate to another lesson and \
                    'master' it, your bookmark will move.  But you can always return with Navigation.

                    Your student might peer at the faint words and try to speed through the first ones by preparing.  Ha ha.  Don't notice.

                    Now click *Mastered* to read about the rational behind this course.  Or *Exit* to add your student and start tutoring.",

            );
    }
}


// ![Copyright: Random House](pix/catinhat2.jpg)
// ![Copyright: Random House](pix/catinhat.jpeg)

// I love Dr Seuss's 'The Cat in The Hat' as a first book, even for teaching adults.   It is real reading, and also fun.

// Click on the second image  to see Page 1 of 'The Cat in The Hat'. \
// This should give you an idea of how complex the text should be for your student's first book.

// But even this easy page has several patterns that your student does not yet know. \
// The next dozen lessons will skim over some of those patterns and ideas quickly, just enough to help your student make the jump.
