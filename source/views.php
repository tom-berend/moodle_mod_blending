<?php

// colour palette from  https://coolors.co/067bc2-e8eef2-c2847a-ffc857-439a86

global $colours;
$colours = ['dark'=>"#067bc2",'light'=>"#e8eef2",'a'=>"#c2847a",'b'=>"#ffc857",'c'=>"#439a86"];






class Views extends ViewComponents{

    function showStudentList():string{
        $HTML = '';

        $HTML .= MForms::submitButton('Add Student','primary','showAddStudentForm');

        $students = new StudentTable();
        $all =$students->getAllStudents();
        $HTML .= $this->quickTable($all,['name'=>'Name','tutoremail1'=>'Tutor 1']);

        $headers = ['Name','Tutor1'];
        $fields = ['name','tutoremail1'];



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


    function showNavigationView():string{
        $HTML = '';
        return $HTML;

    }

    function showReportView():string{
        $HTML = '';
        return $HTML;

    }

    function showTrainingView():string{
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
        foreach ($fields as $f=>$t) {
            $HTML .= "<th>$t</th>";
        }
        $HTML .= "</tr></thead><tbody>";
        foreach ($ret as $r) {
            $aR = (array)$r;
            $HTML .= "<tr>";
            foreach ($fields as $f=>$t) {
                $HTML .= "<td>$aR[$f]</td>";
            }
            $HTML .= "</tr>";
        }

        $HTML .= "</tbody></table>";
        return ($HTML);
    }




        // this is the form for adding or editing a student
        function editTutors(int $studentID): string
        {
            $HTML = '';


            // get the student record
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($_SESSION['currentStudent']);
            printNice($student,'record');

            assertTrue(!empty($student),'MUST have a student for this');

            $HTML .= "   <form>";
            // tell the controller what to do when submitted
            $HTML .= "<input type='hidden' name='p' value='processEditTutorForm' />";
            $HTML .= "<input type='hidden' name='q' value='$studentID' />";

            $HTML .= "     <div class='form-group'>";
            $HTML .= "       <label for='name'>Student Name</label>";
            $HTML .= "       <input type='text' class='form-control' id='name' value='{$student->name}' placeholder='Enter student name'>";
            $HTML .= "     </div>";
            $HTML .= "     </br>";

            for ($i = 1; $i <= 3; $i++) {
                $HTML .= "     <div class='form-group'>";
                $HTML .= "       <label for='tutoremail$i'>Additional Tutor #$i</label>";
                $v = "tutoremail$i";
                $vt = $student->$v ?? '';
                printNice($vt,"value$i");
                $HTML .= "       <input type='text' name= 'tutoremail$i' class='form-control' value='$vt' id='tutoremail$i' placeholder='Enter email' autocomplete='ignore'>";
                $HTML .= "     </div>";
            }
            $HTML .= "     <button type='submit' class='btn btn-primary'>Submit</button>";
            $HTML .= $this->securityHiddens();
            $HTML .= "   </form>";

            return $HTML;
        }


}


