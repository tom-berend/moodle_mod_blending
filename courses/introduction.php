<?php namespace Blending;

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


        $this->clusterWords["Bat + But"] =
        array(
            "group" => 'Introduction',
            "contrast" => "ah,uh",
            "pronounce" => "uh",
            "pronounceSideText" => "## Introduction

            This is a sample lesson from *BLENDING*.  The real lessons will not have as many \
            annoying notes on the side.

            ***

            *BLENDING* is laser-focused on the five short vowels and the simplest CVC spelling.

            This sample lesson starts the sound %% sound('uh') %% with spelling %% spelling('u') %% as in 'but'.  \
            You should always refer to this vowel by its sound %% sound('uh') %%.

            Our notation is non-standard, but easier for tutors and parents than the IPA %% sound('ɛ') %% as in 'bet'. \
            The follow-up course *PHONICS* presents another dozen vowel sounds and about 150 spellings.


            ***



            We are starting the vowel %% sound('uh') %% as in But.  \
            In this course, always refer to letters by their common sound.

            Practice pronouncing %% sound('uh') %%. Make shapes with your mouth, exaggerate, play with saying it.

            Find other words that sound like 'bat'.

            Work through each of the tabs, and don't hesitate to backtrack. The last tab will be a test. ",


            "stretch" => 'bat/but,cat/cut,hat/hut,mat/mutt,pat/putt,rat/rut',
            "words" => array(
                $this->words["bat"],
                $this->words["but"]
            ),

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
            "note1" => "'Well' is an %% sound('eh') %% word that students have not \
                yet practiced.  Let them try, then help them if necessary.

                Check for comprehension. For example, ask your student to act out \
                how a frog catches a bug.",
        );


    }


}
