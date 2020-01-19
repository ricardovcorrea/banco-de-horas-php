<?php

require_once("./classes/classeColaborador.php");

$colaborador_atual = new Colaborador(); 
if ( $colaborador_atual->usuarioLogado() == false ) 
{
    header("Location: login.php");
}

if($_SESSION['usuario_nivel_acesso'] <> 5)
{
     header("Location: proibido.php");  
};

$novo_colaborador = new Colaborador();

$novo_colaborador->nome = $_POST['nome_colaborador'];
$novo_colaborador->admissao = $_POST['admissao_colaborador']; 
echo $novo_colaborador->admissao;
echo "<br>";
$novo_colaborador->admissao = $novo_colaborador->getdata('paraobanco','a')."-".$novo_colaborador->getdata('paraobanco','m')."-".$novo_colaborador->getdata('paraobanco','d');
echo $novo_colaborador->admissao;
$novo_colaborador->ctps = $_POST['ctps_colaborador'];
$novo_colaborador->cargo = $_POST['cargo_colaborador'];
$novo_colaborador->email = $_POST['email_colaborador'];

$novo_colaborador->salvaColaborador($novo_colaborador);

header("Location: colaboradores.php");

?>

