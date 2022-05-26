<?php 

Class Pessoa 
{
    private $pdo;

        //CONEXÃO COM BANCO DE DADOS.
    public function __construct($db, $host, $usuario, $senha) 
    {
        try 
        {
            $this->pdo = new PDO ("mysql:dbname=".$db.";host=".$host,$usuario,$senha);
        } 
        catch (PDOException $e)
        {
            echo "Erro com banco de dados: " .$e -> getMessage();
        }
        catch (Exception $e)
        {
            echo "Erro generico: " .$e->getMessage();
        }
        
    }
    
    //FUNÇÃO É PARA BUSCAR OS DADOS E COLOCAR NO CANTO DIREITO DA TELA.
    public function buscarDados ()
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    
    //FUNCAO DE CADASTRAR PESSOAS NO BANCO DE DADOS
    public function cadastrarPessoa ($nome, $telefone, $email)
    {
        // ANTES DE CADASTRAR VERIFICAR SE JA TEM CADASTRO
        $cmd = $this -> pdo -> prepare ("SELECT id from pessoa WHERE email = :e");
        $cmd -> bindValue (":e", $email);
        $cmd -> execute ();

        if ($cmd->rowCount() > 0) //email já existe no banco de dados.
        {
            return false;
        } 
        else //nao foi encontrado o e-mail
        {
            $cmd = $this -> pdo -> prepare ("INSERT INTO pessoa (nome, telefone, email) VALUES (:n, :t, :e)");
            $cmd->bindValue (":n", $nome);
            $cmd->bindValue (":t", $telefone);
            $cmd->bindValue (":e", $email);
            
            $cmd -> execute ();
            
            return true;
        }

    }

    public function excluirPessoa ($id) 
    {
        $cmd = $this -> pdo -> prepare ("DELETE FROM pessoa WHERE id = :id");
        $cmd -> bindValue (":id", $id);
        $cmd -> execute ();
    }

    //BUSCAR OS DADOS DE UMA PESSOA
    public function buscarDadosPessoa ($id)
    {
        $res = array ();
        $cmd = $this -> pdo -> prepare ("SELECT * FROM pessoa WHERE id = :id");
        $cmd -> bindValue (":id", $id);
        $cmd -> execute ();
        $res = $cmd -> fetch(PDO::FETCH_ASSOC);
        return $res;
    }





    //ATUALIZAR OS DADOS NO BANCO DE DADOS
    public function atualizarDados ($id, $nome, $telefone, $email) 
    {
 
        $cmd = $this -> pdo -> prepare ("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
        $cmd -> bindValue (":n", $nome);
        $cmd -> bindValue (":t", $telefone);
        $cmd -> bindValue (":e", $email);
        $cmd -> bindValue (":id", $id);
        $cmd -> execute ();
        return true;
    
    }
       

}

?>