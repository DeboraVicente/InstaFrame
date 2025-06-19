<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>InstaFrame</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #f0ede4; color: #8d957e;" class="d-flex flex-column min-vh-100">

  <header class="navbar navbar-expand-lg fixed-top" style="background-color: #788990;">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="index.php" style="display: flex; align-items: center;">
      <i class="bi bi-camera"></i>
      <span class="ms-2">InstaFrame</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
         <?php if (isset($_SESSION['user'])): ?>
          <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="userRegister.php">Usuário</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="eventRegister.php">Eventos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="upload.php">Upload</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php">Galeria</a>
            </li>
          <?php elseif ($_SESSION['user']['role'] === 'cliente'): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="galeria.php">Minha Galeria</a>
            </li>
          <?php endif; ?>

          <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php" class="text-danger text-decoration-none ms-3" title="Sair">
              <i class="bi bi-box-arrow-right fs-4"></i>
            </a>
          <?php endif; ?>

        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="login.php">Login</a>
          </li>
        <?php endif; ?>
        </ul>
      </div>
    </div>
  </header>

  <main class="container mt-5 pt-5" style="padding-bottom: 80px;">