<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$groups = groups_get_user_groups($cm->course, $USER->id);

foreach($groups as $g){
	foreach($g as $gr){ $groupid = $gr; }
}

$group = get_group($groupid,$problem->id);

$final_evaluation = get_evaluationByMeasured($group->problemgroup->id, $USER->id);
if(count($group->sessions) > 0){

  $last = 0;
  foreach($group->sessions as $session){
    if($session->timestart > $last){
      $lastsession = $session;
      $last = $session->timestart;
    }
  }
}

$myprofile = get_user($USER->id);
$myprofile->unknown_words = $DB->get_record("problem_unknown_words", array("problem_group" => $group->problemgroup->id, "userid" => $USER->id));

$problem->features = get_features();
$sep = "";
$features_description = "";
foreach ($problem->features as $feature) {
  $features_description .= $sep."\"".$feature->description."\"";
  $sep = ', ';
}

if(problem_is_enrolled($context, "student")){
?>

<div class="container-fluid">

  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <?php if($group->id){ ?>
          <li role="presentation" class="active"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i> Problema</a></li>
          <li role="presentation"><a href="#group" aria-controls="group" role="tab" data-toggle="tab">Grupo</a></li>
          <li role="presentation"><a href="#sessions" aria-controls="sessions" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-th-list"></i> Sessões</a></li>
          <?php } //Se estiver vinculado a algum grupo para eo problema ?>
          <li role="presentation" <?php if(!$group->id){ echo 'class="active"'; } ?>><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Meu perfil</a></li>
        </ul>

        <br />
        
        <div class="tab-content">
          <?php if($group->id){ ?>

          <!-- ################################################################## -->
          <!--                       EXIBIÇÃO DO PROBLEMA                         -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane active" id="problem">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Dados do problema</h3>
              </div>
              <div class="panel-body">
                <h3><?php echo $problem->name; ?></h3><br />
                <p><?php echo $problem->intro; ?></p><hr />
                <p><strong>Produto final:</strong> <?php echo $problem->product_format; ?></p>
                <p><strong>Áreas de conhecimento:</strong> <?php echo $problem->knowledge_area; ?></p>
              </div>
            </div>

            <div class="panel panel-primary">
              <div class="panel-heading"><h3 class="panel-title">Termos desconhecidos</h3></div>
              <div class="panel-body">
                <p>Cite no campo abaixo, os termos contidos na descrição do problema que você desconhece separados por vírgula:</p>

                <form class="form-horizontal" action="student_views/studentactions.php" method="POST">
                  <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                  <input id="problem_group" name="problem_group" type="hidden" value="<?php echo $group->problemgroup->id; ?>">
                  <input id="action" name="action" type="hidden" value="edit_unknown_words">
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                  <?php 
                    if($myprofile->unknown_words != null){
                      echo '<input id="uwid" name="uwid" type="hidden" value="'.$myprofile->unknown_words->id.'">';
                    }
                  ?>
                  <fieldset>
                    <textarea rows="4" name="unknown_words" class="textarea form-control"><?php echo $myprofile->unknown_words->unknown_words; ?></textarea>
                    <hr />
                    <div class="col-md-8">
                      <button id="button2id" name="button2id" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar termos desconhecidos</button>
                    </div>
                  </fieldset>
                </form>
              </div>
            </div>

            <?php if($group->problemgroup->finished == 1){ ?>

            <div class="panel panel-primary">
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

            <div class="panel panel-primary">
              <div class="panel-heading"><h3 class="panel-title">Solução</h3></div>
              <div class="panel-body">
                <?php 
                include(dirname(dirname(__FILE__)).'/form_file.php');

                  $maxbytes = $course->maxbytes;

                  if (empty($entry->id)) {
                    $entry = new stdClass;
                    $entry->id = $group->id;
                    $entry->definition       = $group->problemgroup->report;
                    $entry->definitionformat = FORMAT_HTML;
                    $entry->cmid = $cm->id;
                    $entry->definitiontrust  = 0;
                  }
                  
                  $maxfiles = 99;                // TODO: add some setting
                  $maxbytes = $course->maxbytes; // TODO: add some setting

                  $definitionoptions = array('trusttext'=>true, 'maxfiles'=>$maxfiles, 'maxbytes'=>$maxbytes, 'context'=>$context, 'subdirs'=>file_area_contains_subdirs($context, 'mod_problem', 'entry', $entry->id));
                  $attachmentoptions = array('subdirs'=>false, 'maxfiles'=>$maxfiles, 'maxbytes'=>$maxbytes);

                  $entry = file_prepare_standard_editor($entry, 'definition', $definitionoptions, $context, 'mod_problem', 'entry', $entry->id);
                  $entry = file_prepare_standard_filemanager($entry, 'attachment', $attachmentoptions, $context, 'mod_problem', 'attachment', $entry->id);
                  
                  $url_form = new moodle_url('/mod/problem/view.php', array('id' => $cm->id, 'groupid' => $group->id));
                  $mform = new mod_problem_file_form($url_form, array('current'=>$entry,
                                                       'definitionoptions'=>$definitionoptions, 
                                                       'attachmentoptions'=>$attachmentoptions));

                  if ($data = $mform->get_data()) {
                    $newgroup = new stdClass;
                    $newgroup->id = $group->problemgroup->id;
                    $newgroup->report = $data->definition_editor['text'];

                    problem_save('problem_group', $newgroup, $url_form);
                  }

                  $entry = file_postupdate_standard_editor($entry, 'definition', $definitionoptions, $context,'mod_problem', 'entry', $entry->id);
                  $entry = file_postupdate_standard_filemanager($entry, 'attachment', $attachmentoptions, $context, 'mod_problem', 'attachment', $entry->id);

                  $mform->set_data($entry);
                  $mform->display();
                ?>
              </div>
            </div>

            <?php } ?>
              


          </div>
          <!-- ################################################################## -->



          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                         EXIBIÇÃO DO GRUPO                          -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Dados do Grupo: <?php echo $group->name; ?></h3>
              </div>
              <div class="panel-body">
                <table class="table table-hover table-condensed table-bordered">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Avaliação de pares</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      foreach ($group->members as $member) {
                        if(problem_is_enrolled($context, "student", $member->id)){
                          echo '<tr>';
                          echo '<td><a href="student_views/userprofile.php?id=' . $cm->id . '&userid=' . $member->id . '" clas="btn">' . $member->name .'</a></td>';
                          echo '<td><a href="student_views/evaluation.php?id=' . $cm->id . '&userid=' . $member->id . '" clas="btn">Avaliar</a> | <a href="student_views/pair_evaluation.php?id=' . $cm->id.'&userid='.$member->id.'&groupid='.$group->id.'" clas="btn">Visualizar avaliação</a></td>';
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
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Dados das sessões</h3>
              </div>
              <div class="panel-body">
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
                      echo '<td>' . date("d/m/Y H:i", $session->timestart) .'</td>';
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
                      echo '<a href="student_views/session.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Visualizar</a>';
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
          <?php } //Se estiver vinculado a algum grupo para eo problema ?>
          <!-- ################################################################## -->
          <!--                        PERFIL DE USUÁRIO                           -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane  <?php if(!$group->id){ echo 'active'; } ?>" id="profile">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">TAREFA</h3>
              </div>
              <div class="panel-body">
                <br><br>
    1 - O que é sala de aula invertida?<br>
    <textarea style="width: 500px; height: 200px"></textarea><br>
    2 - Como pode ser aplicada a sala de aula invertida?<br>
    <textarea style="width: 500px; height: 200px"></textarea><br>
    3 - Para que serve a metodologia de Design?<br>
    <textarea style="width: 500px; height: 200px"></textarea><br>
    <br><br>
    <input type="button" value="ENVIAR">

              </div>
            </div>
             
            
          </div>
          <!-- ################################################################## -->


        </div>

      </div>
    </div>
  </div><!-- FIM DA EXIBIÇÃO DO GRUPO -->
</div>
<script src="https://leaverou.github.io/awesomplete/awesomplete.js"></script>
<script type="text/javascript">

    var goal = document.getElementById("feature_description");
    new Awesomplete(goal, {
      list: [<?php echo $features_description; ?>]
    });

    $('.text-editor').wysihtml5({locale: "pt-BR"});
  
  </script>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php
}
