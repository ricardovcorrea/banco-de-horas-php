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

    if($_SESSION['usuario_nivel_acesso'] <> 5)
    {
         header("Location: bancodehoras.php");  
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
			<h1>Novo registro</h1>        
			<ul class="news">
             <li>
                <form method="post" id="form_novo_registro_bancodehoras" action="confirma_registro_bancodehoras.php">
                    <div id="contents_controles">
                        <h2>Selecione o colaborador</h2>
                        <?php $colaborador_atual->dropDownColaboradores(); ?>
                        <input type="button" id="salvar_publicacao" onclick="javascript:validaNovoRegistroBancoDeHoras();"/>
                        <input type="button" id="cancelar_publicacao" onclick="javascript:confirmaCancelamentoNovoRegistro();"/>
                        <br>
                        <br>
                        <h2>Data</h2>
                        <input type="text" name="data_registro" id="data_registro" maxlength="100" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                        <br>
                        <br>
                        <h2>Entrada</h2>
                        <input  type="text" name="entrada_registro" id="entrada_registro" maxlength="50" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                        <br>
                        <br>
                        <h2>Saida Almoço</h2>
                        <input type="text" name="saida_almoco_registro" id="saida_almoco_registro" maxlength="30" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                        <br>
                        <br>
                        <h2>Retorno Almoço</h2>
                        <input type="text" name="retorno_almoco_registro" id="retorno_almoco_registro" maxlength="30" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                        <br>
                        <br>
                        <h2>Saida</h2>
                        <input type="text" name="saida_registro" id="saida_registro" maxlength="30" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                        <br>
                        <br>
                        <input type="checkbox" name="feriado"><label for="check">Feriado/Descanso remunerado</label>
                        <br>
                        <br>
                        <h2>Justificativa</h2>
                        <textarea rows="10" cols="75" name="justificativa_registro" id="justificativa_registro"></textarea>
                        <br><br><br><br><br><br><br><br><br><br><br><br>
                </form>
             </li>               
                            
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