<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);

if ($acao =='inserir') {
     $consulta->PDOInserir("ZPortarePerfil", "(nome)", "('".$nome."')");
     echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortarePerfil", "nome='".$nome."'", "id = ".$id);

    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php\')
                $("#modalCadastro").modal("hide");
              </script>';

} elseif ($acao == 'excluir') {
    $id = $_POST['id'];

    # Tem que checar se esta seguradora não faz parte de um cliente
    $checa = $consulta->PDOQtderegistro("ZPortareLogin", "idperfil = " . $id);
    if ($checa>0) {
        echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Esta PERFIL não pode ser excluído por apresentar usuário vinculados!
              </div>';
    } else {
        $consulta->PDOExcluir("ZPortarePerfil", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    }

}