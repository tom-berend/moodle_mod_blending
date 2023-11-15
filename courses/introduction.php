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

        $this->clusterWords["Introduction"] =
        array(
            "group" => 'test introduction',
            "pagetype" => 'instruction',
            "instructionpage" => ["hello Introduction"],
        );


    }


}
