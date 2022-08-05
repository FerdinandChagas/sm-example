<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$group = get_group(groups_get_activity_group($cm),$problem->id);

$final_evaluation = get_evaluationByMeasured($group->problemgroup->id, $USER->id);

if(problem_is_enrolled($context, "student")){

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
          <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Avaliação final</h3>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th>Meta de aprendizagem</th>
                <th>Valor</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach ($final_evaluation->evaluations as $ev) {
                  echo '<tr>';
                  echo '<td>'.$ev->feature->description.'</td>';
                  echo '<td>'.$ev->value.'</td>';
                  echo '</tr>';
                }
              ?>
            </tbody>
          </table>
          <hr />
          <?php echo "<strong>Avaliação do grupo:</strong> ".$final_evaluation->evaluation; ?>
              
        </div>
      </div>
    </div>
  </div>
</div>

<?php
}
// Finish the page
echo $OUTPUT->footer();