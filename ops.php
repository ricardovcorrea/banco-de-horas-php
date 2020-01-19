<!DOCTYPE HTML>
<?php
    session_start();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
	<title>Ooops! - Sistema de apoio - Elo Consultoria e Automação</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<?php
            include 'header.php';
            desenhaHeader('');
        ?>
    <div id="adbox">
		<div class="clearfix">
		<img src="images/ops.png" alt="D'oh!" height="245" width="288">
			<a href="suporte.php" style="text-decoration: none;"><div>
				<h1>D'oh!! Não encontramos o que voce estava procurando!</h1><br>
				<h2>A equipe da ELO pede desculpa.</h2>
				<p>
					Estamos trabalhando para evitar que este tipo de problema aconteça!
				</p>
			</div></a>
		</div>
	</div>
	<?php
            include 'footer.php';     
        ?>
</body>
</html>