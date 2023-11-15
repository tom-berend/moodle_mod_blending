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



require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');


$id      = optional_param('id', 0, PARAM_INT);
$cmid      = optional_param('cmid', 0, PARAM_INT); // Course Module ID

print_r($_REQUEST);

if ($cmid) {
    if (!$blending = $DB->get_record('blending', array('id'=>$cmid))) {
        throw new \moodle_exception('invalidaccessparameter');
    }
    $cm = get_coursemodule_from_instance('blending', $cmid, $blending->course, false, MUST_EXIST);

} else {
    if (!$cm = get_coursemodule_from_id('blending', $id)) {
        throw new \moodle_exception('invalidcoursemodule');
    }
    $blending = $DB->get_record('blending', array('id'=>$cm->instance), '*', MUST_EXIST);
    $cmid = $cm->instance;
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

// don't have access to Moodle obj when I use xDebug
$GLOBALS['cmid'] = $cmid;


require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);


$PAGE->set_url('/mod/blending/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();

require_once('source/controller.php');

// p,q,r drive the controller
$p = optional_param('p', '', PARAM_TEXT);
$q = optional_param('q', '', PARAM_TEXT);
$r = optional_param('r', '', PARAM_TEXT);

use Blending;
$c = new Blending\Controller();
$content =  $c->controller($p, $q, $r);


echo $OUTPUT->box($content, "generalbox center clearfix");

echo $OUTPUT->footer();
