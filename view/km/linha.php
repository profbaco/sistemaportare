<?php

if (isset($_REQUEST['idlinha'])) {
    include_once('../../dados/config.php');
    require_once('../../dados/classes.php');
    $consulta = new banco();

    $dados = $consulta->PDOSelecionarPadrao("A.id, B.nome, C.NOME motorista, A.idcaminhao, A.idmov, CONVERT(VARCHAR(20), A.dtsaidaempresa, 103) dtsaida,
(CONVERT(VARCHAR(20), A.dtorcamento, 103)+' '+CONVERT(VARCHAR(20), A.dtorcamento, 108)) dataorcamento, A.dtorcamento,
case status
   WHEN 0 THEN 'Montagem'
   WHEN 1 THEN 'Aguardando Resposta'
   WHEN 2 THEN 'Aprovado'
   WHEN 3 THEN 'Em Viagem'
   WHEN 4 THEN 'Concluído'
   WHEN 5 THEN 'Cancelado'
   WHEN 6 THEN 'Em Carregamento'
END AS situacao, A.status, A.kmsaida, A.kmchegada, A.kmpercorrido, A.observacao", "ZPortareKm A, ZPortareLogin B, PFUNC C", "A.id = " . $_REQUEST['idlinha']);
    $d = new ArrayIterator($dados);
}

$m = $d->current();
echo '<tr id="linha'.$m->id.'">
                                <td>
                                <span rel="popover" data-content="<strong>Km Saída:</strong> '.$m->kmsaida.'<br><strong>Km Chegada:</strong> '.$m->kmchegada.'<br><strong>Km Rodado:</strong> '.$m->kmpercorrido.'<br><strong>Observação:</strong> '.$m->observacao.' ">
                                <span class="glyphicon glyphicon-question-sign"></span> '.$m->id.'
                                </span>
                                </td>
                                <td>'.$m->idmov.'</td>
                                <td>'.$m->nome.'</td>
                                <td>'.$m->motorista.'</td>
                                <td>'.$m->idcaminhao.'</td>
                                <td>'.$m->dataorcamento.'</td>
                                <td>'.$m->dtsaida.'</td>
                                <td>'.$m->situacao.'</td>
                                <td> ';

    echo ' <div class="btn-group">
                                          <a href="#" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" rel="tooltip" title="Configurações">
                                            <i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>
                                          </a>
                                          <ul class="dropdown-menu pull-right">
                                            <li>
                                                <a href="javascript:void(0)" onclick="abrirModal(\'editar\', '.$m->id.' , \'view/km/form.php\')">
                                                    <i class="glyphicon glyphicon-edit"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="abrirModal(\'status\', '.$m->id.' , \'view/kmstatus/form.php\')">
                                                    <i class="glyphicon glyphicon-refresh"></i> Alterar Status
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="abrirModal(\'abastecimentos\', '.$m->id.' , \'view/kmabastecimentos/list.php\')">
                                                    <i class="glyphicon glyphicon-tint"></i> Abastecimentos
                                                </a>
                                            </li>
                                            <li class="divider"></li>';
if ($m->status<4){
    echo '                          <li><a href="javascript:void(0)" onclick="abrirModal(\'despesas\', '.$m->id.' , \'view/kmdespesas/list.php\')">
                                        <i class="glyphicon glyphicon-usd"></i> Despesas
                                    </a></li>
                                    <li><a href="javascript:void(0)" onclick="abrirModal(\'clientes\', '.$m->id.' , \'view/kmclientes/list.php\')">
                                        <i class="glyphicon glyphicon-user"></i> Clientes
                                    </a></li>';
} elseif ($m->status==4){
    echo '<li> <a href="javascript:void(0)" onclick="abrirModal(\'despesas\', '.$m->id.' , \'view/kmdespesas/list.php?tipo=realizado\')">
                                        <i class="glyphicon glyphicon-euro"></i> Despesas
                                    </a></li>';
} elseif ($m->status==5){
    echo '<li><a href="javascript:void(0)" onclick="modalExcluir(\'view/'.$path.'/actions.php\', '.$m->id.')">
                                        <i class="glyphicon glyphicon-trash"></i> Excluir
                                    </a></li>';
}
    echo '                                </ul>
                                    </div>';
    echo ' <div class="btn-group">
                                          <a href="#" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" rel="tooltip" title="Opções">
                                            <i class="glyphicon glyphicon-edit"></i> <span class="caret"></span>
                                          </a>
                                          <ul class="dropdown-menu pull-right">
                                            <!--<li><a href="javascript:void(0)" onclick="window.open(\'view/relatorios/calculokmgeral.php?idkm='.$m->id.'\')">Cálculo Geral</a></li>
                                            <li><a href="javascript:void(0)" onclick="window.open(\'view/relatorios/calculokmsintetico.php?idkm='.$m->id.'\')">Cálculo Simples</a></li>-->
                                            <li><a href="javascript:void(0)" onclick="window.open(\'view/relatorios/mapadecargadiariounico.php?idkm='.$m->id.'\')">Mapa de Carga</a></li>
                                            <li><a href="javascript:void(0)" onclick="window.open(\'view/relatorios/maparesumokm.php?idkm='.$m->id.'&placa='.$m->idcaminhao.'\')">Mapa Resumo KM</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)">Enviar RM</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)" onclick="window.open(\'view/checklist/form.php?idkm='.$m->id.'&tipo=S\')">Cleck List - Saída</a></li>
                                            <li><a href="javascript:void(0)" onclick="window.open(\'view/checklist/form.php?idkm='.$m->id.'&tipo=C\')">Cleck List - Chegada</a></li>
                                          </ul>
                                    </div>';
    echo '                      </td>
                              </tr>';
$d->next();