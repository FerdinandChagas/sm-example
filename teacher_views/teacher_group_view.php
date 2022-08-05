<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$group = get_group(optional_param('groupid', 0, PARAM_INT),$problem->id);

if(count($group->sessions) > 0){
  $last = 0;
  foreach($group->sessions as $session){
    if($session->timestart > $last){
      $lastsession = $session;
      $last = $session->timestart;
    }
  }
}

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
  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#group" aria-controls="group" role="tab" data-toggle="tab">Grupo</a></li>
          <li role="presentation"><a href="#sessions" aria-controls="sessions" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-th-list"></i> Sessões</a></li>
        </ul>

        <br />
        
        <div class="tab-content">

          <!-- ################################################################## -->
          <!--                         EXIBIÇÃO DO GRUPO                          -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane active" id="group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Dados do Grupo: <?php echo $group->name; ?></h3>
              </div>
              <div class="panel-body">
                <table class="table table-hover table-condensed table-bordered">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Avaliação final</th>
                      <th>Avaliação de pares</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $i=1;
                      foreach ($group->members as $member) {
                        if(problem_is_enrolled($context, "student", $member->id)){
                          $is_evaluated = $DB->get_records("problem_evaluation_measured", array("measured" => $member->id, "problem_group" => $group->problemgroup->id));
                          echo '<tr>';
                          echo '<td><a href="userprofile.php?id=' . $cm->id . '&userid=' . $member->id . '" clas="btn">' . $member->name .'</a></td>';
                          echo '<td>';
                            if($is_evaluated)
                              echo '<span class="label label-success">Avaliado</span>';
                            else
                              echo '<span class="label label-danger">Não avaliado</span>';
                          echo ' | <a href="final_evaluation.php?id=' . $cm->id.'&userid='.$member->id.'&groupid='.$group->id.'" clas="btn">Avaliar usuário</a></td>';
                          echo '<td><a href="pair_evaluation.php?id=' . $cm->id.'&userid='.$member->id.'&groupid='.$group->id.'" clas="btn">Visualizar avaliação de pares</a></td>';
                          echo '</tr>';
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                        LISTAGEM DE SESSÕES                         -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="sessions">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Dados das sessões</h3>
              </div>
              <div class="panel-body">
                <?php 
                  if($lastsession->finished || count($group->sessions) == 0){
                    if(!$lastsession->last && $group->problemgroup->finished == 0){
                      echo '<a href="session_new.php?id=' . $cm->id . '&groupid=' .$group->id. '" class="btn btn-success btn-lg"><h1 class="glyphicon glyphicon-plus"></h1><br />Criar nova sessão</a><hr />';
                    }
                  }                
                ?>

                <?php if(count($group->sessions) > 0){ ?>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>Data</th>
                      <th>Status</th>
                      <th>Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php 
                    foreach($group->sessions as $session){
                      echo '<tr>';
                      echo '<td>' . date("d/m/Y H:i", $session->timestart).'</td>';
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
                      echo '<a href="session.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Visualizar</a>';
                      if(!$lastsession->last && $group->problemgroup->finished == 0){
                        echo '<a href="teacheractions.php?id='.$cm->id.'&action=delete_session&sessionid='.$session->id.'&url_local='.urlencode($PAGE->url).'" class="btn"  onclick="return confirm(\'Deseja realmente excluir essa sessão?\');"><span class="glyphicon glyphicon-trash"></span> Excluir</a>';
                      }
                      if(!$session->finished){
                        if(!$lastsession->last && $group->problemgroup->finished == 0){
                          echo '<a href="session_edit.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn"><span class="glyphicon glyphicon-pencil"></span> Editar</a>';
                        }
                        if($session->timestart < time()){
                          echo '<a href="teacheractions.php?id='.$cm->id.'&action=finish_session&sessionid='.$session->id.'&url_local='.urlencode($PAGE->url).'" class="btn"  onclick="return confirm(\'Deseja realmente finalizar essa sessão?\');"><span class="glyphicon glyphicon-remove"></span> Finalizar sessão</a>';
                        }
                      }
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }
                  ?>
                  </tbody>
                </table>
                <?php 
                  } else {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo "Nenhuma sessão foi encontrada! Crie a primeira sessão para começar.";
                    echo '</div>';
                  }
                ?>
              </div>
            </div>
          </div>
          <!-- ################################################################## -->


          

        </div>

      </div>
    </div>
  </div><!-- FIM DA EXIBIÇÃO DO GRUPO -->

  <br />
    <!-- ################################################################## -->
    <!--                        SOLUÇÃO DO PROBLEMA                         -->
    <!-- ################################################################## -->

    <?php if($lastsession->last == 1 && $lastsession->finished == 1){ ?>
      <div class="row">
            
        <div class="col-md-12">
          <div class="panel panel-primary">
            <div class="panel-heading"><h3 class="panel-title">Relatório final</h3></div>
            <div class="panel-body">
              <?php if($group->problemgroup->report == ""){ ?>
              <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sign"></span> 
                Ainda não foi enviado relatório para esta sessão.
              </div>
              <?php } else { echo $group->problemgroup->report; } ?>
            </div>
          </div>
        </div>


      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Solução do problema</h3>
          </div>
          <div class="panel-body">
          <?php 
            include(dirname(dirname(__FILE__)).'/form_product.php');

            $maxbytes = $course->maxbytes;

            if (empty($entry->id)) {
              $entry = new stdClass;
              $entry->id = $group->id;
              $entry->cmid = $cm->id;
            }
            
            $maxfiles = 99;                // TODO: add some setting
            $maxbytes = $course->maxbytes; // TODO: add some setting

            
            $attachmentoptions = array('subdirs'=>false, 'maxfiles'=>$maxfiles, 'maxbytes'=>$maxbytes);
            
            $entry = file_prepare_standard_filemanager($entry, 'attachment', $attachmentoptions, $context, 'mod_problem', 'attachment', $entry->id);
            
            $url_form = new moodle_url('/mod/problem/teacher_group_view.php', array('id' => $cm->id, 'groupid' => $group->id));
            $mform = new mod_problem_product_form($url_form, array('current'=>$entry,
                                                 'attachmentoptions'=>$attachmentoptions));

            $entry = file_postupdate_standard_filemanager($entry, 'attachment', $attachmentoptions, $context, 'mod_problem', 'attachment', $entry->id);

            $mform->set_data($entry);
            $mform->display();

          ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  <!-- ################################################################## -->

</div>
<?php
}
echo $OUTPUT->footer();