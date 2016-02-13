<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$status = $_REQUEST['status'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

$consulta->PDOEditar("ZPortareKm", "status=".$status, "id = ".$id);

    /*echo '<script language="javascript">
                carregaPagina(\'view/km/list.php\');
                $("#modalCadastro").modal("hide");
              </script>';*/

echo '<script language="javascript">
                $("#modalCadastro").modal("hide");
                carregaLinha("view/km/linha.php", '.$id.');
              </script>';