<?php

namespace Blending;

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

        $mform->addElement('text', 'name', get_string('studentname','blending'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', $student['name']);

        $mform->addElement('text', 'tutor1email', get_string('tutor1email','blending'));
        $mform->setType('tutor1email', PARAM_NOTAGS);
        $mform->setDefault('tutor1email', $student['tutor1email']);

        $mform->addElement('text', 'tutor2email', get_string('tutor2email','blending'));
        $mform->setType('tutor2email', PARAM_NOTAGS);
        $mform->setDefault('tutor2email', $student['tutor2email']);

        $mform->addElement('text', 'tutor3email', get_string('tutor3email','blending'));
        $mform->setType('tutor3email', PARAM_TEXT);
        $mform->setDefault('tutor3email', $student['tutor3email']);

        $this->add_action_buttons($cancel = false, $submitlabel='Submit');       // $this, not $mform !!
 
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}

/*
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
        $mform->setType('cmid', PARAM_NOTAGS);

        $mform->addElement('hidden', 'p', 'processEditStudentForm');
        $mform->setType('p', PARAM_NOTAGS);

        $mform->addElement('hidden', 'q', $student['id']);    // just table of student nicknames, not secure
        $mform->setType('q', PARAM_NOTAGS);

        $mform->addElement('hidden', 'r', 'edit');          // this is an edit form, not used for add
        $mform->setType('r', PARAM_NOTAGS);

        $mform->addElement('text', 'name', get_string('studentname','blending'));
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', $student['name']);

        $mform->addElement('text', 'tutor1email', get_string('tutor1email','blending'));
        $mform->setType('tutor1email', PARAM_TEXT);
        // $mform->setDefault('tutor1email', $student['tutor1email']);

        $mform->addElement('text', 'tutor2email', get_string('tutor2email','blending'));
        $mform->setType('tutor2email', PARAM_TEXT);
        // $mform->setDefault('tutor2email', $student['tutor2email']);

        $mform->addElement('text', 'tutor3email', get_string('tutor3email','blending'));
        $mform->setType('tutor3email', PARAM_TEXT);
        // $mform->setDefault('tutor3email', $student['tutor3email']);


        $this->add_action_buttons($cancel = false, $submitlabel='Submit');       // $this, not $mform !!
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}



// class gradingform_guide_editguide extends \moodleform {

//     /**
//      * Form element definition
//      */
//     public function definition() {
//         $form = $this->_form;

//         $form->addElement('hidden', 'areaid');
//         $form->setType('areaid', PARAM_INT);

//         $form->addElement('hidden', 'returnurl');
//         $form->setType('returnurl', PARAM_LOCALURL);

//         // Name.
//         $form->addElement('text', 'name', get_string('name', 'gradingform_guide'),
//             array('size' => 52, 'maxlength' => 255));
//         $form->addRule('name', get_string('required'), 'required', null, 'client');
//         $form->setType('name', PARAM_TEXT);
//         $form->addRule('name', null, 'maxlength', 255, 'client');

//         // Description.
//         $options = gradingform_guide_controller::description_form_field_options($this->_customdata['context']);
//         $form->addElement('editor', 'description_editor', get_string('description'), null, $options);
//         $form->setType('description_editor', PARAM_RAW);

//         // Guide completion status.
//         $choices = array();
//         $choices[gradingform_controller::DEFINITION_STATUS_DRAFT]    = html_writer::tag('span',
//             get_string('statusdraft', 'core_grading'), array('class' => 'status draft'));
//         $choices[gradingform_controller::DEFINITION_STATUS_READY]    = html_writer::tag('span',
//             get_string('statusready', 'core_grading'), array('class' => 'status ready'));
//         $form->addElement('select', 'status', get_string('guidestatus', 'gradingform_guide'), $choices)->freeze();

//         // Guide editor.
//         $element = $form->addElement('guideeditor', 'guide', get_string('pluginname', 'gradingform_guide'));
//         $form->setType('guide', PARAM_RAW);

//         $buttonarray = array();
//         $buttonarray[] = &$form->createElement('submit', 'saveguide', get_string('saveguide', 'gradingform_guide'));
//         if ($this->_customdata['allowdraft']) {
//             $buttonarray[] = &$form->createElement('submit', 'saveguidedraft', get_string('saveguidedraft', 'gradingform_guide'));
//         }
//         $editbutton = &$form->createElement('submit', 'editguide', ' ');
//         $editbutton->freeze();
//         $buttonarray[] = &$editbutton;
//         $buttonarray[] = &$form->createElement('cancel');
//         $form->addGroup($buttonarray, 'buttonar', '', array(' '), false);
//         $form->closeHeaderBefore('buttonar');
//     }

//     /**
//      * Setup the form depending on current values. This method is called after definition(),
//      * data submission and set_data().
//      * All form setup that is dependent on form values should go in here.
//      *
//      * We remove the element status if there is no current status (i.e. guide is only being created)
//      * so the users do not get confused
//      */
//     public function definition_after_data() {
//         $form = $this->_form;
//         $el = $form->getElement('status');
//         if (!$el->getValue()) {
//             $form->removeElement('status');
//         } else {
//             $vals = array_values($el->getValue());
//             if ($vals[0] == gradingform_controller::DEFINITION_STATUS_READY) {
//                 $this->findbutton('saveguide')->setValue(get_string('save', 'gradingform_guide'));
//             }
//         }
//     }

//     /**
//      * Form vlidation.
//      * If there are errors return array of errors ("fieldname"=>"error message"),
//      * otherwise true if ok.
//      *
//      * @param array $data array of ("fieldname"=>value) of submitted data
//      * @param array $files array of uploaded files "element_name"=>tmp_file_path
//      * @return array of "element_name"=>"error_description" if there are errors,
//      *               or an empty array if everything is OK (true allowed for backwards compatibility too).
//      */
//     public function validation($data, $files) {
//         $err = parent::validation($data, $files);
//         $err = array();
//         $form = $this->_form;
//         $guideel = $form->getElement('guide');
//         if ($guideel->non_js_button_pressed($data['guide'])) {
//             // If JS is disabled and button such as 'Add criterion' is pressed - prevent from submit.
//             $err['guidedummy'] = 1;
//         } else if (isset($data['editguide'])) {
//             // Continue editing.
//             $err['guidedummy'] = 1;
//         } else if ((isset($data['saveguide']) && $data['saveguide']) ||
//                    (isset($data['saveguidedraft']) && $data['saveguidedraft'])) {
//             // If user attempts to make guide active - it needs to be validated.
//             if ($guideel->validate($data['guide']) !== false) {
//                 $err['guidedummy'] = 1;
//             }
//         }
//         return $err;
//     }

//     /**
//      * Return submitted data if properly submitted or returns NULL if validation fails or
//      * if there is no submitted data.
//      *
//      * @return object submitted data; NULL if not valid or not submitted or cancelled
//      */
//     public function get_data() {
//         $data = parent::get_data();
//         if (!empty($data->saveguide)) {
//             $data->status = gradingform_controller::DEFINITION_STATUS_READY;
//         } else if (!empty($data->saveguidedraft)) {
//             $data->status = gradingform_controller::DEFINITION_STATUS_DRAFT;
//         }
//         return $data;
//     }

//     /**
//      * Check if there are changes in the guide and it is needed to ask user whether to
//      * mark the current grades for re-grading. User may confirm re-grading and continue,
//      * return to editing or cancel the changes
//      *
//      * @param gradingform_guide_controller $controller
//      */
//     public function need_confirm_regrading($controller) {
//         $data = $this->get_data();
//         if (isset($data->guide['regrade'])) {
//             // We have already displayed the confirmation on the previous step.
//             return false;
//         }
//         if (!isset($data->saveguide) || !$data->saveguide) {
//             // We only need confirmation when button 'Save guide' is pressed.
//             return false;
//         }
//         if (!$controller->has_active_instances()) {
//             // Nothing to re-grade, confirmation not needed.
//             return false;
//         }
//         $changelevel = $controller->update_or_check_guide($data);
//         if ($changelevel == 0) {
//             // No changes in the guide, no confirmation needed.
//             return false;
//         }

//         // Freeze form elements and pass the values in hidden fields.
//         // TODO description_editor does not freeze the normal way!
//         $form = $this->_form;
//         foreach (array('guide', 'name'/*, 'description_editor'*/) as $fieldname) {
//             $el =& $form->getElement($fieldname);
//             $el->freeze();
//             $el->setPersistantFreeze(true);
//             if ($fieldname == 'guide') {
//                 $el->add_regrade_confirmation($changelevel);
//             }
//         }

//         // Replace button text 'saveguide' and unfreeze 'Back to edit' button.
//         $this->findbutton('saveguide')->setValue(get_string('continue'));
//         $el =& $this->findbutton('editguide');
//         $el->setValue(get_string('backtoediting', 'gradingform_guide'));
//         $el->unfreeze();

//         return true;
//     }

//     /**
//      * Returns a form element (submit button) with the name $elementname
//      *
//      * @param string $elementname
//      * @return HTML_QuickForm_element
//      */
//     protected function &findbutton($elementname) {
//         $form = $this->_form;
//         $buttonar =& $form->getElement('buttonar');
//         $elements =& $buttonar->getElements();
//         foreach ($elements as $el) {
//             if ($el->getName() == $elementname) {
//                 return $el;
//             }
//         }
//         return null;
//     }
// }
