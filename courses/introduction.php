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


            $this->clusterWords["Bat + But"] =
            array(
                "group" => 'Introduction',
                "pagetype" => 'instruction',

                "About" => "## BLENDING - for Older Students with Severe Reading Deficits

                            ![](pix/toolsforstrugglingreaders.png)

                            ***BLENDING*** provides progressive phonological drills and decodable texts. It is a complete program for tutor-led *intensive* interventions for older students still reading at grade-1 or -2 level.

                            It requires no special skills and can be used by EAs, parents, or even student volunteers.  The software provides hints for the tutors, progress monitoring, record-keeping, and continual formative assessments.

                            Most middle-school classrooms have at least one student who cannot make sense of a grade-2 chapter book; they have stalled and will make no further progress without intensive intervention.  The research is clear: almost all students become strong, competent readers with proper instruction. This program specifically addresses the needs of older students.

                            An intensive intervention prescribes one-on-one tutoring for at least an hour per day, at least five days a week. This program requires about ten weeks of DAILY practice - about 20 minutes per day for drills, plus increasing reading and writing practice.  By the end, the student should be set to read a grade-3 chapterbook (such as Goosebumps) independently.

                            Unsure if BLENDING is appropriate?  Try it for 20 minutes with your student.",




                "Older Students" => "Older students are not ‘emerging readers’ paused as if caught in amber.  They have continued to develop along a bumpy off-road track.  They have reading superpowers like larger vocabularies,  tenacious memory, and world knowledge.

                                But they also have severe deficits that are not faced by emerging readers.

                                Some are unable to read the simplest texts, and barely know the common sounds of the alphabet consonants.

                                More commonly, they ‘read’ using memorized words and guessing. The most common 300 words account for about half the words on any page, and these students have memorized that and more. They guess from context, first-letters, word shapes, and picture clues. They have honed these skills to extraordinary levels.

                                This kind of reading is effortful and error-prone. Small errors mangle comprehension.  A guessing student will not be able to attend to the meaning of a text or have the endurance to complete it.

                                Many older students have taken years of phonics and can decode if prompted. But they don’t use phonics when they read.  Like two-fingered typists, they know how to lay out their hands for touch-typing but then fall back on the bad habits that work for them.

                                ![](pix/fathatsat.png)

                                Non-reading students also develop self-sabotaging behaviors like low self-esteem and learned helplessness.  They learn to cope and to hide their deficits.  They will not persevere unless they can see clear progress in every session.

                                This can all be repaired in a single semester with an intensive intervention.

                                A severe reading deficit is just a compounding failure to develop component skills. [Stanovich](https://communityreading.org/wp/matthew-effects-in-reading/) proposed to break this negative feedback loop by delivering an educational ‘surgical strike’ on one of the weak component skills, and then use it to start a positive bootstrap to other skills. He suggested the most promising target was the ability to blend and segment phonemes (‘phonological processing’).  That’s exactly what we do in this intervention.

                                This program is based on a decade of successful interventions at the [Community Reading Project](https://communityreading.org).",


                "Schedule" => "The [Torgesen Study](https://communityreading.org/wp/the-torgesen-study/) intensive intervention protocol specified two hours per day (two 50-minute sessions with a 20 minute break), five or six days a week.  That is the format we use at the Community Reading Project and it delivers amazing results.  A typical intervention requires about 10 weeks.

                                The first 2-3 weeks develop the first three ‘short’  vowels (‘bat’, ’bit’, ‘bot’) using structured drills.  We don’t want students reverting to memorized words, so we don’t ask them to read during this period.  We have them practice handwriting, and perhaps we just read to them. (Have them share the book, and run your finger on the text as you read, perhaps asking them to decode the words you know they can.)

                                ![click to expand](pix/screenshots.png)

                                Then a short detour, usually a week or so.  **BLENDING** does a quick survey of skills that will help with decodable texts, such as non-content words, common sound-spelling mappings, and affixes.

                                Back to drills for the next 2-3 weeks focusing on the next two vowels (‘but’, ‘bet’), but presented both as drills and with decodable texts.  On the side, we read authentic texts with the student.

                                ![](pix/catinhat.jpeg)

                                We love ‘The Cat in the Hat’ as a first book, *even for adults*.  Then on to grade-2 chapterbooks (we like the ‘Jack Stalwart’ and ‘Junie B Jones’ books).  We share the work, starting by doing most of the reading and handing off to the student as they progress. Authentic texts require guessing, but by now the student has the necessary foundations to bootstrap real reading skills.

                                Then another 2-3 weeks of drilling consonant clusters and digraphs, more spelling patterns including the silent-e vowels, more affix and spelling drills, and reading grade-3 chapterbooks like Goosebumps.

                                Finally, the BLENDING drills are put aside.  We read progressively harder books together (and eventually independently), checking in on comprehension every few pages.  We practice writing hamburger essays. Perhaps we read their classroom texts with them, to help them catch up.",


                "Methods" => "BLENDING provides a gently-progressive step-by-step path from highly-structured phonological drills to decodable reading texts.

                                We do this with *NO GUESSING*. Immersing a student in text and letting them swim works for most emerging readers, but older non-readers need as much structure as possible.

                                Drills intentionally drive *UNLEARNING*, forcing the student to abandon memory-reading, stop guessing, look inside the words, and build their phonological skills.  Unlearning is extremely difficult but necessary for older students.

                                We *OVERLEARN to AUTOMATICITY*, moving the new learning to long-term memory.  Unlearning the bad skills is not enough, we must rewire the brain with good skills to build a solid reading foundation.

                                The student sees *VISIBLE PROGRESS*. The lessons are small and progressive, and each embeds formative assessment. The student can see progress at each session, building confidence and rewiring the self-sabotaging behaviors.",


                "Don’t Hurry" => "Work through the lessons carefully and methodically, slower is faster.  The drills should only be run for 20-30 minutes a day, but try to get to them every day, at least five days a week.

                                The program supports multiple tutors, so parents and volunteers can be harnessed.  It runs nicely on phones.

                                Many students will struggle with ‘b-d-p’ errors.  **BLENDING** offers awareness drills for them, but do not spend time trying to master them (they are clearly marked as ‘do not waste time here’).  The problem will only go away as the student reorganizes his brain.

                                There are about 100 lessons, and your student will only master one or two each day. Often they will struggle with even that.  There is a ‘hull speed’ - a maximum physical speed at which students can automate skills.  So 10 weeks is about right.

                                You will go much faster if you can practice every day.  You will notice a significant loss over each weekend, often we spent Mondays trying to restore skills that seemed confident on the previous Friday.  Enlist parents and volunteers, run a remote lesson with Zoom, anything to keep the intensity up.",

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
