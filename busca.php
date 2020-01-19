<!DOCTYPE HTML>
<?php
    require_once("./classes/classeColaborador.php");


    $colaborador_atual = new Colaborador();
    
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
            desenhaHeader('');
        ?>

	<div id="contents">
		<div class="main">
			<h1>Resultados da busca</h1>
                        
			<ul class="news">
                            
                            
			</ul>
		</div>
                <div class="sidebar">
                    <h1>Opções</h1>
                        <ul class="posts">
                                <li>
                                    <h4 class="title">
                                    <?php 
                                        echo "<a href='pessoal.php'>"; 
                                        echo $_SESSION['usuario_nome']."</a>"; 
                                    ?>
                                    </h4><br><br>
                                     <p id="item_menu_index">
                                         <a href='notificacoes.php'>  Notificações(0)</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='minhas_publicacoes.php'>  Publicações(0)</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='meus_relatos.php'>  Relatos(0)</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='meus_arquivos.php'>  Arquivos(0)</a>
                                     </p>
                                    <h4 class="title">
                                        <a href='javascript:confirmaLogout();'>Sair</a>
                                    </h4>
                                </li>
                        </ul>
                    </div>
		
	</div>
	<?php
        
            include 'footer.php';
            
        ?>
</body>
</html>