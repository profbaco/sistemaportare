<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

if ($acao =='inserir') {
     $consulta->PDOInserir("ZPortareCombustivel", "(descricao)", "('".$descricao."')");
     echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortareCombustivel", "descricao='".$descricao."'", "id = ".$id);

    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
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
        $consulta->PDOExcluir("ZPortareCombustivel", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    #}

}