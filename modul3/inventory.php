<?php
require_once __DIR__ . '/../db.php';
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Склад</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Складские запасы</h2>
      <div class="form-row" style="justify-content:space-between; margin-bottom:12px;">
        <div style="display:flex; gap:10px;"><a href="add_inventory.php" class="button">Добавить партию</a></div>
        <a href="../index.html" class="button secondary">Главная</a>
        <div style="display:flex; gap:10px;"><a href="medications.php" class="button">Лекарства</a><a href="suppliers.php" class="button">Поставщики</a></div>
      </div>
      <?php
      $q = "SELECT i.id, i.medication_id, m.name AS med_name, i.batch_number, i.quantity, i.unit_price, i.expiry_date, i.received_date, i.location, i.status FROM inventory i LEFT JOIN medications m ON m.id=i.medication_id ORDER BY i.expiry_date ASC";
      $res = $conn->query($q);
      if ($res && $res->num_rows>0) {
        echo '<table class="table" cellspacing="0">';
        echo '<thead><tr><th>ID</th><th>Лекарство</th><th>Партия</th><th>Кол-во</th><th>Цена</th><th>Годен до</th><th>Принят</th><th>Локация</th><th>Статус</th></tr></thead><tbody>';
        while ($r=$res->fetch_assoc()) {
          echo '<tr>';
          echo '<td>'.htmlspecialchars((string)$r['id']).'</td>';
          echo '<td>'.htmlspecialchars($r['med_name']).'</td>';
          echo '<td>'.htmlspecialchars($r['batch_number']).'</td>';
          echo '<td>'.htmlspecialchars((string)$r['quantity']).'</td>';
          echo '<td>'.htmlspecialchars((string)$r['unit_price']).'</td>';
          echo '<td>'.htmlspecialchars($r['expiry_date']).'</td>';
          echo '<td>'.htmlspecialchars($r['received_date']).'</td>';
          echo '<td>'.htmlspecialchars($r['location']).'</td>';
          echo '<td>'.htmlspecialchars($r['status']).'</td>';
          echo '</tr>';
        }
        echo '</tbody></table>';
      } else { echo '<p>Записей не найдено.</p>'; }
      if ($res instanceof mysqli_result) $res->free();
      $conn->close();
      ?>
    </div>
  </div>
</body>
</html>
