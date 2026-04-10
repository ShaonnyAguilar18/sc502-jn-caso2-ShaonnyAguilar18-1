<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //crear solicitud
    public function create($usuarioId, $tallerId)
    {
        $stmt = $this->conn->prepare("INSERT INTO solicitudes (usuario_id, taller_id, estado) VALUES (?, ?, 'pendiente')");
        $stmt->bind_param("ii", $usuarioId, $tallerId);
        return $stmt->execute();
    }

    //validar si ya existe solicitud previa para el mismo taller
    public function existeSolicitud($usuarioId, $tallerId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM solicitudes WHERE usuario_id = ? AND taller_id = ? AND (estado = 'pendiente' OR estado = 'aprobada')");
        $stmt->bind_param("ii", $usuarioId, $tallerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    //obtener el id del taller asociado a una solicitud
    public function getTallerIdBySolicitud($solicitudId)
    {
        $stmt = $this->conn->prepare("SELECT taller_id FROM solicitudes WHERE id = ?");
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['taller_id'] : null;
    }
    
    //cambiar estado(aprobada/rechazada)
    public function cambiarEstado($solicitudId, $nuevoEstado)
    {
        $stmt = $this->conn->prepare("UPDATE solicitudes SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevoEstado, $solicitudId);
        return $stmt->execute();
    }

    //listar solicitudes pendientes para el adim
    public function getPendientes()
    {
        $sql = "SELECT s.id, 
        u.username AS usuario, 
        t.nombre AS taller, 
        s.fecha_solicitud AS fecha
                FROM solicitudes s
                JOIN usuarios u ON s.usuario_id = u.id
                JOIN talleres t ON s.taller_id = t.id
                WHERE s.estado = 'pendiente'";
        $result = $this->conn->query($sql);
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }
}