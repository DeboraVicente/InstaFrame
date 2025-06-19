<?php
  require_once 'db.php';
  $userId = $_GET['userId'] ?? null;
  if ($userId) {
      $stmt = $pdo->prepare("SELECT id, eventName as nome_evento FROM event WHERE userId = ?");
      $stmt->execute([$userId]);
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
  }
?>