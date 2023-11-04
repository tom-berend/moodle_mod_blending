<?php namespace Blending;

// moodleform is defined in formslib.php
global $CFG;

require_once("$CFG->libdir/formslib.php");

// documentation:   https://docs.moodle.org/dev/Form_API


class studentadd_form extends \moodleform {

    public function definition() {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'cmid', $GLOBALS['cmid']);     // seems that every form needs to add cmid
        $mform->setType('id', PARAM_NOTAGS);

        $mform->addElement('hidden', 'p', 'processEditStudentForm');
        $mform->setType('p', PARAM_NOTAGS);

        $mform->addElement('hidden', 'q', '0');
        $mform->setType('q', PARAM_NOTAGS);

        $mform->addElement('hidden', 'r', 'add');
        $mform->setType('r', PARAM_NOTAGS);

        $mform->addElement('text', 'name', 'Student Name');
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', '');

        $mform->addElement('hidden', 'tutor1email', '');
        $mform->setType('tutor1email', PARAM_NOTAGS);
        $mform->setDefault('tutor1email', '');

        $mform->addElement('hidden', 'tutor2email', '');
        $mform->setType('tutor2email', PARAM_NOTAGS);
        $mform->setDefault('tutor2email', '');

        $mform->addElement('hidden', 'tutor3email', '');
        $mform->setType('tutor3email', PARAM_NOTAGS);
        $mform->setDefault('tutor3email', '');

        $this->add_action_buttons($cancel = false, $submitlabel='Submit');       // $this, not $mform !!
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}

