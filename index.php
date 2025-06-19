<?php 
  include 'partials/header.php'; 
?>

<form method="GET" class="row g-3 mt-1">
  <div class="col-md-4">
    <label>Cliente</label>
    <select name="cliente" id="filtro-cliente" class="form-select">
      <option value="">Todos</option>
      <?php
      require_once 'db.php';
      $clientes = $pdo->query("SELECT id, name FROM users")->fetchAll();
      foreach ($clientes as $cliente) {
          $sel = (isset($_GET['cliente']) && $_GET['cliente'] == $cliente['id']) ? 'selected' : '';
          echo "<option value='{$cliente['id']}' $sel>{$cliente['name']}</option>";
      }
      ?>
    </select>
  </div>
  <div class="col-md-4">
    <label>Evento</label>
    <select name="evento" id="filtro-evento" class="form-select">
      <option value="">Todos</option>
    </select>
  </div>
  <div class="col-md-4 align-self-end">
    <button class="btn" style="background-color: #974315; color: white;">Filtrar</button>
  </div>
</form>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
<?php
$where = '';
$params = [];
if (!empty($_GET['evento'])) {
    $where = 'WHERE p.eventId = ?';
    $params[] = $_GET['evento'];
} elseif (!empty($_GET['cliente'])) {
    $where = 'WHERE e.userId = ?';
    $params[] = $_GET['cliente'];
}

$sql = "SELECT p.*, e.eventName FROM photos p JOIN event e ON p.eventId = e.id $where ORDER BY p.uploadDate DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$fotos = $stmt->fetchAll();

foreach ($fotos as $foto) {
    echo "<div class='col'>
        <div class='card h-100 shadow-sm'>
          <img src='{$foto['path']}' class='card-img-top' alt='{$foto['originalName']}' style='object-fit:cover; height:200px;'>
          <div class='card-body'>
            <h5 class='card-title'>{$foto['originalName']}</h5>
            <p class='card-text'><small>Evento: {$foto['eventName']}</small></p>
            <a href='{$foto['path']}' class='btn btn-sm' style='background-color: #974315; color: white;' download>Baixar</a>
          </div>
        </div>
      </div>";
}
?>
</div>

<script>
// Atualiza eventos no filtro ao selecionar cliente
const filtroCliente = document.getElementById('filtro-cliente');
const filtroEvento = document.getElementById('filtro-evento');

if (filtroCliente) {
  filtroCliente.addEventListener('change', () => {
    fetch(`get_eventos.php?userId=${filtroCliente.value}`)
      .then(res => res.json())
      .then(data => {
        filtroEvento.innerHTML = '<option value="">Todos</option>';
        data.forEach(ev => {
          filtroEvento.innerHTML += `<option value="${ev.id}">${ev.nome_evento}</option>`;
        });
      });
  });
}
</script>

<?php include 'partials/footer.php'; ?>