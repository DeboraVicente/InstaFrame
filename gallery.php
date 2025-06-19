<?php
  session_start();

  require_once 'db.php';

  $userId = $_SESSION['user']['id'];
  $stmt = $pdo->prepare("SELECT p.* FROM photos p
        JOIN event e ON p.eventId = e.id
        WHERE e.userId = ?");
  $stmt->execute([$userId]);
  $fotos = $stmt->fetchAll();

  include 'partials/header.php';
?>

<div class="container py-5">
  <h2 class="mb-4">Minhas Fotografias</h2>

  <?php if (count($fotos) === 0): ?>
    <div class="alert alert-info">Nenhuma fotografia disponível.</div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($fotos as $foto): ?>
        <div class="col">
          <div class="card shadow-sm">
            <img src="<?= htmlspecialchars($foto['file_path']) ?>" class="card-img-top" alt="Foto">
            <div class="card-body">
              <a href="<?= htmlspecialchars($foto['file_path']) ?>" download class="btn btn-outline-primary w-100">Baixar</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>