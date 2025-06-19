<?php
session_start();
require_once 'db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND dateDeleted IS NULL");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        if ($user['role'] === 'admin') {
            header('Location: index.php');
        } else {
            header('Location: gallery.php');
        }
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>

<?php include 'partials/header.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    
    <div class="col-md-5 d-none d-md-block">
      <img src="img/logo.png" alt="Imagem Login" class="img-fluid rounded shadow">
    </div>
    
    <div class="col-md-6">
      <h2 class="mb-4 text-center">Login</h2>
      <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
      <?php endif; ?>
      <form method="POST" class="shadow p-4 rounded" style="background-color: #f0ede4;">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Senha</label>
          <input type="password" name="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn w-100" style="background-color: #974315; color: white;">Entrar</button>
      </form>
    </div>

  </div>
</div>
<?php include 'partials/footer.php'; ?>
