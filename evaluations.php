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

$id = optional_param('id', 0, PARAM_INT);
$p  = optional_param('p', 0, PARAM_INT);

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

$measurer = optional_param('measurer', 0, PARAM_INT) ? optional_param('measurer', 0, PARAM_INT) : $USER->id;

//add_to_log($course->id, 'problem', 'view', "view.php?id={$cm->id}", $problem->name, $cm->id);

// Print the page header

$PAGE->set_url('/mod/problem/evaluations.php', array('id' => $cm->id, 'measurer' => $measurer));
$PAGE->set_title(format_string($problem->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// PEGA OS JAVASCRIPTs E CSSs REQUERIDOS PARA A PÁGINA
$PAGE->requires->css('/mod/problem/css/bootstrap.min.css');
$PAGE->requires->css('/mod/problem/css/bootstrap-datetimepicker.min.css');
$PAGE->requires->css('/mod/problem/css/bootstrap-editor.css');
$PAGE->requires->css('/mod/problem/css/style.css');
$PAGE->requires->css('/mod/problem/css/tagmanager.css');

$PAGE->requires->js('/mod/problem/js/jquery-1.8.3.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap.min.js', true);
$PAGE->requires->js('/mod/problem/js/wysihtml5-0.3.0.min.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap-editor.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap-editor-pt-BR.js', true);
$PAGE->requires->js('/mod/problem/js/bootstrap-datetimepicker.js', true);
$PAGE->requires->js('/mod/problem/js/locales/bootstrap-datetimepicker.pt-BR.js', true);
$PAGE->requires->js('/mod/problem/js/scripts.js', true);
$PAGE->requires->js('/mod/problem/js/tagmanager.js', true);
$PAGE->requires->js('/mod/problem/js/typehead.js', true);

// Output starts here
echo $OUTPUT->header();

?>
<div class="container-fluid">

  
  <div class="row">
    <div class="col-md-12">
      <div class="btn-group btn-breadcrumb">
        <a href="view.php?id=<?php echo $cm->id; ?>" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
        <!--SE FOR FACILITADOR-->
        <?php 
        if(problem_is_enrolled($context, "editingteacher")){
          echo '<a href="groups.php?id=' . $cm->id . '" class="btn btn-default">Grupos</a>';
        } 
        ?>
        <!--SE FOR ESTUDANTE-->
        <?php 
        if(problem_is_enrolled($context, "student")){
          echo '<a href="groups.php?id=' . $cm->id . '" class="btn btn-default">Grupo</a>';
          echo '<a href="sessions.php?id=' . $cm->id . '" class="btn btn-default">Sessões</a>';
          echo '<a href="evaluations.php?id=' . $cm->id . '" class="btn btn-default">Avaliação de pares</a>';
          echo '<a href="userprofile.php?id=' . $cm->id . '" class="btn btn-default">Meu perfil</a>';
        }
        ?>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
      <br />
      <!-- ################################################################## -->
      <?php 
        if(problem_is_enrolled($context, "student")){   # SE FOR ESTUDANTE 
          if($groupid == null){
            $groupid = groups_get_activity_group($cm);

            if ($problem->finished) {                    # SE O PROBLEMA FOI FINALIZADO
              # pega o id do grupo e seus membros
              $members = groups_get_members($groupid, $fields='u.*', $sort='lastname ASC');
      ?>

      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Avaliação de pares</h3>
        </div>
        <div class="panel-body">
          
          <form role="form" action="studentactions.php" method="POST">
            <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
            <input id="action" name="action" type="hidden" value="pair_evaluation">
            <input id="groupid" name="groupid" type="hidden" value="<?php echo $groupid; ?>">
            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">

            <div class="form-group">

              <table class="table">
                <thead>
                  <tr>
                    <th>Aluno</th>
                    <th>Nota</th>
                  </tr>
                </thead>
                <tbody>
      	      		<?php 
            				# pega a avaliação
            				$evaluation = $DB->get_record('problem_pair_evaluation', array('measurer' => $USER->id, 'problem_group' => $groupid));
            				# se a avaliação existe
                    if($evaluation) {
                      #pega os dados da avaliação
            					$measures = $DB->get_records('problem_evaluation_measured', array('evaluation' => $evaluation->id));
            				}

            				# exibe o formulário
                    foreach ($members as $member) {

                      #só exibe o formulário para estudantes
                      if(problem_is_enrolled($context, "student", $member->id)){
                          echo '<tr>';
                          echo '<td>'.$member->firstname.'</td>';

                          # se existe alguém já avaliado nesse grupo
                          if (!empty($measures)) {
                            foreach ($measures as $measure) {
                              if($measurer == $USER->id)
                                echo '<td><input type="text" name="points['.$member->id.']" class="form-control" value="' . $measure->points . '"></td>';
                              else
                                echo '<td><input type="text" name="points['.$member->id.']" class="form-control" value="' . $measure->points . '" disabled="disabled"></td>';
                            }
                          } else {
                              if($measurer == $USER->id)
                                echo '<td><input type="text" name="points['.$member->id.']" class="form-control" value="0,0"></td>';
                              else
                                echo '<td><input type="text" name="points['.$member->id.']" class="form-control" value="0,0" disabled="disabled"></td>';
                          }

                          echo '</tr>';
                      }
                    }
          				?>
                </tbody>
              </table>
              <hr /><textarea rows="6" class="textarea form-control" placeholder="Comentários" name="description" <?php if($measurer != $USER->id) echo 'disabled="disabled"';?>><?php echo $evaluation->description; ?></textarea>
              <hr /><button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>

      <?php }} else { # se o problema não foi finalizado ?>
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Avaliação de pares</h3>
        </div>
        <div class="panel-body">
          <div class="alert alert-danger">
            <span class="glyphicon glyphicon-info-sign"></span> 
            Você não pode realizar a avaliação de pares. Aguarde a conclusão do problema.
          </div>
        </div>
      </div>
      <?php } ?>
    <?php } ?>
      <!-- ################################################################## -->

      <!-- ################################################################## -->
      
      <?php 
        # se usuário for facilitador
        if(problem_is_enrolled($context, "editingteacher")){
          # pega o id do grupo e seus membros
          $groupid = required_param('groupid', PARAM_INT);

          $PAGE->set_url('/mod/problem/evaluations.php', array('id' => $cm->id, 'measurer' => $measurer, 'groupid' => $groupid));

          $members = groups_get_members($groupid, $fields='u.*', $sort='lastname ASC');

          # pega a avaliação
          $evaluation = $DB->get_record('problem_pair_evaluation', array('measurer' => $measurer, 'problem_group' => $groupid), '*');
          # se a avaliação existir

          if($evaluation) {
            # pega os dados da avaliação
            $measures = $DB->get_records('problem_evaluation_measured', array('evaluation' => $evaluation->id));
          
      ?>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Avaliação de pares (Avaliador)</h3>
        </div>
        <div class="panel-body">
          <?php 
            echo '<div class="well">DESCRIÇÃO'.$evaluation->description.'</div>'; 
          ?>
          <table class="table">
            <thead>
              <tr>
                <th>Aluno</th>
                <th>Nota</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($members as $member) {
                  if(problem_is_enrolled($context, "student", $member->id)){
                      echo '<tr>';
                      echo '<td>'.$member->firstname.' '.$member->lastname.'</td>';
                      foreach ($measures as $measure) {
                        if($measure->measured == $member->id)
                          echo '<td>'.$measure->points.'</td>';
                      }
                      echo '</tr>';
                  }
                }
              ?>
            </tbody>
          </table>
          <hr />
          <p><?php echo $evaluation->evaluation; ?></p>
        </div>
      </div>
      <?php }} ?>

      <!-- ################################################################## -->

    </div>
  </div>
</div>

<?php
// Finish the page
echo $OUTPUT->footer();