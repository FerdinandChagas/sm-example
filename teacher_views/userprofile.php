<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$userid = required_param('userid', PARAM_INT);
$this_user = get_user($userid);

if(problem_is_enrolled($context, "student") || problem_is_enrolled($context, "editingteacher")){
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
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-paperclip"></span> Dados do estudante</h3>
        </div>
        <div class="panel-body">
          <h3>Nome: <?php echo $this_user->name; ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-warning">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-paperclip"></span> Horários preferenciais de estudo</h3>
        </div>
        <div class="panel-body">
          <table class="table table-hover table-condensed table-bordered">
            <thead>
              <tr>
                <th>Horário</th>
                <th>Dom</th>
                <th>Seg</th>
                <th>Ter</th>
                <th>Qua</th>
                <th>Qui</th>
                <th>Sex</th>
                <th>Sab</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Manhã</td>
                <td><?php echo $this_user->prefered_times->sunday{0} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->monday{0} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->tuesday{0} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->wednesday{0} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->thursday{0} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->friday{0} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->saturday{0} == '1' ? 'X' : ''; ?></td>
              </tr>
              <tr>
                <td>Tarde</td>
                <td><?php echo $this_user->prefered_times->sunday{1} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->monday{1} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->tuesday{1} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->wednesday{1} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->thursday{1} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->friday{1} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->saturday{1} == '1' ? 'X' : ''; ?></td>
              </tr>
              <tr>
                <td>Noite</td>
                <td><?php echo $this_user->prefered_times->sunday{2} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->monday{2} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->tuesday{2} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->wednesday{2} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->thursday{2} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->friday{2} == '1' ? 'X' : ''; ?></td>
                <td><?php echo $this_user->prefered_times->saturday{2} == '1' ? 'X' : ''; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Características</h3>
        </div>
        <div class="panel-body">
          <?php if(count($this_user->features) > 0){ ?>
          <table class="table">
            <thead>
              <tr>
                <th>Conhecimento</th>
                <th>Nível</th>
              </tr>
            </thead>
            <tbody>

            <?php 
            foreach ($this_user->features as $feature) {
              echo '<tr>';
              echo '<td>'.$feature->description.'</td>';
              echo '<td>'.$feature->value.'</td>';
              echo '</tr>';
            }
            ?>

            </tbody>
          </table>
          <?php 
            } else {
              echo '<div class="alert alert-danger" role="alert">';
              echo "Nenhuma característica encontrada.";
              echo '</div>';
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
      
<?php
}
// Finish the page
echo $OUTPUT->footer();