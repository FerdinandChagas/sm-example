<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$groups = groups_get_all_groups($course->id);
$groups_enrolled = $DB->get_records('problem_group', array('problemid' => $problem->id), '', '*') ;

$qtn_groups_enrolled = count($groups_enrolled);
$qnt_groups_not_enrolled = count($groups) - count($groups_enrolled);

$problem->requirements = get_requirements($problem->id);
$problem->goals = get_goals($problem->id);
$problem->features = get_features();


$sep = "";
$features_description = "";
foreach ($problem->features as $feature) {
  $features_description .= $sep."\"".$feature->description."\"";
  $sep = ', ';
}

if(problem_is_enrolled($context, "editingteacher")){
?>

<div class="container-fluid">

  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>  CRIAR GRUPOS</a></li>
          <li role="presentation"><a href="#tarefas" aria-controls="tarefas" role="tab" data-toggle="tab">TAREFA</a></li>
            <li role="presentation"><a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">REFERÊNCIAS</a></li>
            <li role="presentation"><a href="#aproveitamento" aria-controls="aproveitamento" role="tab" data-toggle="tab">APROVEITAMENTO</a></li>
            <li role="presentation"><a href="#avaliar" aria-controls="avaliar" role="tab" data-toggle="tab">AVALIAR</a></li>
            <li role="presentation"><a href="#feedback" aria-controls="feedback" role="tab" data-toggle="tab">FEEDBACK</a></li>
        </ul>

        <br />
        
        <div class="tab-content">

          <!-- ################################################################## -->
          <!--                       EXIBIÇÃO DO PROBLEMA                         -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane active" id="groups">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">GRUPOS</h3>
              
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>GRUPO</th>
                      <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>GRUPO 1</td><td>
                        <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-list"></span></button>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                    <tr><td>GRUPO 2</td><td>
                        <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-list"></span></button>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                    <tr><td>GRUPO 3</td><td>
                        <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-list"></span></button>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                    <tr><td>GRUPO 4</td><td>
                        <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-list"></span></button>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                    <tr><td>GRUPO 5</td><td>
                        <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-list"></span></button>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                    
                  </tbody>
                </table>
                    <div class="col-md-8">
                      <button class="btn btn-primary" onclick="document.getElementById('add_group').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR GRUPO</button>
                    </div>
                  
                

                

              </div>
              <div id="add_group" class="panel-body" style="display: none">
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>ALUNO</th>
                      <th>MEDIADOR</th>
                      <th>INSERIR NO GRUPO</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>BRUNO HENRIQUE FREITAS CASTRO</td><td style="text-align: center"><input type="checkbox"></td><td style="text-align: center"><input type="checkbox"></td></tr>
                    <tr><td>LARISSA MANOELA ANDRADE</td><td style="text-align: center"><input type="checkbox"></td><td style="text-align: center"><input type="checkbox"></td></tr>
                    <tr><td>LUCIA MARIA SILVA CAVALCANTE</td><td style="text-align: center"><input type="checkbox"></td><td style="text-align: center"><input type="checkbox"></td></tr>
                    <tr><td>MARCUS AUGUSTO OLIVEIRA</td><td style="text-align: center"><input type="checkbox"></td><td style="text-align: center"><input type="checkbox"></td></tr>
                    <tr><td>MARIA CLARA VIEIRA FERNANDES</td><td style="text-align: center"><input type="checkbox"></td><td style="text-align: center"><input type="checkbox"></td></tr>
                    <tr><td>NAYARA KELLY OLIVEIRA CHAVES</td><td style="text-align: center"><input type="checkbox"></td><td style="text-align: center"><input type="checkbox"></td></tr>
                  </tbody>
                </table>

                <form class="form-horizontal" action="teacher_views/teacheractions.php" method="POST">
                  
                    <div class="col-md-8">
                      <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>
                    </div>
                  
                </form>

              </div>
            </div>

          </div>
          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                        LISTAGEM DE GRUPOS                          -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="tarefas">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">TAREFAS</h3>
                </div>
                <div class="panel-body">
                 <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>TAREFA</th>
                      <th>PRAZO</th>
                        <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                      <tr><td>TAREFA 1</td><td>22/04/2017</td><td>
                            <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-search"></span></button>
                            <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                          </td></tr>
                      <tr><td>TAREFA 2</td><td>15/05/2017</td><td>
                            <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-search"></span></button>
                            <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                          </td></tr>
                      <tr><td>TAREFA 3</td><td>22/06/2017</td><td>
                            <button class="btn btn-info" onclick=""><span class="glyphicon glyphicon-search"></span></button>
                            <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                          </td></tr>
                    
                  </tbody>
                </table>
                    
                    <div class="col-md-8">
                      <button class="btn btn-primary" onclick="document.getElementById('add_task').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR TAREFA</button>
                    </div>
                    
                    <br><br><br>
                <div id="add_task" style="display: none">
                <table class="table table-bordered table-condensed table-hover">
                    <tr><td>TAREFA</td><td><input type="text" size=67 name="nome_aula"></td></tr>
                    <tr><td>ARQUIVO</td><td><input type="file"></td></tr>  
                    <tr><td>DATA INÍCIO</td><td><input type="text" size=20 name"data_inicio"></td></tr>
                    <tr><td>DATA FIM</td><td><input type="text" size=20 name="data_fim"></td></tr>
                    <tr><td>ÚLTIMA TAREFA</td><td><input type="radio" value="SIM">  SIM&nbsp;&nbsp;&nbsp;<input type="radio" value="NÃO">  NAO</td></tr>
                    
                  </table>
                    <button class="btn btn-success" onclick="document.getElementById('add_task').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>
                </div>    
              </div>
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="referencias">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">REFERÊNCIAS</h3>
              
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>REFERÊNCIA</th>
                      <th>TAREFA</th>
                      <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>TUCKER, Bill. The flipped classroom. Education next, v. 12, n. 1, 2012.</td><td>TAREFA 1</td><td>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td></tr>
                      <tr><td>HERREID, Clyde Freeman; SCHILLER, Nancy A. Case studies and the flipped classroom. Journal of College Science Teaching, v. 42, n. 5, p. 62-66, 2013.</td><td>TAREFA 1</td><td>
                        <button class="btn btn-success" onclick=""><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger" onclick=""><span class="glyphicon glyphicon-remove"></span></button>
                        </td></tr>
                    
                  </tbody>
                </table>
                    <div class="col-md-8">
                      <button class="btn btn-primary" onclick="document.getElementById('add_ref').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR REFERÊNCIA</button>
                    </div>
                  
                

                

              </div>
              <div id="add_ref" class="panel-body" style="display: none">
                <table class="table table-bordered table-condensed table-hover">
                  <tbody>
                    <tr><td>REFERÊNCIA</td><td><input type="text" size="80"></td></tr>
                    <tr><td>ARQUIVO</td><td><input type="file"></td></tr>  
                    <tr><td>TAREFA</td>
                        <td>
                            <select>
                                <option>TAREFA 1</option>
                                <option>TAREFA 2</option>
                                <option>TAREFA 3</option>
                                <option>TAREFA 4</option>
                            </select></td></tr>  
                  </tbody>
                </table>

                <form class="form-horizontal" action="teacher_views/teacheractions.php" method="POST">
                  
                    <div class="col-md-8">
                      <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>
                    </div>
                  
                </form>

              </div>
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="aproveitamento">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">APROVEITAMENTO</h3>
              
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <table class="table table-bordered table-condensed table-hover">
                      <tr><th>ALUNO</th><th>APROVEITAMENTO</th></tr>
                      <tr><td>DIEGO BRUNO REGES SOUZA</td><td><input type="number"></td></tr>
                      <tr><td>CAIO HENRIQUE FERNANDES</td><td><input type="number"></td></tr>
                      <tr><td>MARIA LUIZA MAIA ANDRADE</td><td><input type="number"></td></tr>
                      <tr><td>LUCIANA MATIAS PINHO OLIVEIRA</td><td><input type="number"></td></tr>
                      <tr><td>PAULO HENRIQUE FEITOSA DUARTE</td><td><input type="number"></td></tr>

                </table>

                <form class="form-horizontal" action="teacher_views/teacheractions.php" method="POST">
                  
                    <div class="col-md-8">
                      <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-refresh"></span> ATUALIZAR</button>
                    </div>
              
                </form>
              </div>
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="avaliar">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">AVALIAÇÃO DE GRUPOS</h3>
                </div>
                <div class="panel-body">
                 <table class="table table-bordered table-condensed table-hover">
                     <tr><td style="width: 21%">TAREFA</td><td>
                      <select>
                                <option>TAREFA 1</option>
                                <option>TAREFA 2</option>
                                <option>TAREFA 3</option>
                                <option>TAREFA 4</option>
                            </select>   
                      </td></tr>
                 </table>
                    
                 <div id="avalia_grupo" style="display: none">
                     <table class="table table-bordered table-condensed table-hover">
                     <tr><th>CONSIDERAÇÕES SOBRE A TAREFA</th><th>GRUPO 1</th></tr>
                     <tr><td colspan="2"><textarea style="width:100%; height: 200px"></textarea></td></tr>
                     <tr><td>NOTA <input type="text" size=30></td></tr>
                     <tr><td><button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-ok"></span> ENVIAR</button></td></tr>
                     </table>
                 </div>    
                 <br><br>    
                 <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>GRUPO</th>
                      <th>NOTA</th>
                      <th>SITUAÇÂO</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                      <tr><td>GRUPO 1</td><td></td><td><span class="btn btn-warning">PENDENTE</span></td>
                          <td>
                            <button class="btn btn-primary" onclick="document.getElementById('avalia_grupo').style.display = 'inherit';"><span class="glyphicon glyphicon-pencil"></span> AVALIAR</button>         
                          </td>
                      
                      </tr>
                      <tr><td>GRUPO 2</td><td>8,0</td><td><span class="btn btn-success">AVALIADO</span></td>
                          <td>
                            <button class="btn btn-primary" onclick="" style="display: none"><span class="glyphicon glyphicon-pencil"></span> AVALIAR</button>         
                          </td>
                      
                      </tr>
                      <tr><td>GRUPO 3</td><td></td><td><span class="btn btn-warning">PENDENTE</span></td>
                          <td>
                            <button class="btn btn-primary" onclick=""><span class="glyphicon glyphicon-pencil"></span> AVALIAR</button>         
                          </td>
                      
                      </tr>
                      <tr><td>GRUPO 4</td><td>8,0</td><td><span class="btn btn-success">AVALIADO</span></td>
                          <td>
                            <button class="btn btn-primary" onclick="" style="display: none"><span class="glyphicon glyphicon-pencil"></span> AVALIAR</button>         
                          </td>
                      
                      </tr>
                      <tr><td>GRUPO 5</td><td>8,0</td><td><span class="btn btn-success">AVALIADO</span></td>
                          <td>
                            <button class="btn btn-primary" onclick="" style="display: none"><span class="glyphicon glyphicon-pencil"></span> AVALIAR</button>         
                          </td>
                      
                      </tr>
                    
                  </tbody>
                </table>
                
                    <br><br><br>
                
              </div>
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="feedback">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">FEEDBACK</h3>
                </div>
                <div class="panel-body">
                 <div id="feedback_aluno" style="display: none">
                     <table class="table table-bordered table-condensed table-hover">
                     <tr><th>CONSIDERAÇÕES SOBRE A TAREFA</th><th>DIEGO BRUNO REGES SOUZA</th>
                         <th><select>
                                <option>TAREFA 1</option>
                                <option>TAREFA 2</option>
                                <option>TAREFA 3</option>
                                <option>TAREFA 4</option>
                            </select>  </th></tr>
                     <tr><td colspan="3"><textarea style="width:100%; height: 200px"></textarea></td></tr>
                     <tr><td colspan="3"><button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.display:'none'; this.form.submit();"><span class="glyphicon glyphicon-ok"></span> ENVIAR</button></td></tr>
                     </table>
                 </div>        
                 <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>ALUNO</th>
                      <th>GRUPO</th>
                      <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                      <tr><td>DIEGO BRUNO REGES SOUZA</td><td>GRUPO 1</td>
                          <td>
                            <button class="btn btn-primary" onclick="document.getElementById('feedback_aluno').style.display = 'inherit';"><span class="glyphicon glyphicon-arrow-right"></span> FEEDBACK</button>         
                          </td>
                      
                      </tr>
                      <tr><td>CAIO HENRIQUE FERNANDES</td><td>GRUPO 1</td>
                          <td>
                            <button class="btn btn-primary" onclick="document.getElementById('feedback_aluno').style.display = 'inherit';"><span class="glyphicon glyphicon-arrow-right"></span> FEEDBACK</button>         
                          </td>
                      
                      </tr>
                      <tr><td>MARIA LUIZA MAIA ANDRADE</td><td>GRUPO 1</td>
                          <td>
                            <button class="btn btn-primary" onclick="document.getElementById('feedback_aluno').style.display = 'inherit';"><span class="glyphicon glyphicon-arrow-right"></span> FEEDBACK</button>         
                          </td>
                      
                      </tr>
                      <tr><td>LUCIANA MATIAS PINHO OLIVEIRA</td><td>GRUPO 1</td>
                          <td>
                            <button class="btn btn-primary" onclick="document.getElementById('feedback_aluno').style.display = 'inherit';"><span class="glyphicon glyphicon-arrow-right"></span> FEEDBACK</button>         
                          </td>
                      
                      </tr>
                      <tr><td>PAULO HENRIQUE FEITOSA DUARTE</td><td>GRUPO 1</td>
                          <td>
                            <button class="btn btn-primary" onclick="document.getElementById('feedback_aluno').style.display = 'inherit';"><span class="glyphicon glyphicon-arrow-right"></span> FEEDBACK</button>         
                          </td>
                      
                      </tr>
                      
                    
                  </tbody>
                </table>
                
                    <br><br><br>
                
              </div>
            </div>

          </div>
          <!-- ################################################################## -->
        </div>

      </div>
    </div>
  </div><!-- FIM DA EXIBIÇÃO DO GRUPO -->

  <br />

</div>
<script src="https://leaverou.github.io/awesomplete/awesomplete.js"></script>
<script type="text/javascript">

var goal = document.getElementById("goal_description");
new Awesomplete(goal, {
  list: [<?php echo $features_description; ?>]
});
var requirement = document.getElementById("requirement_description");
new Awesomplete(requirement, {
  list: [<?php echo $features_description; ?>]
});

</script>
<?php
}