<?php
require_once __DIR__ . '/../db.php';
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Лекарственные средства</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Лекарственные средства</h2>
      <div class="form-row" style="justify-content:space-between; margin-bottom:12px;">
        <div style="display:flex; gap:10px;">
          <a href="add_medication.php" class="button">Добавить лекарство</a>
        </div>
        <a href="../index.html" class="button secondary">Главная</a>
        <div style="display:flex; gap:10px;">
          <a href="inventory.php" class="button">Склад</a>
        </div>
      </div>
      <?php
      $q = "SELECT id, name, generic_name, form, strength, manufacturer, barcode, atc_code, prescription_required, storage_temperature, min_stock_level, max_stock_level, is_active, created_at FROM medications ORDER BY name";
      $res = $conn->query($q);
      if ($res && $res->num_rows>0) {
        echo '<table class="table" cellspacing="0">';
        echo '<thead><tr><th>ID</th><th>Название</th><th>Межд. название</th><th>Форма</th><th>Доза</th><th>Производитель</th><th>Штрихкод</th><th>ATC</th><th>Рецепт</th><th>Хранение</th></tr></thead><tbody>';
        while ($r=$res->fetch_assoc()) {
          echo '<tr>';
          echo '<td>'.htmlspecialchars((string)$r['id']).'</td>';
          echo '<td>'.htmlspecialchars($r['name']).'</td>';
          echo '<td>'.htmlspecialchars($r['generic_name']).'</td>';
          echo '<td>'.htmlspecialchars($r['form']).'</td>';
          echo '<td>'.htmlspecialchars($r['strength']).'</td>';
          echo '<td>'.htmlspecialchars($r['manufacturer']).'</td>';
          echo '<td>'.htmlspecialchars($r['barcode']).'</td>';
          echo '<td>'.htmlspecialchars($r['atc_code']).'</td>';
          echo '<td>'.htmlspecialchars($r['prescription_required']? 'Да':'Нет').'</td>';
          echo '<td>'.htmlspecialchars($r['storage_temperature']).'</td>';
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
