<?php 
  include 'partials/header.php'; 
  require_once 'db.php';
?>

<form method="POST" class="row g-3">
  <div class="col-md-5">
    <label class="form-label">Nome</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="col-md-5">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="col-2 align-self-end">
    <button type="submit" class="btn px-4 py-2" style="background-color: #974315; color: white; border-radius: 20px;">Cadastrar</button>
  </div>
</form>

<?php
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id = $_GET['excluir'];

    $temEventos = $pdo->prepare("SELECT COUNT(*) FROM event WHERE userId = ? AND dateDeleted IS NULL");
    $temEventos->execute([$id]);

    if ($temEventos->fetchColumn() > 0) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=erro');
        exit;
    } else {
        $pdo->prepare("UPDATE users SET dateDeleted = NOW() WHERE id = ?")->execute([$id]);
        header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=excluido');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email']]);
    header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=cadastrado');
    exit;
}

if (isset($_GET['msg'])) {
    $mensagens = [
        'cadastrado' => ['tipo' => 'success', 'texto' => 'Cliente cadastrado com sucesso!'],
        'excluido'   => ['tipo' => 'warning', 'texto' => 'Cliente marcado como excluído com sucesso.'],
        'erro'       => ['tipo' => 'danger',  'texto' => 'Não é possível excluir: o cliente possui eventos ativos.']
    ];
    $msg = $_GET['msg'];
    if (array_key_exists($msg, $mensagens)) {
        echo '<div class="alert alert-' . $mensagens[$msg]['tipo'] . ' mt-4">' . $mensagens[$msg]['texto'] . '</div>';
    }
}

$clientes = $pdo->query("SELECT * FROM users WHERE dateDeleted IS NULL ORDER BY id DESC")->fetchAll();
if ($clientes):
?>
<hr class="my-5">
<h4>Clientes Cadastrados</h4>
<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Email</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clientes as $c): ?>
    <tr>
      <td><?= $c['id'] ?></td>
      <td><?= htmlspecialchars($c['name']) ?></td>
      <td><?= htmlspecialchars($c['email']) ?></td>
      <td>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?excluir=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
