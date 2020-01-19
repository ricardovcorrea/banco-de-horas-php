<!DOCTYPE HTML>
<?php
    require_once("./classes/classeColaborador.php");
    require_once("./classes/classeBancoDeHoras.php");

    $colaborador_atual = new Colaborador();
    $bancodehoras_atual = new BancoDeHoras();
    
    if ( $colaborador_atual->usuarioLogado() == false ) 
    {
        header("Location: login.php");
    }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
	<title>Inicio - Sistema de apoio - Elo Consultoria e Automação</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<?php
            include 'header.php';
            desenhaHeader('bancodehoras');
        ?>

	<div id="contents">
		<div class="main">
			<h1>Banco de Horas</h1>
            
            <h1>Saldo de 
                <?php
                    $bancodehoras_atual->saldoBancodeHoras();
                ?> </h1>           
			<ul class="news">
                <?php
                    $bancodehoras_atual->carregarListaRegistros('pequeno');
                ?>                         
			</ul>
		</div>
                <div class="sidebar">
                    <h1>Opções</h1>
                        <ul class="posts">
                                <li>
                                    <h4 class="title">
                                        Registros
                                    </h4><br><br>
                                     <p id="item_menu_index">
                                         <a href='bancodehoras.php'>  Visão geral</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='novo_bancodehoras.php'>  Novo registro</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='#'>  Reserva</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='#'>  Reserva</a>
                                     </p>
                                    <h4 class="title">
                                        Relatórios
                                    </h4>
                                    </h4><br><br>
                                     <p id="item_menu_index">
                                         <a href='#'>  Relatorio01</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='#'>  Relatorio02</a>
                                     </p>
                                </li>
                        </ul>
                </div>
		
	</div>
	<?php
        
            include 'footer.php';
            
        ?>
</body>
</html>