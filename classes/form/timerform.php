<?php namespace Blending;
/*
$HTML .= "<form>";
        $HTML .= MForms::hidden('p', 'lessonTest');
        $HTML .= MForms::security();  // makes moodle happy
        $HTML .= MForms::hidden('lesson', $this->lessonName);
        $HTML .= MForms::hidden('score', '0', 'score');

        if (str_contains($controls, 'stopwatch')) {
            $HTML .= MForms::rowOpen(12);
            $HTML .= "<div style='background-color:#ffffe0;float:left;width:$watchSize;height:$watchSize;border:solid 5px grey;border-radius:30px;'>";

            $HTML .= "<div name='timer' id='timer' style='font-size:$fontSize;text-align:center;padding:$fontPadding;'>";
            // $HTML .= "<input type='text' name='timer' id='timer'  placeholder='' value='0' class='' />";
            $HTML .= '0';
            $HTML .= "</div>";
            $HTML .= "</div>";

            $HTML .= "<table style='float:left;$buttonSpacing'><tr><td>";  // use table to give nice vertical spacing
            $HTML .= MForms::onClickButton('Start', 'success', true, "StopWatch.start()");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Stop', 'danger', true, "StopWatch.stop()");
            $HTML .= "</td></tr><tr><td>";
            $HTML .= MForms::onClickButton('Reset', 'secondary', true, "StopWatch.reset()");

            $HTML .= "</td></tr></table>";
            $HTML .= MForms::rowClose();
            // $HTML .= "<br>";
        }
        // remark element
        if (str_contains($controls, 'comment')) {
            $HTML .= MForms::rowOpen($commentWidth);
            $HTML .= MForms::textarea('', 'remark', '', '', '', 3, 'Optional comment...');
            $HTML .= MForms::rowClose();
            $HTML .= "<br>";
        }


        // mastery element
        if (str_contains($controls, 'mastery')) {
            $HTML .= MForms::submitButton('Mastered', 'primary', 'mastered');
            $HTML .= MForms::submitButton('In Progress', 'warning', 'inprogress');
            $HTML .= "<br /><br />";
        }

        // completion element
        if (str_contains($controls, 'completion')) {
            $HTML .= MForms::submitButton('Completed', 'primary', 'mastered');
            $HTML .= "<br /><br />";
        }
        $HTML .= "</form>";

*/