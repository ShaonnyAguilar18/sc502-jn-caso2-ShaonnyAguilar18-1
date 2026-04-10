<!DOCTYPE html>
<html>
<head>
    <title>Listado Talleres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="public/js/jquery-4.0.0.min.js"></script>
</head>
<body class="container mt-5">
    <nav class="d-flex justify-content-between mb-4">
        <div>
            <a href="index.php?page=talleres" class="me-3">Talleres</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div>
            <span>Usuario: <?= htmlspecialchars($_SESSION['user'] ?? 'Invitado') ?></span>
            <button id="btnLogout" class="btn btn-outline-danger btn-sm ms-2">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h3>Talleres Disponibles</h3>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cupo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="contenedor-talleres">
                </tbody>
        </table>
    </main>

    <script src="public/js/auth.js"></script>
    <script src="public/js/taller.js"></script>
</body>
</html>