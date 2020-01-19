<?php
    require_once("./classes/classeColaborador.php");
    $colaborador_atual = new Colaborador();
    
    if ( $colaborador_atual->usuarioLogado() == true ) 
    {
        header("Location: bancodehoras.php");
    }
    
    if (isset($_POST['login_email']) and isset($_POST['login_senha']))
    {
        $login = $_POST['login_email'];
        $senha = $_POST['login_senha'];
        $lembrar = (isset($_POST['login_lembrar']) AND !empty($_POST['login_lembrar']));
        
        if($colaborador_atual->logaUsuario($login, $senha,$lembrar))
        {
            header("Location:index.php");
        }
        else
        {
            echo "<strong>Erro: </strong>" . $colaborador_atual->erro;   
        }
    }
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Login - Sistema de apoio - Elo Consultoria e Automação</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<?php
            include 'header.php';
            desenhaHeader('login');
        ?>
        <div id="contents_login">
            <div class="section">
			<h1>Entrar no sistema</h1>
			<form name="form_login" action="login.php" method="post" class="message">
                                Login
				<input type="text" name="login_email" onFocus="this.select();" onMouseOut="javascript:return false;"/>
				Senha
                                <input type="password" name="login_senha" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                                    <input type="checkbox" name="login_lembrar" id="lembra_login" /> Manter conectado
                                <button style="float:right;margin-right:10px;" id="login_btn_entrar">Entrar</button>
			</form>
		</div>
           
            <div class="section2">
                <p> 
                    <h2>Problemas?<br>A equipe da ELO pode te ajudar.</h2>                  
			<a href="suporte.php" style="text-decoration: none;">
                            Estamos trabalhando para organizar um banco de dados com informações referentes a problemas encontrados em projetos e suas respectivas soluções;
			</a>
		</div>
                </p>	
            </div>
        </div>
        <?php
            include 'footer.php';
        ?>
</body>
</html>