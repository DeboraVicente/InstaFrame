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
    <title>Área do Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Área do Cliente</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Bem-vindo, <?php echo $_SESSION['usuario']['nome']; ?>!</h2>
        <p>Aqui você pode visualizar suas fotos e eventos.</p>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Meus Eventos</h5>
                        <p class="card-text">Veja os eventos que você contratou.</p>
                        <a href="meus_eventos.php" class="btn btn-primary">Ver Eventos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Minhas Fotos</h5>
                        <p class="card-text">Acesse e faça download de suas fotos.</p>
                        <a href="minhas_fotos.php" class="btn btn-primary">Ver Fotos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
