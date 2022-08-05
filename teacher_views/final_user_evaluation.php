<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
include(dirname(__FILE__).'/head.php');

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
        <a href="view.php?id=<?php echo $cm->id; ?>" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
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
          <form class="form-horizontal" action="teacheractions.php" method="POST">
            <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
            <input id="action" name="action" type="hidden" value="evaluate_user">
            <input id="problem_group" name="problem_group" type="hidden" value="<?php echo $group->problemgroup->id; ?>">
            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
            <input id="measured" name="measured" type="hidden" value="<?php echo $measured->id; ?>">
            <fieldset>
              <table class="table table-bordered table-condensed table-hover">
                <thead>
                  <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                      foreach ($problem->goals as $goal) {

                        foreach ($evaluation->evaluations as $ev) {
                          if($ev->feature->id == $goal->id)
                            $thisevaluation = $ev;
                        }

                        echo '<tr>';
                        echo '<td>'.$goal->feature->description.'</td>';
                        echo '<td>';
                        echo '<select name="level['.$goal->feature->id.']" class="form-control">';
                        echo $thisevaluation->value == 0 ? '<option value="0" selected>0</option>' : '<option value="0">0</option>';
                        echo $thisevaluation->value == 1 ? '<option value="1" selected>1</option>' : '<option value="1">1</option>';
                        echo $thisevaluation->value == 2 ? '<option value="2" selected>2</option>' : '<option value="2">2</option>';
                        echo $thisevaluation->value == 3 ? '<option value="3" selected>3</option>' : '<option value="3">3</option>';
                        echo $thisevaluation->value == 4 ? '<option value="4" selected>4</option>' : '<option value="4">4</option>';
                        echo $thisevaluation->value == 5 ? '<option value="5" selected>5</option>' : '<option value="5">5</option>';
                        echo $thisevaluation->value == 6 ? '<option value="6" selected>6</option>' : '<option value="6">6</option>';
                        echo $thisevaluation->value == 7 ? '<option value="7" selected>7</option>' : '<option value="7">7</option>';
                        echo $thisevaluation->value == 8 ? '<option value="8" selected>8</option>' : '<option value="8">8</option>';
                        echo $thisevaluation->value == 9 ? '<option value="9" selected>9</option>' : '<option value="9">9</option>';
                        echo $thisevaluation->value == 10 ? '<option value="10" selected>10</option>' : '<option value="10">10</option>';
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';

                        unset($thisevaluation);
                      }
                  ?>
                </tbody>
              </table>
              <hr />
              <h3>Avaliação do grupo:</h3>
              <textarea rows="4" name="description" class="textarea form-control"><?php echo $evaluation->evaluation; ?></textarea>
              <hr />
              <div class="col-md-8">
                <button id="button2id" name="button2id" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar avaliação</button>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
}
// Finish the page
echo $OUTPUT->footer();