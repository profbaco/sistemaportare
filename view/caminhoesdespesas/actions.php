<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$acao = $_REQUEST['acao'];
$placa = filter_input(INPUT_POST, 'placa', FILTER_SANITIZE_STRING);
$iddespesa = filter_input(INPUT_POST, 'iddespesa', FILTER_SANITIZE_NUMBER_INT);
$valor = (empty($_REQUEST['valor'])) ? 0 : str_replace(",", ".", str_replace(".","",$_REQUEST['valor']));
$periododiario = filter_input(INPUT_POST, 'periododiario', FILTER_SANITIZE_NUMBER_INT);

if ($acao =='inserir') {
     $consulta->PDOInserir("ZPortareCaminhoesdespesas", "(placa, iddespesa, valor, periododiario)", "('".$placa."', ".$iddespesa.", ".$valor.", ".$periododiario.")");
     echo '<script language="javascript">
                CarregarDespesas(\''.$placa.'\');
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortareCaminhoesdespesas", "iddespesa='".$iddespesa."', valor='".$valor."', periododiario='".$periododiario."'", "id = ".$id);

    echo '<script language="javascript">
                CarregarDespesas(\''.$placa.'\');
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'excluir') {
    $id = $_POST['id'];

    # Tem que checar se esta seguradora não faz parte de um cliente
    /*$checa = $consulta->PDOQtderegistro("clientes", "idseguradora = " . $id);
    if ($checa>0) {
        echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Esta seguradora não pode ser excluída por apresentar clientes vinculados!
              </div>';
    } else {*/
        $consulta->PDOExcluir("ZPortareCaminhoesdespesas", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    #}

}