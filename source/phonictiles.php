<?php  namespace Blending;


/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////

class PhonicTiles {

    var	$allVowels =array(array('ah' =>'fat/fat',
                                'aw' =>'law/law,dog,fraud,lawn,fought,caught',
                                'ay' =>'pay/pay',
                                'air'=>'hair/hair',
                                'ar' =>'car/car'),
                          array('ih' =>'hit/hit',
                                'igh'=>'high/high'),
                          array('oh' =>'toe/toe',
                                'oo' =>'book/book',
                                'oy' =>'boy/boy',
                                'ow' =>'cow/cow'),
                          array('uh' =>'but/but',
                                'ue' =>'cue/cue'),
                          array('eh' =>'bed/bed',
                                'ee' =>'see/see',
                                'er' =>'her/her')
                         );


    function render(){     // part of a lesson, not a tab

        $HTML = '';

        $HTML .= "<table width='100%'><tr>";
            $needSpacer = false;
            foreach($this->allVowels as $someVowels){

                if($needSpacer)    // need spacers after the first
                    $HTML .= '<td>&nbsp;&nbsp;</td>';  // spacer
                $needSpacer = true;

                foreach($someVowels as $key=>$vowelInfo){
                    $activeKey = $this->addAction($key,$vowelInfo);
                    $HTML .= "<td>".$this->sound($activeKey)."</td>";
                    //$HTML .= "<td class='vowel-btn-blue'>/$activeKey/</td>";
                }
            }
        $HTML .= "</tr></table>";
        return($HTML);
    }



    function addAction($key,$vowelInfo){

        // first, split $vowelInfo into components
        // info[0] -> keyword               // law
        // info[1] -> list of words         // law,dog,fraud,lawn,fought,caught'
        $info = explode('/',$vowelInfo);


        $func = 'phonicsHelp("vowelSpellings","'.$info[0].'")';
        $HTML = "<a href='javascript:{$func};' style='text-decoration:none;'>$key</a>";
        return($HTML);
    }

    function test(){
        return('phonicsTiles is alive');
    }
}
