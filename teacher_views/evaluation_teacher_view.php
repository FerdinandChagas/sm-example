<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$measured = get_user(required_param('userid', PARAM_INT));

$problem->goals = get_goals($problem->id);

$group = get_group(required_param('groupid', PARAM_INT),$problem->id);

$evaluation = get_evaluation($group->problemgroup->id, $USER->id);

if(problem_is_enrolled($context, "editingteacher")){

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="btn-group btn-breadcrumb">
        <a href="../view.php?id=<?php echo $cm->id; ?>" class="btn btn-default"><i class="glyphicon glyphicon-home"></i> Voltar ao início</a>
      </div>
    </div>
  </div>
</div>

  <br />

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Avaliação de aluno baseado nas metas de aprendizagem</h3>
        </div>
        <div class="panel-body">
          <h3>Nome do aluno: <?php echo $measured->name; ?></h3>
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th>Meta de aprendizagem</th>
                <th>Valor</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                  foreach ($problem->goals as $goal) {

                    foreach ($evaluation->evaluations as $ev) {
                      if($ev->measured->id == $measured->id && $goal->feature->id == $ev->feature->id)
                        $thisevaluation = $ev;
                    }

                    echo '<tr>';
                    echo '<td>'.$goal->feature->description.'</td>';
                    echo '<td>'.$thisevaluation->value.'</td>';
                    echo '</tr>';
                  }
              ?>
            </tbody>
          </table>
          <hr />
          <?php echo "<strong>Avaliação do grupo:</strong> ".$evaluation->evaluation; ?>
              
        </div>
      </div>
    </div>
  </div>
</div>

<?php
}
// Finish the page
echo $OUTPUT->footer();