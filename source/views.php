<?php

// colour palette from  https://coolors.co/067bc2-e8eef2-c2847a-ffc857-439a86

global $colours;
$colours = ['dark' => "#067bc2", 'light' => "#e8eef2", 'a' => "#c2847a", 'b' => "#ffc857", 'c' => "#439a86"];






class Views extends ViewComponents
{

    function navbar(array $options): string
    {
        $HTML = '';

        $buttons = '';
        if (in_array('addStudent', $options))
            $buttons .= MForms::buttonForm('Add Student', 'primary', 'showAddStudentForm');



        $HTML .= "<nav class='navbar navbar-light bg-warning'>";
        $HTML .= "  <a class='navbar-brand' href='#'>";
        $HTML .= "    <img src='pix/fatcat.png' width='40' height='40' alt=''>";
        $HTML .= "  </a>";
        $HTML .= "  <form class='form-inline'>";
        $HTML .= $buttons;
        $HTML .= "    <button class='btn btn-outline-success' type='button'>Main button</button>";
        $HTML .= "    <button class='btn btn-sm btn-outline-secondary' type='button'>Smaller button</button>";
        $HTML .= "  </form>";
        $HTML .= "</nav>";

        return $HTML;
    }

    function showStudentList(): string
    {
        $HTML = '';

        $students = new StudentTable();
        $all = $students->getAllStudents();

        $headers = ['ID', 'Name', 'Tutor1'];
        $fields = ['id', 'name', 'tutoremail1'];

        $HTML .= "<table class='table'><thead><tr>";
        foreach ($headers as $t) {
            $HTML .= "<th>$t</th>";
        }
        $HTML .= "</tr></thead><tbody>";
        foreach ($all as $r) {

            $aR = (array)$r;
            $HTML .= "<tr>";
            foreach ($fields as $f) {
                $HTML .= "<td>$aR[$f]</td>";
            }
            $HTML .= "</tr>";
        }

        $HTML .= "</tbody></table>";
        return ($HTML);


        return $HTML;
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


    // this is the form for adding or editing the tutors
    function addStudent(): string
    {
        $HTML = '';


        // get the student record
        if ($studentID > 0) {
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($studentID);
            printNice($student, 'record');
        }

        $HTML .= "   <form>";

        $HTML .= "     <div class='form-group'>";
        $HTML .= "       <label for='name'>Student Name</label>";
        $HTML .= "       <input type='text' class='form-control' id='name' name ='name' value='{$student->name}' placeholder='Enter student name'>";
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

        // get the student record
        if ($studentID > 0) {
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($studentID);
            printNice($student, 'record');
        }

        $HTML .= "   <form>";

        $HTML .= "     <div class='form-group'>";
        $HTML .= "       <label for='name'>Student Name</label>";
        $HTML .= "       <input type='text' class='form-control' id='name' name='name' value='{$student->name}' '>";  // may want to edit name
        $HTML .= "     </div>";
        $HTML .= "     </br>";

        for ($i = 1; $i <= 3; $i++) {
            $HTML .= "     <div class='form-group'>";
            $HTML .= "       <label for='tutoremail$i'>Additional Tutor #$i</label>";
            $v = "tutoremail$i";
            $vt = $student->$v ?? '';
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
}
