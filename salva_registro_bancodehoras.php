<?php

require_once("./classes/classeColaborador.php");
require_once("./classes/classeBancoDeHoras.php");

$colaborador_atual = new Colaborador();
$novo_registro = new BancoDeHoras();

if ( $colaborador_atual->usuarioLogado() == false ) 
{
    header("Location: login.php");
}

if($_SESSION['usuario_nivel_acesso'] <> 5)
{
     header("Location: proibido.php");  
};


$novo_registro->entrada = $_GET['entrada'];
$novo_registro->saida_almoco = $_GET['saida_almoco'];
$novo_registro->retorno_almoco = $_GET['retorno_almoco'];
$novo_registro->saida = $_GET['saida'];
$novo_registro->total_extra = $_GET['total_extra'];
$novo_registro->data = $_GET['data'];
$novo_registro->data = $novo_registro->getdata('paraobanco','a')."-".$novo_registro->getdata('paraobanco','m')."-".$novo_registro->getdata('paraobanco','d');

$novo_registro->justificativa = $_GET['justificativa'];
$novo_registro->id_colaborador = $_GET['id_colaborador'];


$novo_registro->salvaRegistro($novo_registro);

header("Location: bancodehoras.php");

?>

