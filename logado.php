<?php
if(!isset($_SESSION)) session_start();

if (empty($_SESSION['idlogin'])){
    header('location: index.php');
}

if (!empty($_REQUEST['sair'])){
    session_destroy();
    header('location: index.php');
}

$nomelogin = $_SESSION['nomelogin'];
$idlogin = $_SESSION['idlogin'];
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
    <link href="theme/default/css/datepicker.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

      <script src="theme/default/js/jquery-1.11.1.min.js"></script>
      <script src="theme/default/js/bootstrap.min.js"></script>
      <script src="theme/default/js/functions.js"></script>
      <script src="theme/default/js/jquery.mask.min.js"></script>
      <script src="theme/default/js/jquery.maskMoney.js"></script>
      <script src="theme/default/js/bootstrap-datepicker.js"></script>

	<style>
        .container {
            width: auto;
        }
        .select2-hidden-accessible{
            display: none;
        }
	</style>
	
  </head>
  <body>
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="navbar-header">
          <a href="logado.php" class="navbar-brand" data-placement="right" rel="tooltip" title="Sistema Portare">
            <img src="theme/default/images/logoPortarePequena.png" title="Sistema Portare">
          </a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Cadastro <span class="caret"></span></a>
				<ul class="dropdown-menu" aria-labelledby="themes">
                    <li class="nav-header"> &nbsp; RM</li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/clifor/list.php')">Clientes</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/motoristas/list.php')">Motoristas</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/caminhoes/list.php')">Caminhões</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/carretas/list.php')">Carretas</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/bitrem/list.php')">Bitrem</a></li>
                    <li class="divider"></li>
                    <li class="nav-header"> &nbsp; Despesas</li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/despesas/list.php?tipo=f')">Fixas</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/despesas/list.php?tipo=v')">Variáveis</a></li>
                    <li class="divider"></li>
                    <li class="nav-header"> &nbsp; Gerais</li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/combustivel/list.php')">Produto</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/telefones/list.php')">Telefones</a></li>
                    <li class="divider"></li>
                    <li class="nav-header"> &nbsp; Segurança</li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/perfil/list.php')">Perfil</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/usuarios/list.php')">Usuários</a></li>
                </ul>
              </li>
              <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Cálculo <span class="caret"></span></a>
				<ul class="dropdown-menu" aria-labelledby="download">
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/km/list.php')">Cálculo do KM</a></li>
				</ul>
            </li>
              <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">D.R.E. <span class="caret"></span></a>
				<ul class="dropdown-menu" aria-labelledby="download">
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/planocontas/list.php')">Plano de Contas</a></li>
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/dregrupo/list.php')">Montagem de Grupo</a></li>
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/dreatualizarconta/atualizar.php')">Atualização das Contas</a></li>
                    <li class="divider"></li>
                    <li class="nav-header"> &nbsp; Opções de Contas</li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/contasnaomigradas.php')">Contas não Migradas</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/contasnaoagrupadasdre.php')">Contas não Agrupadas</a></li>
				</ul>
            </li>
            <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Relatórios <span class="caret"></span></a>
				<ul class="dropdown-menu" aria-labelledby="download">
                    <li><a href="javascript:void(0)">Conferência de Check-List</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open('view/relatorios/mapadecargadiario.php')">Mapa de Carga Diário</a></li>
                    <li><a href="javascript:void(0)" onclick="window.open('view/relatorios/mapadecargaporplaca.php')">Mapa de Carga por Placa</a></li>
                    <li class="divider"></li>
                    <li class="nav-header"> &nbsp; D.R.E.</li>
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/conferirgrupos.php')">Grupos de D.R.E.</a></li>
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/dreglobal.php')">Balancete Portare</a></li>
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/gerardre.php')">Gerar D.R.E.</a></li>
                    <li class="divider"></li>
                    <li class="nav-header"> &nbsp; Ponto de Equilíbrio</li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/pontodeequilibrio.php')">Global</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/pontodeequilibrioporplaca.php')">Por Placa</a></li>
                    <li><a href="javascript:void(0)" onclick="carregaPagina('view/relatorios/pontodeequilibriopormotorista.php')">Por Motorista</a></li>
                    <!--<li class="divider"></li>
                    <li class="nav-header"> &nbsp; Cálculo KM</li>
                    <li><a href="#">Cálculo por Placa</a></li>-->
                </ul>
            </li>
            <!--<li>
              <a href="../help/">Relatórios</a>
            </li>-->
            <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Opções <span class="caret"></span></a>
				<ul class="dropdown-menu" aria-labelledby="download">
					<li><a href="javascript:void(0)" onclick="carregaPagina('view/parametros/parametros.php')">Parâmetros</a></li>
				</ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download"><?php echo $nomelogin; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="download">
                <li><a href="#">Dados Pessoais</a></li>
                <li><a href="#">Alterar Senha</a></li>
				<li class="divider"></li>
				<li><a href="#">Sobre...</a></li>
				<li><a href="?sair=sim">Sair</a></li>
              </ul>
            </li>
            <li><a href="javascript:void(0)" target="_blank"></a></li>
          </ul>

        </div>
    </div>
	
	<div class="container" id="conteudoPagina">

	</div>

    <!-- Form -->
    <div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modalForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalTitulo"></h4>
                </div>
                <div class="modal-body" id="modalConteudo">

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Filtros -->
    <div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modalFiltro">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Filtro de Registros</h4>
                </div>
                <div class="modal-body" id="mostrarConteudoFiltro">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Excluir -->
    <div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Exclusão de Registro</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idExcluir"/>
                    <input type="hidden" id="urlExcluir"/>
                    Tem certeza que deseja excluir este registro?<br>
                    <div id="msgExcluir" class="msgTexto">&nbsp;</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>
                    <button type="button" class="btn btn-success" onClick="confirmaExcluir()" id="btnExcluir"><span class="glyphicon glyphicon-floppy-saved"></span> Confirmar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Form -->
    <div class="modal fade" id="modalExtra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalTituloExtra"></h4>
                </div>
                <div class="modal-body" id="modalConteudoExtra">

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


  </body>
</html>