<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }
    
    // Método para alimentar la tabla dinámica (AJAX GET)
    // Se activa con index.php?option=solicitudes_json
    public function getSolicitudesJson()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode([]);
            return;
        }
        
        $solicitudes = $this->solicitudModel->getPendientes();
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }
    
    // Aprobar solicitud
    // Se activa con $_POST['option'] == "aprobar"
    public function aprobar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        try {
            // 1. Obtener el ID del taller de esta solicitud
            $tallerId = $this->solicitudModel->getTallerIdBySolicitud($solicitudId);
            
            if (!$tallerId) {
                throw new Exception("La solicitud no existe.");
            }

            // 2. Verificar cupo disponible en tiempo real (Regla de negocio)
            $taller = $this->tallerModel->getById($tallerId);
            if ($taller['cupo_disponible'] <= 0) {
                throw new Exception("No hay cupos disponibles para aprobar esta inscripción.");
            }

            // 3. Ejecutar cambios (Transacción lógica)
            // Descontamos cupo y luego cambiamos estado
            if ($this->tallerModel->descontarCupo($tallerId)) {
                $this->solicitudModel->cambiarEstado($solicitudId, 'aprobada');
                echo json_encode(['success' => true, 'message' => 'Aprobado correctamente']);
            } else {
                throw new Exception("Error al descontar el cupo.");
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function rechazar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        // Simplemente cambiamos el estado
        if ($this->solicitudModel->cambiarEstado($solicitudId, 'rechazada')) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }
}