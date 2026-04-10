<?php
class Taller
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $result = $this->conn->query("SELECT * FROM talleres ORDER BY nombre");
        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }
        return $talleres;
    }

    public function getAllDisponibles()
    {
        $sql = "SELECT * FROM talleres WHERE cupo_disponible > 0 ORDER BY nombre";
        $result = $this->conn->query($sql);
        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }
        return $talleres;

    }

    public function getById($id)
    {
    
        $stmt = $this->conn->prepare("SELECT * FROM talleres WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function descontarCupo($tallerId)
    {
        $stmt = $this->conn->prepare("UPDATE talleres SET cupo_disponible = cupo_disponible - 1 WHERE id = ? AND cupo_disponible > 0");
        $stmt->bind_param("i", $tallerId);
        return $stmt->execute();

    }

    public function sumarCupo($tallerId)
    {
        //  decidi implementar la lógica de "devolver" el cupo al rechazar 
        $stmt = $this->conn->prepare("UPDATE talleres SET cupo_disponible = cupo_disponible + 1 WHERE id = ?");
        $stmt->bind_param("i", $tallerId);
        return $stmt->execute();

    }
}
