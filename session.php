<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(__FILE__).'/head.php');

$group = get_group(required_param('groupid', PARAM_INT),$problem->id);
$sessionid = optional_param("sessionid", 0, PARAM_INT);
foreach ($group->sessions as $s) {
  if($s->id == $sessionid)
    $session = $s;
}
foreach ($group->members as $m) {
  if($session->reporter == $m->id)
    $session->reporter = $m;
  if($session->leader == $m->id)
    $session->leader = $m;
}

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
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-paperclip"></span> Informações</h3>
        </div>
        <div class="panel-body">

          <?php 
            //$url_chat = new moodle_url('/mod/chat/view.php');
            //$url_chat->param('id',$problem->chat);

            $url_chat = new moodle_url('/mod/chat/gui_ajax/index.php');
            $url_chat->param('id',$problem->id);
            $url_chat->param('groupid',$group->id);
            
            echo '<a href="'.$url_chat.'" class="btn btn-lg" target="_blank"><h1 class="glyphicon glyphicon-envelope"></h1><br />Chat</a>';
            if(problem_is_enrolled($context, "editingteacher")){
              if(!$session->finished){
                echo '<a href="session_edit.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn btn-lg"><h1 class="glyphicon glyphicon-pencil"></h1><br />Editar</a>';
                echo '<a href="teacheractions.php?id='.$cm->id.'&action=delete_session&sessionid='.$session->id.'&url_local='.urlencode($PAGE->url).'" class="btn btn-lg"><h1 class="glyphicon glyphicon-trash"></h1><br />Excluir</a>';
                if($session->timestart < time()){
                  echo '<a href="teacheractions.php?id='.$cm->id.'&action=finish_session&sessionid='.$session->id.'&url_local='.urlencode($PAGE->url).'" class="btn btn-lg"><h1 class="glyphicon glyphicon-remove"></h1><br />Finalizar sessão</a>';
                }
              }
            }
          ?>
          
          <hr />
          <strong>Data:</strong> <?php echo date("d/m/Y H:i", $session->timestart); ?><br />
          <strong>Coordenador:</strong> <?php echo $session->leader->name; ?><br />
          <strong>Relator:</strong> <?php echo $session->reporter->name; ?><br />
          <strong>Última sessão: </strong>
          <?php
            if ($session->last == 1) echo '<span class="label label-success">Sim</span><br />';
            else echo '<span class="label label-primary">Não</span><br />';
          ?>
        </div>
      </div>
    </div>



      <!-- ################################################################## -->
      <!--                          SE FOR RELATOR                            -->
      <!-- ################################################################## -->
      <?php if($USER->id == $session->reporter->id && $session->finished){ ?>
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Relatório</h3>
        </div>
        <div class="panel-body">
          <form action="studentactions.php" method="POST" class="form-horizontal" role="form">
            <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
            <input id="groupid" name="groupid" type="hidden" value="<?php echo $groupid; ?>">
            <input id="sessionid" name="sessionid" type="hidden" value="<?php echo $session->id; ?>">
            <input id="action" name="action" type="hidden" value="<?php echo 'add_report'; ?>">
            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
            <h3>Relatório da sessão:</h3>
             
            <textarea rows="10" name="report" class="textarea form-control text-editor" placeholder="Dados do relatório"><?php echo $session->report ?></textarea>
            <hr />

            <div class="col-md-2">
              <button id="button2id" name="button2id" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar dados</button>
            </div>
          </form>
        </div>
      </div>
    </div>
      <?php } ?>
      <!-- ################################################################## -->

      <!-- ################################################################## -->
      <!--                          SE NÃO FOR RELATOR                        -->
      <!-- ################################################################## -->
      <?php if($USER->id != $session->reporter->id){ ?>
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Relatório</h3>
        </div>
        <div class="panel-body">
          <?php if($session->report == "" && $session->finished){ ?>
          <div class="alert alert-danger">
            <span class="glyphicon glyphicon-info-sign"></span> 
            Ainda não foi enviado relatório para esta sessão.
          </div>
          <?php } else { echo $session->report; } ?>
        </div>
      </div>
    </div>
      <?php } ?>
      <!-- ################################################################## -->



      <!-- ################################################################## -->
      <!--                        SE FOR FACILITADOR                          -->
      <!-- ################################################################## -->
      <?php if(problem_is_enrolled($context, "editingteacher") && $session->finished){ ?>
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Avaliação de sessão</h3>
        </div>
        <div class="panel-body">
          <div class="col-md-12">
            <form action="teacheractions.php" method="POST" class="form-horizontal" role="form">
              <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
              <input id="groupid" name="groupid" type="hidden" value="<?php echo $groupid; ?>">
              <input id="sessionid" name="sessionid" type="hidden" value="<?php echo $session->id; ?>">
              <input id="action" name="action" type="hidden" value="evaluate_session">
              <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
              <div class="form-group">
                <label>Coordenador:</label>
                <textarea rows="4" name="leader_evaluation" class="textarea form-control" placeholder="Dados do relatório"><?php echo $session->leader_evaluation; ?></textarea>
              </div>
              <hr />
              <div class="form-group">
                <label>Relator:</label>
                <textarea rows="4" name="reporter_evaluation" class="textarea form-control" placeholder="Dados do relatório"><?php echo $session->reporter_evaluation; ?></textarea>
              </div>
              <div class="form-group">
                <label>Grupo:</label>
                <textarea rows="4" name="group_evaluation" class="textarea form-control" placeholder="Dados do relatório"><?php echo $session->group_evaluation; ?></textarea>
              </div>                  
              <div class="col-md-2">
                <button id="button2id" name="button2id" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar dados</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
      <?php } ?>
      <!-- ################################################################## -->

      <!-- ################################################################## -->
      <!--                        SE FOR ESTUDANTE                          -->
      <!-- ################################################################## -->
      <?php if(problem_is_enrolled($context, "student") && $session->finished){ ?>
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Avaliação de sessão</h3>
        </div>
        <div class="panel-body">
          <strong> Avaliação de líder: </strong>
          <?php echo $session->leader_evaluation != '' ? $session->leader_evaluation : 'Ainda não foi realizada a avalíação de líder!'; ?>
          <hr />
          <strong> Avaliação de relator: </strong>
          <?php echo $session->reporter_evaluation != '' ? $session->reporter_evaluation : 'Ainda não foi realizada a avalíação de relator!'; ?>
          <hr />
          <strong> Avaliação de grupo: </strong>
          <?php echo $session->group_evaluation != '' ? $session->group_evaluation : 'Ainda não foi realizada a avalíação de grupo!'; ?>
        </div>
      </div>
    </div>
      <?php } ?>
      <!-- ################################################################## -->
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