<?php 
  include 'partials/header.php'; 
?>
<h2>Upload de Fotografias</h2>
<form method="POST" enctype="multipart/form-data" class="mt-3">
  <div class="mb-3">
    <label class="form-label">Cliente</label>
    <select id="cliente" name="cliente_id" class="form-select" required>
      <option value="">Selecione</option>
      <?php
      require_once 'db.php';
      $clientes = $pdo->query("SELECT id, name FROM users")->fetchAll();
      foreach ($clientes as $cliente) {
          echo "<option value='{$cliente['id']}'>{$cliente['name']}</option>";
      }
      ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Evento</label>
    <select id="evento" name="evento_id" class="form-select" required>
      <option value="">Selecione o cliente primeiro</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Fotografias</label>
    <input type="file" name="fotos[]" class="form-control" multiple required accept="image/*">
  </div>
  <button type="submit" class="btn" style="background-color: #974315; color: white;">Enviar</button>
</form>

<script>
document.getElementById('cliente').addEventListener('change', function () {
  const clienteId = this.value;
  const eventoSelect = document.getElementById('evento');
  eventoSelect.innerHTML = '<option>Carregando eventos...</option>';

  fetch('get_eventos.php?userId=' + clienteId)
    .then(res => res.json())
    .then(data => {
      eventoSelect.innerHTML = '<option value="">Selecione</option>';
      data.forEach(evento => {
        eventoSelect.innerHTML += `<option value="${evento.id}">${evento.nome_evento}</option>`;
      });
    });
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/';
    $evento_id = $_POST['evento_id'];
    $errors = [];
    $success = 0;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($_FILES['fotos']['name'] as $key => $name) {
        $tmpName = $_FILES['fotos']['tmp_name'][$key];
        $error = $_FILES['fotos']['error'][$key];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($error === UPLOAD_ERR_OK && in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $safeName = uniqid() . '-' . basename($name);
            $destino = $uploadDir . $safeName;
            move_uploaded_file($tmpName, $destino);

            $stmt = $pdo->prepare("INSERT INTO photos (originalName, fileName, path, eventId) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $safeName, $destino, $evento_id]);
            $success++;
        } else {
            $errors[] = "$name não foi enviado (erro ou formato inválido).";
        }
    }

    if ($success > 0) {
        echo "<div class='alert alert-success mt-3'>$success foto(s) enviada(s) com sucesso.</div>";
    }
    if (!empty($errors)) {
        echo "<div class='alert alert-danger mt-3'><ul><li>" . implode("</li><li>", $errors) . "</li></ul></div>";
    }
}
include 'partials/footer.php';
?>