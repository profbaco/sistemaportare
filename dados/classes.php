<?php
class banco{
    public static $db;
    public $query;
    public function __construct(){}

    public function conect(){
        if(is_null(self::$db)){
            try{
                self::$db = new PDO(DRIVE.':Server='.HOST.';Database='.BANCO.'', ''.USER.'', ''.PASS.'');
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Erro ao conectar. Motivo: " . $e->getMessage();
            }
        }
        return self::$db;
    }

    public function PDOSelecionar($campos, $tabela, $condicao, $inicio, $fim, $ordenacao){
        $ord = explode(".", $ordenacao);
        $ordem = end($ord);
        $this->query = "WITH tabela AS (
                            SELECT ".$campos.",
                            INDICE = ROW_NUMBER() OVER (ORDER BY ".$ordenacao.")
                            FROM ".$tabela."
                            WHERE ".$condicao."
                        )
                        SELECT *
                        FROM tabela
                        WHERE INDICE BETWEEN " . $inicio . " AND " . $fim ."
                        ORDER BY ". $ordem;
        try {
            $select = $this->conect()->prepare($this->query);
            $select->execute();
            $dados = $select->fetchAll(PDO::FETCH_OBJ);
            return $dados;

        } catch (PDOException $e){
            echo 'Erro na seleção. Motivo: ' . $e->getMessage() . ' <br><strong>Query:</strong> ' . $this->query;
        }
    }

    public function PDOSelecionarPadrao($campos, $tabela, $condicao){
        $this->query = "SELECT ".$campos." FROM ".$tabela;
        if ($condicao<>'') $this->query .= " WHERE ".$condicao;

        try {
            $select = $this->conect()->prepare($this->query);
            $select->execute();
            $dados = $select->fetchAll(PDO::FETCH_OBJ);
            return $dados;

        } catch (PDOException $e){
            echo 'Erro na seleção. Motivo: ' . $e->getMessage() . ' <br><strong>Query:</strong> ' . $this->query;
        }
    }

    public function PDOInserir($tabela, $campos, $values){
        $this->query = "INSERT INTO " . $tabela . " " . $campos . " VALUES " . $values;
        try{
            $select = $this->conect()->prepare($this->query);
            if($select->execute()){
                return true;
            }
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function PDOEditar($tabela, $alteracao, $condicao){
        $this->query = " UPDATE " . $tabela . " SET " . $alteracao ;
        if (!empty($condicao)){
            $this->query .= " WHERE " . $condicao;
        }
        try {
            $select = $this->conect()->prepare($this->query);
            if($select->execute()){
                return true;
            }
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function PDOExcluir($tabela, $condicao){
        $this->query = "DELETE FROM " . $tabela;
        if (!empty($condicao)) {
            $this->query .= " WHERE " . $condicao;
        }
        try{
            $select = $this->conect()->prepare($this->query);
            if ($select->execute()){
                return true;
            }
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }


    public function PDOQtderegistro($tabela, $condicao){
        $this->query = "SELECT count(*) total FROM " . $tabela;
        if (!empty($condicao)){
            $this->query .= " WHERE " . $condicao;
        }
        try {
            $select = $this->conect()->query($this->query);
            $dados = $select->fetchObject();
            return $dados->total;

        } catch (PDOException $e){
            echo 'Erro na seleção. Motivo: ' . $e->getMessage();
        }
    }
}