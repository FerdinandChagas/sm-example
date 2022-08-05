<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


include(dirname(__FILE__).'/head.php');

if(problem_is_enrolled($context, "student")){
  include(dirname(__FILE__).'/student_views/student_view.php');
}
else if(problem_is_enrolled($context, "editingteacher")){
  include(dirname(__FILE__).'/teacher_views/teacher_problem_view.php');
}

// Finish the page
echo $OUTPUT->footer();