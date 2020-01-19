r<?php

require_once("./classes/classeColaborador.php");

    $colaborador_atual = new Colaborador();
    
    if ( $colaborador_atual->usuarioLogado() == false ) 
    {
        header("Location: login.php");
    }

    if (!isset($_GET['id_colaborador']))
    {
        header("Location: colaboradores.php");
    }

    if($_SESSION['usuario_nivel_acesso'] <> 5)
    {
         header("Location: proibido.php");  
    };

$colaborador_atual->deletaColaborador($_GET['id_colaborador'],"lixo");

header("Location: colaboradores.php");

?>

