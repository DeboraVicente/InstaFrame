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

?>

<form method="POST" class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Cliente</label>
    <select name="userId" class="form-select" required>
      <option value="">Selecione</option>
      <?php
      $clientes = $pdo->query("SELECT id, name FROM users WHERE dateDeleted IS NULL")->fetchAll();
      foreach ($clientes as $cliente) {
          echo "<option value='{$cliente['id']}'>{$cliente['name']}</option>";
      }
      ?>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Nome do Evento</label>
    <input type="text" name="eventName" class="form-control" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">Data do Evento</label>
    <input type="date" name="eventDate" class="form-control">
  </div>
  <div class="col-2 align-self-end">
    <button type="submit" class="btn px-4 py-2" style="background-color: #974315; color: white; border-radius: 20px;">Salvar</button>
  </div>
</form>

<?php
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $pdo->prepare("UPDATE event SET dateDeleted = NOW() WHERE id = ?")->execute([$id]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=excluido");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? null;
    $eventName = $_POST['eventName'] ?? null;
    $eventDate = $_POST['eventDate'] ?? null;

    if ($userId && $eventName) {
        $stmt = $pdo->prepare("INSERT INTO event (userId, eventName, eventDate) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $eventName, $eventDate]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=cadastrado");
        exit;
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=erro");
        exit;
    }
}

if (isset($_GET['msg'])) {
    $mensagens = [
        'cadastrado' => ['tipo' => 'success', 'texto' => 'Evento cadastrado com sucesso!'],
        'excluido'   => ['tipo' => 'warning', 'texto' => 'Evento excluído com sucesso.'],
        'erro'       => ['tipo' => 'danger',  'texto' => 'Preencha todos os campos obrigatórios.']
    ];
    $msg = $_GET['msg'];
    if (array_key_exists($msg, $mensagens)) {
        echo '<div class="alert alert-' . $mensagens[$msg]['tipo'] . ' mt-4">' . $mensagens[$msg]['texto'] . '</div>';
    }
}

$eventos = $pdo->query("
    SELECT e.*, u.name AS user 
    FROM event e 
    JOIN users u ON e.userId = u.id 
    WHERE e.dateDeleted IS NULL AND u.dateDeleted IS NULL 
    ORDER BY e.id DESC
")->fetchAll();

if ($eventos):
?>
<hr class="my-4">
<h4>Eventos Cadastrados</h4>
<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Evento</th>
      <th>Cliente</th>
      <th>Data do Evento</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($eventos as $e): ?>
    <tr>
      <td><?= $e['id'] ?></td>
      <td><?= htmlspecialchars($e['eventName']) ?></td>
      <td><?= htmlspecialchars($e['user']) ?></td>
      <td><?= $e['eventDate'] ?></td>
      <td>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?excluir=<?= $e['id'] ?>" 
            class="btn btn-sm btn-outline-danger" 
            title="Excluir"
            onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
            <i class="bi bi-trash"></i>
          </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>