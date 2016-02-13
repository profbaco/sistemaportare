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
                Cálculo do KM
            </strong>
        </h4>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>RM</th>
                <th>Criado Por</th>
                <th>Motorista</th>
                <th>Caminhão</th>
                <th>Dt Criação</th>
                <th>Dt Saída</th>
                <th>Status</th>
                <th>
                    <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Novo Registro" onclick="abrirModal('inserir', 0 , 'view/<?php echo $path; ?>/form.php')">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-xs" rel="tooltip" title="Filtar" onclick="modalFiltro('view/km/filtro.php')">
                        <i class="glyphicon glyphicon-filter"></i>
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($_POST['acaoFiltro'])) $total_reg = 12; // número de registros por página
            else $total_reg = 250;

            $start = ($pc==1) ? 0 : ($pc-1) * $total_reg;
            $end = $start + $total_reg;

            $condicao = "A.idusuario = B.id AND A.idmotorista = C.CHAPA AND C.CODCOLIGADA = ".$coligada;
            # Aqui virá os filtros
            foreach ($_POST as $chave => $valor) {
                if(($chave<>'acaoFiltro') and ($chave<>'tipo')){
                    if($valor<>'') {
                        if ((str_replace("_", ".", $chave)=='A.id') or (str_replace("_", ".", $chave)=='A.idservico') or (str_replace("_", ".", $chave)=='A.idcliente') or (str_replace("_", ".", $chave)=='A.idseguradora') or (str_replace("_", ".", $chave)=='A.status')) {
                            $condicao .= " and ". str_replace("_", ".", $chave). " = ".$valor;
                        } else {
                            if (str_replace("_", ".", $chave)=='A.dtsaidaempresaInicial') {
                                list($dia, $mes, $ano) = explode("/", $valor);
                                $condicao .= " AND A.dtsaidaempresa >= '".$ano."-".$mes."-".$dia."'";
                            } elseif (str_replace("_", ".", $chave)=='A.dtsaidaempresaFinal') {
                                list($dia, $mes, $ano) = explode("/", $valor);
                                $condicao .= " AND A.dtsaidaempresa <= '".$ano."-".$mes."-".$dia."'";
                            } elseif (str_replace("_", ".", $chave)=='A.dtorcamentoInicial') {
                                list($dia, $mes, $ano) = explode("/", $valor);
                                $condicao .= " AND A.dtorcamento >= '".$ano."-".$mes."-".$dia."'";
                            } elseif (str_replace("_", ".", $chave)=='A.dtorcamentoFinal') {
                                list($dia, $mes, $ano) = explode("/", $valor);
                                $condicao .= " AND A.dtorcamento <= '".$ano."-".$mes."-".$dia."'";
                            } else  {
                                $condicao .= " and ". str_replace("_", ".", $chave). " like '%".$valor."%'";
                            }
                        }
                    }
                }
            }
            #echo $condicao;

            $dados = $consulta->PDOSelecionar("A.id, B.nome, C.NOME motorista, A.idcaminhao, A.idmov, CONVERT(VARCHAR(20), A.dtsaidaempresa, 103) dtsaida,
(CONVERT(VARCHAR(20), A.dtorcamento, 103)+' '+CONVERT(VARCHAR(20), A.dtorcamento, 108)) dataorcamento, A.dtorcamento,
case status
   WHEN 0 THEN 'Montagem'
   WHEN 1 THEN 'Aguardando Resposta'
   WHEN 2 THEN 'Aprovado'
   WHEN 3 THEN 'Em Viagem'
   WHEN 4 THEN 'Concluído'
   WHEN 5 THEN 'Cancelado'
   WHEN 6 THEN 'Em Carregamento'
END AS situacao, A.status, A.kmsaida, A.kmchegada, A.kmpercorrido, A.observacao", "ZPortareKm A, ZPortareLogin B, PFUNC C", $condicao, ($start+1), $end, "A.dtorcamento DESC");
            $d = new ArrayIterator($dados);
            $tr = $consulta->PDOQtderegistro("ZPortareKm A, ZPortareLogin B, PFUNC C", $condicao); // Total de registros

            $tp = ceil($tr / $total_reg); // Total de registros na página

            while ($d->valid()){

                include("linha.php");

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