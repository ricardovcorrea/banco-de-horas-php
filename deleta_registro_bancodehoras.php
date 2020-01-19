r<?php

require_once("./classes/classeColaborador.php");
require_once("./classes/classeBancoDeHoras.php");

    $colaborador_atual = new Colaborador();
    $bancodehoras_atual = new BancoDeHoras();
    
    if ( $colaborador_atual->usuarioLogado() == false ) 
    {
        header("Location: login.php");
    }

    if (!isset($_GET['id_registro']))
    {
        header("Location: bancodehoras.php");
    }

    if($_SESSION['usuario_nivel_acesso'] <> 5)
    {
         header("Location: proibido.php");  
    };

$bancodehoras_atual->deletaRegistro($_GET['id_registro'],"lixo");

header("Location: bancodehoras.php");

?>

