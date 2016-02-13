<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$pagina = $_REQUEST['pagina'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
$codconta = filter_input(INPUT_POST, 'codconta', FILTER_SANITIZE_STRING);
$considerar = filter_input(INPUT_POST, 'considerar', FILTER_SANITIZE_STRING);
$tipodespesa = filter_input(INPUT_POST, 'tipodespesa', FILTER_SANITIZE_STRING);
$valorconta = str_replace(",", ".", str_replace(".", "", $_REQUEST['valorconta']));

if ($acao =='inserir') {
    #Checando se o código já existe
    $qtde = $consulta->PDOQtderegistro("ZPortarePlanocontas", "codigo = '".$codigo."'");
    if ($qtde>0) {
        echo '<script>
                jQuery("#alerta").show();
              </script>';
    } else {
        $consulta->PDOInserir("ZPortarePlanocontas", "(codconta, codigo, considerar, tipodespesa, valorconta)", "('" . $codconta . "', '" . $codigo . "', '" . $considerar . "', '" . $tipodespesa . "', " . $valorconta . ")");
        echo '<script language="javascript">
                carregaPagina(\'view/' . $path . '/list.php\');
                jQuery("#alerta").hide();
                $("#modalCadastro").modal("hide");
              </script>';
    }

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortarePlanocontas", "codconta='".$codconta."', codigo='".$codigo."', considerar='".$considerar."', tipodespesa='".$tipodespesa."', valorconta=".$valorconta, "id = ".$id);
    echo '<script language="javascript">
                carregaPagina(\'view/'.$path.'/list.php?pagina='.$pagina.'\');
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
        $consulta->PDOExcluir("ZPortarePlanocontas", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    #}

}