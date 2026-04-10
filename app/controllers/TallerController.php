<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Taller.php';
require_once __DIR__ . '/../models/Solicitud.php';

class TallerController
{
    private $tallerModel;
    private $solicitudModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->tallerModel = new Taller($db);
        $this->solicitudModel = new Solicitud($db);
    }

    public function index()
    {
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/taller/listado.php';
    }
    
    public function getTalleresJson()
    {
        if (!isset($_SESSION['id'])) {
            echo json_encode([]);
            return;
        }
        
        $talleres = $this->tallerModel->getAllDisponibles();
        header('Content-Type: application/json');
        echo json_encode($talleres);
    }
    
    public function solicitar()
{
    header('Content-Type: application/json'); // Crucial para que JS sepa que es JSON

    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'error' => 'Sesión expirada']);
        return;
    }
    
    $tallerId = $_POST['taller_id'] ?? 0;
    $usuarioId = $_SESSION['id'];

    if ($this->solicitudModel->existeSolicitud($usuarioId, $tallerId)) {
        echo json_encode(['success' => false, 'error' => 'Ya solicitaste este taller']);
        return;
    }

    if ($this->solicitudModel->create($usuarioId, $tallerId)) {
        // En lugar de que salga un "1", enviamos esto:
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error en DB']);
    }
}
}