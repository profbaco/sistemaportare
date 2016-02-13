<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$idgrupo = filter_input(INPUT_POST, 'idgrupo', FILTER_SANITIZE_NUMBER_INT);
$idconta = filter_input(INPUT_POST, 'idconta', FILTER_SANITIZE_NUMBER_INT);

if ($acao =='inserir') {
    # Checando se a mesma já se encontra cadastrada
    $checa = $consulta->PDOQtderegistro("ZPortareDremontagem", "idconta = " . $idconta);
    if ($checa>0) {
        echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Esta conta já se encontra vinculada!
              </div>';
    } else {
        $consulta->PDOInserir("ZPortareDremontagem", "(idgrupo, idconta)", "(".$idgrupo.", ".$idconta.")");
        echo '<script language="javascript">
                    $("#modalExtra").modal("hide");
                    $("#modalConteudo").load("view/dremontagem/list.php", {id: '.$idgrupo.'});
                  </script>';
    }

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortareDregrupo", "codigo='".$codigo."', descricao='".$descricao."'", "id = ".$id);
    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'excluir') {
    $id = $_POST['id'];
        $consulta->PDOExcluir("ZPortareDremontagem", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';

}