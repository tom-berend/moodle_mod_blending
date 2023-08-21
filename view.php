<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_blending.
 *
 * @package     mod_blending
 * @copyright   2023 Tom Berend  <tom@communityreading.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// we don't use the FORMS API for this plugin, so we need these two values
$GLOBALS['id'] = $id;
$GLOBALS['session'] = sesskey();


// Activity instance id.
$b = optional_param('b', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('blending', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('blending', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('blending', array('id' => $b), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('blending', $moduleinstance->id, $course->id, false, MUST_EXIST);
}
$GLOBALS['cm'] = $cm;


require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);


$PAGE->set_url('/mod/blending/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();


require_once('source/controller.php');
$content =  controller();

echo $OUTPUT->box($content, "generalbox center clearfix");


echo $OUTPUT->footer();




