<?php

namespace Blending;



/****************
 * CC BY-NC-SA 4.0
 * Attribution-NonCommercial-ShareAlike 4.0 International
 *
 * This license requires that reusers give credit to the creator. It allows
 * reusers to distribute, remix, adapt, and build upon the material in any
 * medium or format, for noncommercial purposes only. If others modify or
 * adapt the material, they must license the modified material under identical terms.
 *
 * BY: Credit must be given to the Community Reading Project, who created it.
 *
 * NC: Only noncommercial use of this work is permitted.
 *
 *     Noncommercial means not primarily intended for or directed towards commercial
 *     advantage or monetary compensation.
 *
 * SA: Adaptations must be shared under the same terms.
 *
 * see the license deed here:  https://creativecommons.org/licenses/by-nc-sa/4.0
 *
 ******************/



// moodleform is defined in formslib.php
global $CFG;

require_once("$CFG->libdir/formslib.php");

// documentation:   https://docs.moodle.org/dev/Form_API



// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class studentedit_form extends \moodleform
{
    // Add elements to form.
    public function definition()
    {

        $studentID = $_SESSION['currentStudent'];       // this isn't a moodle person, student is only used in blending
        // get the student record
        if ($studentID > 0) {
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($studentID);
        }

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'cmid', $GLOBALS['cmid']);     // seems that every form needs to add cmid
        $mform->setType('cmid', PARAM_NOTAGS);

        $mform->addElement('hidden', 'p', 'processEditStudentForm');
        $mform->setType('p', PARAM_NOTAGS);

        $mform->addElement('hidden', 'q', $student['id']);    // just table of student nicknames, not secure
        $mform->setType('q', PARAM_NOTAGS);

        $mform->addElement('hidden', 'r', 'edit');          // this is an edit form, not used for add
        $mform->setType('r', PARAM_NOTAGS);

        $mform->addElement('text', 'name', get_string('studentname','mod_blending'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', $student['name']);

        $mform->addElement('text', 'tutor1email', get_string('tutor1email','mod_blending'));
        $mform->setType('tutor1email', PARAM_NOTAGS);
        $mform->setDefault('tutor1email', $student['tutor1email']);

        $mform->addElement('text', 'tutor2email', get_string('tutor2email','mod_blending'));
        $mform->setType('tutor2email', PARAM_NOTAGS);
        $mform->setDefault('tutor2email', $student['tutor2email']);

        $mform->addElement('text', 'tutor3email', get_string('tutor3email','mod_blending'));
        $mform->setType('tutor3email', PARAM_TEXT);
        $mform->setDefault('tutor3email', $student['tutor3email']);

        $this->add_action_buttons($cancel = false, $submitlabel='Submit');       // $this, not $mform !!

    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}

