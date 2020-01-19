<?php

class BancoDados 
{
    
    private $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
    private $host = "localhost";
    private $dbname;
    private $usuario = "root";
    private $senha = "";
    private $erro = null;
    private $bancoDados = null;
    private $consulta = null;
    private $consultaNumeroResultados = null;
    
    
    public function BancoDados($dbname)
    {
        
        $this->setDbname($dbname);
        $this->conectaBanco();
        
    }
    
    public function conectaBanco()
    {
        
        try
        {
            $this->bancoDados = new PDO("mysql:host=".$this->getHost().";dbname=".$this->getDbname(),$this->getUsuario(),$this->getSenha(),$this->getOpcoes());
        } 
        catch (Exception $ex) 
        {
            $this->setErro("Numero erro:<br>".$ex->getCode()."<br><br>Mensagem:<br>".$ex->getMessage());
        }
    }
    
    public function consultaUsuario($idUsuario,$campos = array())
    {
          $camposConsulta = "id_usuario,";
          foreach($campos as $campoAtual)
          {
            $camposConsulta = $camposConsulta.$campoAtual.",";
          }
          if ($camposConsulta === "id_usuario,")
          {
              $camposConsulta = "*";
          }
          else
          {
              $camposConsulta = chop($camposConsulta," ,");
          } 
        $this->consulta = $this->bancoDados->prepare("select ".$camposConsulta." from usuarios where id_usuario=:id_usuario");    
        $this->consulta->bindValue(":id_usuario",$idUsuario);
        
        try
        {
            $this->consulta->execute();
        } 
        catch (Exception $ex) 
        {
            $this->setErro("Numero erro:<br>".$ex->getCode()."<br><br>Mensagem:<br>".$ex->getMessage());
        }
        
        if($this->consulta->rowCount() > 0)
        {
            $this->setConsultaNumeroResultados($this->consulta->rowCount());
            return $this->consulta->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            $this->setErro("Este usuario não existe!");
        }
    }
    
    public function toString()
    {
        return "Host:<br> ".$this->getHost()." <br>Database:<br> ".$this->getDbname()." <br>Usuario:<br> ".$this->getUsuario()." <br>Senha:<br> ".$this->getSenha()." <br>Erro:<br> ".$this->getErro();
    }
    
    public function getOpcoes() {
        return $this->opcoes;
    }

    public function getHost() {
        return $this->host;
    }

    public function getDbname() {
        return $this->dbname;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getErro() {
        return $this->erro;
    }

    public function setOpcoes($opcoes) {
        $this->opcoes = $opcoes;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setErro($erro) {
        $this->erro = $erro;
    }
    public function getConsultaNumeroResultados() {
        return $this->consultaNumeroResultados;
    }

    public function setConsultaNumeroResultados($consultaNumeroResultados) {
        $this->consultaNumeroResultados = $consultaNumeroResultados;
    }


}

?>
