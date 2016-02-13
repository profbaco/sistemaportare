<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$idkm = $_REQUEST['idkm'];
?>
<script src="theme/default/js/functions.js"></script>

<table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th>Cliente</th>
        <!--<th>Total Km</th>
        <th>Dt Saída</th>
        <th>Combustível</th>
        <th>Volume</th>-->
        <th>
            <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Novo Registro" onclick="abrirModalExtra('inserir', 0 , 'view/<?php echo $path; ?>/formclientes.php')">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $condicao = "A.idcliente = B.CODCFO AND A.idcombustivel = C.id AND B.CODCOLIGADA IN (0,5) AND A.idkm = " . $idkm;
    $dados = $consulta->PDOSelecionar("A.id, A.idcliente, B.CODCFO, B.nome, (A.kmasfalto+A.kmchao) totalkm, CONVERT(VARCHAR(15), A.datasaida, 103) datasaida, C.descricao combustivel, A.volume",
        "ZPortareKmClientes A, FCFO B, ZPortareCombustivel C", $condicao, 0, 100, "B.nome");
    $d = new ArrayIterator($dados);
    while ($d->valid()){
        $m = $d->current();
        echo '<tr id="'.$path.$m->id.'">
                                <td>'.$m->CODCFO.' - '.$m->nome.'</td>
                                <!--<td>'.number_format($m->totalkm, 0, ',', '.').'</td>
                                <td>'.$m->datasaida.'</td>
                                <td>'.$m->combustivel.'</td>
                                <td>'.number_format($m->volume, 2, ',', '.').'</td>-->
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-success btn-xs" rel="tooltip" title="Editar" onclick="abrirModalExtra(\'editar\', '.$m->id.' , \'view/'.$path.'/formclientes.php\')">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-warning btn-xs" rel="tooltip" title="Despesas" onclick="CarregarDespesasCliente('.$idkm.' , \''.$m->idcliente.'\')">
                                        <i class="glyphicon glyphicon-usd"></i>
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