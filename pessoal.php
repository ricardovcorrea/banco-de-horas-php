<!DOCTYPE HTML>
<?php
    require_once("./classes/classeUsuario.php");
    $usuario_atual = new Usuario();
    
    if ( $usuario_atual->usuarioLogado() == false ) 
    {
        header("Location: login.php");
    }
   else
   {
       $perfil_usuario = new Usuario();
   }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
	<title>Publicação - Sistema de apoio - Elo Consultoria e Automação</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<?php
            include 'header.php';
            desenhaHeader('pessoal');
        ?>
	<div id="contents">
		<div class="main">
			
                        <?php
                                echo "<h1>Inicio</h1>";
                        ?>
			<ul class="news">
                            <li>
                            </li>   
			</ul>
		</div>
                <div class="sidebar">
                    <h1>Menu</h1>
                        <ul class="posts">
                                <li>
                                    <h4 class="title">
                                        <?php 
                                        echo "<a href='pessoal.php'>"; 
                                        echo $_SESSION['usuario_nome']."</a>"; 
                                    ?>
                                    </h4>
                                    <br>
                                     <p id="item_menu_index">
                                         <a href='index.php'>  Resumo</a>
                                     </p>
                                     <p id="item_menu_index">
                                        <?php echo"<a href='pessoa.php?id_usuario=";
                                        echo $_SESSION['usuario_id_usuario'];
                                        echo"'>  Minhas informações</a>";?>
                                     </p>
                                    <h4 class="title">
                                        Documentos
                                    </h4>
                                    <br>
                                    
                                     <p id="item_menu_index">
                                         <a href='boletos_faculdade.php'>  Boletos faculdade</a>
                                     </p>
                                     <p id="item_menu_index">
                                         <a href='envia_documentos.php'>  Documentos</a>
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