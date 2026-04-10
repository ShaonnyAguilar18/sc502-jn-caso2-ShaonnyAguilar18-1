<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="public/js/jquery-4.0.0.min.js"></script>
</head>
<body class="container mt-5">
    <nav class="d-flex justify-content-between mb-4">
        <div>
            <a href="index.php?page=talleres" class="me-3">Talleres</a>
            <a href="index.php?page=admin">Gestionar Solicitudes</a>
        </div>
        <div>
            <span class="badge bg-info text-dark">Admin: <?= htmlspecialchars($_SESSION['user'] ?? 'Admin') ?></span>
            <button id="btnLogout" class="btn btn-outline-danger btn-sm ms-2">Cerrar sesión</button>
        </div>
    </nav>
    
    <main>
        <h2>Solicitudes pendientes de aprobación</h2>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tabla-solicitudes">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Taller</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudes-body">
                    </tbody>
            </table>
        </div>
    </main>

    <script src="public/js/auth.js"></script>
    <script src="public/js/solicitud.js"></script>
</body>
</html>