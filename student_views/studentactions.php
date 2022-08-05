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
$url = urldecode(optional_param('url_local', '',PARAM_TEXT));


if(problem_is_enrolled($context, "student")){
	switch ($action) {
		case 'add_feature':

			$feature = $DB->get_record("problem_features", array("description" => required_param('feature_description', PARAM_TEXT)));

			if(!$feature->id){
				$feature->description = required_param('feature_description', PARAM_TEXT);
				$feature->id = problem_save('problem_features', $feature);
			}

			//CRIA UM OBJETO CARACTERÍSTICA

			$uf = $DB->get_record("problem_user_features", array("featureid" => $feature->id));

			$uf->featureid = $feature->id;
			$uf->value = required_param('level', PARAM_TEXT);
			$uf->userid = $USER->id;
			
			//SALVA OS DADOS DO OBJETO CARACTERÍSTICA NO BANCO DE DADOS
			problem_save('problem_user_features', $uf);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'delete_feature':

			//DELETA A CARACTERÍSTICA DO BANCO DE DADOS
			$params->featureid = required_param('featureid', PARAM_INT);
			problem_delete('problem_user_features', $params->featureid);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
			
			break;

		case 'edit_prefered_times':

			//CRIA UM OBJETO PERFIL
			$user_prefered_times = new stdClass();
			//VERIFICA SE PERFIL JÁ EXISTE, SE SIM PEGA O ID
			if($pid = $DB->get_record('problem_user_prefered_times', array("userid" => $USER->id))->id)
				$user_prefered_times->id = $pid;

			$user_prefered_times->sunday = optional_param('sun_m', 0, PARAM_TEXT) . optional_param('sun_t', 0, PARAM_TEXT) . optional_param('sun_n', 0, PARAM_TEXT);
			$user_prefered_times->monday = optional_param('mon_m', 0, PARAM_TEXT) . optional_param('mon_t', 0, PARAM_TEXT) . optional_param('mon_n', 0, PARAM_TEXT);
			$user_prefered_times->tuesday = optional_param('tue_m', 0, PARAM_TEXT) . optional_param('tue_t', 0, PARAM_TEXT) . optional_param('tue_n', 0, PARAM_TEXT);
			$user_prefered_times->wednesday = optional_param('wed_m', 0, PARAM_TEXT) . optional_param('wed_t', 0, PARAM_TEXT) . optional_param('wed_n', 0, PARAM_TEXT);
			$user_prefered_times->thursday = optional_param('thu_m', 0, PARAM_TEXT) . optional_param('thu_t', 0, PARAM_TEXT) . optional_param('thu_n', 0, PARAM_TEXT);
			$user_prefered_times->friday = optional_param('fri_m', 0, PARAM_TEXT) . optional_param('fri_t', 0, PARAM_TEXT) . optional_param('fri_n', 0, PARAM_TEXT);
			$user_prefered_times->saturday = optional_param('sat_m', 0, PARAM_TEXT) . optional_param('sat_t', 0, PARAM_TEXT) . optional_param('sat_n', 0, PARAM_TEXT);
			$user_prefered_times->userid = $USER->id;

			//SALVA OS DADOS DO OBJETO PERFIL NO BANCO DE DADOS
			problem_save('problem_user_prefered_times', $user_prefered_times);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
			
			break;

		case 'edit_unknown_words':
			
			$unknown_words = new stdClass();
			$unknown_words->id = optional_param('uwid', null, PARAM_INT);
			$unknown_words->unknown_words = required_param('unknown_words', PARAM_TEXT);
			$unknown_words->problem_group = required_param('problem_group', PARAM_INT);
			$unknown_words->userid = $USER->id;

			problem_save('problem_unknown_words', $unknown_words);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;
		case 'add_report':
		
			//PEGA VALORES ANTERIORES DA SESSÃO
			$old_session = new stdClass();
			$old_session = $DB->get_record('problem_group_session', array("id" => required_param('sessionid', PARAM_INT)));
			
			//CRIA UM OBJETO SESSÃO
			$session = new stdClass();
			$session->id = required_param('sessionid', PARAM_INT);
			$session->report = optional_param('report', $old_session->report, PARAM_TEXT);
			
			//SALVA OS DADOS DO OBJETO SESSÃO NO BANCO DE DADOS
			problem_save('problem_group_session', $session);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

			break;

		case 'pair_evaluation':

			//PEGA VALORES ANTERIORES DA SESSÃO
			$old_pe = new stdClass();
			$old_pe = $DB->get_record('problem_pair_evaluation', array("measured" => required_param('measured', PARAM_INT), "measurer" => $USER->id, "problem_group" => required_param('problemgroup', PARAM_INT)));

			//CRIA UM OBJETO SESSÃO
			$pe = $old_pe;
			$pe->description = optional_param('description', '', PARAM_TEXT);
			$pe->problem_group = required_param('problemgroup', PARAM_INT);
			$pe->measurer = $USER->id;
			$pe->measured = required_param('measured', PARAM_INT);

			//SALVA OS DADOS DO OBJETO SESSÃO NO BANCO DE DADOS
			problem_save('problem_pair_evaluation', $pe);

			$url_problem = new moodle_url('/mod/problem/view.php', array('id' => $id));
			echo '<br /><br /><a href="'.$url_problem.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
			
			break;

		default:
			# code...
			break;
	}
}

echo $OUTPUT->footer();
?>