<!DOCTYPE HTML>
<?php

    date_default_timezone_set('America/Araguaina');

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

    $data_registro = date('d/m/Y',strtotime($_POST["data_registro"])); 
    $hora_entrada = strtotime($_POST["entrada_registro"]);
    $hora_saida_almoco = strtotime($_POST["saida_almoco_registro"]);
    $hora_retorno_almoco = strtotime($_POST["retorno_almoco_registro"]);
    $hora_saida = strtotime($_POST["saida_registro"]);

    $total_manha = round(abs($hora_saida_almoco - $hora_entrada) / 60);
    $total_almoco = round(abs($hora_retorno_almoco - $hora_saida_almoco) / 60);
    if($total_almoco > 60)
    {
      $total_negativo_almoco = $total_almoco - 60;

    }    
    $total_tarde = round(abs($hora_saida - $hora_retorno_almoco) / 60);
    $total_diario = $total_manha + $total_tarde - $total_almoco;

    $total_primeira_faixa = 0;
    $total_segunda_faixa = 0;
    $total_terceira_faixa = 0;
    $total_quarta_faixa = 0;
    $total_extra_parcial = 0;
    $total_extra = 0;
    $total_negativo = 0;
    if($total_diario < 468)
    {
        $tipo_hora = "Negativa";
        $total_negativo ="-".(468 - $total_diario - $total_negativo_almoco);
    }
    elseif ($total_diario > 468)
    {
        $tipo_hora = "Positiva";
        if(!isset($_POST["feriado"]) and !(date("l",strtotime($data_registro)) == "Sunday") and !(date("l",strtotime($data_registro)) == "Saturday"))
        {
            $total_extra_parcial = $total_diario - 468;

            if($total_extra_parcial <= 120)
            {
                $total_primeira_faixa = $total_extra_parcial * 102 / 60;     
            }
            else
            {
                $total_primeira_faixa = 120 * 102 / 60; 
                $total_extra_parcial = $total_extra_parcial - 120;
                $total_segunda_faixa = $total_extra_parcial * 126 / 60;
            }

                $total_extra = $total_primeira_faixa + $total_segunda_faixa; 
        }
        else
        {
            $total_terceira_faixa = $total_diario * 132 / 60; 
            $total_extra = $total_terceira_faixa;    
        }              
    }
    elseif (($total_diario == 468) and (!isset($_POST["feriado"])) and !(date("l",strtotime($data_registro)) == "Sunday") and !(date("l",strtotime($data_registro)) == "Saturday") )
    {
        $tipo_hora = "SemHoras";    
    }
    elseif (($total_diario == 468) and ( (isset($_POST["feriado"]))) or (date("l",strtotime($data_registro)) == "Sunday") or (date("l",strtotime($data_registro)) == "Saturday"))
    {
        $tipo_hora = "Positiva";
        $total_terceira_faixa = $total_diario * 132 / 60; 
        $total_extra = $total_terceira_faixa;    
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
			<h1>Confirmar novo registro</h1>
                        
			<ul class="news">
             <li>
                <form>
                    <div id="contents_controles">
                        <h2>Nome</h2>
                        <?php 
                            echo $colaborador_atual->carregaInfo($_POST["id_colaborador"],"nome"); 
                            echo"<input type='hidden' id='confirma_id_colaborador' value='".$_POST["id_colaborador"]."'>";
                        ?>
                        <input type="button" id="salvar_publicacao" onclick="javascript:confirmaNovoRegistroBancoDeHoras();"/>
                        <input type="button" id="cancelar_publicacao" onclick="javascript:confirmaCancelamentoNovoRegistro();"/>
                        <br>
                        <br>
                        <h2>Data</h2>
                        <span id="confirma_data_registro"><?php echo $_POST["data_registro"]; ?></span>
                        <br>
                        <br>
                        <br>
                        <h2>Entrada / Saida</h2>
                        <?php echo"<span id='confirma_entrada_registro'>".$_POST["entrada_registro"]."</span> / <span id='confirma_saida_registro'>".$_POST["saida_registro"]; ?></span>
                        <br>
                        <br>
                        <h2>Saida Almoço / Retorno Almoço</h2>
                        <?php echo"<span id='confirma_saida_almoco_registro'>".$_POST["saida_almoco_registro"]."</span> / <span id='confirma_retorno_almoco_registro'>".$_POST["retorno_almoco_registro"]; ?></span>
                        <br>
                        <br>
                        <?php
                            if($tipo_hora == "Positiva")
                            {
                                echo"<h2>Total por faixa</h2>";
                                if($total_primeira_faixa <> 0)
                                {
                                    echo"1ª Faixa : <span id='total_primeira_faixa'>".$total_primeira_faixa."</span> minutos <br>";
                                }  
                            
                                if($total_segunda_faixa <> 0)
                                {
                                    echo"2ª Faixa : <span id='total_segunda_faixa'>".$total_segunda_faixa."</span> minutos <br>";
                                } 
                                if($total_terceira_faixa <> 0)
                                {
                                    echo"3ª Faixa : <span id='total_terceira_faixa'>".$total_terceira_faixa."</span> minutos <br>";
                                }
                                echo"<br><h2>Total extra</h2>";
                                echo "<span id='total_extra'>".$total_extra."</span> minutos ou ".($total_extra / 60)." horas";
                            }
                            elseif($tipo_hora == "Negativa")
                            {
                                echo"<br><h2>Total horas negativas</h2>";
                                echo "<span id='total_extra'>".$total_negativo."</span> minutos ou ".($total_negativo / 60)." horas";   
                            }
                            elseif($tipo_hora == "SemHoras")
                            {
                                echo"<br><h2>Não existem horas positivas ou negativas a serem declaradas!</h2>
                                <span id='total_extra'></span>";    
                            }
                            echo "<br><br><h2>Justificativa</h2><span id='confirma_justificativa_registro'>".$_POST["justificativa_registro"];
                            echo "</span><br><br><br><br><br><br><br><br>";
                        ?>
                        
                </div><br>
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