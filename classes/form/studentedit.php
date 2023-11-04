<?php namespace Blending;

// moodleform is defined in formslib.php
global $CFG;

require_once("$CFG->libdir/formslib.php");

// documentation:   https://docs.moodle.org/dev/Form_API



class studentedit_form extends \moodleform {

    public function definition() {


        $studentID = $_SESSION['currentStudent'];       // this isn't a moodle person, student is only used in blending
        // get the student record
        if ($studentID > 0) {
            $studentTable = new StudentTable();
            $student = $studentTable->getStudent($studentID);
        }



        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'cmid', $GLOBALS['cmid']);     // seems that every form needs to add cmid
        $mform->setType('id', PARAM_NOTAGS);

        $mform->addElement('hidden', 'p', 'processEditStudentForm');
        $mform->setType('p', PARAM_NOTAGS);

        $mform->addElement('hidden', 'q', $student['id']);    // just table of student nicknames, not secure
        $mform->setType('q', PARAM_NOTAGS);

        $mform->addElement('hidden', 'r', 'edit');          // this is an edit form, not used for add
        $mform->setType('r', PARAM_NOTAGS);

        $mform->addElement('text', 'name', get_string('studentname'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', $student['name']);

        $mform->addElement('text', 'tutor1email', get_string('tutor1email'));
        $mform->setType('tutor1email', PARAM_TEXT);
        $mform->setDefault('tutor1email', $student['tutor1email']);

        $mform->addElement('text', 'tutor2email', get_string('tutor1email'));
        $mform->setType('tutor2email', PARAM_TEXT);
        $mform->setDefault('tutor2email', $student['tutor2email']);

        $mform->addElement('text', 'tutor3email', get_string('tutor1email'));
        $mform->setType('tutor3email', PARAM_TEXT);
        $mform->setDefault('tutor3email', $student['tutor3email']);


        $this->add_action_buttons($cancel = false, $submitlabel='Submit');       // $this, not $mform !!
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}