<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include(dirname(__FILE__).'/head.php');

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
          echo '<a href="sessions.php?id=' . $cm->id . '" class="btn btn-default">Sessões</a>';
          echo '<a href="evaluations.php?id=' . $cm->id . '" class="btn btn-default">Avaliação de pares</a>';
          echo '<a href="userprofile.php?id=' . $cm->id . '" class="btn btn-default">Meu perfil</a>';
        }
        ?>
      </div>
    </div>
  </div>

  <br />
    
  <?php if(problem_is_enrolled($context, "editingteacher")){ ?>
  <div class="row">
    <div class="col-md-12">
      	<ul id="myTab" class="nav nav-tabs">
			<li class="active"><a href="#vinc" data-toggle="tab">Grupos vinculados ao problema <span class="badge"><?php echo $qtn_groups_enrolled; ?></span></a></li>
		    <li><a href="#nvinc" data-toggle="tab">Grupos não vinculados ao problema <span class="badge"><?php echo $qnt_groups_not_enrolled; ?></span></a></li>
		</ul>
    </div>
  </div>
  <?php } ?>

	<div class="row">
  	<div class="col-md-12">

      <?php
      // VERIFICA SE USUÁRIO TEM PERMISSÃO PARA GERIR GRUPOS
      if(problem_is_enrolled($context, "editingteacher")){

        $groups = groups_get_all_groups($course->id);
        $groups_enrolled = get_problem_groups($problem->id);
        

        $qtn_groups_enrolled = count($groups_enrolled);
        $qnt_groups_not_enrolled = count($groups) - count($groups_enrolled);

      ?>
  		<div id="myTabContent" class="tab-content">
    		<div class="tab-pane fade in active" id="vinc">
          <br />
    			<div class="panel-group">

            <?php
                foreach ($groups as $group) {
                  if(in_array_field($group->id, 'groupid', $groups_enrolled)){
                    $url_form = new moodle_url('/mod/problem/form_file.php');
                    $url_form->param('id',$cm->id);
                    $url_form->param('groupid',$group->id);
                    $group->members = groups_get_members($group->id, $fields='u.*', $sort='lastname ASC');

                    $panel_config->title = $group->name;
                    $panel_config->buttons[1] = '<a href="sessions.php?id='.$cm->id.'&groupid='.$group->id.'" class="btn btn-warning btn-sm">Ver sessões</a>';
                    $panel_config->buttons[2] = '<a href="'.$url_form.'" class="btn btn-primary btn-sm">Solução</a>';
                    $panel_config->buttons[3] = '<a href="teacheractions.php?id='.$cm->id.'&groupid='.$group->id.'&action=unlink_group&url_local='.urlencode($PAGE->url).'" class="btn btn-danger btn-sm">Desvincular grupo do problema</a>';
                    $panel_config->body = '<ol>';

                    foreach($group->members as $member){
                      if(problem_is_enrolled($context, "student", $member->id))
                        $panel_config->body .= '<li>' . $member->firstname . ' ' . $member->lastname . ' <a href="evaluations.php?id=' . $id . '&measurer=' . $member->id . '&groupid=' . $group->id . '" class="btn btn-default">Avaliação</a> <a href="userprofile.php?id=' . $id . '&userid=' . $member->id . '" class="btn btn-primary">Perfil</a></li>';
                    }

                    $panel_config->body .= '</ol>';

                    echo problem_create_panel($panel_config);
                  }
                }
            ?>
					</div>
				</div>
        <div class="tab-pane fade" id="nvinc">
          <br />
          <div class="panel-group">

            <?php
                foreach ($groups as $group) {
                  if(!in_array_field($group->id, 'groupid', $groups_enrolled)){
                    $group->members = groups_get_members($group->id, $fields='u.*', $sort='lastname ASC');

                    $panel_config->title = $group->name;
                    $panel_config->buttons[1] = '<a href="teacheractions.php?id='.$cm->id.'&groupid='.$group->id.'&action=link_group&url_local='.urlencode($PAGE->url).'" class="btn btn-success btn-sm">Vincular grupo ao problema</a>';
                    $panel_config->buttons[2] = '';
                    $panel_config->body = '<ol>';

                    foreach($group->members as $member){
                      if(problem_is_enrolled($context, "student", $member->id))
                        $panel_config->body .= '<li>' . $member->firstname . ' ' . $member->lastname . '</li>';
                    }

                    $panel_config->body .= '</ol>';

                    echo problem_create_panel($panel_config);
                  }
                }
            ?>
          </div>
        </div>
			</div>
      <?php
        } else if(problem_is_enrolled($context, "student")){
          if($groupid != 0) {
            if($group = groups_get_group($groupid, $fields='*', $strictness=IGNORE_MISSING)){
              $group->members = groups_get_members($groupid, $fields='u.*', $sort='lastname ASC');

              $panel_config->title = $group->name;
              $panel_config->body = '<ol>';

              foreach($group->members as $member){
                if(problem_is_enrolled($context, "student", $member->id))
                  $panel_config->body .= '<li>' . $member->firstname . ' ' . $member->lastname . ' <a href="userprofile.php?id=' . $id . '&userid=' . $member->id . '" class="btn btn-primary">Perfil</a></li>';
              }

              $panel_config->body .= '</ol>';

              echo problem_create_panel($panel_config);
            }
          } else {
            error("Parâmetro não encontrado: Id do grupo!");
          }
        }
      ?>

		</div>
	</div>

</div>


<?php
// $groupid = groups_get_activity_group($cm);
// groups_group_exists($groupid)
// groups_is_member($groupid, $userid=null)

// Finish the page
echo $OUTPUT->footer();