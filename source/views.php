<?php

namespace Blending;

// colour palette from  https://coolors.co/067bc2-e8eef2-c2847a-ffc857-439a86

global $colours;
$colours = ['dark' => "#067bc2", 'light' => "#e8eef2", 'a' => "#c2847a", 'b' => "#ffc857", 'c' => "#439a86"];






class Views extends ViewComponents
{
    public $widthCols = 12;
    public $smallFont = "";

    function __construct()
    {
        if ($GLOBALS['mobileDevice']) {
            $this->widthCols = 12;
            $this->smallFont = "style='font-size:smaller;'";
        } else {
            $this->widthCols = 6;
            $this->smallFont = "";
        }
    }


    function appHeader(): string
    {
        $HTML = '';


        $HTML .= MForms::rowOpen($this->widthCols);
        $HTML .= "<img src='pix/toolsforstrugglingreaders.png' style='width:100%;max-width:600px;'><br><br>";
        $HTML .= MForms::rowClose();

        return $HTML;
    }


    function appFooter(): string
    {
        $HTML = '';

        unset($_SESSION['showLicenseOnce']);

        if (!isset($_SESSION['showLicenseOnce'])) {   // hide after first display
            $HTML .= "<br><br><br><hr>";    // leave a gap
            $HTML .= MForms::rowOpen($this->widthCols);

            $HTML .= "<p $this->smallFont>";
            $HTML .= "&copy; 2013-2023 <a href='http://communityreading.org' target='_blank'>Community Reading Project</a>";
            $HTML .= "</p><br><p $this->smallFont>";
            $HTML .= "<a rel='license' href='http://creativecommons.org/licenses/by-nc-sa/4.0/' target='_blank'><img alt='Creative Commons Licence' style='border-width:0' src='https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png'></a>";
            $HTML .= "<br>This work is licensed under a <a rel='license' href='http://creativecommons.org/licenses/by-nc-sa/4.0/' target='_blank'>Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.";
            $HTML .= "</p>";

            $HTML .= MForms::rowClose();
            $HTML .= MForms::rowOpen($this->widthCols);

            $HTML .= "<p $this->smallFont>";
            $HTML .= "<a rel='license' href='http://creativecommons.org/licenses/by-nc-sa/3.0/' target='_blank'><img alt='Creative Commons Licence' style='border-width:0' src='https://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png'></a>";
            $HTML .= "<br>Portions of this work are adapted from an original work of the <a href='https://www.coreknowledge.org/' target = '_blank'>Core Knowledge ";
            $HTML .= "Foundation</a> made available through licensing under a ";
            $HTML .= "<a href='https://creativecommons.org/licenses/by-nc-sa/3.0/' target='_blank'> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported</a> ";
            $HTML .= "License. This does not in any way imply that Core Knowledge ";
            $HTML .= "Foundation endorses this work.  Core Knowledge licence terms are ";
            $HTML .= "<a href='https://www.coreknowledge.org/wp-content/uploads/2016/12/CKLA-CCL-Terms-of-Use.pdf' target='_blank' class='ui-link'>here</a>";
            $HTML .= "</p>";

            $HTML .= MForms::rowClose();
            $HTML .= MForms::rowOpen($this->widthCols);

            $HTML .= "<p $this->smallFont>";
            $HTML .= "<a rel='license' href='http://creativecommons.org/licenses/by-sa/3.0/' target='_blank'><img alt='Creative Commons Licence' style='border-width:0' src='https://i.creativecommons.org/l/by-sa/3.0/88x31.png'></a>";
            $HTML .= "<br>Portions of this work are adapted from  <a href='https://www.freereading.net/wiki/Passages_to_practice_advanced_phonics_skills,_fluency,_and_comprehension.html' target = '_blank'>Free Reading</a> ";
            // <a href='https://freereading.net/' target = '_blank'>Free Reading ";
            $HTML .= "made available through licensing under a ";
            $HTML .= "<a href='https://creativecommons.org/licenses/by-sa/3.0/' target='_blank'> Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.";
            $HTML .= "</p>";

            $HTML .= MForms::rowClose();
            $HTML .= MForms::rowOpen($this->widthCols);

            $HTML .= "<p $this->smallFont>";
            $HTML .= "<a rel='license' href='http://creativecommons.org/licenses/by-nc-sa/4.0/' target='_blank'><img alt='Creative Commons Licence' style='border-width:0' src='https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png'></a>";
            $HTML .= "<br>Portions of this work are adapted from  <a href='https://www.opensourcephonics.org/' target = '_blank'>Open Source Phonics</a> ";
            $HTML .= "made available through licensing under a ";
            $HTML .= "<a href='https://creativecommons.org/licenses/by-nc-sa/4.0/' target='_blank'> Creative Commons Attribution-Non Commercial-ShareAlike 4.0 International</a> License.";
            $HTML .= "Open Source Phonics licence terms are ";
            $HTML .= "<a href='https://www.opensourcephonics.org/terms-of-use/' target='_blank' class='ui-link'>here</a>";
            $HTML .= "</p>";
        }
        $HTML .= MForms::rowClose();
        $_SESSION['showLicenseOnce'] = true;

        return $HTML;
    }


    function showStudentHistory(int $studentID): string
    {
        $HTML = '';
        $HTML .= $GLOBALS['mobileDevice'] ? MForms::rowOpen(12) : MForms::rowOpen(8);

        $HTML .= $this->navbar(['exit','addStudent']);

        $students = new LogTable();
        $all = $students->getStudentAll($studentID);
        // printNice($all);

        $headers = ['Date', 'Action', 'Lesson', 'Result', 'Score', 'Remark', 'Tutor'];
        $fields = ['timecreated', 'action', 'lesson', 'result', 'score', 'remark', 'tutoremail'];


        $HTML .= "<table class='table w-auto'><thead><tr>";
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
                } elseif ($f == 'tutoremail') {
                    $HTML .= '<td>' . str_replace('@', '@&#8203', $aR[$f]) . '</td>';  // add an invisible space to make long emails break nicely on mobile
                } elseif ($f == 'score') {
                    $HTML .= ($aR[$f] == 0) ? "<td></td>" : "<td>$aR[$f]</td>";  // hide zeros
                } else
                    $HTML .= "<td>$aR[$f]</td>";
            }
            $HTML .= "</tr>";
        }

        $HTML .= "</tbody></table>";
        $HTML .= MForms::rowClose();
        return ($HTML);
    }




    function showStudentList(): string
    {
        $HTML = '';
        $HTML .= $this->navbar(['addStudent']);

        $HTML .= "<h5>Students do not need Moodle IDs</h5>";

        $students = new StudentTable();
        $all = $students->getAllStudents();

        $headers = ['Student', 'Last Visit', 'Last Lesson', 'History', 'Edit Tutors', 'Tutor1', 'Tutor2', 'Tutor3', 'Delete'];
        $fields = ['name', 'lastlesson', 'lesson', 'history', 'edit', 'tutor1email', 'tutor2email', 'tutor3email', 'delete'];

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
                } elseif ($f == 'delete') {
                    $temp = htmlentities($aR['name']);
                    $HTML .= "<td>" . MForms::badge('Delete', 'danger', 'deleteStudent', $aR['id'], '', true, "Delete Student $temp") . "</td>";      // wastebasket
                } else
                    $HTML .= "<td>" . htmlentities($aR[$f] ?? '') . "</td>";
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
        $HTML .= $views->navbar(['exit'], 'Add new Student');

        $HTML .= MForms::rowOpen($GLOBALS['mobileDevice']?8:4);

        // require_once ('classes/form/.php');
        // $mform = new studentadd_form();
        // // printNice($mform);
        // $mform->display();
        // $HTML .= ob_get_contents();
        // ob_end_clean();
        // $HTML .= MForms::rowClose();

        require_once('classes/form/studentadd.php');
        $mform = new studentadd_form();

        ob_start();
        $mform->display();
        $HTML .= ob_get_contents();
        ob_end_clean();

        $HTML .= MForms::rowClose();


        return $HTML;
    }

    // this is the form for adding or editing the tutors
    function editTutors(int $studentID): string   // id=0 means add
    {
        $HTML = '';

        $views = new Views();
        $HTML .= $views->navbar(['exit'], 'Edit Student');


        // get the student record
        if ($studentID > 0) {
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($studentID);
            printNice($student, 'record');
        }


        // $HTML .= "   <form>";


        $HTML .= "Additional tutors may be assigned for this student.  Use the email from their
                    Moodle account.<br><br>";

        ob_start();
        require_once('classes/form/studentedit.php');
        $mform = new studentedit_form();

        $mform->display();
        $HTML .= ob_get_contents();
        ob_end_clean();


        return $HTML;
    }


    function blendingAccordian(int $studentI, $course): string
    {
        $HTML = '';

        assert(in_array($course, $GLOBALS['allCourses']), "sanity check - unexpected course '' ?");
        require_once("courses/$course.php");

        $Course = ucfirst(($course));
        $bTable = new $Course;  // 'blending' becomes 'Blending'

        $log = new LogTable();
        $mastered = $log->getAllMastered($studentID);

        $lastContent = "";
        $lastGroup = "";

        $newGroup = true;
        $newLevel = true;

        $tool = '';
        $accordianData = [];

        $contentStart = "<table class='table'>";
        $contentEnd = "</table>";


        $groups = $bTable->getLessonsByGroups();     // to power the accordian

        foreach ($groups as $lessonName => $group) {

            if ($lastGroup == '') {     // only the VERY FIRST TIME
                $lastGroup = $group;
                $lastContent = $contentStart;
            }

            if (!($lastGroup == $group)) { // we have changed groups
                $lastContent .= $contentEnd;

                $accordianData[$lastGroup] = $lastContent;
                $lastContent = $contentStart;      // reset and start collecting again
                $lastGroup = $group;
            }

            $link = $this->accordianButton($lessonName);
            $lastContent .= "<tr><td>$link</td><td>{$lessonName}</td></tr>";


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
