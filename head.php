<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$p  = optional_param('p', 0, PARAM_INT);  // problem instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('problem', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $problem  = $DB->get_record('problem', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $problem  = $DB->get_record('problem', array('id' => $p), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $problem->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('problem', $problem->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

/// Print the page header
$PAGE->set_url('/mod/problem/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($problem->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// O código da página começa aqui.

// PEGA OS JAVASCRIPTs E CSSs REQUERIDOS PARA A PÁGINA
$PAGE->requires->css('/mod/problem/css/bootstrap.css');
$PAGE->requires->css('/mod/problem/css/bootstrap-datetimepicker.css');
$PAGE->requires->css('/mod/problem/css/bootstrap-editor.css');
$PAGE->requires->css('/mod/problem/css/style.css');
$PAGE->requires->css('/mod/problem/css/awesomplete.css');


$PAGE->requires->js('/mod/problem/js/jquery-1.8.3.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap.js', true);
$PAGE->requires->js('/mod/problem/js/wysihtml5-0.3.0.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap-editor.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap-editor-pt-BR.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap-datetimepicker.js', true);
$PAGE->requires->js('/mod/problem/js/locales/bootstrap-datetimepicker.pt-BR.js', true);
$PAGE->requires->js('/mod/problem/js/scripts.js', true);

//add_to_log($course->id, 'problem', 'view_group', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'view_session', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'send_final_report', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'send_session_report', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'finish_session', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'finish_problem', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'evaluate_pair', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'evaluate_session', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'evaluate_group', "view.php?id={$cm->id}", $problem->name, $cm->id);
//add_to_log($course->id, 'problem', 'view', "view.php?id={$cm->id}", $problem->name, $cm->id);

// Output starts here
echo $OUTPUT->header();
?>