<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$measurer = $DB->get_record("user", array("id" => required_param('userid', PARAM_INT)), 'id, CONCAT(firstname, " ", lastname) as name');

$group = get_group(required_param('groupid', PARAM_INT),$problem->id);

$evaluations = $DB->get_records("problem_pair_evaluation", array("measurer" => $measurer->id, "problem_group" => $group->problemgroup->id));

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
          <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Avaliação de pares</h3>
        </div>
        <div class="panel-body">
          <h3>Avaliador: <?php echo $measurer->name; ?></h3>
          <?php if(count($evaluations)){ ?>
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th>Avaliado</th>
                <th>Avaliação</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach ($evaluations as $evaluation) {
                  $measured = $DB->get_record("user", array("id" => $evaluation->measured), 'id, CONCAT(firstname, " ", lastname) as name');
                  echo '<tr>';
                  echo '<td><strong>'.$measured->name.'</strong></td>';
                  echo '<td>'.$evaluation->description.'</td>'; 
                  echo '<tr>';
                }
                
              ?>
            </tbody>
          </table>
          <?php } else echo 'Este usuário ainda não realizou nenhuma avaliação!'; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
}
// Finish the page
echo $OUTPUT->footer();