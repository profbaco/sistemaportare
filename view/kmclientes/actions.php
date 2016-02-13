<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$kmchegada = (empty($_REQUEST['kmchegada'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['kmchegada']));
$idkm = filter_input(INPUT_POST, 'idkm', FILTER_SANITIZE_NUMBER_INT);
$idcliente = filter_input(INPUT_POST, 'idcliente', FILTER_SANITIZE_STRING);
$origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_STRING);
$destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_STRING);

$idcombustivel = filter_input(INPUT_POST, 'idcombustivel', FILTER_SANITIZE_NUMBER_INT);
$kmchao = (empty($_REQUEST['kmchao'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['kmchao']));
$kmasfalto = (empty($_REQUEST['kmasfalto'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['kmasfalto']));
$volume = (empty($_REQUEST['volume'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['volume']));
$horasaida = (empty($_REQUEST['horasaida'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['horasaida']));
list($dias,$mess,$anos)=explode("/", $_REQUEST['datasaida']);
$datasaida = $anos.'-'.$mess.'-'.$dias;
list($diac,$mesc,$anoc)=explode("/", $_REQUEST['datachegada']);
$datachegada = $anoc.'-'.$mesc.'-'.$diac;

if ($acao =='inserir') {
    $data = date('Y-m-d H:i:s');
    $consulta->PDOInserir("ZPortareKmClientes",
        "(idkm, idcliente, idcombustivel, kmchao, kmasfalto, horasaida, datachegada, datasaida, volume, valorkm, origem, destino, kmchegada)",
        "(" . $idkm . ", '" . $idcliente . "', " . $idcombustivel . "," . $kmchao . ", " . $kmasfalto . ", '" . $horasaida . "', '" . $datachegada . "', '" . $datasaida . "', " . $volume . ", 0, '" . $origem . "', '" . $destino . "', ".$kmchegada.")");

    echo '<script language="javascript">
                $("#modalExtra").modal("hide");
                $("#verClientes").load("view/kmclientes/verclientes.php", {idkm:' . $idkm . '});
                $("#verDespesas").html("");
              </script>';

} elseif ($acao =='editar') {
    $consulta->PDOEditar("ZPortareKmClientes",
        "idcliente = '".$idcliente."', idcombustivel = ".$idcombustivel.", kmchao=" . $kmchao . ", kmasfalto=" . $kmasfalto . ",
          horasaida='" . $horasaida . "', datachegada='" . $datachegada . "', datasaida='" . $datasaida . "',
          volume=" . $volume . ", origem='" . $origem . "', destino='" . $destino . "', kmchegada = ".$kmchegada, "id = " . $id);
    echo '<script language="javascript">
                $("#modalExtra").modal("hide");
                $("#verClientes").load("view/kmclientes/verclientes.php", {idkm:' . $idkm . '});
                $("#verDespesas").html("");
              </script>';

} elseif ($acao == 'excluir') {
    $id = $_POST['id'];

    if ($_REQUEST['quem']=='desp'){
        $consulta->PDOExcluir("ZPortareKmDespesas", "id = ".$id);

        echo '<script language="javascript">
                $("#'.$path.$id.'").remove();
                $("#modalExcluir").modal("hide");
            </script>';
    } else {
        # Tem que checar se esta seguradora não faz parte de um cliente
        $checa = $consulta->PDOSelecionar("idcliente", "ZPortareKmClientes", "id = ".$id, 0, 10, "idcliente");
        $d = new ArrayIterator($checa);
        $valor = $d->current();

        $consulta->PDOExcluir("ZPortareKmDespesas", "idcliente = '".$valor->idcliente."' and agregado=4");
        $consulta->PDOExcluir("ZPortareKmClientes", "id = ".$id);

            echo '<script language="javascript">
                $("#'.$path.$id.'").remove();
                $("#modalExcluir").modal("hide");
            </script>';
    }

} elseif ($acao == 'inserirdespesa') {
    $valor = (empty($_REQUEST['valor'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['valor']));
    $periododiario = $_REQUEST['periododiario'];
    $iddespesa = $_REQUEST['iddespesa'];
    $calculado = $valor/$periododiario;

    $consulta->PDOInserir("ZPortareKmDespesas",
        "(idkm, iddespesa, idcaminhao, idcliente, periododiario, valor, calculado, agregado)",
        "(".$idkm.", '".$iddespesa."', '--', '".$idcliente."', ".$periododiario.", ".$valor.", '".$calculado."', 4)");

    echo '<script language="javascript">
            $("#modalExtra").modal("hide");
            CarregarDespesasCliente('.$idkm.' , \''.$idcliente.'\');
        </script>';

}