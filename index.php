<?php

// this is only used for starting the debugger.  of course you can't
// use any MOODLE functions because Moodle isn't in the environment.

$_SESSION['usingXDEBUG'] = true;        // set to false for production
if ($_SESSION['usingXDEBUG']) {
    echo '<h1>Running DEBUGGER - disable on line 26 in moodle/mod/blending/index.php</h1>';
    // die;
}


// we don't use the FORMS API for this plugin, so we need these two values
$GLOBALS['id'] = 999;
$GLOBALS['session'] = 'abc';

require_once('source/controller.php');
$content =  controller();
echo $content;

echo '<br>all done';
return;
















// require(__DIR__ . '/../../config.php');

// require_once(__DIR__ . '/lib.php');

// $id = required_param('id', PARAM_INT);

// $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
// require_course_login($course);

// $coursecontext = context_course::instance($course->id);

// $event = \mod_blending\event\course_module_instance_list_viewed::create(array(
//     'context' => $modulecontext
// ));
// $event->add_record_snapshot('course', $course);
// $event->trigger();

// $PAGE->set_url('/mod/blending/index.php', array('id' => $id));
// $PAGE->set_title(format_string($course->fullname));
// $PAGE->set_heading(format_string($course->fullname));
// $PAGE->set_context($coursecontext);

// echo $OUTPUT->header();

// $modulenameplural = get_string('modulenameplural', 'mod_blending');
// echo $OUTPUT->heading($modulenameplural);

// $blendings = get_all_instances_in_course('blending', $course);

// if (empty($blendings)) {
//     notice(get_string('no$blendinginstances', 'mod_blending'), new moodle_url('/course/view.php', array('id' => $course->id)));
// }

// $table = new html_table();
// $table->attributes['class'] = 'generaltable mod_index';

// if ($course->format == 'weeks') {
//     $table->head  = array(get_string('week'), get_string('name'));
//     $table->align = array('center', 'left');
// } else if ($course->format == 'topics') {
//     $table->head  = array(get_string('topic'), get_string('name'));
//     $table->align = array('center', 'left', 'left', 'left');
// } else {
//     $table->head  = array(get_string('name'));
//     $table->align = array('left', 'left', 'left');
// }

// foreach ($blendings as $blending) {
//     if (!$blending->visible) {
//         $link = html_writer::link(
//             new moodle_url('/mod/blending/view.php', array('id' => $blending->coursemodule)),
//             format_string($blending->name, true),
//             array('class' => 'dimmed')
//         );
//     } else {
//         $link = html_writer::link(
//             new moodle_url('/mod/blending/view.php', array('id' => $blending->coursemodule)),
//             format_string($blending->name, true)
//         );
//     }

//     if ($course->format == 'weeks' || $course->format == 'topics') {
//         $table->data[] = array($blending->section, $link);
//     } else {
//         $table->data[] = array($link);
//     }
// }

// echo html_writer::table($table);
// echo $OUTPUT->footer();
