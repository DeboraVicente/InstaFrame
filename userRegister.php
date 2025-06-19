<?php 
  session_start();

  if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
  }
  
  if ($_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
  }

  include 'partials/header.php'; 
  require_once 'db.php';

  $mostrarExcluidos = isset($_GET['ver_excluidos']);

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

  if (isset($_GET['restaurar'])) {
      $id = $_GET['restaurar'];
      $pdo->prepare("UPDATE users SET dateDeleted = NULL WHERE id = ?")->execute([$id]);
      header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=restaurado');
      exit;
  }

  if (isset($_POST['editar_id']) && !empty($_POST['editar_id'])) {
      $editar_id = $_POST['editar_id'];
      $nome = $_POST['name'];
      $email = $_POST['email'];

      $verifica = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
      $verifica->execute([$email, $editar_id]);

      if ($verifica->fetchColumn() > 0) {
          header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=emailduplicado');
          exit;
      } else {
          $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
          $stmt->execute([$nome, $email, $editar_id]);
          header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=editado');
          exit;
      }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['editar_id'])) {
      $nome = $_POST['name'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];
      $role = $_POST['role'] ?? 'cliente';
      $hash = password_hash($senha, PASSWORD_DEFAULT);

      $verifica = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
      $verifica->execute([$email]);

      if ($verifica->fetchColumn() > 0) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=emailduplicado');
        exit;
      } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $hash, $role]);
        header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=cadastrado');
        exit;
      }
  }

  if (isset($_GET['msg'])) {
      $mensagens = [
          'cadastrado' => ['tipo' => 'success', 'texto' => 'Cliente cadastrado com sucesso!'],
          'excluido'   => ['tipo' => 'warning', 'texto' => 'Cliente marcado como excluído com sucesso.'],
          'erro'       => ['tipo' => 'danger',  'texto' => 'Não é possível excluir: o cliente possui eventos ativos.'],
          'restaurado' => ['tipo' => 'success', 'texto' => 'Cliente restaurado com sucesso.'],
          'emailduplicado' => ['tipo' => 'danger', 'texto' => 'Já existe um cliente com esse e-mail.'],
          'editado'    => ['tipo' => 'info', 'texto' => 'Cliente editado com sucesso.']
      ];
      $msg = $_GET['msg'];
      if (array_key_exists($msg, $mensagens)) {
          echo '<div class="alert alert-' . $mensagens[$msg]['tipo'] . ' mt-4">' . $mensagens[$msg]['texto'] . '</div>';
      }
  }
?>

<form method="POST" class="row g-3">
  <input type="hidden" name="editar_id" id="editar_id">
  <div class="col-md-3">
    <label class="form-label">Nome</label>
    <input type="text" name="name" id="name" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">Senha</label>
    <input type="password" name="senha" id="senha" class="form-control" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">Tipo</label>
    <select name="role" id="role" class="form-select" required>
      <option value="cliente">Cliente</option>
      <option value="admin">Admin</option>
    </select>
  </div>
  <div class="col-2 align-self-end">
    <button type="submit" class="btn px-4 py-2" style="background-color: #974315; color: white; border-radius: 20px;">Salvar</button>
  </div>
</form>

<?php
  $clientes = $pdo->query("SELECT * FROM users WHERE dateDeleted " . ($mostrarExcluidos ? "IS NOT NULL" : "IS NULL") . " ORDER BY id DESC")->fetchAll();
?>
<hr class="my-3">
<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="m-0">Clientes <?= $mostrarExcluidos ? 'Excluídos' : 'Cadastrados' ?></h4>
  <a href="<?= $_SERVER['PHP_SELF'] . ($mostrarExcluidos ? '' : '?ver_excluidos=1') ?>" class="btn btn-sm btn-outline-secondary">
    <?= $mostrarExcluidos ? 'Ver Ativos' : 'Ver Excluídos' ?>
  </a>
</div>

<?php if (count($clientes) > 0): ?>
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <?php if ($mostrarExcluidos): ?>
        <th>Data de Exclusão</th>
        <?php endif; ?>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($clientes as $c): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= htmlspecialchars($c['email']) ?></td>
        <?php if ($mostrarExcluidos): ?>
        <td><?= $c['dateDeleted'] ?></td>
        <?php endif; ?>
        <td>
          <?php if ($mostrarExcluidos): ?>
          <a href="<?= $_SERVER['PHP_SELF'] ?>?restaurar=<?= $c['id'] ?>" class="btn btn-sm btn-outline-success">Restaurar</a>
          <?php else: ?>
          <button class="btn btn-sm btn-outline-primary" title="Editar" onclick="editarCliente(<?= $c['id'] ?>, '<?= htmlspecialchars($c['name']) ?>', '<?= htmlspecialchars($c['email']) ?>')">
            <i class="bi bi-pencil"></i>
          </button>
          <a href="<?= $_SERVER['PHP_SELF'] ?>?excluir=<?= $c['id'] ?>" 
            class="btn btn-sm btn-outline-danger" 
            title="Excluir"
            onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
            <i class="bi bi-trash"></i>
          </a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<div class="alert alert-info mt-3">Nenhum cliente <?= $mostrarExcluidos ? 'excluído' : 'cadastrado' ?> no momento.</div>
<?php endif; ?>

<script>
function editarCliente(id, nome, email) {
  document.getElementById('editar_id').value = id;
  document.getElementById('name').value = nome;
  document.getElementById('email').value = email;
  document.getElementById('senha').value = '';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php include 'partials/footer.php'; ?>