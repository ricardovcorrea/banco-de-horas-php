<!DOCTYPE HTML>
<?php
    require_once("./classes/classeColaborador.php");

    $colaborador_atual = new Colaborador();
    
    if ( $colaborador_atual->usuarioLogado() == false ) 
    {
        header("Location: login.php");
    }
    else
    {
        header("Location: bancodehoras.php");    
    }
?>