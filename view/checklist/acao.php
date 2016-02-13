<?php
if(!isset($_SESSION)) session_start();
$idlogin = $_SESSION['idlogin'];

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$item = (empty($_POST['item'])) ? 0 : 1;
$idcheck = $_POST['idcheck'];
$campo = $_POST['campo'];

echo "UPDATE ZPortareCheckList SET ".$campo." = ".$item.", idusuario=".$idlogin." WHERE id = " . $idcheck;

$consulta->PDOEditar("ZPortareCheckList", $campo."=".$item.", idusuario=".$idlogin, "id=".$idcheck);