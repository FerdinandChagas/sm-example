<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(__FILE__).'/head.php');

$groupid = optional_param('groupid', 0, PARAM_INT) ? optional_param('groupid', 0, PARAM_INT) : groups_get_activity_group($cm);
$sessions = get_sessions_by_group($groupid, $problem->id);
if(count($sessions) > 0){
  
  $last = 0;
  foreach($sessions as $session){
    if($session->timestart > $last){
      $lastsession = $session;
      $last = $session->timestart;
    }
  }
}

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
          echo '<a href="sessions.php?id=' . $cm->id . '&groupid=' . $groupid . '" class="btn btn-default">Sessões</a>';
          echo '<a href="evaluations.php?id=' . $cm->id . '" class="btn btn-default">Avaliação de pares</a>';
          echo '<a href="userprofile.php?id=' . $cm->id . '" class="btn btn-default">Meu perfil</a>';
        }
        ?>
      </div>
    </div>
  </div>

</div>
<br />
<?php
/*
if(count($sessions) > 0){

	foreach($sessions as $session){
		if(problem_is_enrolled($context, "editingteacher")){

		}
		
		if(problem_is_enrolled($context, "student")){
			if($session->reporter == $USER->id){
			} else if($session->leader == $USER->id){
			} else {
      }
		}
	}

} else {
	error("Nenhuma sessão foi encontrada!");
}
*/
?>


<div class="container-fluid">

  <div class="row">
    <div class="col-md-12">
      <div id="myTabContent" class="tab-content">

          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-paperclip"></span> Listagem de sessões

                <div class="btn-group pull-right">
                  <?php 
                    if(problem_is_enrolled($context, "editingteacher")){
                      if($lastsession->finished){
                        if(!$lastsession->last){
                          echo '<a href="session_new.php?id=' . $cm->id . '&groupid=' .$groupid. '" id="" name="" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span> Criar nova sessão</a>';
                        }
                      }
                      if(count($sessions) == 0){  
                        echo '<a href="session_new.php?id=' . $cm->id . '&groupid=' .$groupid. '" id="" name="" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span> Criar nova sessão</a>';
                      }                 
                  ?>
                </div>
              </h3>
            </div>
            <div class="panel-body">
              <?php if(count($sessions) > 0){ ?>
              <table class="table">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                 <?php 

                  foreach($sessions as $session){
                    if(problem_is_enrolled($context, "editingteacher")){
                      echo '<tr>';
                      echo '<td>' . date("d/m/Y H:i", $session->timestart) .' ----- '. date("d/m/Y H:i", strtotime($_REQUEST["DateOfRequest"])) .'</td>';
                      echo '<td><span class="">';
                      if($session->finished)
                        echo '<span class="label label-success">Finalizada</span>';
                      else if($session->id == $lastsession->id && $session->timestart < time())
                        echo '<span class="label label-warning">Sessão atual</span>';
                      else 
                        echo '<span class="label label-primary">Próxima sessão</span>';
                      echo '</span></td>';
                      echo '<td>';
                      echo '<div class="btn-group">';
                      echo '<a href="session.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$groupid. '" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Visualizar</a>';
                      if(!$session->finished){
                        echo '<a href="session_edit.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$groupid. '" class="btn"><span class="glyphicon glyphicon-pencil"></span> Editar</a>';
                        echo '<a href="teacheractions.php?id='.$cm->id.'&action=delete_session&sessionid='.$session->id.'&url_local='.urlencode($PAGE->url).'" class="btn"><span class="glyphicon glyphicon-trash"></span> Excluir</a>';
                      }
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }

                     if(problem_is_enrolled($context, "student")){
                      echo '<tr>';
                      echo '<td>' . date("d/m/Y H:i", $session->timestart) .' ----- '. date("d/m/Y H:i", now()) .'</td>';
                      echo '<td><span class="">';
                      if($session->finished)
                        echo '<span class="label label-success">Finalizada</span>';
                      else if($session->id == $lastsession->id && $session->timestart < time())
                        echo '<span class="label label-warning">Sessão atual</span>';
                      else 
                        echo '<span class="label label-primary">Próxima sessão</span>';
                      echo '</span></td>';
                      echo '<td>';
                      echo '<div class="btn-group">';
                      echo '<a href="session.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$groupid. '" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Visualizar</a>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }
                  }
                ?>
                </tbody>
              </table>
              <?php 
                } else {
                  echo "Nenhuma sessão foi encontrada! Crie a primeira sessão para este problema no botão acima.";
                }
              }
              ?>
            </div>
          </div>

        <!-- ################################################################## -->

      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $('.text-editor').wysihtml5({locale: "pt-BR"});

  $('.form_datetime').datetimepicker({
    language:  'pt-BR',
    format: 'dd/mm/yyyy hh:ii',
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    forceParse: 0,
    showMeridian: 1,
    minuteStep: 10,
    startDate: <?php echo '\''.date('Y-m-d H:i').'\''; ?>
  });

</script>




<?php

// Finish the page
echo $OUTPUT->footer();