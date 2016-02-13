<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$tipo = $_REQUEST['tipo'];
$requisicao = $_REQUEST['requisicao'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$idkm = filter_input(INPUT_POST, 'idkm', FILTER_SANITIZE_NUMBER_INT);
$valor = (empty($_REQUEST['valor'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['valor']));
$litragem = (empty($_REQUEST['litragem'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['litragem']));
$km = (empty($_REQUEST['km'])) ? 0 : str_replace(",", ".", str_replace(".", "", $_REQUEST['km']));
list($dia, $mes, $ano) = explode("/", $_REQUEST['data']);
$data = $ano.'-'.$mes.'-'.$dia;


if ($acao =='inserir') {
    # Checando se já existe Saída do caminhão
    if ($tipo=='S') {
        $saida = $consulta->PDOQtderegistro("ZPortareKmAbastecimentos", "idkm = " . $idkm . " and tipo = 'S'");
        if ($saida>0) {
            echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Já existe um abastecimento do <b>tipo Saída</b>!
              </div>';
            exit;
        }
    }
    if ($tipo=='A') {
        #Verificando se já existe um abastecimento de chegada para não deixar cadastrar novos abastecimentos
        $chegada = $consulta->PDOQtderegistro("ZPortareKmAbastecimentos", "idkm = " . $idkm . " and tipo = 'C'");
        if ($chegada>0) {
            echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Não pode inserir abastecimento por já ter um do <b>tipo Chegada</b>!
              </div>';
            exit;
        }
    }
    if ($tipo=='C') {
        $chegada = $consulta->PDOQtderegistro("ZPortareKmAbastecimentos", "idkm = " . $idkm . " and tipo = 'C'");
        if ($chegada>0) {
            echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  Já existe um abastecimento do <b>tipo Chegada</b>!
              </div>';
            exit;
        }
    }

    /*
    #Buscando o último KM do sistema para comparar o km digitado com o km atual
    $sql = $consulta->PDOSelecionarPadrao("max(km) km", "ZPortareKmAbastecimentos", "idkm = " . $idkm);
    $d = new ArrayIterator($sql);
    $valorkm = $d->current();
    if ($valorkm->km >= $km) {
        echo '<div class="alert alert-dismissable alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>'.'
                  O Km informado não pode ser menor do que o Km anterior!
              </div>';
        exit;
    }
    */

    $consulta->PDOInserir("ZPortareKmAbastecimentos",
            "(idkm, data, litragem, valor, tipo, km, requisicao)",
            "(".$idkm.", '".$data."', ".$litragem.", '".$valor."', '".$tipo."', '".$km."', '".$requisicao."')");

    # Inserindo o KM inicial do sistema automaticamente
    if ($tipo=="S") $consulta->PDOEditar("ZPortareKm", "kmsaida = " . $km, "id = " . $idkm);
    if ($tipo=="C") {
        $kmsaida = $consulta->PDOSelecionarPadrao("kmsaida", "ZPortareKm", "id = " . $idkm);
        $dkm = new ArrayIterator($kmsaida);
        $valorkmsaida = $dkm->current();
        $kmpercorrido = $km - $valorkmsaida->kmsaida;

        $consulta->PDOEditar("ZPortareKm", "kmchegada = " . $km . ", kmpercorrido = " . $kmpercorrido, "id = " . $idkm);
    }

    if (!empty($requisicao)){
        # Atualiazando registros do Item do Movimento
        $consulta->PDOEditar("A",
        "A.QUANTIDADE = ".$litragem.", 
        A.QUANTIDADEARECEBER = ".$litragem.",
        A.QUANTIDADEORIGINAL = ".$litragem.",
        A.QUANTIDADESEPARADA = ".$litragem.",
        A.QUANTIDADETOTAL = ".$litragem.",
        A.PRECOUNITARIO = " . ($valor/$litragem). ",
        A.VALORBRUTOITEM = ".$valor."
        FROM TITMMOV A
          INNER JOIN TMOV B ON A.CODCOLIGADA = B.CODCOLIGADA AND A.IDMOV = B.IDMOV",
        "B.CODTMV = '1.1.01' AND A.CODCOLIGADA = 5 AND B.NUMEROMOV = '".$requisicao."'");

        # Atualizando registros do Movimento
        $consulta->PDOEditar("TMOV", "VALORBRUTOORIG = ".$valor.", VALORBRUTO=".$valor.", VALOROUTROS=".$valor.", VALORLIQUIDO=".$valor.", DATAEXTRA1 = '".$data."', VALOREXTRA1 = '".$km."', NORDEM = " . $idkm, "CODTMV = '1.1.01' AND CODCOLIGADA = 5 AND NUMEROMOV = '".$requisicao."'");

        # Atualizando o Rateio do Movimento
        $consulta->PDOEditar("C",
          "C.PERCENTUAL = 100, C.VALOR = ".$valor."
          FROM TITMMOVRATCCU C
                JOIN TITMMOV A ON A.CODCOLIGADA = C.CODCOLIGADA AND A.IDMOV = C.IDMOV AND A.NSEQITMMOV = C.NSEQITMMOV
                JOIN TMOV B ON A.CODCOLIGADA = B.CODCOLIGADA AND A.IDMOV = B.IDMOV",
          "B.CODTMV = '1.1.01'
           AND A.CODCOLIGADA = 5
           AND B.NUMEROMOV = '".$requisicao."'");

        # Atualizando o Rateio do Item do Movimento
        $consulta->PDOEditar("C",
            "C.PERCENTUAL = 100, C.VALOR = ".$valor."
             FROM TMOVRATCCU C
                JOIN TMOV B ON C.CODCOLIGADA = B.CODCOLIGADA AND C.IDMOV = B.IDMOV",
            "B.CODTMV = '1.1.01'
             AND B.CODCOLIGADA = 5
             AND B.NUMEROMOV = '".$requisicao."'");
    }

    echo '<script language="javascript">
                    $("#modalExtra").modal("hide");
                    $("#modalConteudo").load("view/kmabastecimentos/list.php", {id: '.$idkm.'});
                  </script>';

} elseif ($acao == 'editar') {

    if ($tipo=="S") $consulta->PDOEditar("ZPortareKm", "kmsaida = " . $km, "id = " . $idkm);
    if ($tipo=="C") {
        $kmsaida = $consulta->PDOSelecionarPadrao("kmsaida", "ZPortareKm", "id = " . $idkm);
        $dkm = new ArrayIterator($kmsaida);
        $valorkmsaida = $dkm->current();
        $kmpercorrido = $km - $valorkmsaida->kmsaida;
        $consulta->PDOEditar("ZPortareKm", "kmchegada = " . $km . ", kmpercorrido = " . $kmpercorrido, "id = " . $idkm);
    }

    $consulta->PDOEditar("ZPortareKmAbastecimentos", "requisicao = '".$requisicao."', data='".$data."', litragem='".$litragem."',  valor='".$valor."', tipo='".$tipo."', km='".$km."'", "id = ".$id);

    if (!empty($requisicao)){
        # Atualiazando registros do Item do Movimento
        $consulta->PDOEditar("A",
            "A.QUANTIDADE = ".$litragem.",
        A.QUANTIDADEARECEBER = ".$litragem.",
        A.QUANTIDADEORIGINAL = ".$litragem.",
        A.QUANTIDADESEPARADA = ".$litragem.",
        A.QUANTIDADETOTAL = ".$litragem.",
        A.PRECOUNITARIO = " . ($valor/$litragem). ",
        A.VALORBRUTOITEM = ".$valor."
        FROM TITMMOV A
          INNER JOIN TMOV B ON A.CODCOLIGADA = B.CODCOLIGADA AND A.IDMOV = B.IDMOV",
            "B.CODTMV = '1.1.01' AND A.CODCOLIGADA = 5 AND B.NUMEROMOV = '".$requisicao."'");

        # Atualizando registros do Movimento
        $consulta->PDOEditar("TMOV", "VALORBRUTOORIG = ".$valor.", VALORBRUTO=".$valor.", VALOROUTROS=".$valor.", VALORLIQUIDO=".$valor.", DATAEXTRA1 = '".$data."', VALOREXTRA1 = '".$km."', NORDEM = " . $idkm, "CODTMV = '1.1.01' AND CODCOLIGADA = 5 AND NUMEROMOV = '".$requisicao."'");

        # Atualizando o Rateio do Movimento
        $consulta->PDOEditar("C",
            "C.PERCENTUAL = 100, C.VALOR = ".$valor."
          FROM TITMMOVRATCCU C
                JOIN TITMMOV A ON A.CODCOLIGADA = C.CODCOLIGADA AND A.IDMOV = C.IDMOV AND A.NSEQITMMOV = C.NSEQITMMOV
                JOIN TMOV B ON A.CODCOLIGADA = B.CODCOLIGADA AND A.IDMOV = B.IDMOV",
            "B.CODTMV = '1.1.01'
           AND A.CODCOLIGADA = 5
           AND B.NUMEROMOV = '".$requisicao."'");

        # Atualizando o Rateio do Item do Movimento
        $consulta->PDOEditar("C",
            "C.PERCENTUAL = 100, C.VALOR = ".$valor."
             FROM TMOVRATCCU C
                JOIN TMOV B ON C.CODCOLIGADA = B.CODCOLIGADA AND C.IDMOV = B.IDMOV",
            "B.CODTMV = '1.1.01'
             AND B.CODCOLIGADA = 5
             AND B.NUMEROMOV = '".$requisicao."'");
    }

    echo '<script language="javascript">
                    $("#modalExtra").modal("hide");
                    $("#modalConteudo").load("view/kmabastecimentos/list.php", {id: '.$idkm.'});
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
        $consulta->PDOExcluir("ZPortareKmAbastecimentos", "id = ".$id);

        echo '<script language="javascript">
            $("#'.$path.$id.'").remove();
            $("#modalExcluir").modal("hide");
        </script>';
    #}

}