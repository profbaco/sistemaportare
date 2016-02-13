<?php
if(!isset($_SESSION)) session_start();

include_once('dados/config.php');
require_once('dados/classes.php');
$consulta = new banco();

$sql = $consulta->PDOSelecionarPadrao("VERSAO", "ZPortareVersion", "1=1");
$d = new ArrayIterator($sql);
$valor = $d->current();
?>
<!-- http://bootswatch.com/simplex/ -->
<!DOCTYPE html>
<html lang="pt_br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Portare</title>
    <link rel="shortcut icon" href="theme/default/images/portare.ico" >
    <link rel="icon" type="image/gif" href="theme/default/images/portare.gif" >

    <!-- Bootstrap -->
    <link href="theme/default/css/bootstrap.min.css" rel="stylesheet">
    <link href="theme/default/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

      <script src="theme/default/js/jquery-1.11.1.min.js"></script>
      <script src="theme/default/js/bootstrap.min.js"></script>
      <script src="theme/default/js/functions.js"></script>

      <style>
          .container {
              width: auto;
          }
          .loginwrapper {
              background-color: #fff;
              height: 370px;
              width: 600px;
              padding:5px;
              position: absolute;
              top:0;
              bottom: 0;
              left: 0;
              right: 0;
              margin: auto;
          }

          .loginform {
              margin: 0 0 10px 0;
          }

          .loginwrapper .form-horizontal .control-label{
              float: left;
              width: 140px;
              padding: 18px 5px 0 0;
              text-align:right;
          }
          .loginwrapper .form-horizontal .controls {
              display: inline-block;
              padding: 10px 0 0 20px;
              text-align:left;
          }

          .loginwrapper .form-horizontal .controls .email {
              display: inline-block;
              padding: 0 0 0 20px;
              text-align:left;
              width: 350px;
          }

      </style>
  </head>
  <body>
  <div class="navbar navbar-inverse navbar-fixed-top"> </div>



  <div class="loginwrapper">
      <div class="panel panel-default">
          <div class="panel-heading">
              <img src="theme/default/images/logoPortare.png">
              <div class="pull-right">v<?php echo $valor->VERSAO; ?></div>
          </div>
          <div class="panel-body form-horizontal">

              <!-- Login Form -->
              <form action="#" method="post" name="frmZLogin" id="frmZLogin" class="form-horizontal loginform">
                      <div class="control-group">
                          <label class="control-label" for="inputUsername">E-mail:</label>
                          <div class="controls">
                              <input type="text" class="form-control email" id="inputEmail" placeholder="E-mail">
                          </div>
                      </div>
                      <div class="control-group">
                          <label class="control-label" for="inPassword">Senha:</label>
                          <div class="controls">
                              <input type="password" class="form-control" id="inputSenha" placeholder="Senha">
                          </div>
                      </div>
                  <div id="msgRetorno" class="msgDiv"> &nbsp; </div>
              </form>
              <!-- End Login Form -->

          </div>

          <div class="panel-footer">
              <button type="submit" class="btn btn-primary" name="sublogin1" onclick="logarSistema()">Acessar Sistema</button>
              <div class="pull-right">
                <button type="submit" class="btn btn-link" name="sublogin2">Lembrar Senha</button>
              </div>
          </div>
      </div>
  </div>


  </body>
</html>