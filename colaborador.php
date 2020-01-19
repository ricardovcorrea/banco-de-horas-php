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

    if (!isset($_GET['id_colaborador']))
    {
        header("Location: colaboradores.php");
    }
    
    if( isset($_GET["id_colaborador"]) )
    {
        $colaborador_atual->id_colaborador = $_GET["id_colaborador"];
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
            desenhaHeader('colaboradores');
        ?>

	<div id="contents">
		<div class="main">
			<h1>Colaborador</h1>
			<ul class="news">
                <?php 
                    $colaborador_atual->carregarColaborador($colaborador_atual->id_colaborador,"medio");
                    echo"<br><h1>Registros</h1>"; 
                    $bancodehoras_atual->carregarListaRegistrosColaborador($colaborador_atual->id_colaborador,"pequeno");     
                ?>                        
			</ul>
		</div>
                <div class="sidebar">
                    <h1>Opções</h1>
                        <ul class="posts">
                                <li>
                                    <h4 class="title">
                                        Cadastros
                                    </h4><br><br>
                                     <p id="item_menu_index">
                                         <a href='colaboradores.php'>  Listar todos</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='novo_colaborador.php'>  Novo colaborador</a>
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