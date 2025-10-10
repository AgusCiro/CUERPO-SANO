<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../bts/public/css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h3 class="text-center mb-4">Crear nuevo usuario</h3>

            <form action="../../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="accion" value="nuevo_usuario">

                <div class="mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="dni" class="form-control" placeholder="DNI" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <div class="mb-3">
                    <select name="rol" class="form-select">
                        <option value="Cliente">Cliente</option>
                        <option value="Entrenador">Entrenador</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrar</button>
                <a href="login.php" class="btn btn-secondary w-100 mt-2">Volver al login</a>
            </form>
        </div>
    </div>
</body>
</html>
