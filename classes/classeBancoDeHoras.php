<?php

class BancoDeHoras 
{

	var $id_registro = null;
	var $entrada = null;
	var $saida_almoco = null;
	var $retorno_almoco = null;
	var $saida = null;
	var $total_extra = null;
	var $data = null;
	var $justificativa = null;
	var $id_colaborador = null;
    var $erro = null;

	function carregaInfo($id_registro,$tipo_info)
        {
        try
            {
                $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
                $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
            } 
                catch (Exception $ex) 
            {
                echo $ex->getCode();
            }
            
            $consulta = $banco->prepare("select entrada,saida_almoco,retorno_almoco,saida,total_extra,data,justificativa,id_colaborador from bancodehoras where id_registro=:id_registro");

            $consulta->bindValue(":id_registro", $id_registro);
            if ($consulta->execute()) 
            {
                if($consulta->rowCount() > 0)
                {
                    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                    if($tipo_info == 'entrada')
                    {
                        return $resultado['entrada'];
                    }
                    elseif($tipo_info == 'saida_almoco')
                    {
                        return $resultado['saida_almoco'];
                    }
                    elseif($tipo_info == 'retorno_almoco')
                    {
                        return $resultado['retorno_almoco'];
                    }
                    elseif($tipo_info == 'saida')
                    {
                        return $resultado['saida'];
                    }
                    elseif($tipo_info == 'total_extra')
                    {
                        return $resultado['total_extra'];
                    }
                    elseif($tipo_info == 'data')
                    {
                        return $resultado['data'];
                    }
                    elseif($tipo_info == 'justificativa')
                    {
                        return $resultado['justificativa'];
                    }
                    elseif($tipo_info == 'id_colaborador')
                    {
                        return $resultado['id_colaborador'];
                    }
                }
            } 
            else 
            {       
                return null;
            }
        }

    function getData($tipo,$formato)
    {
        if($tipo == "dobanco")
        {
            $data_quebrada = explode("-",$this->data);

            if($formato == 'd')
            {
                return $data_quebrada[2];
            }
            elseif($formato == 'm')
            {
                return $data_quebrada[1];
            }
            elseif($formato == 'a')
            {
                return $data_quebrada[0];
            }
            elseif($formato == 'dm')
            {
                return $data_quebrada[2]."/".$data_quebrada[1];
            }
        }
        elseif($tipo == "paraobanco")
        {
            $data_quebrada = explode("/",$this->data);

            if($formato == 'd')
            {
                return $data_quebrada[1];
            }
            elseif($formato == 'm')
            {
                return $data_quebrada[0];
            }
            elseif($formato == 'a')
            {
                return $data_quebrada[2];
            }
            elseif($formato == 'dm')
            {
                return $data_quebrada[0]."/".$data_quebrada[1];
            }   
        }
    }

	function carregarListaRegistros($tamanho)
    {
        try
        {
            $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
        } 
            catch (Exception $ex) 
        {
            echo $ex->getCode();
        }

        $consulta = $banco->prepare("select * from bancodehoras where habilitado = 1 order by data");
        if ($consulta->execute()) 
        {
            for($i=0;$i<$consulta->rowCount();$i++)
            {
                $bancodehoras_atual = new BancoDeHoras();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                $bancodehoras_atual->id_registro = $resultado["id_registro"];
                $bancodehoras_atual->entrada = $resultado["entrada"];
                $bancodehoras_atual->saida= $resultado["saida"];
                $bancodehoras_atual->total_extra = $resultado["total_extra"];
                $bancodehoras_atual->data = $resultado["data"];
                $bancodehoras_atual->data = $bancodehoras_atual->getdata('dobanco','d')."/".$bancodehoras_atual->getdata('dobanco','m')."/".$bancodehoras_atual->getdata('dobanco','a');
                $bancodehoras_atual->id_colaborador = $resultado["id_colaborador"];

                $this->desenharPaginaRegistro($bancodehoras_atual,$tamanho);
            }
            echo "<br><br><br><br><br><br>";
        }
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }
    }

    function carregarListaRegistrosColaborador($id_colaborador,$tamanho)
    {
        try
        {
            $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
        } 
            catch (Exception $ex) 
        {
            echo $ex->getCode();
        }

        $consulta = $banco->prepare("select * from bancodehoras where (habilitado = 1) and (id_colaborador=:id_colaborador) order by data");
        $consulta->bindValue(":id_colaborador",$id_colaborador);
        if ($consulta->execute()) 
        {
            for($i=0;$i<$consulta->rowCount();$i++)
            {
                $bancodehoras_atual = new BancoDeHoras();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                $bancodehoras_atual->id_registro = $resultado["id_registro"];
                $bancodehoras_atual->entrada = $resultado["entrada"];
                $bancodehoras_atual->saida= $resultado["saida"];
                $bancodehoras_atual->total_extra = $resultado["total_extra"];
                $bancodehoras_atual->data = $resultado["data"];
                $bancodehoras_atual->data = $bancodehoras_atual->getdata('dobanco','d')."/".$bancodehoras_atual->getdata('dobanco','m')."/".$bancodehoras_atual->getdata('dobanco','a');
                $bancodehoras_atual->id_colaborador = $resultado["id_colaborador"];

                $this->desenharPaginaRegistro($bancodehoras_atual,$tamanho);
            }
            echo "<br><br><br><br><br><br>";
        }
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }
    }

    function carregarRegistro($id_registro,$tamanho)
    {
        $bancodehoras_atual = new BancoDeHoras();
        $bancodehoras_atual->id_registro = $id_registro;
        $bancodehoras_atual->entrada = $bancodehoras_atual->carregaInfo($id_registro,"entrada");
        $bancodehoras_atual->saida_almoco = $bancodehoras_atual->carregaInfo($id_registro,"saida_almoco");
        $bancodehoras_atual->retorno_almoco = $bancodehoras_atual->carregaInfo($id_registro,"retorno_almoco");
        $bancodehoras_atual->saida = $bancodehoras_atual->carregaInfo($id_registro,"saida");
        $bancodehoras_atual->total_extra = $bancodehoras_atual->carregaInfo($id_registro,"total_extra");
        $bancodehoras_atual->data = $bancodehoras_atual->carregaInfo($id_registro,"data");
        $bancodehoras_atual->data = $bancodehoras_atual->getdata('dobanco','d')."/".$bancodehoras_atual->getdata('dobanco','m')."/".$bancodehoras_atual->getdata('dobanco','a');
        $bancodehoras_atual->justificativa = $bancodehoras_atual->carregaInfo($id_registro,"justificativa");
        $bancodehoras_atual->id_colaborador = $bancodehoras_atual->carregaInfo($id_registro,"id_colaborador");

        $this->desenharPaginaRegistro($bancodehoras_atual,$tamanho);
    }

    function  desenharPaginaRegistro($registro,$tamanho)
    {

        $colaborador_registro = new Colaborador();
        $colaborador_registro->nome = $colaborador_registro->carregaInfo($registro->id_colaborador,"nome");

       if($tamanho == 'pequeno')
       {
           echo"<li>
                    <div class='icone_registro_bancodehoras'>
                    </div>
                    <div id='controles_publicacao_medio'>
                        <a href='#'>
                            <img src='images/edit.png' alt='Editar registro...'>
                        </a>
                        <a href=\"javascript:confirmaExclusaoRegistro('".$registro->id_registro."');\">
                            <img src='images/minus.png' alt='Deletar registro...'>
                        </a>
                    </div>
                    <p style='display:block;'>
                        <a style='text-decoration:none;font-size:24px;' href='registro_bancodehoras.php?id_registro=".$registro->id_registro."'>".$colaborador_registro->nome."
                        </a>
                        <br>
                            Data: ".$registro->data."
                        <br>
                            Total: ".$registro->total_extra." minutos ou ".($registro->total_extra / 60)." horas
                        <br>
                            Entrada: ".$registro->entrada." - Saida: ".$registro->saida."
                    </p>
                </li>"; 
       }
       elseif($tamanho == 'medio')
       {
        echo"<li>
                    <div class='icone_registro_bancodehoras'>
                    </div>
                    <div id='controles_publicacao_medio'>
                        <a href='#'>
                            <img src='images/edit.png' alt='Editar registro...'>
                        </a>
                        <a href=\"javascript:confirmaExclusaoRegistro('".$registro->id_registro."');\">
                            <img src='images/minus.png' alt='Deletar registro...'>
                        </a>
                    </div>
                    <p style='display:block;'>
                        <a style='text-decoration:none;font-size:30px;' href='colaborador.php?id_colaborador=".$registro->id_colaborador."'>".$colaborador_registro->nome."</a>
                        <br>
                        <br>
                        <a style='text-decoration:none;font-size:26px;' href='bancodehoras.php'>Total: ".$registro->total_extra." minutos </a>
                        <br> 
                        <br>
                            Data: ".$registro->data."
                        <br>
                            Entrada: ".$registro->entrada."
                        <br>
                            Saida almoço: ".$registro->saida_almoco."
                        <br>
                            Retorno almoço: ".$registro->retorno_almoco."
                        <br>
                            Saida: ".$registro->saida."
                    </p>
                </li>";       
       }
       elseif($tamanho == 'grande')
       {

       }
    }

    function salvaRegistro($registro)
    {
        try
        {
            $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
        } 
            catch (Exception $ex) 
        {
            echo $ex->getCode();
        }
            $consulta = $banco->prepare("insert into bancodehoras (entrada,saida_almoco,retorno_almoco,saida,total_extra,data,justificativa,id_colaborador,habilitado) values (:entrada,:saida_almoco,:retorno_almoco,:saida,:total_extra,:data,:justificativa,:id_colaborador,:habilitado)");
            
            $consulta->bindValue(":entrada",$registro->entrada);
            $consulta->bindValue(":saida_almoco",$registro->saida_almoco); 
            $consulta->bindValue(":retorno_almoco",$registro->retorno_almoco);
            $consulta->bindValue(":saida",$registro->saida);
            $consulta->bindValue(":total_extra",$registro->total_extra);
            $consulta->bindValue(":data",$registro->data);
            $consulta->bindValue(":justificativa",$registro->justificativa);
            $consulta->bindValue(":id_colaborador",$registro->id_colaborador);
            $consulta->bindValue(":habilitado","1");

        if ($consulta->execute()){}
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
            echo $this->erro;
        }
    }

    function deletaRegistro($id_registro,$destino)
    {
        try
        {
            $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
        } 
            catch (Exception $ex) 
        {
            echo $ex->getCode();
        }
        if($destino = "lixo")
            $consulta = $banco->prepare("delete from bancodehoras where id_registro =:id_registro");
        else
            $consulta = $banco->prepare("");

        $consulta->bindValue(":id_registro",$id_registro);
        if ($consulta->execute()){}
        else
        {
            $this->erro = "Erro na realização da operação!"; 
        }
    }

    function saldoBancodeHoras()
    {
        try
        {
            $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
        } 
            catch (Exception $ex) 
        {
            echo $ex->getCode();
        }

        $consulta = $banco->prepare("select sum(total_extra) as 'saldo_atual' from bancodehoras");
        if ($consulta->execute()) 
        {
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            if($resultado["saldo_atual"] >= 0)
            {
                echo number_format($resultado["saldo_atual"] / 60 * -1,1)." horas"; 
            }
            else
            {
                echo number_format($resultado["saldo_atual"] / 60 * -1 ,1)." horas"; 
            }

        }
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }    

    }

} 

?>