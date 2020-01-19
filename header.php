<?php

include 'scripts.html';

function desenhaHeader($local)
{
if (isset($_SESSION['usuario_logado']))
{
    $logado = $_SESSION['usuario_logado'];   
}
else
{
    $logado = false;
}
echo '<div id="header">
        <div>
            <div class="logo">
                <a href="index.php">Consultoria e Automação</a>
            </div>';

if($logado)
{
    
    echo'<ul id="navigation">
                        <li';if ($local == 'bancodehoras') echo ' class="active"';
    echo'>
                                <a href="bancodehoras.php">Banco de Horas</a>
                        </li>
                        <li';if ($local == 'colaboradores') echo ' class="active"';
    echo'>
                                <a href="colaboradores.php">Colaboradores</a>
                        </li>
                        <li';if ($local == 'reserva0') echo ' class="active"';
    echo'>
                                <a href="ops.php">Reserva</a>
                        </li>
                        <li';if ($local == 'reserva1') echo ' class="active"';
    echo'>
                                <a href="ops.php">Reserva</a>
                        </li>
                        <li';if ($local == 'sair') echo ' class="active"';
    echo'>
                                <a href="javascript:confirmaLogout();">Sair do sistema</a>
                        </li>
                </ul>
                <form name="form_busca_geral" action="pesquisa.php" method="get">
                <input type="button" id="img_lupa" onClick="javascript:validaBuscaGeral();">
                    <input type="text" name="busca_suporte" onFocus="this.select();" onMouseOut="javascript:return false;"/>
                     <select name="filtro_busca_suporte">
                        <option value="" selected="true">Buscar em todos</option>
                        <option value="bancodehoras">Banco de Horas</option>
                        <option value="funcionarios">Funcionários</option> 
                    </select>
                    <input alt ="Voltar..." type="button" id="img_voltar" onClick="javascript:window.history.go(-1);">
                        </form>';
    
}

echo'
    </div>
</div>';
}
?>