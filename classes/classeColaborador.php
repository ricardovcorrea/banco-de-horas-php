<?php
class Colaborador {
	
	var $iniciaSessao = true;
	var $prefixoChaves = 'usuario_';
	var $cookie = true;
	var $filtraDados = true;
	var $lembrarTempo = 7;	
	var $cookiePath = '/';
	var $erro = ''; 

    var $id_colaborador = null;
    var $nome = null;
    var $ctps = null;
    var $cargo = null;
    var $admissao = null;
    var $saldo = null;
    var $email = null;
    var $nivel_acesso = null;
    var $habilitado = null;
        
	function codificaSenha($senha) 
        {
            $senha = md5($senha);
            return $senha;
	}
        function carregaInfo($id_colaborador,$tipo_info)
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
            
            $consulta = $banco->prepare("select id_usuario,nome,ctps,cargo,admissao,email,nivel_acesso,habilitado from usuarios where id_usuario=:id_usuario");
            $consulta->bindValue(":id_usuario", $id_colaborador);
            if ($consulta->execute()) 
            {
                if($consulta->rowCount() > 0)
                {
                    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                    if($tipo_info == 'nome')
                    {
                        return $resultado['nome'];
                    }
                    elseif($tipo_info == 'ctps')
                    {
                        return $resultado['ctps'];
                    }
                    elseif($tipo_info == 'cargo')
                    {
                        return $resultado['cargo'];
                    }
                    elseif($tipo_info == 'admissao')
                    {
                        return $resultado['admissao'];
                    }
                    elseif($tipo_info == 'email')
                    {
                        return $resultado['email'];
                    }
                    elseif($tipo_info == 'nivel_acesso')
                    {
                        return $resultado['nivel_acesso'];
                    }
                    elseif($tipo_info == 'habilitado')
                    {
                        return $resultado['habilitado'];
                    }
                }
            } 
            else 
            {       
                return null;
            }
        }
        
	function validaUsuario($email, $senha) {
            try
            {
                $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
                $banco = new PDO("mysql:host=localhost;dbname=sistema_apoio_elo","root","",$opcoes);
            } 
                catch (Exception $ex) 
            {
                echo $ex->getCode();
            }
            
            $senha = $this->codificaSenha($senha);

            if ($this->filtraDados) {
                    $email = mysql_escape_string($email);
                    $senha = mysql_escape_string($senha);
            }

            $consulta = $banco->prepare("select id_usuario,nome,email,senha,nivel_acesso from usuarios where (email=:email) and (senha=:senha)");
            $consulta->bindValue(":email", $email);
            $consulta->bindValue(":senha", $senha);
            if ($consulta->execute()) 
            {
                return $consulta->fetch(PDO::FETCH_ASSOC);
            } 
            else 
            {       
                return null;
            }           
           
	}
	
	function logaUsuario($email, $senha, $lembrar) {
                $info_validada = $this->validaUsuario($email, $senha);
		if ($info_validada != null) 
                {
                    if ($this->iniciaSessao AND !isset($_SESSION)) 
                    {
                        session_start();
                    }
                    foreach ($info_validada AS $chave=>$valor) 
                    {
                            $_SESSION[$this->prefixoChaves . $chave] = $valor;
                    }
                    $_SESSION[$this->prefixoChaves . 'logado'] = true;
                    if ($this->cookie) {
                            $valor = join('#', array($email, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']));
                            $valor = sha1($valor);
                            setcookie($this->prefixoChaves . 'token', $valor, 0, $this->cookiePath);
                    }
                    if ($lembrar='on') $this->lembrarDados($email, $senha);
                   
                    return true;			
		} 
                else 
                {
                    $this->erro = 'Usurio Invalido';
                    return false;
		}
	}
	function usuarioLogado($cookies = true) {
		if ($this->iniciaSessao AND !isset($_SESSION)) {
			session_start();
		}
		
		
		if (!isset($_SESSION[$this->prefixoChaves . 'logado']) OR !$_SESSION[$this->prefixoChaves . 'logado']) {
			
			if ($cookies) {
				
				return $this->verificaDadosLembrados();
			} else {
				$this->erro = 'NÃ£o hÃ¡ usuÃ¡rio logado';
				return false;
			}
		}
		

		if ($this->cookie) {

			if (!isset($_COOKIE[$this->prefixoChaves . 'token'])) {
				$this->erro = 'NÃ£o hÃ¡ usuÃ¡rio logado';
				return false;
			} else {

				$valor = join('#', array($_SESSION[$this->prefixoChaves . 'email'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']));
	

				$valor = sha1($valor);
	

				if ($_COOKIE[$this->prefixoChaves . 'token'] !== $valor) {
					$this->erro = 'NÃ£o hÃ¡ usuÃ¡rio logado';
					return false;
				}
			}
		}
		

		return true;
	}
	

	function logout($cookies = true) {
		if ($this->iniciaSessao AND !isset($_SESSION)) {
			session_start();
		}
		$tamanho = strlen($this->prefixoChaves);

		foreach ($_SESSION AS $chave=>$valor) {
			if (substr($chave, 0, $tamanho) == $this->prefixoChaves) {
				unset($_SESSION[$chave]);
			}
		}
		
		
		if (count($_SESSION) == 0) {
			session_destroy();
			
			if (isset($_COOKIE['PHPSESSID'])) {
				setcookie('PHPSESSID', false, (time() - 3600));
				unset($_COOKIE['PHPSESSID']);
			}
		}
				
		if ($this->cookie AND isset($_COOKIE[$this->prefixoChaves . 'token'])) {
			setcookie($this->prefixoChaves . 'token', false, (time() - 3600), $this->cookiePath);
			unset($_COOKIE[$this->prefixoChaves . 'token']);
		}
                
		if ($cookies) $this->limpaDadosLembrados();
		

		return !$this->usuarioLogado(false);
	}

	function lembrarDados($email, $senha) {	
		
		$tempo = strtotime("+{$this->lembrarTempo} day", time());

		$email = rand(1, 9) . base64_encode($email);
		$senha = rand(1, 9) . base64_encode($senha);
	
		setcookie($this->prefixoChaves . 'lu', $email, $tempo, $this->cookiePath);
		
		setcookie($this->prefixoChaves . 'ls', $senha, $tempo, $this->cookiePath);
	}
	
	
	function verificaDadosLembrados() {
		
		if (isset($_COOKIE[$this->prefixoChaves . 'lu']) AND isset($_COOKIE[$this->prefixoChaves . 'ls'])) {
			$email = base64_decode(substr($_COOKIE[$this->prefixoChaves . 'lu'], 1));
			$senha = base64_decode(substr($_COOKIE[$this->prefixoChaves . 'ls'], 1));
			return $this->logaUsuario($email, $senha, true);		
		}
		
		return false;
	}   
	function limpaDadosLembrados() {
		// Deleta o cookie com o usuÃ¡rio
		if (isset($_COOKIE[$this->prefixoChaves . 'lu'])) {
			setcookie($this->prefixoChaves . 'lu', false, (time() - 3600), $this->cookiePath);
			unset($_COOKIE[$this->prefixoChaves . 'lu']);			
		}
		// Deleta o cookie com a senha
		if (isset($_COOKIE[$this->prefixoChaves . 'ls'])) {
			setcookie($this->prefixoChaves . 'ls', false, (time() - 3600), $this->cookiePath);
			unset($_COOKIE[$this->prefixoChaves . 'ls']);			
		}
	}

    function getData($tipo,$formato)
    {
        if($tipo == "dobanco")
        {
            $data_quebrada = explode("-",$this->admissao);

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
            $data_quebrada = explode("/",$this->admissao);

            if($formato == 'd')
            {
                return $data_quebrada[0];
            }
            elseif($formato == 'm')
            {
                return $data_quebrada[1];
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

    function carregarListaColaboradores($tamanho)
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

        $consulta = $banco->prepare("select * from usuarios where habilitado = 1 order by nome");
        if ($consulta->execute()) 
        {
            for($i=0;$i<$consulta->rowCount();$i++)
            {
                $colaborador_atual = new Colaborador();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                $colaborador_atual->id_colaborador = $resultado["id_usuario"];
                $colaborador_atual->nome = $resultado["nome"];
                $colaborador_atual->saldo = "+ 5 horas";
                $colaborador_atual->admissao = $resultado["admissao"];
                $colaborador_atual->admissao = $colaborador_atual->getdata('dobanco','d')."/".$colaborador_atual->getdata('dobanco','m')."/".$colaborador_atual->getdata('dobanco','a');
                $colaborador_atual->saldo = $colaborador_atual->saldoColaborador($colaborador_atual->id_colaborador);

                $this->desenharPaginaColaborador($colaborador_atual,$tamanho);
            }
            echo "<br><br><br><br><br><br>";
        }
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }
    }

    function carregarColaborador($id_colaborador,$tamanho)
    {
        $colaborador_atual = new Colaborador();
        $colaborador_atual->id_colaborador = $id_colaborador;
        $colaborador_atual->nome = $colaborador_atual->carregaInfo($id_colaborador,"nome");
        $colaborador_atual->saldo = "+ 5 Horas";
        $colaborador_atual->admissao = $colaborador_atual->carregaInfo($id_colaborador,"admissao");
        $colaborador_atual->admissao = $colaborador_atual->getdata('dobanco','d')."/".$colaborador_atual->getdata('dobanco','m')."/".$colaborador_atual->getdata('dobanco','a');
        $colaborador_atual->ctps = $colaborador_atual->carregaInfo($id_colaborador,"ctps");
        $colaborador_atual->cargo = $colaborador_atual->carregaInfo($id_colaborador,"cargo");
        $colaborador_atual->email = $colaborador_atual->carregaInfo($id_colaborador,"email");
        $colaborador_atual->saldo = $colaborador_atual->saldoColaborador($colaborador_atual->id_colaborador);
        $this->desenharPaginaColaborador($colaborador_atual,$tamanho);
    }

    function  desenharPaginaColaborador($colaborador,$tamanho)
    {
       if($tamanho == 'pequeno')
       {
           echo"<li>
                    <div class='date'>
                    </div>
                    <div id='controles_publicacao_medio'>
                        <a href='#'>
                            <img src='images/edit.png' alt='Editar colaborador...'>
                        </a>
                        <a href=\"javascript:confirmaExclusaoColaborador('".$colaborador->id_colaborador."');\">
                            <img src='images/minus.png' alt='Deletar colaborador...'>
                        </a>
                    </div>
                    <p style='display:block;'>
                        <a style='text-decoration:none;font-size:24px;' href='colaborador.php?id_colaborador=".$colaborador->id_colaborador."'>".$colaborador->nome."
                        </a>
                        <br>
                            Saldo: ".$colaborador->saldo."
                        <br>
                            Admissão: ".$colaborador->admissao."
                    </p>
                </li>"; 
       }
       elseif($tamanho == 'medio')
       {
           echo"<li>
                    <div class='date'>
                    </div>
                    <div id='controles_publicacao_medio'>
                        <a href='#'>
                            <img src='images/edit.png' alt='Editar colaborador...'>
                        </a>
                        <a href=\"javascript:confirmaExclusaoColaborador('".$colaborador->id_colaborador."');\">
                            <img src='images/minus.png' alt='Deletar colaborador...'>
                        </a>
                    </div>
                    <p style='display:block;'>
                        <a style='text-decoration:none;font-size:30px;' href='#'>".$colaborador->nome."</a>
                        <br>
                        <br>
                        <a style='text-decoration:none;font-size:26px;' href='bancodehoras.php'>Saldo: ".$colaborador->saldo."</a>
                        <br> 
                        <br>
                            CTPS: ".$colaborador->ctps."
                        <br>
                            Admissão: ".$colaborador->admissao."
                        <br>
                            Cargo: ".$colaborador->cargo."
                        <br>
                            Email: ".$colaborador->email."
                    </p>
                </li>";    
       }
       elseif($tamanho == 'grande')
       {

       }
    }

    function salvaColaborador($colaborador)
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
            $consulta = $banco->prepare("insert into usuarios (nome,ctps,cargo,admissao,email,senha,nivel_acesso,habilitado) values (:nome,:ctps,:cargo,:admissao,:email,:senha,:nivel_acesso,:habilitado)");
            $consulta->bindValue(":nome",$colaborador->nome);
            $consulta->bindValue(":ctps",$colaborador->ctps); 
            $consulta->bindValue(":cargo",$colaborador->cargo);
            $consulta->bindValue(":admissao",$colaborador->admissao);
            $consulta->bindValue(":email",$colaborador->email);
            $consulta->bindValue(":senha","202cb962ac59075b964b07152d234b70");
            $consulta->bindValue(":nivel_acesso","1");
            $consulta->bindValue(":habilitado","1");
        if ($consulta->execute()){}
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }
    }

    function deletaColaborador($id_colaborador,$destino)
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
            $consulta = $banco->prepare("update usuarios set habilitado = 0 where id_usuario =:id_colaborador");
        else
            $consulta = $banco->prepare("");

        $consulta->bindValue(":id_colaborador",$id_colaborador);
        if ($consulta->execute()){}
        else
        {
            $this->erro = "Erro na realização da operação!"; 
        }
    }

    function dropDownColaboradores()
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

        $consulta = $banco->prepare("select * from usuarios where habilitado = 1 order by nome");
        if ($consulta->execute()) 
        {
            $colaborador_atual = new Colaborador();
            echo"<select id='colaborador_selecionado' style='width:300px;' name='id_colaborador'><option selected='true'></option>";
            for($i=0;$i<$consulta->rowCount();$i++)
            {
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                $colaborador_atual->id_colaborador = $resultado["id_usuario"];
                $colaborador_atual->nome = $resultado["nome"];    
                echo" <option value='".$colaborador_atual->id_colaborador."'>".$colaborador_atual->nome."</option>";
            }
            echo"</select>";
        }
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }  
    }

    function saldoColaborador($id_colaborador)
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

        $consulta = $banco->prepare("select sum(total_extra) as 'saldo_atual' from bancodehoras where id_colaborador=:id_colaborador");
        $consulta->bindValue(":id_colaborador",$id_colaborador);
        if ($consulta->execute()) 
        {
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            if($resultado["saldo_atual"] >= 0)
            {
                return number_format($resultado["saldo_atual"] / 60,1)." horas"; 
            }
            else
            {
                return number_format($resultado["saldo_atual"] / 60,1)." horas"; 
            }

        }
        else
        {
            $this->erro = $this->erro."Erro na realização da operação"; 
        }    

    }
}
?>