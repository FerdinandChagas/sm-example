<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$params = new stdClass();
$action = required_param('action', PARAM_TEXT);

if(problem_is_enrolled($context, "editingteacher")){
	switch ($action) {
		case 'unlink_group':

			//Verifica se grupo está desvinculado

			$params->groupid = required_param('groupid', PARAM_INT);

			//CRIA UM OBJETO PROBLEM_GROUP
			$pg = new stdClass();
			$pg = $DB->get_record('problem_group', array("groupid" => $params->groupid, "problemid" => $problem->id));

			//DELETA O OBJETO PROBLEM_GROUP DO BANCO DE DADOS
			problem_delete('problem_group', $pg->id);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'link_group':

			//Verifica se grupo está vinculado

			//CRIA UM OBJETO PROBLEM_GROUP
			$pg = new stdClass();
			$pg->groupid = required_param('groupid', PARAM_INT);
			$pg->problemid = $problem->id;

			//SALVA OS DADOS DO OBJETO PROBLEM_GROUP NO BANCO DE DADOS
			problem_save('problem_group', $pg);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'delete_session':
		
			//DELETA A SESSÃO DO BANCO DE DADOS
			$params->sessionid = required_param('sessionid', PARAM_INT);
			problem_delete('problem_group_session', $params->sessionid);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
		
			break;

		case 'add_session':

			//PEGA OS VALORES DO PROBLEM_GROUP
			$params->groupid = required_param('groupid', PARAM_INT);

			//ALTERA FORMATO DA DATA PARA TIMESTAMP
			list($day, $month, $year, $hour, $minute) = split('[/ :]', required_param('timestart', PARAM_TEXT)); 
			$timestamp = mktime($hour-3, $minute, 0, $month, $day, $year);
			
			// VOLTAR DATA AO FORMATO NORMAL
			//echo date("d-m-Y H:i", $timestamp);

			//CRIA UM OBJETO EVENTO
			$event = new stdClass();
			$event->name = "Nova sess&atilde;o";
			$event->description = 'Uma nova sess&atilde;o foi inserida para o problema: <a href="'.$url_problem.'">'.$problem->name.'</a>';
			$event->format = 1;
			$event->courseid = (int)$course->id;
			$event->groupid = $params->groupid;
			$event->userid = (int)$USER->id;
			$event->eventtype = "group";
			$event->timestart = $timestamp;
			$event->visible = 1;
			$event->sequence = 1;
			$event->timemodified = time();

			//SALVA OS DADOS DO OBJETO EVENTO NO BANCO
			$eventid = problem_save('event', $event, "", false);

			$timestamp = mktime($hour, $minute, 0, $month, $day, $year);

			//CRIA UM OBJETO SESSÃO
			$session = new stdClass();
			$session->problem_group 	= required_param('problem_group', PARAM_INT);
			$session->leader 			= required_param('leader', PARAM_INT);
			$session->reporter 			= required_param('reporter', PARAM_INT);
			$session->last 				= optional_param('last', 0, PARAM_INT);
			$session->timestart 		= $timestamp;
			$session->eventid			= $eventid;

			//SALVA OS DADOS DO OBJETO SESSÃO NO BANCO DE DADOS
			problem_save('problem_group_session', $session);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'edit_session':

			//PEGA VALORES ANTERIORES DA SESSÃO
			$old_session = new stdClass();
			$old_session = $DB->get_record('problem_group_session', array("id" => required_param('sessionid', PARAM_INT)));

			//ALTERA FORMATO DA DATA PARA TIMESTAMP
			list($day, $month, $year, $hour, $minute) = split('[/ :]', optional_param('timestart', $old_session->timestart, PARAM_TEXT)); 
			$timestamp = mktime($hour-3, $minute, 0, $month, $day, $year);

			//CRIA UM OBJETO EVENTO
			$event = new stdClass();
			if((int)$old_session->eventid > 0){
				$event->id = (int)$old_session->eventid;
				$event->timestart = $timestamp;
				$event->timemodified = time();
			} else {
				$event->name = "Sess&atilde;o alterada";
				$event->description = 'Uma sess&atilde;o foi alterada para o problema: <a href="'.$url_problem.'">'.$problem->name.'</a>';
				$event->format = 1;
				$event->courseid = (int)$course->id;
				$event->groupid = required_param('groupid', PARAM_INT);
				$event->userid = (int)$USER->id;
				$event->eventtype = "group";
				$event->timestart = $timestamp;
				$event->visible = 1;
				$event->sequence = 1;
				$event->timemodified = time();
			}

			//SALVA OS DADOS DO OBJETO EVENTO NO BANCO DE DADOS
			$eventid = problem_save('event', $event, "", false);

			$timestamp = mktime($hour, $minute, 0, $month, $day, $year);
			//CRIA UM OBJETO SESSÃO
			$session = new stdClass();
			$session->id 				= required_param('sessionid', PARAM_INT);
			$session->leader 			= optional_param('leader', $old_session->leader, PARAM_INT);
			$session->reporter 			= optional_param('reporter', $old_session->reporter, PARAM_INT);
			$session->timestart 		= $timestamp;
			$session->last 				= optional_param('last', $old_session->last, PARAM_INT);
			$session->eventid 			= $eventid;

			//SALVA OS DADOS DO OBJETO SESSÃO NO BANCO DE DADOS
			problem_save('problem_group_session', $session);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'finish_session':
			//PEGA VALORES ANTERIORES DA SESSÃO
			$old_session = new stdClass();
			$old_session = $DB->get_record('problem_group_session', array("id" => required_param('sessionid', PARAM_INT)));
			
			//ALTERA OS DADOS DA SESSÃO E SALVA NO BANCO DE DADOS
			$nsession = new stdClass();
			$nsession = $old_session;
			$nsession->timemodified = time();
			$nsession->finished = 1;

			problem_save('problem_group_session', $nsession);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'evaluate_session':
			//PEGA VALORES ANTERIORES DA SESSÃO
			$old_session = new stdClass();
			$old_session = $DB->get_record('problem_group_session', array("id" => required_param('sessionid', PARAM_INT)));

			//CRIA UM OBJETO SESSÃO
			$nsession = new stdClass();			
			$nsession = $old_session;

			$nsession->leader_evaluation = optional_param('leader_evaluation', '', PARAM_TEXT);
			$nsession->reporter_evaluation = optional_param('reporter_evaluation', '', PARAM_TEXT);
			$nsession->group_evaluation 	= optional_param('group_evaluation', '', PARAM_TEXT);

			//SALVA OS DADOS DO OBJETO SESSÃO NO BANCO DE DADOS
			problem_save('problem_group_session', $nsession);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'add_problem_requirement':
			$old_feature = $DB->get_record('problem_features', array("description" => required_param('requirement_description', PARAM_TEXT)));

			//CRIA UM OBJETO FEATURE
			$feature = new stdClass();
			$feature->id = $old_feature->id;
			$feature->description = required_param('requirement_description', PARAM_TEXT);
			$feature->category = 0;

			//SALVA OS DADOS DO OBJETO REQUERIMENTO NO BANCO DE DADOS
			$feature->id = problem_save('problem_features', $feature);

			$old_requirement = $DB->get_record('problem_requirements', array("featureid" => $feature->id));

			if(!$old_requirement){
				//CRIA UM OBJETO REQUERIMENTO
				$pr = new stdClass();
				$pr->problemid = (int) $problem->id;
				$pr->featureid = $feature->id;
				$pr->value = required_param('level', PARAM_TEXT);
				$pr->importance = required_param('importance', PARAM_FLOAT);
			
				//SALVA OS DADOS DO OBJETO REQUERIMENTO NO BANCO DE DADOS
				problem_save('problem_requirements', $pr);
			} else {
				echo "Já existe um requerimento com essa descrição, exclua-o para inserir outro!";
				if($url != "") 
					echo '<br /><br /><a href="'.$url.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
			}

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
		
			break;
		
		case 'delete_problem_requirement':

			//DELETA O REQUERIMENTO DO BANCO DE DADOS
			$params->requirementid = required_param('requirementid', PARAM_INT);
			problem_delete('problem_requirements', $params->requirementid);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'add_problem_goal':
			$old_feature = $DB->get_record('problem_features', array("description" => required_param('goal_description', PARAM_TEXT)));
			
			//CRIA UM OBJETO FEATURE
			$feature = new stdClass();
			$feature->id = $old_feature->id;
			$feature->description = required_param('goal_description', PARAM_TEXT);
			$feature->category = 0;

			//SALVA OS DADOS DO OBJETO META NO BANCO DE DADOS
			$feature->id = problem_save('problem_features', $feature);

			$old_goal = $DB->get_record('problem_goals', array("featureid" => $feature->id));
			if(!$old_goal->id){
				//CRIA UM OBJETO META
				$goal = new stdClass();
				$goal->problemid = $problem->id;
				$goal->featureid = $feature->id;
			
				//SALVA OS DADOS DO OBJETO META NO BANCO DE DADOS
				problem_save('problem_goals', $goal);
			} else {
				echo "Já existe uma meta com essa descrição, é necessário excluí-la para inserir outra!";
				if($url != "") 
					echo '<br /><br /><a href="'.$url.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
			}

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
		
			break;
		
		case 'delete_problem_goal':

			//DELETA O META DO BANCO DE DADOS
			$params->goalid = required_param('goalid', PARAM_INT);
			problem_delete('problem_goals', $params->goalid);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;
		
		case 'finish_problem':

			//Verifica se grupo está desvinculado

			$params->groupid = required_param('groupid', PARAM_INT);

			//CRIA UM OBJETO PROBLEM_GROUP
			$pg = new stdClass();
			$pg = $DB->get_record('problem_group', array("groupid" => $params->groupid, "problemid" => $problem->id));

			//DELETA O OBJETO PROBLEM_GROUP DO BANCO DE DADOS
			problem_change('problem_group', $pg->id, 'finished', 1);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'evaluate_user':
			$description = optional_param('description', '', PARAM_TEXT);
			$problemgroup = required_param('problem_group', PARAM_INT);
			$measurer = (int) $USER->id;
			$measured = required_param('measured', PARAM_INT);

			$pg = $DB->get_record('problem_group', array("id" => $problemgroup));
			
			$pg->evaluation = $description;

			problem_save('problem_group', $pg);

			$levels = required_param_array('level', PARAM_INT);

			foreach($levels as $feature=>$level){
				$ms = $DB->get_record('problem_evaluation_measured', array("feature" => $feature, "problem_group" => $pg->id, "measurer" => $USER->id, "measured" => $measured));
				
				$measure = $ms;
				$measure->value = $level;
				$measure->feature = $feature;
				$measure->measured = $measured;
				$measure->measurer = $USER->id;
				$measure->problem_group = $pg->id;

				problem_save('problem_evaluation_measured', $measure);
				
				$uf = $DB->get_record('problem_user_features', array("featureid" => $feature, "userid" => $measured));

				$user_feature = $uf;
				$user_feature->value = $level;
				$user_feature->featureid = $feature;
				$user_feature->userid = $measured;

				problem_save('problem_user_features', $user_feature);
			}
		
			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
		default:
			# code...
			break;
	}

}

echo $OUTPUT->footer();
?>