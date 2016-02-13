<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$agregado = $_REQUEST['agregado'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$idkm = filter_input(INPUT_POST, 'idkm', FILTER_SANITIZE_NUMBER_INT);
$iddespesa = filter_input(INPUT_POST, 'iddespesa', FILTER_SANITIZE_NUMBER_INT);
$periododiario = filter_input(INPUT_POST, 'periododiario', FILTER_SANITIZE_NUMBER_INT);
$valor = (empty($_REQUEST['valor'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['valor']));
$calculado = $valor/$periododiario;

if ($acao =='inserir') {
    $data = date('Y-m-d H:i:s');
    $consulta->PDOInserir("ZPortareKmDespesas",
            "(idkm, iddespesa, idcaminhao, periododiario, valor, calculado, agregado)",
            "(".$idkm.", ".$iddespesa.", '--',".$periododiario.", '".$valor."', ".$calculado.", ".$agregado.")");
    if ($agregado==3) {
        echo '<script language="javascript">
                    $("#modalExtra").modal("hide");
                    $("#tabDespesaAdicional").load("view/kmdespesas/conteudo.php", {idkm: '.$idkm.', agregado: "3"})
                  </script>';
    } else {
        echo '<script language="javascript">
                $("#modalExtra").modal("hide");
                $("#tabDespesaRealizadas").load("view/kmdespesas/conteudo.php", {idkm: '.$idkm.', agregado: "5"})
              </script>';
    }

} elseif ($acao == 'editar') {
    $consulta->PDOEditar("ZPortareKmDespesas", "iddespesa='".$iddespesa."', periododiario='".$periododiario."',  valor='".$valor."', calculado='".$calculado."'", "id = ".$id);

    echo '<script language="javascript">
                $("#modalExtra").modal("hide");
                $("#tabDespesaCaminhao").load("view/kmdespesas/conteudo.php", {idkm: '.$idkm.', agregado: "0,1"})
                $("#tabDespesaFixa").load("view/kmdespesas/conteudo.php", {idkm: '.$idkm.', agregado: "2"})
                $("#tabDespesaAdicional").load("view/kmdespesas/conteudo.php", {idkm: '.$idkm.', agregado: "3"})
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
        $consulta->PDOExcluir("ZPortareKmDespesas", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    #}

}