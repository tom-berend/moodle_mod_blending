<?php

// colour palette from  https://coolors.co/067bc2-e8eef2-c2847a-ffc857-439a86

global $colours;
$colours = ['dark' => "#067bc2", 'light' => "#e8eef2", 'a' => "#c2847a", 'b' => "#ffc857", 'c' => "#439a86"];






class Views extends ViewComponents
{

    function navbar(array $options, $title = 'BLENDING'): string
    {
        printNice($options,"Navbar(title=$title)");

        $buttons = '';
        if (in_array('addStudent', $options)) {
            $buttons .= "<li class='nav-item active'>";
            $buttons .= MForms::buttonForm('Add Student', 'primary', 'showAddStudentForm');
            $buttons .= "</li>";
        }

        if (in_array('exit', $options)) {
            $buttons .= "<li class='nav-item active'>";
            $buttons .= MForms::buttonForm('Exit', 'primary', 'showAddStudentList');
            $buttons .= "</li>";
        }

        if (in_array('next', $options)) {
            $buttons .= "<li class='nav-item active'>";
            $buttons .= MForms::buttonForm('Next', 'primary', 'showAddStudentList');
            $buttons .= "</li>";
        }

        if (in_array('navigation', $options)) {
            $buttons .= "<li class='nav-item active'>";
            $buttons .= MForms::buttonForm('Navigation', 'primary', 'showAddStudentList');
            $buttons .= "</li>";
        }


        $aboutButton =
            "<form  action= 'source/blending.pdf' target='_blank'>
               <button type='submit' aria-label='About' class='btn-sm btn-danger rounded' style='margin:3px;'>About</button>
        </form>";


        $HTML = "<nav class='navbar navbar-expand-md navbar-light' style='background-color:#ffffd3;border:solid 2px blue;border-radius:10px;'>";
        $HTML .= "  <a class='navbar-brand' href='#'>";
        $HTML .= "    <img src='pix/blending.png' height='36' alt=''>";
        $HTML .= "  </a>";

        $HTML .= "  <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#collapsingNavbar'>";
        $HTML .= "      <span class='navbar-toggler-icon'></span>";
        $HTML .= "  </button>";
        $HTML .= "  <div class='navbar-collapse collapse' id='collapsingNavbar'>";
        $HTML .= "      <ul class='navbar-nav'>";
        $HTML .= $buttons;
        $HTML .= "       </ul>";

        // title is centered
        $HTML .= "       <ul class='nav navbar-nav mx-auto'>
                            <li class='nav-item'><a class='nav-link' href='#'><b style='font-size:130%'>$title</b></a></li>
                         </ul>";

        // about button is right
        $HTML .= "       <ul class='navbar-nav ml-auto'>";
        $HTML .= "           <li class='nav-item'>";
        $HTML .= $aboutButton;
        // $HTML .= "               <button class='nav-link' href='source/blending.pdf' data-target='#myModal' data-toggle='modal'>About</button>";
        $HTML .= "           </li>";
        $HTML .= "       </ul>";
        $HTML .= "   </div>";
        $HTML .= "</nav>";

        $HTML .= '<br>';

        return $HTML;










        $HTML .= "<nav class='navbar navbar-light' style='background-color:#ffffb3;border:solid 2px blue;border-radius:10px;'>";
        $HTML .= "  <a class='navbar-brand' href='#'>";
        $HTML .= "    <img src='pix/blending.png' height='36' alt=''>";
        $HTML .= "  </a>";

        $HTML .= "  <form class='form-inline'>";
        $HTML .= MForms::navButton('test', 'primary', 'test');
        $HTML .= "$buttons";

        $HTML .= "  </form>";
        $HTML .= "</nav>";

        return $HTML;
    }


    function showStudentHistory(int $studentID): string
    {
        $HTML = '';
        $HTML .= $this->navbar(['addStudent']);

        $students = new LogTable();
        $all = $students->getStudentAll($studentID);
        printNice($all);

        $headers = ['Date Lesson', 'Tutor', 'Action', 'Lesson', 'Result', 'Score', 'Remark'];
        $fields = ['timecreated', 'tutoremail', 'action', 'lesson', 'result', 'score', 'remark'];

        $HTML .= "<table class='table'><thead><tr>";
        foreach ($headers as $t) {
            $HTML .= "<th>$t</th>";
        }
        $HTML .= "</tr></thead><tbody>";
        foreach ($all as $r) {

            $aR = (array)$r;
            $HTML .= "<tr>";
            foreach ($fields as $f) {
                if ($f == 'timecreated') {
                    $HTML .= "<td>";
                    if (!empty($aR[$f]))
                        $HTML .= printableTime($aR[$f]);
                    $HTML .= "</td>";
                } elseif ($f == 'score') {
                    $HTML .= ($aR[$f] == 0) ? "<td></td>" : "<td>$aR[$f]</td>";  // hide zeros
                } else
                    $HTML .= "<td>$aR[$f]</td>";
            }
            $HTML .= "</tr>";
        }

        $HTML .= "</tbody></table>";
        return ($HTML);
    }




    function showStudentList(): string
    {
        $HTML = '';
        $HTML .= $this->navbar(['addStudent']);


        $students = new StudentTable();
        $all = $students->getAllStudents();

        $headers = ['Name', 'Last Visit', 'Lesson', 'History', 'Edit', 'Tutor1', 'Tutor2','Tutor3'];
        $fields = ['name', 'lastlesson', 'lesson', 'history', 'edit', 'tutoremail1', 'tutoremail2','tutoremail3'];

        $HTML .= "<table class='table'><thead><tr>";
        foreach ($headers as $t) {
            $HTML .= "<th>$t</th>";
        }
        $HTML .= "</tr></thead><tbody>";
        foreach ($all as $r) {

            $aR = (array)$r;
            $HTML .= "<tr>";
            foreach ($fields as $f) {
                if ($f == 'name') {
                    $HTML .= "<td>" . MForms::button($aR[$f], 'primary', 'selectStudent', $aR['id']) . "</td>";
                } elseif ($f == 'lastlesson') {
                    $HTML .= "<td>";
                    if (!empty($aR[$f]))
                        $HTML .= date("D F j Y g:ia", $aR[$f]);
                    $HTML .= "</td>";
                } elseif ($f == 'history') {
                    $HTML .= "<td>" . MForms::badge('history', 'info', 'studentHistory', $aR['id']) . "</td>";
                } elseif ($f == 'edit') {
                    $HTML .= "<td>" . MForms::badge('edit', 'info', 'showEditTutorsForm', $aR['id']) . "</td>";
                } else
                    $HTML .= "<td>$aR[$f]</td>";
            }
            $HTML .= "</tr>";
        }

        $HTML .= "</tbody></table>";
        return ($HTML);
    }


    function showTrainingView(): string
    {
        $HTML = '';
        return $HTML;
    }


    function showNavigationView(): string
    {
        $HTML = '';
        return $HTML;
    }

    function showReportView(): string
    {
        $HTML = '';
        return $HTML;
    }


    ////////////////////////////////////
    ////////////////////////////////////

    // this doesn't do any select, it just formats up a $ret into HTML
    static function quickTable(array $ret, array $fields)  // fields should be 'field'=>'title'
    {
        printNice($ret);
        $HTML = "<table class='table'><thead><tr>";
        foreach ($fields as $f => $t) {
            $HTML .= "<th>$t</th>";
        }
        $HTML .= "</tr></thead><tbody>";
        foreach ($ret as $r) {
            $aR = (array)$r;
            $HTML .= "<tr>";
            foreach ($fields as $f => $t) {
                $HTML .= "<td>$aR[$f]</td>";
            }
            $HTML .= "</tr>";
        }

        $HTML .= "</tbody></table>";
        return ($HTML);
    }


    // this is the form for adding a new student.  Remember that we are in TEACHER'S moodle account.
    function addStudent(): string
    {
        $HTML = '';

        $views = new Views();
        $HTML .= $views->navbar([], 'Add new Student');

        $HTML .= "   <form>";

        $HTML .= "     <div class='form-group'>";
        $HTML .= "       <label for='name'>Student Name</label>";
        $HTML .= "       <input type='text' class='form-control' id='name' name ='name' value='' placeholder='Enter student name'>";
        $HTML .= "     </div>";
        $HTML .= "     </br>";

        $HTML .= MForms::submitButton('Submit', 'primary', 'processEditStudentForm');
        $HTML .= MForms::hidden('p', 'processEditStudentForm');
        $HTML .= MForms::hidden('q', 0);        // we don't have a studentID yet
        $HTML .= MForms::hidden('r',  'add');
        $HTML .= "   </form>";

        return $HTML;
    }

    // this is the form for adding or editing the tutors
    function editTutors(int $studentID): string   // id=0 means add
    {
        $HTML = '';
        printNice($studentID, 'editTutors()');

        // get the student record
        if ($studentID > 0) {
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($studentID);
            printNice($student, 'record');
        }


        $HTML .= "   <form>";

        $HTML .= "     <div class='form-group'>";
        $HTML .= "       <label for='name'>Student Name</label>";
        $HTML .= "       <input type='text' class='form-control' id='name' name='name' value='{$student['name']}' '>";  // may want to edit name
        $HTML .= "     </div>";
        $HTML .= "     </br>";

        $HTML .= "Additional tutors may be assigned for this student.  Use the email from their
                    Moodle account.<br><br>";

        for ($i = 1; $i <= 3; $i++) {
            $HTML .= "     <div class='form-group'>";
            $HTML .= "       <label for='tutoremail$i'>Additional Tutor #$i</label>";
            $v = "tutoremail$i";
            $vt = $student[$v] ?? '';
            printNice($vt, "value$i");
            $HTML .= "       <input type='text' name= 'tutoremail$i' class='form-control' value='$vt' id='tutoremail$i' placeholder='Enter email' autocomplete='ignore'>";
            $HTML .= "     </div>";
        }

        $HTML .= MForms::submitButton('Submit', 'primary', 'processEditStudentForm');
        $HTML .= MForms::hidden('p', 'processEditStudentForm');
        $HTML .= MForms::hidden('q', $studentID);
        $HTML .= MForms::hidden('r', $studentID == 0 ? 'add' : 'edit');  // if we didn't find this student
        $HTML .= "   </form>";

        return $HTML;
    }


    function blendingAccordian(int $studentID): string
    {
        $HTML = '';

        $bTable = new BlendingTable();

        $log = new LogTable();
        $mastered = $log->getAllMastered($studentID);


        $logTable = new LogTable();
        $mastered = $logTable->GetAllMastered($studentID);

        $bTable = new BlendingTable();

        $lastContent = "";
        $lastGroup = "";

        $newGroup = true;
        $newLevel = true;

        $tool = '';
        $accordianData = [];

        $contentStart = "<table class='table'>";
        $contentEnd = "</table>";


        foreach ($bTable->clusterWords as $key => $value) {

            if ($lastGroup == '') {     // only the VERY FIRST TIME
                $lastGroup = $value['group'];
                $lastContent = $contentStart;
            }

            if (!($lastGroup == $value['group'])) { // we have changed groups
                $lastContent .= $contentEnd;

                $accordianData[$lastGroup] = $lastContent;
                $lastContent = $contentStart;      // reset and start collecting again
                $lastGroup = $value['group'];
            }

            // $link = MForms::buttonForm($key,'primary','blendingLesson',$key,'',false);
            $link = $this->accordianButton($key);
            $lastContent .= "<tr><td>$link</td><td>{$key}</td></tr>";


            // hunt through the applicable rules to see if this rule is in it
            // $tool = IconLink("help16.png", $alt = "", $script = DECODE . "/training/addToActive/$key", $style = 'gauge');
            // $gauge = ""; // nothing to show


            // if we have already mastered, show a gold star.  but click has the same action as clicking on a disabled icon
            // if (in_array($value['firstrule'], $mastered)) {
            //     // $tool = IconLink("favorite16.png", $alt = "", $script = DECODE . "/training/addToActive/$key", $style = 'gauge');
            // }

            // if (array_key_exists($value['firstrule'], $applicableRules)) {
            //     $thatRule = $applicableRules[$value['firstrule']];
            //     if ($thatRule['mastery'] < 5) {
            //         $tool = IconLink("accept16.png", $alt = "", $script = DECODE . "/training/deleteFromActive/$key", $style = 'gauge');
            //         $gauge = IconLink("gauge{$applicableRules[$value['firstrule']]['mastery']}.jpg", $alt = "", $script = '', $style = 'gauge');
            //     }
            // }

            // $HTML .= "<tr><td nobreak>&nbsp;$tool</td><td nobreak>"; //button to set or reset
            // $HTML .= "<span style=\"font-size:12px;\"><a href=\"/training/navigationParam/{$value['firstrule']}\">{$value['page']}</a></span>"; //rule ($value) is param1
            // //$this->document->write("</td><td nobreak>$gauge");     //maybe show a gauge
            // $HTML .= "</td></tr>"; //maybe show a gauge


        }

        $HTML .= $this->accordian($accordianData);
        // $HTML .= $this->accordianButton($key);


        return $HTML;
    }
}
