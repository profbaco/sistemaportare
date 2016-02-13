<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$placa = $_REQUEST['placa'];

$pc = (empty($_REQUEST['pagina'])) ? "1" : $_REQUEST['pagina'];
?>
<input type="hidden" id="placa" value="<?php echo $placa; ?>">

<script src="theme/default/js/functions.js"></script>

<div class="panel panel-info">
    <div class="panel-heading">
        <h4><strong>
                Despesas da Placa: <?php echo $placa; ?>
            </strong>
        </h4>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Despesa</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>
                    <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Adicionar" onclick="abrirModal('inserir', 0 , 'view/<?php echo $path; ?>/form.php')">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($_POST['acao'])) $total_reg = 10; // número de registros por página
            else $total_reg = 150;

            $start = ($pc==1) ? 0 : ($pc-1) * $total_reg;
            $end = $start + $total_reg;

            $condicao = "A.id = B.iddespesa AND B.placa = '".$placa."'";
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

            $dados = $consulta->PDOSelecionar("B.id, A.descricao, B.valor, CASE B.periododiario
	WHEN 365 THEN 'Anual'
	WHEN 180 THEN 'Semestral'
	WHEN 90 THEN 'Trimestral'
	WHEN 60 THEN 'Bimestral'
	WHEN 30 THEN 'Mensal'
	WHEN 1 THEN 'Diária'
END AS tipodiaria", "ZPortareDespesas A, ZPortareCaminhoesdespesas B", $condicao, ($start+1), $end, "A.descricao");
            $d = new ArrayIterator($dados);
            $tr = $consulta->PDOQtderegistro("ZPortareDespesas A, ZPortareCaminhoesdespesas B", $condicao); // Total de registros

            $tp = ceil($tr / $total_reg); // Total de registros na página

            while ($d->valid()){
                $m = $d->current();
                echo '<tr id="'.$path.$m->id.'">
                                <td>'.$m->descricao.'</td>
                                <td>'.$m->tipodiaria.'</td>
                                <td>'.number_format($m->valor, 2, ',', '.').'</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-xs" rel="tooltip" title="Editar" onclick="abrirModal(\'editar\', '.$m->id.' , \'view/'.$path.'/form.php\')">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" rel="tooltip" title="Excluir" onclick="modalExcluir(\'view/'.$path.'/actions.php\', '.$m->id.')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
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
            echo '<li><a href="javascript:void(0)" onclick="PaginacaoDespesas(\''.$placa.'\', 1)">&laquo;&laquo;</a></li>
		              <li><a href="javascript:void(0)" onclick="PaginacaoDespesas(\''.$placa.'\', '.$anterior.')">&laquo;</a></li>';
        }

        $antes = (($pc-4) < 1 ) ? 1 : $pc-4;
        $depois = (($pc+4) > $tp ) ? $tp : $pc + 4;

        for($i=$antes;$i <= $depois;$i++) {
            if ($i==$pc){
                echo '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
            } else {
                echo '<li><a href="javascript:void(0)" onclick="PaginacaoDespesas(\''.$placa.'\', '.$i.')">'.$i.'</a></li>';
            }
        }

        if ($pc==$tp) {
            echo '<li class="disabled"><a href="#">&raquo;</a></li>
		  <li class="disabled"><a href="#">&raquo;&raquo;</a></li>';
        } else {
            echo '<li><a href="javascript:void(0)" onclick="PaginacaoDespesas(\''.$placa.'\', '.$proximo.')">&raquo;</a></li>
		  <li><a href="javascript:void(0)" onclick="PaginacaoDespesas(\''.$placa.'\', '.$tp.')">&raquo;&raquo;</a></li>';
        }

        echo '<li><a href="javascript:void(0)" onclick="FecharDespesas()">Fechar</a></li>';

        echo '</ul>';
        ?>

    </div>

</div>