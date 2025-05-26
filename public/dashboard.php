<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Fotógrafo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Painel do Fotógrafo</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Bem-vindo, <?php echo $_SESSION['usuario']['nome']; ?>!</h2>
        <p>Aqui você pode gerenciar clientes, eventos e fotos.</p>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Gerencie seus clientes cadastrados.</p>
                        <a href="clientes.php" class="btn btn-primary">Ver Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Eventos</h5>
                        <p class="card-text">Crie e organize seus eventos de fotografia.</p>
                        <a href="eventos.php" class="btn btn-primary">Gerenciar Eventos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Galeria</h5>
                        <p class="card-text">Faça upload e compartilhe fotos.</p>
                        <a href="galeria.php" class="btn btn-primary">Acessar Galeria</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
