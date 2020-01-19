<?php
    require_once("./classes/classeColaborador.php");
    $colaborador_atual = new Colaborador();
    
    if ( $colaborador_atual->logout()) 
    {
        header("Location: login.php");
        exit;
    }
?>