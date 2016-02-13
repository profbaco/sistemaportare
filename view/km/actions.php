<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$status = $_REQUEST['status'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$idusuario = filter_input(INPUT_POST, 'idusuario', FILTER_SANITIZE_NUMBER_INT);
$idcaminhao = filter_input(INPUT_POST, 'idcaminhao', FILTER_SANITIZE_STRING);
$idmotorista = filter_input(INPUT_POST, 'idmotorista', FILTER_SANITIZE_STRING);
$observacao = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_STRING);
$valorcombustivel = str_replace(",", ".", str_replace(".", "", $_REQUEST['valorcombustivel']));
$kmchegada = (empty($_REQUEST['kmchegada'])) ? 0 : $_REQUEST['kmchegada'];
$kmsaida = (empty($_REQUEST['kmsaida'])) ? 0 : $_REQUEST['kmsaida'];
list($diac,$mesc,$anoc)=explode("/", $_REQUEST['dtretornoempresa']);
$dtretornoempresa = $anoc.'-'.$mesc.'-'.$diac;
list($diac,$mesc,$anoc)=explode("/", $_REQUEST['dtsaidaempresa']);
$dtsaidaempresa = $anoc.'-'.$mesc.'-'.$diac;



if ($acao =='inserir') {
    $data = date('Y-m-d H:i:s');
    $consulta->PDOInserir("ZPortareKm",
            "(idusuario, idmotorista, idcaminhao, dtorcamento, observacao, status, valorcombustivel, kmsaida, kmchegada, kmpercorrido, dtretornoempresa, dtsaidaempresa)",
            "(".$idusuario.", '".$idmotorista."', '".$idcaminhao."', '".$data."', '".$observacao."', ".$status.", ".$valorcombustivel.", 0, 0, 0, '".$dtretornoempresa."', '".$dtsaidaempresa."')");
    # A parte onde faz a inserção das despesas se encontram na trigger

    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\');
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortareKm", "idmotorista='".$idmotorista."', idcaminhao='".$idcaminhao."', observacao='".$observacao."', valorcombustivel='".$valorcombustivel."', dtretornoempresa='".$dtretornoempresa."', dtsaidaempresa = '".$dtsaidaempresa."'", "id = ".$id);
    $consulta->PDOEditar("ZPortareCheckList", "idmotorista='".$idmotorista."'", "idkm = ".$id);

    echo '<script language="javascript">
                $("#modalCadastro").modal("hide");
                carregaLinha("km/linha.php", '.$id.');
              </script>';

} elseif ($acao == 'excluir') {
    $id = $_POST['id'];
    #Checando se ele já tem movimento gerado
    $temmov = $consulta->PDOSelecionar("isnull(idmov, 0) idmov, id", "ZPortareKm", "id=".$id, 0, 100, "id");
    $d = new ArrayIterator($temmov);
    $valor = $d->current();
    if ($valor->idmov!=0){
        echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Este movimento não pode ser excluído por ter vínculo ao RM Nucleus!
              </div> &nbsp;';
    } else {
        $consulta->PDOExcluir("ZPortareKmClientes", "idkm = ".$id);
        $consulta->PDOExcluir("ZPortareKmDespesas", "idkm = ".$id);
        $consulta->PDOExcluir("ZPortareCheckList", "idkm = ".$id);
        $consulta->PDOExcluir("ZPortareKmAbastecimentos", "idkm = ".$id);
        $consulta->PDOExcluir("ZPortareKm", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    }

}