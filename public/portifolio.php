<?php
require 'config/database.php';

$stmt = $pdo->query("SELECT * FROM portfolio ORDER BY data_upload DESC");
$fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfólio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Portfólio</a>
            <a href="login.php" class="btn btn-light">Login</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Meu Portfólio</h2>
        <p class="text-center">Confira alguns dos meus trabalhos</p>

        <div class="row">
            <?php foreach ($fotos as $foto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo $foto['arquivo']; ?>" class="card-img-top" alt="Foto">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $foto['titulo']; ?></h5>
                            <p class="card-text"><?php echo $foto['descricao']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
