<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$conteudo = "codtmv='".$_REQUEST['codtmv']."'";
$conteudo .= (!empty($_REQUEST['codloc'])) ? ", codloc='".$_REQUEST['codloc']."'" : ", codloc=NULL";
$conteudo .= (!empty($_REQUEST['codtb1flx'])) ? ", codtb1flx='".$_REQUEST['codtb1flx']."'" : ", codtb1flx=NULL";
$conteudo .= (!empty($_REQUEST['codtb2flx'])) ? ", codtb2flx='".$_REQUEST['codtb2flx']."'" : ", codtb2flx=NULL";
$conteudo .= (!empty($_REQUEST['codtb3flx'])) ? ", codtb3flx='".$_REQUEST['codtb3flx']."'" : ", codtb3flx=NULL";
$conteudo .= (!empty($_REQUEST['codtb4flx'])) ? ", codtb4flx='".$_REQUEST['codtb4flx']."'" : ", codtb4flx=NULL";
$conteudo .= (!empty($_REQUEST['codfilial'])) ? ", codfilial='".$_REQUEST['codfilial']."'" : ", codfilial=NULL";
$conteudo .= (!empty($_REQUEST['codven1'])) ? ", codven1='".$_REQUEST['codven1']."'" : ", codven1=NULL";
$conteudo .= (!empty($_REQUEST['codccusto'])) ? ", codccusto='".$_REQUEST['codccusto']."'" : ", codccusto=NULL";
$conteudo .= (!empty($_REQUEST['idprd'])) ? ", idprd='".$_REQUEST['idprd']."'" : ", idprd=NULL";

$consulta->PDOEditar("ZPortareParametros", $conteudo, "");

    echo '<div class="alert alert-dismissable alert-success">
              <button type="button" class="close" data-dismiss="alert">×</button>
              Registro salvo com sucesso.
          </div>';
