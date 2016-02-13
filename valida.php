<?php
if(!isset($_SESSION)) session_start();

include_once('dados/config.php');
require_once('dados/classes.php');
$consulta = new banco();

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = md5(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING));

$conta = $consulta->PDOQtderegistro("ZPortareLogin", "email = '".$email."'");

if ($conta > 0) {
    $sql = $consulta->PDOSelecionar("*", "ZPortareLogin", "email = '".$email."'", 0, 10, "id");
    $d = new ArrayIterator($sql);
    $valor = $d->current();
    if ($valor->senha == $senha) {
        if($valor->ativo==0){
            echo '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>'.'Usuário desativado</div> &nbsp;';
        } else {
            $_SESSION['nomelogin'] = $valor->nome;
            $_SESSION['idlogin'] = $valor->id;
            echo 'Por favor, aguarde enquanto é redirecionado!';
            echo "<script language='javascript'>location.href='logado.php';</script>";
            echo '&nbsp;';
        }
    } else {
        echo '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>'.'A senha digitada é errada</div> &nbsp;';
    }

} else {
    echo '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>'.'E-mail não cadastrado</div> &nbsp;';
}