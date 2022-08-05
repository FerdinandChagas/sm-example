<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(dirname(__FILE__)).'/head.php');

$group = get_group(required_param('groupid', PARAM_INT),$problem->id);
$sessionid = optional_param("sessionid", 0, PARAM_INT);
foreach ($group->sessions as $s) {
  if($s->id == $sessionid)
    $session = $s;
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

  <div class="row">
    <div class="col-md-12">
      <div id="myTabContent" class="tab-content">



          <br />
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="glyphicon glyphicon-pencil"></span> Editar sessão</h3>
            </div>
            <div class="panel-body">
            <!-- ################################################################## -->
            <!--                        SE FOR FACILITADOR                          -->
            <!-- ################################################################## -->

              <form action="teacheractions.php" method="POST" class="form-horizontal" role="form" >
                <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                <input id="groupid" name="groupid" type="hidden" value="<?php echo $groupid; ?>">
                <input id="sessionid" name="sessionid" type="hidden" value="<?php echo $session->id; ?>">
                <input id="action" name="action" type="hidden" value="<?php echo 'edit_session'; ?>">
                <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                <fieldset>
                  <div class="form-group">
                    <label for="dtp_input1" class="col-md-2 control-label">Data e hora:</label>
                    <div class="input-group date form_datetime col-md-5" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                      <input class="form-control" id="timestart" name="timestart" size="16" type="text" value="<?php echo date("d/m/Y H:i", $session->timestart); ?>" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input1" value="" /><br/>
                  </div>


                  <dl class="dl-horizontal">
                    <dt>Coordenador: </dt>
                    <dd>
                      <select id="leader" name="leader" class="form-control">
                        <?php 
                          foreach($group->members as $member){
                            if(problem_is_enrolled($context, "student", $member->id)){
                              if($member->id != $session->leader)
                                echo '<option value="' . $member->id . '">'.$member->name .'</option>';
                              else
                                echo '<option value="' . $member->id . '" selected="selected">'.$member->name .'</option>';
                            }
                          }
                        ?>
                      </select>
                    </dd>
                    <br />
                    <dt>Relator: </dt>
                    <dd>
                      <select id="reporter" name="reporter" class="form-control">
                        <?php 
                          foreach($group->members as $member){
                            if(problem_is_enrolled($context, "student", $member->id)){
                              if($member->id != $session->reporter)
                                echo '<option value="' . $member->id . '">'.$member->name .'</option>';
                              else
                                echo '<option value="' . $member->id . '" selected="selected">'.$member->name .'</option>';
                            }
                          }
                        ?>
                      </select>
                    </dd>
                    <br />
                    <dt>Última sessão: </dt>
                    <dd>
                      <div class="radio">
                        <label> <input type="radio" name="last" id="last_1" value="1" <?php echo $session->last ? "checked" : ""; ?>> Sim </label>
                      </div>
                      <div class="radio">
                        <label> <input type="radio" name="last" id="last_2" value="0" <?php echo !$session->last ? "checked" : ""; ?>> Não </label>
                      </div>
                    <dd>
                  </dl>
                  <hr />
                  
                  <div class="col-md-8">
                    <button id="button2id" name="button2id" class="btn btn-primary" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar dados</button>
                  </div>
                </fieldset>
              </form>            
            </div>
          </div>

        <!-- ################################################################## -->


      </div>
    </div>
  </div>
</div>
<?php } ?>

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