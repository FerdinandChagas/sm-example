<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$measured = $DB->get_record("user", array("id" => required_param('userid', PARAM_INT)), 'id, CONCAT(firstname, " ", lastname) as name');

$group = get_group(groups_get_activity_group($cm),$problem->id);

$evaluation = $DB->get_record("problem_pair_evaluation", array("measured" => $measured->id, "measurer" => $USER->id, "problem_group" => $group->problemgroup->id));

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
          <h3>Nome do aluno: <?php echo $measured->name; ?></h3>
          <form action="studentactions.php" method="POST" class="col-md-12">
            <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
            <input id="action" name="action" type="hidden" value="<?php echo 'pair_evaluation'; ?>">
            <input id="problemgroup" name="problemgroup" type="hidden" value="<?php echo $group->problemgroup->id; ?>">
            <input id="measured" name="measured" type="hidden" value="<?php echo $measured->id; ?>">
            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
            <div class="form-group">
              <label>Descrição</label>
              <textarea rows="4" name="description" class="textarea form-control"><?php echo $evaluation->description; ?></textarea>
            </div>
            <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span> Avaliar</button>
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