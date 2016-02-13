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

if ($acao =='inserir') {
    $data = date('Y-m-d H:i:s');
    $consulta->PDOInserir("ZPortareKm",
            "(idusuario, idmotorista, idcaminhao, dtorcamento, observacao, status, valorcombustivel)",
            "(".$idusuario.", '".$idmotorista."', '".$idcaminhao."', '".$data."', '".$observacao."', ".$status.", ".$valorcombustivel.")");

    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'editar') {
    if (empty($_POST['senha']))
        $consulta->PDOEditar("ZPortareLogin", "idperfil='".$idperfil."', nome='".$nome."', email='".$email."', ativo='".$ativo."'", "id = ".$id);
    else
        $consulta->PDOEditar("ZPortareLogin", "idperfil='".$idperfil."', nome='".$nome."', email='".$email."', senha='".$senha."', ativo='".$ativo."'", "id = ".$id);

    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
                $("#modalCadastro").modal("hide");
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
        $consulta->PDOExcluir("ZPortareKm", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    }

}