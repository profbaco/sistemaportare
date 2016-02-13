<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];


    $dados = $consulta->PDOSelecionar("*", "ZPortareLogin", "id = " . $id, 0, 1, "id");
    $d = new ArrayIterator($dados);
    $valor = $d->current();
?>

<input type="hidden" id="id" value="<?php echo $id; ?>">
<input type="hidden" id="acao" value="<?php echo $acao; ?>">


<div class="col-md-12">
    <label for="nome">Nome</label>
    <input type="text" id="nome" class="form-control" placeholder="Nome de Usuário" value="<?php echo $valor->nome; ?>">
    <label for="email">E-mail</label>
    <input type="text" id="email" class="form-control" placeholder="E-mai para login" value="<?php echo $valor->email; ?>">

    <div class="row">
        <div class="col-md-4">
            <label for="ativo">Status</label>
            <!--<input type="text" id="operadora" class="form-control" placeholder="Operadora" value="<?php echo $valor->operadora; ?>">-->
            <select class="form-control" id="ativo">
                <option value="0" <?php if ($valor->ativo == '0') echo 'selected'; ?>>Inativo</option>
                <option value="1" <?php if ($valor->ativo =='1') echo 'selected'; ?>>Ativo</option>
            </select>
        </div>
        <div class="col-md-4 has-error">
            <label for="senha" class="msgDiv">senha</label>
            <input type="password" id="senha" class="form-control" placeholder="******">
        </div>
        <div class="col-md-4">
            <label for="idperfil">Perfil</label>
            <select class="form-control" id="idperfil">
                <?php
                $dadosPerfil = $consulta->PDOSelecionar("*", "ZPortarePerfil", "1=1", 1, 130, "nome");
                $dPerfil = new ArrayIterator($dadosPerfil);
                while ($dPerfil->valid()){
                    $valorPerfil = $dPerfil->current();
                    echo '<option value="'.$valorPerfil->id.'"';
                    if ($valor->idperfil == $valorPerfil->id) echo ' selected ';
                    echo '>'.$valorPerfil->nome.'</option>';

                    $dPerfil->next();
                }

                ?>
            </select>
        </div>
    </div>
</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarUsuario()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>