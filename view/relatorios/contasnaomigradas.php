<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$pc = (empty($_REQUEST['pagina'])) ? "1" : $_REQUEST['pagina'];
?>

<script src="theme/default/js/functions.js"></script>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Contas não Migradas
            </strong>
        </h4>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($_POST['acao'])) $total_reg = 120; // número de registros por página
            else $total_reg = 150;

            $start = ($pc==1) ? 0 : ($pc-1) * $total_reg;
            $end = $start + $total_reg;

            $condicao = "1=1";
            # Aqui virá os filtros
            /*foreach ($_POST as $chave => $valor) {
                if(($chave<>'acao') and ($chave<>'tipo')){
                    if($valor<>'') {
                        if ((str_replace("_", ".", $chave)=='A.id') or (str_replace("_", ".", $chave)=='A.idservico') or (str_replace("_", ".", $chave)=='A.idcliente') or (str_replace("_", ".", $chave)=='A.idseguradora') or (str_replace("_", ".", $chave)=='A.status')) {
                            $condicao .= " and ". str_replace("_", ".", $chave). " = ".$valor;
                        } else {
                            $condicao .= " and ". str_replace("_", ".", $chave). " like '%".$valor."%'";
                        }
                    }
                }
            }
            //echo $condicao;*/

            $dados = $consulta->PDOSelecionar("CODCONTA, DESCRICAO", "(
                        SELECT CODCONTA, DESCRICAO FROM CCONTA
                        WHERE CODCONTA NOT IN (SELECT X.codconta FROM ZPortarePlanocontas X)
                        AND ANALITICA = 1
                        AND CODCONTA LIKE '3%'
                        UNION
                        SELECT CODCONTA, DESCRICAO FROM CCONTA
                        WHERE CODCONTA NOT IN (SELECT X.codconta FROM ZPortarePlanocontas X)
                        AND ANALITICA = 1
                        AND CODCONTA LIKE '4%'
                    ) AS TABELA", $condicao, ($start+1), $end, "CODCONTA");
            $d = new ArrayIterator($dados);
            $tr = $consulta->PDOQtderegistro("(
                        SELECT CODCONTA, DESCRICAO FROM CCONTA
                        WHERE CODCONTA NOT IN (SELECT X.codconta FROM ZPortarePlanocontas X)
                        AND ANALITICA = 1
                        AND CODCONTA LIKE '3%'
                        UNION
                        SELECT CODCONTA, DESCRICAO FROM CCONTA
                        WHERE CODCONTA NOT IN (SELECT X.codconta FROM ZPortarePlanocontas X)
                        AND ANALITICA = 1
                        AND CODCONTA LIKE '4%'
                    ) AS TABELA", $condicao); // Total de registros

            $tp = ceil($tr / $total_reg); // Total de registros na página

            while ($d->valid()){
                $m = $d->current();
                echo '<tr id="'.$path.$m->id.'">
                                <td>'.$m->CODCONTA.'</td>
                                <td>'.$m->DESCRICAO.'</td>
                              </tr>';
                $d->next();
            }
            ?>
            </tbody>
        </table>

        <?php
        $anterior = $pc - 1;
        $proximo = $pc + 1;
        echo '<ul class="pagination pagination-sm pull-right">';
        if (($pc==0) or ($pc==1)) {
            echo '<li class="disabled"><a href="#">&laquo;&laquo;</a></li>
		              <li class="disabled"><a href="#">&laquo;</a></li>';
        } else { //carregaPagina('View/corretoras/list.php')
            echo '<li><a href="javascript:void(0)" onclick="carregaPagina(\'view/'.$path.'/list.php?pagina=1\')">&laquo;&laquo;</a></li>
		              <li><a href="javascript:void(0)" onclick="carregaPagina(\'view/'.$path.'/list.php?pagina='.$anterior.'\')">&laquo;</a></li>';
        }

        $antes = (($pc-4) < 1 ) ? 1 : $pc-4;
        $depois = (($pc+4) > $tp ) ? $tp : $pc + 4;

        for($i=$antes;$i <= $depois;$i++) {
            if ($i==$pc){
                echo '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
            } else {
                echo '<li><a href="javascript:void(0)" onclick="carregaPagina(\'view/'.$path.'/list.php?pagina='.$i.'\')">'.$i.'</a></li>';
            }
        }

        if ($pc==$tp) {
            echo '<li class="disabled"><a href="#">&raquo;</a></li>
		  <li class="disabled"><a href="#">&raquo;&raquo;</a></li>';
        } else {
            echo '<li><a href="javascript:void(0)" onclick="carregaPagina(\'view/'.$path.'/list.php?pagina='.$proximo.'\')">&raquo;</a></li>
		  <li><a href="javascript:void(0)" onclick="carregaPagina(\'view/'.$path.'/list.php?pagina='.$tp.'\')">&raquo;&raquo;</a></li>';
        }

        echo '</ul>';
        ?>
    </div>
</div>